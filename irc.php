<?php

/**
 * Copyright (C) 2008-2012 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

define('PUN_ROOT', dirname(__FILE__).'/');
define('PUN_ACTIVE_PAGE', 'irc');
require PUN_ROOT.'include/common.php';
require PUN_ROOT.'lang/'.$pun_user['language'].'/index.php';

if ( $pun_user['is_guest'] )
    message( $lang_common['No permission'] );

if ($pun_config['o_feed_type'] == '1')
    $page_head = array('feed' => '<link rel="alternate" type="application/rss+xml" href="extern.php?action=feed&amp;type=rss" title="'.$lang_common['RSS active topics feed'].'" />');
else if ($pun_config['o_feed_type'] == '2')
    $page_head = array('feed' => '<link rel="alternate" type="application/atom+xml" href="extern.php?action=feed&amp;type=atom" title="'.$lang_common['Atom active topics feed'].'" />');

$nick = str_replace( array( 'à','á','â','ã','ä', 'ç', 'è','é','ê','ë', 'ì','í','î','ï', 'ñ', 'ò','ó','ô','õ','ö', 'ù','ú','û','ü', 'ý','ÿ', 'À','Á','Â','Ã','Ä', 'Ç', 'È','É','Ê','Ë', 'Ì','Í','Î','Ï', 'Ñ', 'Ò','Ó','Ô','Õ','Ö', 'Ù','Ú','Û','Ü', 'Ý', ' ', '\'', '"' ), array( 'a','a','a','a','a', 'c', 'e','e','e','e', 'i','i','i','i', 'n', 'o','o','o','o','o', 'u','u','u','u', 'y','y', 'A','A','A','A','A', 'C', 'E','E','E','E', 'I','I','I','I', 'N', 'O','O','O','O','O', 'U','U','U','U', 'Y', '_', '', '' ), $pun_user['username'] );

$page_title = array( $pun_config['o_board_title'], pun_htmlspecialchars( 'Chat IRC On En A Gros!' ) );
require PUN_ROOT.'header.php';
?>
                <div id="msg" class="block">
                  <iframe src="http://www.wsirc.com/?username=<?php echo $nick; ?>&amp;server=irc.langochat.net%3A6667&amp;channel=%23OnEnAGros&amp;autojoin=true&amp;color=%23efefef&amp;dark=false" style="width:100%;height:500px;" width="100%" height="500" frameborder="0" border="0" scrolling="NO" scrollbar="NO"></iframe>
                </div>
<?php

$footer_style = 'index';
require PUN_ROOT.'footer.php';
