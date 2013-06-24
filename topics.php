<?php

/**
 * Copyright (C) 2008-2012 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

define('PUN_ROOT', dirname(__FILE__).'/');
define('PUN_ACTIVE_PAGE', 'topics');
require PUN_ROOT.'include/common.php';
require PUN_ROOT.'lang/'.$pun_user['language'].'/index.php';

if ($pun_config['o_feed_type'] == '1')
	$page_head = array('feed' => '<link rel="alternate" type="application/rss+xml" href="extern.php?action=feed&amp;type=rss" title="'.$lang_common['RSS active topics feed'].'" />');
else if ($pun_config['o_feed_type'] == '2')
	$page_head = array('feed' => '<link rel="alternate" type="application/atom+xml" href="extern.php?action=feed&amp;type=atom" title="'.$lang_common['Atom active topics feed'].'" />');

$page_title = array( pun_htmlspecialchars( 'Liste des discussions' ), $pun_config['o_board_title'] );
require PUN_ROOT.'header.php';

$categories = array();
$forums = array();
$topics = array();

if ( $pun_user['g_id'] == PUN_ADMIN || $pun_user['g_moderator'] == '1' )
    $q = 'SELECT id, forum_name, cat_id, disp_position FROM forums WHERE id NOT IN (2)';
else if ( $pun_user['g_id'] != PUN_ADMIN && ( $pun_user['g_moderator'] != '1' ) )
    $q = 'SELECT id, forum_name, cat_id, disp_position FROM forums WHERE id NOT IN (2,15,117,118,120)';

$result = $db->query( $q ) or error( 'Impossible de retrouver la liste des forums<br /><br />'.mysql_error().'<br /><br />'.$q, __FILE__, __LINE__, $db->error() );
while( $r = $db->fetch_assoc( $result ) ) :
    $forums[$r['cat_id'].$r['disp_position']] = array( 'id' => $r['id'], 'name' => $r['forum_name'] );
    
    $q2 = 'SELECT id, subject FROM topics WHERE forum_id='.$r['id'].' ORDER BY subject ASC';
    $result2 = $db->query( $q2 ) or error( 'Impossible de retrouver la liste des forums<br /><br />'.mysql_error().'<br /><br />'.$q2, __FILE__, __LINE__, $db->error() );
    
    while($r2 = $db->fetch_assoc( $result2 ) ) :
        $topics[$r['id']][$r2['id']] = $r2['subject'];
    endwhile;
endwhile;

ksort( $forums );
?>
                <div id="msg" class="block">
                <div style="background:#efefef;border:1px solid #888;float:right;width:300px;padding:10px">
                  <p style="font-weight:bold;text-align:center">Accès rapide</p>
                  <ul>
<?php foreach( $forums as $forum ) : ?>
                    <li style="list-style:square;margin:0 0 0 20px;"><a href="#forum_<?php echo $forum['id']; ?>"><?php echo $forum['name']; ?></a></li>
<?php endforeach; ?>
                  </ul>
                </div>

<?php foreach( $forums as $forum ) : ?>
                <h2 id="forum_<?php echo $forum['id']; ?>" style="border-bottom:1px solid #333"><a style="text-decoration:none" href="http://forum.onenagros.org/viewforum.php?id=<?php echo $forum['id']; ?>"><?php echo $forum['name']; ?></a></h2>
                <div id="forum_<?php echo $forum['id']; ?>_html">
                  <ul>
<?php foreach( $topics[$forum['id']] as $tid => $topic ) : ?>
                    <li style="list-style:circle;margin:0 0 0 35px;"><a href="http://forum.onenagros.org/viewtopic.php?id=<?php echo $tid; ?>"><?php echo $topic; ?></a></li>
<?php endforeach; ?>
                  </ul>
                </div>
<?php if ( $pun_user['g_id'] == PUN_ADMIN || $pun_user['g_moderator'] == '1' ) : ?>
                <div id="forum_<?php echo $forum['id']; ?>_bbcode" style="background:#F8F9F0;border-color:#7AADBD;border-width:1px 1px 1px 3px;border-style:solid;padding:5px 5px 5px 10px;margin-left:25px;">
                  [list=*]<br />
<?php foreach( $topics[$forum['id']] as $tid => $topic ) : ?>
                  &nbsp;&nbsp;[*][url=http://forum.onenagros.org/viewtopic.php?id=<?php echo $tid; ?>]<?php echo $topic; ?>[/url][/*]<br />
<?php endforeach; ?>
                  [/list]<br />
                </div>
<?php endif; ?>
<?php endforeach; ?>
                </div>
<?php

$footer_style = 'index';
require PUN_ROOT.'footer.php';
