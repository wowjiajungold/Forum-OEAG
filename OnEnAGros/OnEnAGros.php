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
     * Add some custom smilies
     * 
     * @return array complete smilies array
     * 
     * @since OnEnAGros 1.5.3
     */
    public function oeag_custom_smilies() {
        
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
    
    
    
    
    
}


?>