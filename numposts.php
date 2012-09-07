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

$page_title = array( pun_htmlspecialchars( 'Comparatif des nombres de posts' ), $pun_config['o_board_title'] );
require PUN_ROOT.'header.php';

if (!$pun_user['is_admmod'])
	message($lang_common['No permission']);

$ret = array();
$table = array();

$results = $db->query( 'SELECT id, username, num_posts FROM users WHERE id NOT IN (1,3) ORDER BY id' ) or error( 'Unable to fetch users', __FILE__, __LINE__, $db->error() );

while ( $ret = $db->fetch_assoc( $results ) )
	$users[$ret['id']] = $ret;

foreach( $users as $user ) :
	$results = $db->query( 'SELECT COUNT(id) AS count FROM posts WHERE poster_id='.$user['id'] ) or error( 'Unable to fetch users', __FILE__, __LINE__, $db->error() );
	while ( $ret = $db->fetch_assoc( $results ) ) :
		$users[$user['id']]['diff_posts'] = $user['num_posts'] - $ret['count'];
	endwhile;
endforeach;
?>
	<h2>Liste réelle des nombres de posts</h2>

	<div class="blocktable">
		<div class="box">
			<div class="inbox">
				<table>
					<thead>
						<tr>
							<th class="tcl">Utilisateur</th>
							<th class="tc2">Nombre de posts</th>
							<th class="tc3">Nombre réel</th>
							<th class="tcr">Différence</th>
						</tr>
					</thead>
<?php foreach( $users as $user ) : ?>
					<tbody>
						<tr>
							<td class="tcl"><a href="http://forum.onenagros.org/profile.php?id=<?php echo $user['id']; ?>"><?php echo $user['username']; ?></a></td>
							<td class="tc2"><?php echo $user['num_posts']; ?></td>
							<td class="tc3"><?php echo $user['count']; ?></td>
							<td class="tcr"><strong><?php echo $user['diff_posts']; ?></strong></td>
						</tr>
					</tbody>
<?php endforeach; ?>
				</table>
			</div>
		</div>
	</div>

<?php

$footer_style = 'index';
require PUN_ROOT.'footer.php';