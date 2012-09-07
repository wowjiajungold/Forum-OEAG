<?php

/**
 * EditMonitor is a simple class to detect malicious post edits.
 *
 * @author Charlie Merland
 * @link http://www.caercam.org/
 * @version v1.2
 * @license MIT License
 *
 * Copyright (c) 2012 Charlie Merland
 *
 * Permission is hereby granted, free of charge, to any person
 * obtaining a copy of this software and associated documentation
 * files (the "Software"), to deal in the Software without
 * restriction, including without limitation the rights to use,
 * copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the
 * Software is furnished to do so, subject to the following
 * conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
 * OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT
 * HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY,
 * WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING
 * FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR
 * OTHER DEALINGS IN THE SOFTWARE.
 */

// minimum message length accepted
define( 'MINIMUM_EDIT_LENGTH', 4 );

// data to post alerts
define( 'POSTER', "Père Blaise" );
define( 'POSTER_ID', 3 );
define( 'TOPIC_ID', 2767 );
define( 'FORUM_ID', 15 );

// custom error message to be displayed on malicious edit attempt
define( 'ERROR_MESSAGE', 'les blanchiments sont interdits. Pour supprimer vos messages, veuillez vous adresser à l’équipe modération.' );

// internal limits: maximum edit quota is 6 edits in the last 10 minutes
define( 'EDIT_MONITOR_PERIOD', 600 );
define( 'EDIT_MONITOR_MAX_PER_PERIOD', 6 );
// 3 new urls allowed
define( 'EDIT_MONITOR_MAX_TAGS', 3 );


/**
 * Every time a member submit a modification of a message, run through a routine of tests
 * in order to determine wether or not we should accept the modification. A member should
 * not modify numerous posts in short period, nor replace posts by short/almost void.
 * Content new urls are checked too.
 */

class EditMonitor {
	
	// submitted message
	public $message;
	// error list
	public $errors;
	// current user
	public $user;
	// original post data
	public $post;
	
	// informative return
	private $status;
	
	/**
	 * Launch class
	 */
	public function __construct() {
	
		$this->init();
		$this->check();
	}
	
	/**
	 * Initialisation. Recover needed data
	 */
	private function init() {
		global $message, $error, $pun_user, $cur_post, $id;
		
		$this->message = $message;
		$this->errors = $errors;
		$this->user = $pun_user;
		$this->post = $cur_post;
		$this->post_id = $id;
	}
	
	/**
	 * Check edit validity.
	 */
	public function check() {
		
		if ( !$this->user_integrity() ) {
			$this->status = '<em>human karma alert:</em> plus de '.EDIT_MONITOR_MAX_PER_PERIOD.' messages modifiés durant les '.(EDIT_MONITOR_PERIOD / 60).' dernières minutes.';
			$this->alert();
		}
		else if ( !$this->check_small_length() ) {
			$this->errors[] = '<em>laughable karma alert:</em> ' . ERROR_MESSAGE;
			$this->status = '<em>laughable karma alert:</em> message ne contenant que quelques caractères.';
		}
		else if ( !$this->check_size_change() ) {
			$this->status = '<em>sizable karma alert:</em> modification importante de la longueur du message.';
			$this->alert();
		}
		else if ( !$this->check_new_tags() ) {
			$this->status = '<em>tagged karma alert:</em> plusieurs liens ou images ont été ajoutés.';
			$this->alert();
		}
	}
	
	/**
	 * Edited message should not be shorter than a fixed limit.
	 * If shorter, simply reject modification throwing an error.
	 * 
	 * @return boolean false if shorter, true if not.
	 */
	private function check_small_length() {
		if ( strlen( $this->message ) < MINIMUM_EDIT_LENGTH )
			return false;
		
		return true;
	}
	
	/**
	 * Edited message's length should not change too much. If 25% 
	 * or more of the original message is lost, accept the modification
	 * but send an alert with a backup of the original message.
	 * 
	 * @return boolean false if suspect, true if not.
	 */
	private function check_size_change() {
		
		$message_length = strlen( $this->message );
		$postmessage_length = strlen( $this->post['message'] );
		
		$diff = $postmessage_length - $message_length;
		$max = floor( ( $postmessage_length / 100 ) * 25 );
		
		if ( $diff > 0 && $diff >= $max )
			return false;
		
		return true;
	}
	
	/**
	 * Edited message's should contain numerous urls add. If 3 
	 * or more urls are added, accept the modification but send an
	 * alert with a backup of the original message.
	 * 
	 * @return boolean false if suspect, true if not.
	 */
	private function check_new_tags() {
		
		// regexp taken from include/parser.php
		preg_match_all( '%(?<=[\s\]\)])(<)?(\[)?(\()?([\'"]?)(https?|ftp|news){1}://([\p{L}\p{N}\-]+\.([\p{L}\p{N}\-]+\.)*[\p{L}\p{N}]+(:[0-9]+)?(/[^\s\[]*[^\s.,?!\[;:-])?)\4(?(3)(\)))(?(2)(\]))(?(1)(>))(?![^\s]*\[/(?:url|img)\])%uie', $this->message, $m1 );
		preg_match_all( '%(?<=[\s\]\)])(<)?(\[)?(\()?([\'"]?)(www|ftp)\.(([\p{L}\p{N}\-]+\.)*[\p{L}\p{N}]+(:[0-9]+)?(/[^\s\[]*[^\s.,?!\[;:-])?)\4(?(3)(\)))(?(2)(\]))(?(1)(>))(?![^\s]*\[/(?:url|img)\])%uie', $this->message, $m2 );
		
		if ( ( count( $m1 ) + count( $m2 ) ) > EDIT_MONITOR_MAX_TAGS )
			return false;
		
		return true;
	}
	
	/**
	 * A member should not modify message repeatedly in a short period.
	 * If currend user has more than a X registered edits
	 * in the last Y, reject modification throwing an error.
	 * 
	 * @return boolean false if multiple edits, true if not.
	 */
	private function user_integrity() {
		
		global $db;
		
		$query = "SELECT COUNT(id) as c FROM ".$db->prefix."posts WHERE edited > ".(time() - EDIT_MONITOR_PERIOD)." AND edited_by = '".$db->escape( $this->user['username'] )."';";
		$result = $db->query( $query ) or die( mysql_error() );
		$c = $db->fetch_assoc($result);
		
		if ( (int) $c['c'] > EDIT_MONITOR_MAX_PER_PERIOD )
			return false;
		
		return true;
	}
	
	/**
	 * Send an alert to the administration team in case of suspect edit.
	 */
	private function alert() {
		
		$diff = $this->htmlDiff( $this->post['message'], $this->message );
		
		$texte  = "[h]EditMonitor a détecté une édition suspecte[/h]\n\n";
		$texte .= "[url=".$pun_config['o_base_url']."/profile?id=".$this->user['id']."]".$this->user['username']."[/url] a édité le [url=".$pun_config['o_base_url']."/viewtopic.php?pid=".$this->post_id."#p".$this->post_id."]message #".$this->post_id."[/url].\n";
		
		if ( strlen( $diff['old'] ) != strlen( $diff['new'] ) ) {
			$texte .= "[quote=version originale]".$diff['old']."[/quote]\n";
			$texte .= "[quote=version éditée]".$diff['new']."[/quote]\n";
		}
		
		$texte .= "Si cette modification est anodine, ne tenez pas compte de cette alerte.\n";
		
		$this->post( $texte );
	}
	
	/**
	 * Add an alert message to the specified topic.
	 */
	private function post( $text ) {
	
		global $db;
		$now = time();
		
		$query = "INSERT INTO ".$db->prefix."posts (poster, poster_id, poster_ip, message, hide_smilies, posted, topic_id) VALUES('".POSTER."', '".POSTER_ID."', '0.0.0.0', '".$db->escape( $text )."', 0, '$now', '".TOPIC_ID."')";
		$db->query( $query ) or die( mysql_error() );
		$pid = mysql_insert_id();

		$query = "UPDATE ".$db->prefix."topics SET last_post='$now', last_post_id='$pid', last_poster='".POSTER."', num_replies=num_replies+1 WHERE id='".TOPIC_ID."'";
		$db->query( $query ) or die( mysql_error() );

		$query = "UPDATE ".$db->prefix."forums SET last_post='$now', last_post_id='$pid', last_poster='".POSTER."', num_posts=num_posts+1 WHERE id='".FORUM_ID."'";
		$db->query( $query ) or die( mysql_error() );
	}
	
	/**
	 * Simple Diff Algorithm
	 * https://github.com/paulgb/simplediff/blob/5bfe1d2a8f967c7901ace50f04ac2d9308ed3169/simplediff.php
	 */ 
	private function diff( $old, $new ) {
	
		foreach ( $old as $oindex => $ovalue ) {
			$nkeys = array_keys( $new, $ovalue );
			foreach ( $nkeys as $nindex ) {
				$matrix[$oindex][$nindex] = isset( $matrix[$oindex - 1][$nindex - 1] ) ? $matrix[$oindex - 1][$nindex - 1] + 1 : 1;
				if( $matrix[$oindex][$nindex] > $maxlen ) {
					$maxlen = $matrix[$oindex][$nindex];
					$omax = $oindex + 1 - $maxlen;
					$nmax = $nindex + 1 - $maxlen;
				}
			}
		}
		
		if( $maxlen == 0 )
			return array(array('d'=>$old, 'i'=>$new));
		
		return array_merge(
					$this->diff( array_slice( $old, 0, $omax ), array_slice( $new, 0, $nmax ) ),
					array_slice( $new, $nmax, $maxlen ),
					$this->diff( array_slice( $old, $omax + $maxlen ), array_slice( $new, $nmax + $maxlen ) ) );
	}
	
	/**
	 * Simple Diff Algorithm
	 * https://github.com/paulgb/simplediff/blob/5bfe1d2a8f967c7901ace50f04ac2d9308ed3169/simplediff.php
	 * Added some BBCode customization
	 */
	function htmlDiff( $old, $new ) {
		$diff = $this->diff( explode( ' ', $old ), explode( ' ', $new ) );
		
		foreach ( $diff as $k ) {
			if ( is_array( $k ) ) {
				$cmp_old .= ( !empty( $k['d'] ) ? '[color=#E01B3C]'.implode( ' ', $k['d'] ).'[/color] ' : '' );
				$cmp_new .= ( !empty( $k['i'] ) ? '[color=#1B77E0]'.implode( ' ', $k['i'] ).'[/color] ' : '' );
			}
			else {
				$cmp_old .= $k . ' ';
				$cmp_new .= $k . ' ';
			}
		}
		
		return array( 'old' => $cmp_old, 'new' => $cmp_new );
	}
}
?>