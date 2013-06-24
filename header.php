<?php

/**
 * Copyright (C) 2008-2012 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
	exit;

if ( $pun_user['style'] == 'OnEnAGros-v5' ) {
    require PUN_ROOT.'OnEnAGros/header-v5.php';
    return false;
}

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
    <link rel="shortcut icon" href="style/OnEnAGros/img/favicon.png" />

    <script type="text/javascript" src="//code.jquery.com/jquery-latest.js"></script>
    <script type="text/javascript" src="OnEnAGros/js/jquery.tablesorter.min.js"></script>
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
$tpl_main = str_replace('<pun_title>', '', $tpl_main);
// END SUBST - <pun_title>


// START SUBST - <pun_desc>
// $tpl_main = str_replace('<pun_desc>', '<div id="brddesc">'.$pun_config['o_board_desc'].'</div>', $tpl_main);
$tpl_main = str_replace('<pun_desc>', '', $tpl_main);
// END SUBST - <pun_desc>


// START SUBST - <pun_navlinks>
$links = array();

// Index should always be displayed
$links[] = '<li id="navindex"'.((PUN_ACTIVE_PAGE == 'index') ? ' class="isactive"' : '').'><a class="brdmenu" href="index.php"> <i></i> <span>'.$lang_common['Index'].'</span></a></li>';

if ($pun_user['g_read_board'] == '1' && $pun_user['g_view_users'] == '1')
	$links[] = '<li id="navuserlist"'.((PUN_ACTIVE_PAGE == 'userlist') ? ' class="isactive"' : '').'><a class="brdmenu" href="userlist.php"> <i></i> <span>'.$lang_common['User list'].'</span></a></li>';

if (!$pun_user['is_guest'])
	$links[] = '<li id="navirc"'.((PUN_ACTIVE_PAGE == 'irc') ? ' class="isactive"' : '').'><a class="brdmenu" href="irc.php"> <i></i> <span>Chat</span></a></li>';

if ($pun_config['o_rules'] == '1' && (!$pun_user['is_guest'] || $pun_user['g_read_board'] == '1' || $pun_config['o_regs_allow'] == '1'))
	$links[] = '<li id="navrules"'.((PUN_ACTIVE_PAGE == 'rules') ? ' class="isactive"' : '').'><a class="brdmenu" href="misc.php?action=rules"> <i></i> <span>'.$lang_common['Rules'].'</span></a></li>';

if ($pun_user['g_read_board'] == '1' && $pun_user['g_search'] == '1')
	$links[] = '<li id="navsearch"'.((PUN_ACTIVE_PAGE == 'search') ? ' class="isactive"' : '').'><a class="brdmenu" href="search.php"> <i></i> <span>'.$lang_common['Search'].'</span></a></li>';

if ($pun_user['is_admmod'])
	$links[] = '<li id="navadmin"'.((PUN_ACTIVE_PAGE == 'admin') ? ' class="isactive"' : '').'><a class="brdmenu" href="admin_index.php"> <i></i> <span>'.$lang_common['Admin'].'</span></a></li>';

// Are there any additional navlinks we should insert into the array before imploding it?
if ($pun_user['g_read_board'] == '1' && $pun_config['o_additional_navlinks'] != '')
{
	if (preg_match_all('%([0-9]+)\s*=\s*(.*?)\n%s', $pun_config['o_additional_navlinks']."\n", $extra_links))
	{
		// Insert any additional links into the $links array (at the correct index)
		$num_links = count($extra_links[1]);
		for ($i = 0; $i < $num_links; ++$i)
			array_splice($links, $extra_links[1][$i], 0, array('<li id="navextra'.($i + 1).'">'.$extra_links[2][$i].'</li>'));
	}
}

$tpl_temp = '<div id="brdmenu" class="inbox">'."\n\t\t\t".'<ul>'."\n\t\t\t\t".implode("\n\t\t\t\t", $links)."\n\t\t\t".'</ul>'."\n\t\t".'</div>';
$tpl_main = str_replace('<pun_navlinks>', $tpl_temp, $tpl_main);
// END SUBST - <pun_navlinks>


// START SUBST - <pun_status>
$page_statusinfo = $page_topicsearches = array();

if ($pun_user['is_guest']) {
	$page_statusinfo[] = '<li class="user_avatar" style="background-image: url('.generate_avatar_markup(3, true).');"></li>';
	$page_statusinfo[] = '<li class="nav user_welcome"><span class="user_logout">'.$lang_common['Not logged in'].'</span></li>';
	$page_statusinfo[] = '<li class="nav user_navprofile"><span class="user_register"><a class="brdmenu" href="register.php">'.$lang_common['Register'].'</a></span></li>';
	$page_statusinfo[] = '<li class="nav user_navprofile"><span class="user_login"><a class="brdmenu" href="login.php">'.$lang_common['Login'].'</a></span>';
}
else
{
	$avatar = generate_avatar_markup($pun_user['id'], true);
	if ($avatar != '') {
		$page_statusinfo[] = '<li class="user_avatar" style="background-image: url('.$avatar.');"></li>';
	}
	
	$page_statusinfo[] = '<li class="nav user_welcome"><span class="user_logout">'.$lang_common['Logged in as'].' <strong>'.pun_htmlspecialchars($pun_user['username']).'</strong></span></li>';
	$page_statusinfo[] = '<li class="nav user_lastvisit"><span class="user_lastlog">'.sprintf($lang_common['Last visit'], format_time($pun_user['last_visit'])).'</span></li>';
	
	$page_statusinfo[] = '<li class="nav user_navprofile">';
	$page_statusinfo[] = '<span class="user_profile"><a class="brdmenu" href="profile.php?id='.$pun_user['id'].'">'.$lang_common['Profile'].'</a></span>';
	
	require PUN_ROOT.'plugins/apms/header_add1.php';
	
	$page_statusinfo[] = '<span class="user_logout"><a href="login.php?action=out&amp;id='.$pun_user['id'].'&amp;csrf_token='.pun_hash($pun_user['id'].pun_hash(get_remote_address())).'">'.$lang_common['Logout'].'</a></span>';
	$page_statusinfo[] = '</li>';

	if ($pun_user['g_read_board'] == '1' && $pun_user['g_search'] == '1')
	{
		$page_topicsearches[] = '<a href="search.php?action=show_new" title="'.$lang_common['Show new posts'].'">'.$lang_common['New posts header'].'</a>';
		$page_topicsearches[] = '<a href="search.php?action=show_replies" title="'.$lang_common['Show posted topics'].'">'.$lang_common['Posted topics'].'</a>';
	}
}

// Quick searches
if ($pun_user['g_read_board'] == '1' && $pun_user['g_search'] == '1')
{
	$page_topicsearches[] = '<a href="search.php?action=show_recent" title="'.$lang_common['Show active topics'].'">'.$lang_common['Active topics'].'</a>';
	$page_topicsearches[] = '<a href="search.php?action=show_unanswered" title="'.$lang_common['Show unanswered topics'].'">'.$lang_common['Unanswered topics'].'</a>';
}


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

$tpl_temp .= "\n\t\t\t".'<div id="announce" class="block">';
$tpl_temp .= "\n\t\t\t\t".'<div class="box">';
$tpl_temp .= "\n\t\t\t\t\t".'<div id="announce-block" class="inbox">';
$tpl_temp .= "\n\t\t\t\t\t\t".'<div class="usercontent">';
$tpl_temp .= "\n\t\t\t\t\t\t\t".$pun_config['o_announcement_message'].'<br />';

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

$tpl_temp .= "\n\t\t\t\t\t\t".'</div>';
$tpl_temp .= "\n\t\t\t\t\t".'</div>';
$tpl_temp .= "\n\t\t\t\t".'</div>';
$tpl_temp .= "\n\t\t\t".'</div>';
$tpl_temp .= "\n\t\t\t".'<div style="clear:both"></div>';

// Generate quicklinks
if (!empty($page_topicsearches))
{
	$tpl_temp .= "\n\t\t\t".'<ul class="conr">';
	$tpl_temp .= "\n\t\t\t\t".'<li><span>'.$lang_common['Topic searches'].' '.implode(' | ', $page_topicsearches).'</span></li>';
	$tpl_temp .= "\n\t\t\t".'</ul>';
}

$tpl_temp .= "\n\t\t\t".'<div class="boxnews">';
$tpl_temp .= "\n\t\t\t\t".'<span>Derniers articles sur le site :</span>';
$tpl_temp .= "\n\t\t\t\t".'<ul>';

ob_start();

$result_wp = $db->query("SELECT ID, post_date, post_name, post_title FROM wp_posts WHERE post_type='post' AND post_status='publish' ORDER BY post_date DESC LIMIT 2;");
$first = true;

while($assoc = $db->fetch_assoc($result_wp)) {
	$post_date = substr($assoc['post_date'],8,2)."/".substr($assoc['post_date'],5,2);
	$post_title = $assoc['post_title'];
	$url = "http://www.onenagros.org/".substr($assoc['post_date'],0,10)."-".$assoc['post_name'].".html";
	$tpl_temp .= "\n\t\t\t\t".'<li';
	if ( $first ) {
		$tpl_temp .= ' class="visible"';
		$first = false;
	}
	$tpl_temp .= '><b>'.$post_date.'</b> − <a href="'.$url.'">'.$post_title.'</a></li>';
}

ob_end_clean();

$tpl_temp .= "\n\t\t\t\t".'</ul>';
$tpl_temp .= "\n\t\t\t".'</div>';

$tpl_temp .= "\n\t\t\t".'<div class="clearer"></div>'."\n\t\t".'</div>';

$tpl_main = str_replace('<pun_status>', $tpl_temp, $tpl_main);
// END SUBST - <pun_status>

// START SUBST - <pun_main>
ob_start();


define('PUN_HEADER', 1);
