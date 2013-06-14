<?php

class OnEnAGros {
    
    var $config;
    var $db;
    var $lang;
    var $user;

    public function __construct() {
        
        $this->oeag_set_db();
        $this->oeag_set_lang();
        
    }
    
    private function oeag_set_db() {
        global $db;
        $this->db     = &$db;
        $this->config = &$pun_config;
        $this->user   = &$pun_user;
    }
    
    private function oeag_set_lang() {
        global $pun_user;
        if (file_exists(PUN_ROOT.'OnEnAGros/lang/'.$pun_user['language'].'/oeag.php'))
            include PUN_ROOT.'OnEnAGros/lang/'.$pun_user['language'].'/oeag.php';
        $this->lang = $lang_oeag;
    }
    
    /**
     * Current day activity stats
     * How many topics/posts published today?
     * 
     * @return string HTML content
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_get_today_stats() {
        
        $r = $this->db->query('SELECT COUNT(id) FROM '.$this->db->prefix.'posts WHERE posted >= '.strtotime(date('Y-m-d'))) or error('Unable to fetch total user count', __FILE__, __LINE__, $this->db->error());
        $stats['total_posts_today'] = $this->db->result($r);

        $r = $this->db->query('SELECT COUNT(id) FROM '.$this->db->prefix.'topics WHERE posted >= '.strtotime(date('Y-m-d'))) or error('Unable to fetch total user count', __FILE__, __LINE__, $this->db->error());
        $stats['total_topics_today'] = $this->db->result($r);

        $s1 = ($stats['total_topics_today']>1) ? 's' : '';
        $s2 = ($stats['total_posts_today']>1) ? 's' : '';

        return '<dd><span>'.sprintf($this->lang['Today'], '<strong>'.forum_number_format($stats['total_topics_today']).'</strong>', $s1, '<strong>'.forum_number_format($stats['total_posts_today']).'</strong>', $s2).'</span></dd>'."\n";
    }
    
    /**
     * Day activity display
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_today_stats() {
        echo $this->oeag_get_today_stats();
    }

    /**
     * Current day user activity
     * How many users came here today?
     * 
     * @return string HTML content
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_get_today_users() {
        global $num_users, $num_guests, $lang_index;
        
        $result = $this->db->query('SELECT count(id) FROM '.$this->db->prefix.'users WHERE last_visit >= \''.strtotime(date('Y-m-d')).'\'') or error('Impossible de retrouver la liste des utilisateurs en ligne aujourd\'hui', __FILE__, __LINE__, $this->db->error());
        $users_today = $this->db->fetch_row($result);
        
        return "\t\t\t\t".'<dd><span>'.sprintf( $lang_index['Users online'], '<strong>'.forum_number_format( $num_users ).'</strong>' ).'</span></dd>'."\n\t\t\t\t".'<dd><span>'.sprintf( $lang_index['Guests online'], '<strong>'.forum_number_format( $num_guests ).'</strong>' ).'</span></dd>'."\n\t\t\t\t".'<dd><span>'.sprintf( $this->lang['Members online today'], '<strong>'.forum_number_format( $users_today[0] ).'</strong>' ).'</span></dd>'."\n\t\t\t".'</dl>'."\n";
    }

    /**
     * Day user activity display
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_today_users() {
        echo $this->oeag_get_today_users();
    }

    /**
     * Birthday announce in index footer
     * 
     * @return string HTML content
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_get_today_birthdays() {
        
        $todaystamp = date( 'j/n/' );
        $limitstamp = strtotime( date( 'Y-m-d' ) ) - 7776000;

        $ret = '';

        $result = $this->db->query( 'SELECT id, username, birthdate FROM '.$this->db->prefix.'users WHERE birthdate LIKE \''.$todaystamp.'%\' AND last_visit >= \''.$limitstamp.'\' AND last_post >= \''.$limitstamp.'\'' ) or error( 'Impossible de retrouver la liste des utilisateurs en ligne aujourd\'hui', __FILE__, __LINE__, $this->db->error() );
        while ( $birth = $this->db->fetch_row( $result ) )
            $birthdays[] = $birth;

        if ( count( $birthdays ) > 0 ) {
            
            $ret .= "\t\t\t".'<dl id="birthdaylist" class="clearb">'."\n\t\t\t\t".'<dt><strong>'.$this->lang['Birthday'].' </strong></dt>'."\n";
            foreach ( $birthdays as $birthday )
                $ret .= "\t\t\t\t".'<dd><a href="profile.php?id='.$birthday[0].'">'.pun_htmlspecialchars( $birthday[1] ).'</a> ('.calculAge( $birthday[2] ).')</dd>';
            $ret .= "\n\t\t\t".'</dl>'."\n";
        }
        else
            $ret .= "\t\t\t".'<div class="clearer"></div>'."\n";
        
        return $ret;
    }

    /**
     * If user specified sex in profile, display corresponding icon
     * 
     * @since OnEnAGros 1.5.3
     */
    private function oeag_set_sex_icon() {
        
        global $cur_post, $username;
        
        if( $cur_post['sex'] != '' && in_array( $cur_post['sex'], array( 0, 1, 2 ) ) ) {
            
            $src = 'OnEnAGros/img/s'.$cur_post['sex'].'.gif';
            $alt = $cur_post['sex'] ? $this->lang['Female'] : $this->lang['Male'];

            $username = sprintf( '<img src="%s" alt="%s" /> <span>%s</span>', $src, $alt, $username );
        }
    }

    /**
     * Birthday announce display
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_today_birthdays() {
        echo $this->oeag_get_today_birthdays();
    }

    /**
     * Viewtopic.php posts' display override
     * If current post's author is gone (user_id = 3), hide some informations
     * Add user sex icon and last visit
     * 
     * @return string HTML content
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_postleft() {
        
        global $lang_common, $cur_post, $user_avatar, $user_info, $user_contacts, $signature;
        
        $ret = array();
        
        $this->oeag_set_sex_icon();
        
        if ( $this->config['o_show_last_visit'] == '1' || $this->user['is_admmod'] ) {
            array_splice( $user_info, 1, 0, '<dd><span>'.sprintf( $lang_common['Last visit'], format_time( $cur_post['last_visit'], true ) ).'</span></dd>' );
            $ret = array(
                'user_info'     => $user_info,
            );
        }
        
        if ( $cur_post['poster_id'] == 3 && $cur_post['username'] != "Père Blaise" ) {
            $ret = array(
                'user_avatar'   => generate_avatar_markup('h0'),
                'user_title'    => '',
                'user_info'     => array(),
                'user_contacts' => '',
                'signature'     => '',
                'is_online'     => '',
            );
        }
        
        return $ret;
    }

    /** ******************************************************
     * 
     *     parser.php section
     * 
     * ****************************************************** */

    /**
     * Add some custom smilies
     * 
     * @return array complete smilies array
     * 
     * @since OnEnAGros 1.5.3
     */
    public static function oeag_custom_smilies() {
        
        global $smilies;
        
        $oeag_smilies = array(
            ':-)'        => 'smile.png',
            ':-('        => 'sad.png',
            ':-D'        => 'big_smile.png',
            ';-)'        => 'wink.png',
            ':-/'        => 'hmm.png',
            ':beuh:'     => 'beuh.gif',
            'o_O'        => 'beuh.gif',
            'O_o'        => 'beuh.gif',
            ':beuh:'     => 'beuh.gif',
            ':huh:'      => 'huh.gif',
            ':siffle:'   => 'siffle.gif',
            ':euh:'      => 'euh.gif',
            ':fete:'     => 'fete.gif',
            ':hug:'      => 'hug.gif',
            ':b'         => 'ironique.gif',
            ':B'         => 'ironique.gif',
            ':love:'     => 'love.gif',
            ':luv:'      => 'luv.gif',
            ':hs:'       => 'hs.gif',
            ':dehors:'   => 'dehors.gif'
        );
        
        return array(
            'smilies' => array_merge( $smilies, $oeag_smilies ),
        );
    }

    /**
     * Add some custom BBCode tags
     * 
     * @return array new tags
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_custom_tags() {
        
        global $tags, $tags_block, $tags_inline, $tags_trim, $tags_quotes, $tags_limit_bbcode, $tags_fix;
        
        $new_tags = array('size', 'spoiler', 'acronym', 'q', 'sup', 'sub', 'left', 'right', 'center', 'justify', 'video', 'scenario', 'titre', 'intro', 'texte', 'perso', 'didascalie', 'noir', 'table', 'tr', 'th', 'td');
        $tags = array_merge($tags, $new_tags);
    
        // Block tags, block tags can only go within another block tag, they cannot be in a normal tag
        $new_tags = array('spoiler', 'left', 'right', 'center', 'justify', 'scenario', 'intro', 'texte', 'didascalie', 'table', 'tr', 'th', 'td');
        $tags_block = array_merge($tags_block, $new_tags);
        
        // Inline tags, we do not allow new lines in these
        $new_tags = array('acronym', 'q', 'sup', 'sub', 'video', 'titre', 'perso');
        $tags_inline = array_merge($tags_inline, $new_tags);
        
        // Tags we trim interior space
        $new_tags = array('video'/*, 'table', 'tr', 'th', 'td'*/);
        $tags_trim = array_merge($tags_trim, $new_tags);
        
        // Tags we remove quotes from the argument
        $new_tags = array('video');
        $tags_quotes = array_merge($tags_quotes, $new_tags);
        
        /*$tags_limit_bbcode = array(
            '*'         => array('b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'list', 'img', 'code', 'topic', 'post', 'forum', 'user'),
            'list'      => array('*'),
            'url'       => array('img'),
            'email'     => array('img'),
            'topic'     => array('img'),
            'post'      => array('img'),
            'forum'     => array('img'),
            'user'      => array('img'),
            'img'       => array(),
            'h'         => array('b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'topic', 'post', 'forum', 'user'),
        );*/
        
        // Tags we limit bbcode in
        $tags_limit_bbcode = array(
            '*'         => array('b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'list', 'img', 'code', 'topic', 'post', 'forum', 'user', 'acronym', 'q', 'sup', 'sub', 'video'),
            'list'      => array('*'),
            'url'       => array('img', 'b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'acronym', 'q', 'sup', 'sub'),
            'email'     => array('img', 'b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'acronym', 'q', 'sup', 'sub'),
            'topic'     => array('img', 'b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'acronym', 'q', 'sup', 'sub'),
            'post'      => array('img', 'b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'acronym', 'q', 'sup', 'sub'),
            'forum'     => array('img', 'b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'acronym', 'q', 'sup', 'sub'),
            'user'      => array('img', 'b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'acronym', 'q', 'sup', 'sub'),
            'img'       => array(),
            'h'         => array('b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'topic', 'post', 'forum', 'user'),
            
            'size'      => array('b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'img', 'acronym', 'q', 'sup', 'sub'),
            'video'     => array(),
            'scenario'  => array('b', 'i', 'u', 's', 'em', 'color', 'colour', 'titre', 'intro', 'texte'),
            'texte'     => array('b', 'i', 'u', 's', 'em', 'color', 'colour', 'perso', 'didascalie', 'noir'),
            'noir'      => array('b', 'i', 'u', 's', 'em', 'color', 'colour', 'didascalie'),
            'table'     => array('tr'),
            'tr'        => array('th', 'td'),
            'th'        => array('b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'list', 'img', 'code', 'acronym', 'q', 'sup', 'sub', 'video'),
            'td'        => array('b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'list', 'img', 'code', 'acronym', 'q', 'sup', 'sub', 'video', 'topic', 'post', 'forum', 'user')
        );
        
        // Tags we can automatically fix bad nesting
        $new_tags = array('spoiler'); 
        $tags_fix = array_merge($tags_fix, $new_tags);
        
        $ret = array(
            'tags'               => $tags,
            'tags_block'         => $tags_block,
            'tags_inline'        => $tags_inline,
            'tags_trim'          => $tags_trim,
            'tags_quotes'        => $tags_quotes,
            'tags_limit_bbcode'  => $tags_limit_bbcode,
            'tags_fix'           => $tags_fix,
        );
        
        return $ret;
    }

    /**
     * Fix line-breaking bug on table BBCode
     * 
     * @return HTML cleaned markup
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_clean_table_br( $text ) {
        
        $text = str_replace('<table class="postable"><br />', "<table class=\"postable\">\n", $text);
        $text = str_replace('<tr><br />', "<tr>\n", $text);
        $text = str_replace('</tr><br />', "</tr>\n", $text);
        $text = str_replace('<th><br />', "<th>\n", $text);
        $text = str_replace('</th><br />', "</th>\n", $text);
        $text = str_replace('</td><br />', "</td>\n", $text);
        
        return $text;
    }

    /**
     * Add a [size] BBCode
     * 
     * @param int $s text size. Should be 6 < $s < 28 for readability.
     * @param string $t text
     * @return HTML markup
     * 
     * @since OnEnAGros 1.5.3
     */
    public static function oeag_size_bbcode( $s, $t ) {
        
        if( $s > 28 )
            $size = 28;
        if( $s < 6 )
            $size = 6;
        else
            $size = $s;
    
        return '<span style="font-size:'.$size.'px;">'.$t.'</span>';
    }

    /**
     * Add a [spoiler] BBCode
     * 
     * @return HTML markup
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_spoiler_bbcode() {
        
        global $text;
        
        $ret = array();
        
        if ( strpos( $text, '[spoiler' ) !== false ) {
            $text = preg_replace('#\[spoiler\]\s*#', '</p><div class="quotebox" onclick="pchild=this.getElementsByTagName(\'p\'); if(pchild[0].style.visibility!=\'hidden\'){pchild[0].style.visibility=\'hidden\';}else{pchild[0].style.visibility=\'\';}"><cite>Spoiler :<br /><span style="font-weight:normal;font-size:80%;">(Cliquez pour afficher)</span></cite><blockquote><div><p style="visibility:hidden;">', $text);
            $text = preg_replace('#\[spoiler=(&quot;|"|\'|)(.*?)\\1\]#se', '"</p><div class=\"quotebox\" onclick=\"pchild=this.getElementsByTagName(\'p\'); if(pchild[0].style.visibility!=\'hidden\'){pchild[0].style.visibility=\'hidden\';}else{pchild[0].style.visibility=\'\';}\"><cite>Spoiler : ".str_replace(array(\'[\', \'\\"\'), array(\'&#91;\', \'"\'), \'$2\')." <br /><span style=\"font-weight:normal;font-size:80%;\">(Cliquez pour afficher)</span></cite><blockquote><div><p style=\"visibility:hidden;\">$1"', $text);
            $text = preg_replace('#\s*\[\/spoiler\]#S', '</p></div></blockquote></div><p>', $text);
            
            $ret = array(
                'text' => $text,
            );
        }
        return $ret;
    }

    /** ******************************************************
     * 
     *     register.php section
     * 
     * ****************************************************** */

    /**
     * Magick test to avoid vilain automatic inscriptions
     * 
     * @return string valid email if ok
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_vilain_detector() {
        
        global $lang_common;
        
        $magick = 'oeag';
        
        $l_m  = strlen( $magick );
        $l_ml = strlen( $email1 );
        
        if ( substr( $email1, 0, $l_m ) === $magick )
                return substr( $email1, $l_m, $l_ml );
        else
                message( $lang_common['Invalid email'] );
    }

    /** ******************************************************
     * 
     *     profile.php section
     * 
     * ****************************************************** */

    /**
     * Calculate user's age based on birthdate
     * 
     * @return int User age
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_get_age( $date ) {
        
        $tmp = explode("/", $date);

        $d = $tmp[0];
        $m = $tmp[1];
        $y = $tmp[2];

        $today['m'] = date('n');
        $today['d'] = date('j');
        $today['y'] = date('Y');
        
        $y = $today['y'] - $y;
        
        if ( $today['m'] <= $m ) {
            if ( $m == $today['m'] ) {
                if ( $d > $today['d'] )
                    $y--;
            }
            else
                $y--;
        }
        
        return $y;
    }

    /**
     * Display user's gender in profile view
     * 
     * @return array user and user_info modified content
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_prof_reg_sex_view() {
        
        global $user, $user_personal;
        
        $l   = array( $this->lang['Male'], $this->lang['Female'], $this->lang['Bigg'] );
        $ret = array();
        
        $u = (int) $user['sex'];
        
        if ( isset( $u ) && $u >= 0 && $u < 3 ) {
            $_user = array(
                'sex' => $u,
            );
            $_user_personal = array(
                sprintf( '<dt>%s: </dt>', $this->lang['Sex'] ),
                sprintf( '<dd><img src="/OnEnAGros/img/s%d.gif" alt="%s" /> %s</dd>', $u, $l[$u], $l[$u] ),
            );
            
            $ret = array(
                'user'          => array_merge( $user, $_user ),
                'user_personal' => array_merge( $user_personal, $_user_personal ),
            );
        }
        
        return $ret;
    }

    /**
     * Display user's age in profile view
     * 
     * @return array user_personnal modified content
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_prof_reg_birthdate_view() {
        
        global $user, $user_personal;
        
        $ret = array();
        
        if ( $user['birthdate'] != '' ) {
            $_user_personal = array(
                sprintf( '<dt>%s: </dt>', $this->lang['Age'] ),
                sprintf( '<dd>%d %s</dd>', $this->oeag_get_age( $user['birthdate'] ), $this->lang['Years old'] ),
            );
            
            $ret = array(
                'user_personal' => array_merge( $user_personal, $_user_personal ),
            );
        }
        
        return $ret;
    }

    /**
     * Add user sex & birthdate in profile
     * 
     * @return array user_personnal modified content
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_prof_reg_personnal() {
        
        global $form, $_POST;
        
        $_form = array(
            'birthdate'     => isset( $_POST['form']['birthdate'] ) ? pun_trim( $_POST['form']['birthdate'] ) : '',
            'sex'           => isset( $_POST['form']['sex'] )       ? pun_trim( $_POST['form']['sex'] )       : '',
        );
        
        $d = isset( $_POST['form']['birthd'] ) ? pun_trim( $_POST['form']['birthd'] ) : '';
        $m = isset( $_POST['form']['birthm'] ) ? pun_trim( $_POST['form']['birthm'] ) : '';
        $y = isset( $_POST['form']['birthy'] ) ? pun_trim( $_POST['form']['birthy'] ) : '';
        
        if ( ( is_numeric( $d ) && $d != 0 ) &&
             ( is_numeric( $m ) && $m != 0 ) &&
             ( is_numeric( $y ) && $y != 0 ) ) {
                $_form['birthdate'] = "$d/$m/$y";
        }
        
        return array(
            'form' => array_merge( $form, $_form ),
        );
    }

    /**
     * Add user sex input in profile form
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_prof_reg_sex_form() {
        
        global $pun_user;
        
        $d = $m = $y = '';
        $l = array( "janvier", "février", "mars", "avril", "mai", "juin", "juillet", "août", "septembre", "octobre", "novembre", "décembre" );
        $thisyear = date("Y");
        
        if ( !empty( $pun_user['birthdate'] ) ) {
            $t = explode( "/", $pun_user['birthdate'] );
            $d = $t[0];
            $m = $t[1];
            $y = $t[2];
        }
        
        echo "\t\t\t\t\t\t\t".'<label>'.$this->lang['Birthdate'].' ('.$this->lang['Age profile use'].')'."\n";
        echo "\t\t\t\t\t\t\t\t".'<br />'."\n";
        echo "\t\t\t\t\t\t\t\t".'<select name="form[birthd]">'."\n";
        echo "\t\t\t\t\t\t\t\t\t".'<option value="0">--</option>'."\n";

        for ( $i = 1; $i < 32; $i++ )
            echo "\t\t\t\t\t\t\t\t\t".'<option value="'.$i.'"'.( $d == $i ? ' selected="selected"' : '' ).'>'.$i.'</option>'."\n";

        echo "\t\t\t\t\t\t\t\t".'</select>'."\n";
        echo "\t\t\t\t\t\t\t\t".'<select name="form[birthm]">'."\n";
        echo "\t\t\t\t\t\t\t\t\t".'<option value="0">--</option>'."\n";

        for ( $i = 1; $i < 13; $i++ )
            echo "\t\t\t\t\t\t\t\t\t".'<option value="'.$i.'"'.( $m == $i ? ' selected="selected"' : '' ).'>'.$i.'</option>'."\n";

        echo "\t\t\t\t\t\t\t\t".'</select>'."\n";
        echo "\t\t\t\t\t\t\t\t".'<select name="form[birthy]">'."\n";
        echo "\t\t\t\t\t\t\t\t\t".'<option value="0">----</option>'."\n";

        for ( $i = ( $thisyear - 100 ); $i < ( $thisyear - 8 ); $i++ )
            echo "\t\t\t\t\t\t\t\t\t".'<option value="'.$i.'"'.( $y == $i ? ' selected="selected"' : '' ).'>'.$i.'</option>'."\n";

        echo "\t\t\t\t\t\t\t\t".'</select>'."\n";
        echo "\t\t\t\t\t\t\t".'</label>'."\n";
        
    }

    /**
     * Add user birthdate input in profile form
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_prof_reg_birthdate_form() {
        
        global $pun_user;

        echo "\t\t\t\t\t\t\t".'<label>'.$this->lang['Sex'].'<br />'."\n";
        echo "\t\t\t\t\t\t\t\t".'<select name="form[sex]">'."\n";
        echo "\t\t\t\t\t\t\t\t\t".'<option value=""'.( $pun_user['sex'] == null ? ' selected="selected"' : '' ).'>--</option>'."\n";
        echo "\t\t\t\t\t\t\t\t\t".'<option value="0"'.( $pun_user['sex'] == "0" ? ' selected="selected"' : '' ).'>'.$this->lang['Male'].'</option>'."\n";
        echo "\t\t\t\t\t\t\t\t\t".'<option value="1"'.( $pun_user['sex'] == "1" ? ' selected="selected"' : '' ).'>'.$this->lang['Female'].'</option>'."\n";
        echo "\t\t\t\t\t\t\t\t\t".'<option value="2"'.( $pun_user['sex'] == "2" ? ' selected="selected"' : '' ).'>'.$this->lang['Bigg'].'</option>'."\n";
        echo "\t\t\t\t\t\t\t\t".'</select>'."\n";
        echo "\t\t\t\t\t\t\t".'</label>'."\n";

    }

    


    /**
     * Check for submitted posts while we were typing current post
     * 
     * @return boolead $revision true if someone posted anything new, false else
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_check_revision() {
        
        global $cur_posting, $_POST, $tid;
        
        $revision = false;
        
        if ( isset( $_POST['form_time'] ) &&
             isset( $cur_posting['last_post'] ) &&
             $tid != 0 && 
             $_POST['form_time'] < $cur_posting['last_post'] )
            $revision = true;
        
        return $revision;
    }

    /**
     * Display user's age in profile view
     * 
     * @return array user_personnal modified content
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_double_post_view() {
        
        global $group;
        
        echo "\t\t\t\t\t\t\t\t".'<tr>'."\n";
        echo "\t\t\t\t\t\t\t\t\t".'<th scope="row">'.$this->lang['Double post label'].'</th>'."\n";
        echo "\t\t\t\t\t\t\t\t\t".'<td>'."\n";
        echo "\t\t\t\t\t\t\t\t\t\t".'<input type="text" name="double_post" size="5" maxlength="4" value="'.$group['g_double_post'].'" tabindex="26" />'."\n";
        echo "\t\t\t\t\t\t\t\t\t\t".'<span>'.$this->lang['Double post help'].'</span>'."\n";
        echo "\t\t\t\t\t\t\t\t\t".'</td>'."\n";
        echo "\t\t\t\t\t\t\t\t".'</tr>'."\n";
    }

    public function oeag_double_post_form() {
         
    }

    /**
     * Check for double posts: is current user the thread's last poster?
     * 
     * @return array error message
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_check_double_post() {
        
        global $tid, $pun_user, $cur_posting, $_POST, $errors;

        $ret = array();

        if ( $tid && !isset( $_POST['preview'] ) )
            if( $pun_user['last_post'] != '' && $pun_user['username'] == $cur_posting['last_poster'] && ( time() - $cur_posting['last_post'] ) < ( $pun_user['g_double_post'] * 60 ) )
                $_errors = sprintf( $this->lang['Double post'], $pun_user['g_double_post'] );
        
        return array(
            'errors' => array_merge( $errors, $_errors ),
        );
    }

    /**
     * Display revision announce if needed.
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_check_revision_view() {
        
        global $revision;
        
        if ( !$revision )
            return false;
        
        echo '<div id="postrevision" class="blockform">'."\n";
        echo '    <h2><span>'.$this->lang['Post revision'].'</span></h2>'."\n";
        echo '    <div class="box">'."\n";
        echo '        <div class="inform">'."\n";
        echo '            <p style="padding:40px 0 0 10px;">'.$this->lang['Post revision info'].'</p>'."\n";
        echo '        </div>'."\n";
        echo '    </div>'."\n";
        echo '</div>'."\n";
        echo '<br />'."\n";
    }

    
    public function oeag_get_fluxtoolbar( $wut = 'form' ) {
        if (file_exists(FORUM_CACHE_DIR.'cache_fluxtoolbar_'.$wut.'.php'))
            include FORUM_CACHE_DIR.'cache_fluxtoolbar_'.$wut.'.php';
        else
        {
            require_once PUN_ROOT.'include/cache_fluxtoolbar.php';
            generate_ftb_cache( $wut );
            require FORUM_CACHE_DIR.'cache_fluxtoolbar_'.$wut.'.php';
        }
    }
}


?>