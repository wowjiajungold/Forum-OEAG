<?php

/**
 * Copyright (C) 2008-2012 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

define('PUN_ROOT', dirname(__FILE__).'/');
define('PUN_ACTIVE_PAGE', 'edited');
require PUN_ROOT.'include/common.php';
require PUN_ROOT.'lang/'.$pun_user['language'].'/index.php';

if ($pun_config['o_feed_type'] == '1')
	$page_head = array('feed' => '<link rel="alternate" type="application/rss+xml" href="extern.php?action=feed&amp;type=rss" title="'.$lang_common['RSS active topics feed'].'" />');
else if ($pun_config['o_feed_type'] == '2')
	$page_head = array('feed' => '<link rel="alternate" type="application/atom+xml" href="extern.php?action=feed&amp;type=atom" title="'.$lang_common['Atom active topics feed'].'" />');

$page_title = array( pun_htmlspecialchars( 'Messages récemment édités' ), $pun_config['o_board_title'] );
require PUN_ROOT.'header.php';

$is_admmod = ($pun_user['g_id'] == PUN_ADMIN || ($pun_user['g_moderator'] == '1' && array_key_exists($pun_user['username'], $mods_array))) ? true : false;

// if ( !$is_admmod )
//     message( $lang_common['No permission'] );

$posts = array();
$timestamp = time() - 5184000; //60*24*3600

$q = 'SELECT * FROM posts WHERE edited > '.$timestamp.' AND edited_by != "NULL" ORDER BY edited';
//$q = 'SELECT * FROM posts WHERE edited != "NULL" AND edited_by != "NULL" ORDER BY edited';
$result = $db->query( $q ) or error( 'Impossible de retrouver la liste des forums !', __FILE__, __LINE__, $db->error() );
while( $r = $db->fetch_assoc( $result ) )
    $posts[] = array( 'id' => $r['id'],
                      'poster' => $r['poster'],
                      'poster_id' => $r['poster_id'],
                      'posted' => $r['posted'],
                      'edited' => $r['edited'],
                      'edited_by' => $r['edited_by'] );

krsort( $posts );

$total = count( $posts );
$s = ( $total > 1 ? 's' : '' );

?>
                <div id="msg" class="block">
                  <h2 id="post_title" style="border-bottom:1px solid #333"><?php echo "$total Message$s édité$s récemment"; ?></h2>
                  
                  <div id="select" class="block" style="background:#fefefe;border:1px solid #333;margin:25px 0px;padding:5px;">
                    <form id="selectform" action="">
                      <p>
                        <label for="interv1">Délai entre création et édition : <input id="interv1" name="interv1" type="text" /></label><br />
                        <label for="interv2">Délai entre édition et date actuelle : <input id="interv2" name="interv2" type="text" /></label>
                        <input type="submit" value="Actualiser" />
                      </p>
                    </form>
                  </div>
                  
                  <div id="posts">
                    <table style="background:#fefefe;margin:25px 0px;padding:5px;">
                      <thead style="border:1px solid #333">
                        <tr>
                          <th>ID</th>
                          <th>Auteur</th>
                          <th>Date</th>
                          <th>Édité le </th>
                          <th>Édité par</th>
                        </tr>
                      </thead>
                      <tbody id="table_posts" style="border:1px solid #333">
<?php foreach( $posts as $post ) : ?>
                        <tr id="post_<?php echo $post['id']; ?>" class="post">
                          <td style="padding:5px;"><a class="id" href="<?php echo $pun_config['o_base_url']."/viewtopic.php?pid=".$post['id']."#p".$post['id']; ?>">#<?php echo $post['id']; ?></a></td>
                          <td style="padding:5px;"><a class="poster" href="<?php echo $pun_config['o_base_url']."/profile.php?id=".$post['poster_id']."#p".$post['id']; ?>"><?php echo $post['poster']; ?></a></td>
                          <td style="padding:5px;"><a class="posted" rel="<?php echo $post['posted']; ?>"><?php echo date( 'j F Y \à H\:i', $post['posted'] ); ?></a></td>
                          <td style="padding:5px;"><a class="edited" rel="<?php echo $post['edited']; ?>"><?php echo date( 'j F Y \à H\:i', $post['edited'] ); ?></a></td>
                          <td style="padding:5px;"><a class="edited_by"><?php echo $post['edited_by']; ?></a></td>
                        </tr>
<?php endforeach; ?>
                      </tbody>
                    </table>
                  </div>
                </div>

                <script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
                <script type="text/javascript">

function formatDate(timestamp, type) {
    
    var ret = "";
    console.log(timestamp);
    
    if ( type == 'string' ) {
        
        d = timestamp.split('/');
        var date = new Date(d[1], d[0]-1, 1, 0, 0, 0, 0);
    
        var months = ['janvier','février','mars','avril','mai','juin','juillet','août','septembre','octobre','novembre','décembre'];
        var year   = date.getFullYear();
        var month  = months[date.getMonth()];
        var date   = date.getDate();
    
        ret = 'le '+date+' '+month+' '+year;
    }
    else {
        d = timestamp.split('/');
        ret = Date.UTC(d[1], d[0], 1)/1000;
    }
    
    return ret;
}

$(document).ready(function() {
    
    $(".interv").click(function() { $(this).val(''); });
    
    $("#selectform").submit(function(e) {
    
        var interv1 = $("#interv1").val();
        var interv2 = $("#interv2").val();
        
        var interv1_utc = 0;
        var interv2_utc = 0;
        
        if ( interv1 != '' && interv1 != undefined ) interv1_utc = formatDate(interv1, 'utc');
        if ( interv2 != '' && interv2 != undefined ) interv2_utc = formatDate(interv2, 'utc');
        
        $("a.posted").parent("td").parent("tr").show();
        $("a.posted").each(function(i) {
                
            var pid = $(this).parent("td").parent("tr").prop('id');
            if ( pid != 'undefined' ) {
                
                var posted = $(this).prop('rel');
                var edited = $("#"+pid).find("a.edited").prop('rel');
                
                 if ( interv1_utc > 0 && interv2_utc > 0 ) {
                    var test = ( posted > interv1_utc && edited > interv2_utc );
                    var msg = "Messages postés avant "+formatDate(interv1, 'string')+" et édités avant "+formatDate(interv2, 'string');
                }
                else if ( interv1_utc == 0 && interv2_utc > 0 ) {
                    var test = ( edited > interv2_utc );
                    var msg = "Messages édités avant "+formatDate(interv2, 'string');
                }
                else if ( interv1_utc > 0 && interv2_utc == 0 ) {
                    var test = ( posted > interv1_utc );
                    var msg = "Messages postés avant "+formatDate(interv1, 'string');
                }
                
                if ( test ) {
                    $(this).parent("td").parent("tr").hide();
                    $("#formmsg").empty().text(msg);
                    
                }
            }
        });
        
        e.preventDefault();
        return false;
    });

    return;
});
                </script>
<?php

$footer_style = 'index';
require PUN_ROOT.'footer.php';
