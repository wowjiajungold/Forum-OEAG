<?php

/**
 * Copyright (C) 2008-2012 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

define('PUN_ROOT', dirname(__FILE__).'/');
define('PUN_ACTIVE_PAGE', 'stats-users');
require PUN_ROOT.'include/common.php';
require PUN_ROOT.'lang/'.$pun_user['language'].'/index.php';

// if ($pun_user['is_guest'])
//     message($lang_common['No permission']);

$page_title = array( pun_htmlspecialchars( 'Statistiques des utilisateurs' ), $pun_config['o_board_title'] );
require PUN_ROOT.'header.php';

$result = $db->query('SELECT id, username, num_posts, registered FROM users WHERE last_visit >= '.( time() - 2592000 ).' OR last_post >= '.( time() - 2592000 ).' ORDER BY num_posts DESC') or error('Unable to fetch data', __FILE__, __LINE__, $db->error());
$users = array();

while ( $r = $db->fetch_assoc( $result ) ) {
    $users[$r['id']] = array(
        'id'             => $r['id'],
        'username'       => $r['username'],
        'num_posts'      => $r['num_posts'],
        'num_topics'     => 0,
        'registered'     => $r['registered'],
        'present_day'    => 0,
        'post_avg_day'   => 0,
        'post_avg_year'  => 0,
        'topic_avg_day'  => 0,
        'topic_avg_year' => 0,
        'posts'          => array(),
    );
    
    $result2 = $db->query('SELECT COUNT(id) AS c FROM topics WHERE poster = "'.$r['username'].'" LIMIT 1') or error('Unable to fetch data', __FILE__, __LINE__, $db->error());
    while ( $r2 = $db->fetch_assoc( $result2 ) )
        $users[$r['id']]['num_topics'] = $r2['c'];
    
    $date  = date( "Y\-m\-d", $r['registered'] );
    $now   = new DateTime("now");
    $start = new DateTime( $date );
    
    $interval = $now->diff( $start );
    $_day  = ($interval->format( '%a' ) == 0 ? 1 : $interval->format( '%a' ) );
    $_year = ( $interval->format( '%y' ) == 0 ? 1 : $interval->format( '%y' ) );
    
    $users[$r['id']]['present_day']   = $_day;
    $users[$r['id']]['post_avg_day']  = round( ( $r['num_posts'] / $_day ), 2 );
    $users[$r['id']]['post_avg_year'] = round( ( $r['num_posts'] / $_year ), 2 );
    $users[$r['id']]['topic_avg_day']  = round( ( $r['num_topics'] / $_day ), 2 );
    $users[$r['id']]['topic_avg_year'] = round( ( $r['num_topics'] / $_year ), 2 );
}
?>

<div id="vf" class="blocktable">
	<h2><span class="cat_title"></span></h2>
	<div class="box">
		<div class="inbox">
			<table class="tablesorter">
			<thead>
				<tr>
					<th class="tc2" scope="col" style="color:#fff">Membres</th>
					<th class="tc2" scope="col">Nombre de messages</th>
					<th class="tc2" scope="col">Moyenne quotidienne</th>
					<th class="tc2" scope="col">Moyenne annuelle</th>
					<th class="tc2" scope="col">Nombre de discussions</th>
					<th class="tc2" scope="col">Moyenne quotidienne</th>
					<th class="tc2" scope="col">Moyenne annuelle</th>
					<th class="tc2" scope="col">Inscription</th>
					<th class="tc2" scope="col">Nombre de jours</th>
				</tr>
			</thead>
			<tbody>
<?php foreach ( $users as $user ) { ?>
				<tr class="">
					<td class="tc2"><a href="profile.php?id=<?php echo $user['id']; ?>"><?php echo $user['username']; ?></a></td>
					<td class="tc2"><?php echo $user['num_posts']; ?></td>
					<td class="tc2"><?php echo ( $user['post_avg_day'] == 0 ? '-' : $user['post_avg_day'] ); ?></td>
					<td class="tc2"><?php echo ( $user['post_avg_year'] == 0 ? '-' : $user['post_avg_year'] ); ?></td>
					<td class="tc2"><?php echo $user['num_topics']; ?></td>
					<td class="tc2"><?php echo ( $user['topic_avg_day'] == 0 ? '-' : $user['topic_avg_day'] ); ?></td>
					<td class="tc2"><?php echo ( $user['topic_avg_year'] == 0 ? '-' : $user['topic_avg_year'] ); ?></td>
					<td class="tc2"><?php echo date( 'd\/m\/Y', $user['registered'] ); ?></td>
					<td class="tc2"><?php echo $user['present_day']; ?></td>
				</tr>

<?php } ?>
</tbody>
			</table>
		</div>
	</div>
</div>
<style>
.tablesorter  {
    border-collapse: separate;
    border-spacing: 0; 
    border-radius: 3px;
    -webkit-border-radius: 3px;
    border: 1px solid #333;
    width: 95%;
}

.tablesorter thead {
    background: #333;
    color: #fff;
}

.tablesorter thead tr .header {
    background-image: url(img/bg.png);
    background-repeat: no-repeat;
    background-position: center left;
    cursor: pointer;
    padding: 0 4px 0 18px !important;
    text-align: left;
    width: 5%;
}

.tablesorter thead tr .headerSortUp {
    background-image: url(img/asc.png);
}

.tablesorter thead tr .headerSortDown {
    background-image: url(img/desc.png);
}

.tablesorter td {
    background: transparent !important;
    border-top: 1px solid #aaa;
    padding: 8px;
}

.tablesorter tbody tr:first-child td {
    border-top: 0;
}

.tablesorter tbody tr:nth-child(odd) {
    background-color: #fff;
}

.tablesorter tbody tr:nth-child(even) {
    background-color: #eee;
}
</style>
<script type='text/javascript' src='include/jquery.tablesorter.min.js'></script>
<script type="text/javascript">jQuery(document).ready(function($) { console.log("sort"); $(".tablesorter").tablesorter({sortList: [[1,1]], dateFormat: 'uk'}); });</script>
<?php

$footer_style = 'index';
require PUN_ROOT.'footer.php';

?>