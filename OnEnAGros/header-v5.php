<?php

/**
 * Copyright (C) 2008-2012 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
	exit;

global $db, $oeag;

// Send no-cache headers
header('Expires: Thu, 21 Jul 1977 07:30:00 GMT'); // When yours truly first set eyes on this world! :)
header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache'); // For HTTP/1.0 compatibility

// Send the Content-type header in case the web server is setup to send something else
header('Content-type: text/html; charset=utf-8');

// Load the template
if (defined('PUN_ADMIN_CONSOLE'))
	$tpl_file = 'admin.tpl';
else if (defined('PUN_HELP'))
	$tpl_file = 'help.tpl';
else
	$tpl_file = 'main.tpl';

if (file_exists(PUN_ROOT.'style/'.$pun_user['style'].'/'.$tpl_file))
{
	$tpl_file = PUN_ROOT.'style/'.$pun_user['style'].'/'.$tpl_file;
	$tpl_inc_dir = PUN_ROOT.'style/'.$pun_user['style'].'/';
}
else
{
	$tpl_file = PUN_ROOT.'include/template/'.$tpl_file;
	$tpl_inc_dir = PUN_ROOT.'include/user/';
}

$tpl_main = file_get_contents($tpl_file);

// START SUBST - <pun_include "*">
preg_match_all('%<pun_include "([^"]+)">%i', $tpl_main, $pun_includes, PREG_SET_ORDER);

foreach ($pun_includes as $cur_include)
{
	ob_start();

	$file_info = pathinfo($cur_include[1]);
	
	if (!in_array($file_info['extension'], array('php', 'php4', 'php5', 'inc', 'html', 'txt'))) // Allow some extensions
		error(sprintf($lang_common['Pun include extension'], pun_htmlspecialchars($cur_include[0]), basename($tpl_file), pun_htmlspecialchars($file_info['extension'])));
		
	if (strpos($file_info['dirname'], '..') !== false) // Don't allow directory traversal
		error(sprintf($lang_common['Pun include directory'], pun_htmlspecialchars($cur_include[0]), basename($tpl_file)));

	// Allow for overriding user includes, too.
	if (file_exists($tpl_inc_dir.$cur_include[1]))
		require $tpl_inc_dir.$cur_include[1];
	else if (file_exists(PUN_ROOT.'include/user/'.$cur_include[1]))
		require PUN_ROOT.'include/user/'.$cur_include[1];
	else
		error(sprintf($lang_common['Pun include error'], pun_htmlspecialchars($cur_include[0]), basename($tpl_file)));

	$tpl_temp = ob_get_contents();
	$tpl_main = str_replace($cur_include[0], $tpl_temp, $tpl_main);
	ob_end_clean();
}
// END SUBST - <pun_include "*">


// START SUBST - <pun_language>
$tpl_main = str_replace('<pun_language>', $lang_common['lang_identifier'], $tpl_main);
// END SUBST - <pun_language>


// START SUBST - <pun_content_direction>
$tpl_main = str_replace('<pun_content_direction>', $lang_common['lang_direction'], $tpl_main);
// END SUBST - <pun_content_direction>


// START SUBST - <pun_head>
ob_start();

// Define $p if it's not set to avoid a PHP notice
$p = isset($p) ? $p : null;

// Is this a page that we want search index spiders to index?
if (!defined('PUN_ALLOW_INDEX'))
	echo '<meta name="ROBOTS" content="NOINDEX, FOLLOW" />'."\n";

?>
    <title><?php echo generate_page_title($page_title, $p) ?></title>
    <link rel="stylesheet" type="text/css" href="style/<?php echo $pun_user['style'].'.css' ?>" />
    <!--<link rel="stylesheet" type="text/css" href="OnEnAGros/css/bootstrap.min.css" />-->
    <link rel="stylesheet" type="text/css" href="OnEnAGros/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Raleway:400" />
    <link rel="shortcut icon" href="OnEnAGros/img/favicon.png" />

    <script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript" src="OnEnAGros/js/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="OnEnAGros/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="OnEnAGros/js/custom.js"></script>
<?php

if (defined('PUN_ADMIN_CONSOLE'))
{
	if (file_exists(PUN_ROOT.'style/'.$pun_user['style'].'/base_admin.css'))
		echo '<link rel="stylesheet" type="text/css" href="style/'.$pun_user['style'].'/base_admin.css" />'."\n";
	else
		echo '<link rel="stylesheet" type="text/css" href="style/imports/base_admin.css" />'."\n";
}

if (isset($required_fields))
{
	// Output JavaScript to validate form (make sure required fields are filled out)

?>
<script type="text/javascript">
/* <![CDATA[ */
function process_form(the_form)
{
	var required_fields = {
<?php
	// Output a JavaScript object with localised field names
	$tpl_temp = count($required_fields);
	foreach ($required_fields as $elem_orig => $elem_trans)
	{
		echo "\t\t\"".$elem_orig.'": "'.addslashes(str_replace('&#160;', ' ', $elem_trans));
		if (--$tpl_temp) echo "\",\n";
		else echo "\"\n\t};\n";
	}
?>
	if (document.all || document.getElementById)
	{
		for (var i = 0; i < the_form.length; ++i)
		{
			var elem = the_form.elements[i];
			if (elem.name && required_fields[elem.name] && !elem.value && elem.type && (/^(?:text(?:area)?|password|file)$/i.test(elem.type)))
			{
				alert('"' + required_fields[elem.name] + '" <?php echo $lang_common['required field'] ?>');
				elem.focus();
				return false;
			}
		}
	}
	return true;
}
/* ]]> */
</script>
<?php

}

// JavaScript tricks for IE6 and older
echo '<!--[if lte IE 6]><script type="text/javascript" src="style/imports/minmax.js"></script><![endif]-->'."\n";

if (isset($page_head))
	echo implode("\n", $page_head)."\n";

$tpl_temp = trim(ob_get_contents());
$tpl_main = str_replace('<pun_head>', $tpl_temp, $tpl_main);
ob_end_clean();
// END SUBST - <pun_head>


// START SUBST - <body>
if (isset($focus_element))
{
	$tpl_main = str_replace('<body onload="', '<body onload="document.getElementById(\''.$focus_element[0].'\').elements[\''.$focus_element[1].'\'].focus();', $tpl_main);
	$tpl_main = str_replace('<body>', '<body onload="document.getElementById(\''.$focus_element[0].'\').elements[\''.$focus_element[1].'\'].focus()">', $tpl_main);
}
// END SUBST - <body>


// START SUBST - <pun_page>
$tpl_main = str_replace('<pun_page>', htmlspecialchars(basename($_SERVER['PHP_SELF'], '.php')), $tpl_main);
// END SUBST - <pun_page>


// START SUBST - <pun_title>
// $tpl_main = str_replace('<pun_title>', '<h1><a href="index.php">'.pun_htmlspecialchars($pun_config['o_board_title']).'</a></h1>', $tpl_main);
if ( $pun_user['is_guest'] )
	$tpl_main = str_replace('<pun_title>', '', $tpl_main);
else {
	$pun_title   = array();
	$pun_title[] = '          <div class="nav user_welcome">'."\n";
	$pun_title[] = '              <span class="user_name">'.pun_htmlspecialchars($pun_user['username']).'</span>'."\n";
	$pun_title[] = '              <span class="user_title">'.pun_htmlspecialchars($pun_user['title']).'</span>'."\n";
	$pun_title[] = '          </div>'."\n";

	$pun_title[] = '          <div class="nav user_search">'."\n";
	$pun_title[] = '              <form id="search" method="get" action="search.php">'."\n";
	$pun_title[] = '                  <input type="hidden" name="action" value="search" />'."\n";
	$pun_title[] = '                  <input type="text" class="search_query" name="keywords" size="24" placeholder="Elle est où la poulette ?" maxlength="100" />'."\n";
	$pun_title[] = '                  <input type="submit" class="search_submit icon-search" name="search" value="&#xf002;" accesskey="s">'."\n";
	$pun_title[] = '              </form>'."\n";
	$pun_title[] = '          </div>'."\n";

	$tpl_main = str_replace('<pun_title>', implode( '', $pun_title ), $tpl_main);
}
// END SUBST - <pun_title>


// START SUBST - <pun_desc>
// $tpl_main = str_replace('<pun_desc>', '<div id="brddesc">'.$pun_config['o_board_desc'].'</div>', $tpl_main);
$tpl_main = str_replace('<pun_desc>', '', $tpl_main);
// END SUBST - <pun_desc>


// START SUBST - <pun_navlinks>
$links = array();

// Index should always be displayed
$links[] = '<li id="navindex" class="nav '.((PUN_ACTIVE_PAGE == 'index') ? 'isactive' : '').'"><a href="index.php" data-original-title="'.$lang_common['Index'].'"><i class="icon-home"></i></a></li>';

if ($pun_user['g_read_board'] == '1' && $pun_user['g_view_users'] == '1')
	$links[] = '<li id="navuserlist" class="nav '.((PUN_ACTIVE_PAGE == 'userlist') ? 'isactive' : '').'"><a href="userlist.php" data-original-title="'.$lang_common['User list'].'"> <i class="icon-group"></i></a></li>';

if (!$pun_user['is_guest'])
	$links[] = '<li id="navirc" class="nav '.((PUN_ACTIVE_PAGE == 'irc') ? 'isactive' : '').'"><a href="irc.php" data-original-title="Chat"><i class="icon-comments"></i></a></li>';

if ($pun_config['o_rules'] == '1' && (!$pun_user['is_guest'] || $pun_user['g_read_board'] == '1' || $pun_config['o_regs_allow'] == '1'))
	$links[] = '<li id="navrules" class="nav '.((PUN_ACTIVE_PAGE == 'rules') ? 'isactive' : '').'"><a href="misc.php?action=rules" data-original-title="'.$lang_common['Rules'].'"><i class="icon-legal"></i></a></li>';

//if ($pun_user['g_read_board'] == '1' && $pun_user['g_search'] == '1')
//	$links[] = '<li id="navsearch" class="nav '.((PUN_ACTIVE_PAGE == 'search') ? 'isactive' : '').'"><a href="search.php" data-original-title="'.$lang_common['Search'].'"><i class="icon-search"></i></a></li>';

$links[] = '<li id="navsite" class="nav"><a href="http://www.onenagros.org/" data-original-title="Allez sur le site OnEnAGros!"><img src="/OnEnAGros/img/favicon.png" alt="OnEnAGros!"></a></li>';

// Are there any additional navlinks we should insert into the array before imploding it?
if ($pun_user['g_read_board'] == '1' && $pun_config['o_additional_navlinks'] != '')
{
	if (preg_match_all('%([0-9]+)\s*=\s*(.*?)\n%s', $pun_config['o_additional_navlinks']."\n", $extra_links))
	{
		// Insert any additional links into the $links array (at the correct index)
		$num_links = count($extra_links[1]);
		for ($i = 0; $i < $num_links; ++$i)
			array_splice($links, $extra_links[1][$i], 0, array('<li id="navextra'.($i + 1).'" class="nav">'.$extra_links[2][$i].'</li>'));
	}
}

$links = implode("\n\t\t\t\t", $links);
//$tpl_temp = '<div id="brdmenu" class="inbox">'."\n\t\t\t".'<ul>'."\n\t\t\t\t".implode("\n\t\t\t\t", $links)."\n\t\t\t".'</ul>'."\n\t\t".'</div>';
//$tpl_main = str_replace('<pun_navlinks>', $tpl_temp, $tpl_main);
$tpl_main = str_replace('<pun_navlinks>', '', $tpl_main);
// END SUBST - <pun_navlinks>

// START SUBST - <pun_headmenu>
$headmenu = array();

$headmenu[] = '<li id="home"><a href="http://www.onenagros.org/"><img src="OnEnAGros/img/logo.png" alt="OnEnAGros!"></a></li>';
$headmenu[] = $links;
$headmenu[] = '<li id="socialz"><ul>';
$headmenu[] = '<li><a href="https://www.facebook.com/OnEnAGros"><i class="icon-facebook"></i></a></li>';
$headmenu[] = '<li><a href="https://twitter.com/OnEnAGros"><i class="icon-twitter"></i></a></li>';
$headmenu[] = '<li><a href="https://www.google.com/onenagros"><i class="icon-google-plus"></i></a></li>';
$headmenu[] = '</ul></li>';

$headmenu_ = '<ul>'."\n\t\t\t\t".implode("\n\t\t\t\t", $headmenu)."\n\t\t\t".'</ul>';
// END SUBST - <pun_headmenu>


// START SUBST - <pun_status>
$page_statusinfo = $page_topicsearches = array();

if ($pun_user['is_guest']) {
	$page_statusinfo[] = '<li class="user_avatar_container"><ul><li class="user_avatar" style="background-image: url(/OnEnAGros/img/h0.png);"></li></ul></li>';
	$page_statusinfo[] = '<li class="brdmenu user_navprofile"><a class="brdmenu user_register" href="register.php" data-original-title="'.$lang_common['Register'].'"><i class="icon-terminal"></i></a></li>';
	$page_statusinfo[] = '<li class="brdmenu user_navprofile"><a class="brdmenu user_login" href="login.php" data-original-title="'.$lang_common['Login'].'"><i class="icon-signin"></i></a></li>';
}
else
{
	$avatar = generate_avatar_markup($pun_user['id'], true);
	if ( $avatar != '' )
		$page_statusinfo[] = '<li class="user_avatar_container"><ul><li class="user_avatar" style="background-image: url('.$avatar.');"></li></ul></li>';
	
	$page_statusinfo[] = '<li class="brdmenu user_navprofile"><a class="brdmenu user_profile" href="profile.php?id='.$pun_user['id'].'" data-original-title="'.$lang_common['Profile'].'"><i class="icon-user"></i></a></li>';
	
	if ($pun_user['g_pm'] == '1' && $pun_user['use_pm'] == '1' && $pun_config['o_pms_enabled'] == '1')
	{
		$result_messages = $db->query('SELECT COUNT(id) FROM '.$db->prefix.'messages WHERE showed=0 AND show_message=1 AND owner='.$pun_user['id']) or error('Unable to check the availibility of new messages', __FILE__, __LINE__, $db->error());
		$num_new_pm = $db->result($result_messages);
		
		if ($num_new_pm > 0)
			$page_statusinfo[] = '<li class="brdmenu user_mp"><a class="user_mp_on gotapm" href="pms_inbox.php" data-original-title="'.$lang_pms['Private Messages'].'"><i class="icon-envelope"></i><sup>'.$num_new_pm.'</sup></a></li>';
		else
			$page_statusinfo[] = '<li class="brdmenu user_mp"><a class="empty" href="pms_inbox.php" data-original-title="'.$lang_pms['Private Messages'].'"><i class="icon-envelope"></i></a></li>';
	}

	if ($pun_user['is_admmod'])
		$page_statusinfo[] = '<li class="brdmenu user_admin"><a class="" href="admin_index.php" data-original-title="'.$lang_common['Admin'].'"><i class="icon-cogs"></i></a></li>';

	if (!$pun_user['is_guest'])
		$page_statusinfo[] = '<li class="brdmenu user_pix"><a class="" href="http://pix.onenagros.org" data-original-title="Envoi d’images"><i class="icon-upload-alt"></i></a></li>';
	
	$page_statusinfo[] = '<li class="brdmenu user_logout"><a class="" href="login.php?action=out&amp;id='.$pun_user['id'].'&amp;csrf_token='.pun_hash($pun_user['id'].pun_hash(get_remote_address())).'" data-original-title="'.$lang_common['Logout'].'"><i class="icon-signout"></i></a></li>';

	$page_statusinfo[] = '<li class="brdmenu user_stats">';
	$page_statusinfo[] = '<span><i class="icon-time"></i> '.sprintf( $lang_common['Last visit'], format_time( $pun_user['last_visit'] ) ).'</span>';
	$page_statusinfo[] = '<span><i class="icon-edit"></i> '.$pun_user['num_posts'].' '.( $pun_user['num_posts'] > 1 ? strtolower( $lang_common['Posts'] ) : strtolower( $lang_common['Message'] ) ).'</span>';

	$results = $db->query( 'SELECT COUNT(*) AS c FROM '.$db->prefix.'topics WHERE poster LIKE "'.$pun_user['username'].'"' );
	if ( $result = $db->fetch_assoc( $results ) )
		$page_statusinfo[] = '<span><i class="icon-list"></i> '.$result['c'].' '.strtolower( $lang_common['Topic'] ).( $result['c'] > 1 ? 's' : '' ).'</span>';

	if ($pun_user['g_read_board'] == '1' && $pun_user['g_search'] == '1')
	{
		$page_topicsearches[] = '<li class="brdmenu show_new"><a href="search.php?action=show_new" title="'.$lang_common['Show new posts'].'" data-original-title="'.$lang_common['New posts header'].'"><i class="icon-star"></i></a></li>';
		$page_topicsearches[] = '<li class="brdmenu show_replies"><a href="search.php?action=show_replies" title="'.$lang_common['Show posted topics'].'" data-original-title="'.$lang_common['Posted topics'].'"><i class="icon-comment"></i></a></li>';
	}
}

// Quick searches
if ($pun_user['g_read_board'] == '1' && $pun_user['g_search'] == '1')
{
	$page_topicsearches[] = '<li class="brdmenu show_recent"><a href="search.php?action=show_recent" title="'.$lang_common['Show active topics'].'" data-original-title="'.$lang_common['Active topics'].'"><i class="icon-time"></i></a></li>';
	$page_topicsearches[] = '<li class="brdmenu show_unanswered"><a href="search.php?action=show_unanswered" title="'.$lang_common['Show unanswered topics'].'" data-original-title="'.$lang_common['Unanswered topics'].'"><i class="icon-comment-alt"></i></a></li>';
}

// OEAG latest news
$page_oeag = $wp_page = array();

//$wp_page[] = '<li><strong>16/05</strong> : <a href="#">Nouvelle version… Malgré tout !</a></li>';

ob_start();

$result_wp = $db->query("SELECT ID, post_date, post_name, post_title FROM wp_posts WHERE post_type='post' AND post_status='publish' ORDER BY post_date DESC LIMIT 4;");
$first = true;

while ( $assoc = $db->fetch_assoc( $result_wp ) ) {
	$post_date = substr( $assoc['post_date'], 8, 2 )."/".substr( $assoc['post_date'], 5, 2 );
	$post_title = strip_tags( $assoc['post_title'] );
	$post_title = ( strlen( $post_title ) > 25 ? substr( $post_title, 0, 25 ).'…' : $post_title );
	$url = "http://www.onenagros.org/".substr( $assoc['post_date'], 0, 10 )."-".$assoc['post_name'].".html";
	$wp_page[] = '<li class="wp_post"><span class="wp_post_date">'.$post_date.'</span><span class="wp_post_url"><a href="'.$url.'">'.$post_title.'</span></a></li>';
}

ob_end_clean();

$page_oeag[] = "\n\t\t\t\t".'<ul>';
$page_oeag[] = "\n\t\t\t\t\t".'<li class="wp_index"><span class="wp_post_date">Derniers articles sur le site :</span></li>';
$page_oeag[] = "\n\t\t\t\t\t".implode("\n\t\t\t\t\t", $wp_page);
$page_oeag[] = "\n\t\t\t\t".'</ul>';

// Generate all that jazz
$tpl_temp = '<div id="brdwelcome" class="inbox">';

// The status information
if (is_array($page_statusinfo))
{
	$tpl_temp .= "\n\t\t\t".'<ul class="conl">';
	$tpl_temp .= "\n\t\t\t\t".implode("\n\t\t\t\t", $page_statusinfo);
	$tpl_temp .= "\n\t\t\t".'</ul>';
}
else
	$tpl_temp .= "\n\t\t\t".$page_statusinfo;

// Generate quicklinks
if (!empty($page_topicsearches))
{
	$tpl_temp .= "\n\t\t\t".'<ul class="conr">';
	$tpl_temp .= "\n\t\t\t\t".'<li class="brdmenu show_"><a data-original-title="'.$lang_common['Topic searches'].'"><i class="icon-rss"></i></a></li>'."\n".implode("\n\t\t\t\t", $page_topicsearches).'';
	$tpl_temp .= "\n\t\t\t".'</ul>';
}

$tpl_temp .= "\n\t\t".'</div>';

$tpl_temp .= "\n\t\t\t".'<div id="brdheadmenu" class="links">';
$tpl_temp .= "\n\t\t\t\t".'<ul>';
$tpl_temp .= "\n\t\t\t\t\t".$links;
$tpl_temp .= "\n\t\t\t\t".'</ul>';
$tpl_temp .= "\n\t\t\t".'</div>';

if (is_array($page_oeag)) {
	$tpl_temp .= "\n\t\t\t".'<div class="boxnews">';
	$tpl_temp .= "\n\t\t\t\t".implode("",$page_oeag);
	$tpl_temp .= "\n\t\t\t".'</div>';
}

$tpl_temp .= "\n\t\t\t".'<div id="announce" class="block">';
$tpl_temp .= "\n\t\t\t\t".'<div class="inbox">';
$tpl_temp .= "\n\t\t\t\t\t".'<h4 class="quote">Bienvenue sur <strong>OnEnAGros!</strong>, '.$oeag->oeag_random_quote().' !</h4>';
$tpl_temp .= "\n\t\t\t\t\t".'<p>'.$pun_config['o_announcement_message'].'</p>'.'<br />';

if ($pun_user['is_admmod'])
{
	if ($pun_config['o_report_method'] == '0' || $pun_config['o_report_method'] == '2')
	{
		$result_header = $db->query('SELECT 1 FROM '.$db->prefix.'reports WHERE zapped IS NULL') or error('Unable to fetch reports info', __FILE__, __LINE__, $db->error());
		if ($db->result($result_header))
			$page_statusinfo[] = '<li class="reportlink"><span><strong><a href="admin_reports.php">'.$lang_common['New reports'].'</a></strong></span></li>';
	}
	if ($pun_config['o_maintenance'] == '1')
		$page_statusinfo[] = '<li class="maintenancelink"><span><strong><a href="admin_options.php#maintenance">'.$lang_common['Maintenance mode enabled'].'</a></strong></span></li>';
}

$tpl_temp .= "\n\t\t\t\t".'</div>';
$tpl_temp .= "\n\t\t\t".'</div>';

$tpl_main = str_replace('<pun_announcement>', '', $tpl_main);
$tpl_main = str_replace('<pun_status>', $tpl_temp, $tpl_main);
// END SUBST - <pun_status>

// START SUBST - <pun_main>
ob_start();


define('PUN_HEADER', 1);
