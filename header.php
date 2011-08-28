<?php

/**
 * Copyright (C) 2008-2011 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
	exit;

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
preg_match_all('%<pun_include "([^/\\\\]*?)\.(php[45]?|inc|html?|txt)">%i', $tpl_main, $pun_includes, PREG_SET_ORDER);

foreach ($pun_includes as $cur_include)
{
	ob_start();

	// Allow for overriding user includes, too.
	if (file_exists($tpl_inc_dir.$cur_include[1].'.'.$cur_include[2]))
		require $tpl_inc_dir.$cur_include[1].'.'.$cur_include[2];
	else if (file_exists(PUN_ROOT.'include/user/'.$cur_include[1].'.'.$cur_include[2]))
		require PUN_ROOT.'include/user/'.$cur_include[1].'.'.$cur_include[2];
	else
		error(sprintf($lang_common['Pun include error'], htmlspecialchars($cur_include[0]), basename($tpl_file)));

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

// Define $p if its not set to avoid a PHP notice
$p = isset($p) ? $p : null;

// Is this a page that we want search index spiders to index?
if (!defined('PUN_ALLOW_INDEX'))
	echo '<meta name="ROBOTS" content="NOINDEX, FOLLOW" />'."\n";

?>
<title><?php echo generate_page_title($page_title, $p) ?></title>
<link rel="stylesheet" type="text/css" href="style/<?php echo $pun_user['style'].'.css' ?>" />
<script type="text/javascript" src="http://code.jquery.com/jquery-latest.js"></script>
<script type="text/javascript" src="include/custom.js"></script>
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
	var element_names = {
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
			if (elem.name && (/^req_/.test(elem.name)))
			{
				if (!elem.value && elem.type && (/^(?:text(?:area)?|password|file)$/i.test(elem.type)))
				{
					alert('"' + element_names[elem.name] + '" <?php echo $lang_common['required field'] ?>');
					elem.focus();
					return false;
				}
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

if (file_exists(PUN_ROOT.'style/'.$pun_user['style'].'/pms.css'))
	echo '<link rel="stylesheet" type="text/css" href="style/'.$pun_user['style'].'/pms.css" />';
else
	echo '<link rel="stylesheet" type="text/css" href="style/imports/pms.css" />';

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
//$tpl_main = str_replace('<pun_title>', '<h1><a href="index.php">'.pun_htmlspecialchars($pun_config['o_board_title']).'</a></h1>', $tpl_main);
$tpl_main = str_replace('<pun_title>', '<img src="img/logo.png" alt="On en a gros !" />', $tpl_main);
// END SUBST - <pun_title>


// START SUBST - <pun_desc>
//$tpl_main = str_replace('<pun_desc>', '<div id="brddesc">'.$pun_config['o_board_desc'].'</div>', $tpl_main);
$tpl_main = str_replace('<pun_desc>', '', $tpl_main);
// END SUBST - <pun_desc>


// START SUBST - <pun_navlinks>
$links = array();

// Index should always be displayed
$links[] = '<li id="navindex"'.((PUN_ACTIVE_PAGE == 'index') ? ' class="isactive"' : '').'><a href="index.php">'.$lang_common['Index'].'</a></li>';

if ($pun_user['g_read_board'] == '1' && $pun_user['g_view_users'] == '1')
	$links[] = '<li id="navuserlist"'.((PUN_ACTIVE_PAGE == 'userlist') ? ' class="isactive"' : '').'><a href="userlist.php">'.$lang_common['User list'].'</a></li>';

if ($pun_config['o_rules'] == '1' && (!$pun_user['is_guest'] || $pun_user['g_read_board'] == '1' || $pun_config['o_regs_allow'] == '1'))
	$links[] = '<li id="navrules"'.((PUN_ACTIVE_PAGE == 'rules') ? ' class="isactive"' : '').'><a href="misc.php?action=rules">'.$lang_common['Rules'].'</a></li>';

if ($pun_user['g_read_board'] == '1' && $pun_user['g_search'] == '1')
	$links[] = '<li id="navsearch"'.((PUN_ACTIVE_PAGE == 'search') ? ' class="isactive"' : '').'><a href="search.php">'.$lang_common['Search'].'</a></li>';

if ($pun_user['is_guest'])
{
	$links[] = '<li id="navregister"'.((PUN_ACTIVE_PAGE == 'register') ? ' class="isactive"' : '').'><a href="register.php">'.$lang_common['Register'].'</a></li>';
	$links[] = '<li id="navlogin"'.((PUN_ACTIVE_PAGE == 'login') ? ' class="isactive"' : '').'><a href="login.php">'.$lang_common['Login'].'</a></li>';
}
else
{
	$links[] = '<li id="navprofile"'.((PUN_ACTIVE_PAGE == 'profile') ? ' class="isactive"' : '').'><a href="profile.php?id='.$pun_user['id'].'">'.$lang_common['Profile'].'</a></li>';
	
	if ($pun_config['o_pms_enabled'] == '1' && $pun_user['g_pm'] == '1' && $pun_user['use_pm'] == '1')
		$links[] = '<li id="navpm"'.((PUN_ACTIVE_PAGE == 'pm') ? ' class="isactive"' : '').'><a href="pms_inbox.php">'.$lang_pms['PM'].'</a></li>';

	if ($pun_user['is_admmod'])
		$links[] = '<li id="navadmin"'.((PUN_ACTIVE_PAGE == 'admin') ? ' class="isactive"' : '').'><a href="admin_index.php">'.$lang_common['Admin'].'</a></li>';

	$links[] = '<li id="navlogout"><a href="login.php?action=out&amp;id='.$pun_user['id'].'&amp;csrf_token='.pun_hash($pun_user['id'].pun_hash(get_remote_address())).'">'.$lang_common['Logout'].'</a></li>';
}

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

if ($pun_user['is_guest'])
	$page_statusinfo = '<p class="conl">'.$lang_common['Not logged in'].'</p>';
else
{
	$page_statusinfo[] = '<li><span>'.$lang_common['Logged in as'].' <strong>'.pun_htmlspecialchars($pun_user['username']).'</strong></span></li>';
	$page_statusinfo[] = '<li><span>'.sprintf($lang_common['Last visit'], format_time($pun_user['last_visit'])).'</span></li>';

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

	$num_new_pm = 0;
	if ($pun_user['g_pm'] == '1' && $pun_user['use_pm'] == '1' && $pun_config['o_pms_enabled'] == '1')
	{
		// Boxes status
		$pm_boxes_full = ($pun_user['num_pms'] >= $pun_user['g_pm_limit']) ? true : false;
		$pm_boxes_empty = ($pun_user['num_pms'] <= '0') ? true : false;
		if ($pun_user['g_pm_limit'] != '0' && !$pun_user['is_admmod'])
		{
			if ($pm_boxes_empty)
				$page_statusinfo[] = '<li><span>'.$lang_pms['Empty boxes'].'</span></li>';
			elseif ($pm_boxes_full)
				$page_statusinfo[] = '<li><span><a href="pms_inbox.php"><strong>'.$lang_pms['Full boxes'].'</strong></a></span></li>';
			else
			{
				$per_cent_box = ceil($pun_user['num_pms'] / $pun_user['g_pm_limit'] * '100');
				$page_statusinfo[] = '<li><span>'.sprintf($lang_pms['Full to'],$per_cent_box.'%').' <div id="mp_bar_ext"><div id="mp_bar_int" style="width:'.$per_cent_box.'px;"><!-- --></div></div></span></li>';
			}
		}
		
		// Check for new messages
		$result_messages = $db->query('SELECT COUNT(id) FROM '.$db->prefix.'messages WHERE showed=0 AND show_message=1 AND owner='.$pun_user['id']) or error('Unable to check the availibility of new messages', __FILE__, __LINE__, $db->error());
		$num_new_pm = $db->result($result_messages);
		
		if ($num_new_pm > 0)
			$page_statusinfo[] = '<li><span><a href="pms_inbox.php"><strong>'.($num_new_pm == '1' ? $lang_pms['New message'] : sprintf($lang_pms['New messages'],$num_new_pm)).'</strong></a></span></li>';		
	}

	if ($pun_user['g_read_board'] == '1' && $pun_user['g_search'] == '1')
	{
		$page_topicsearches[] = '<a href="search.php?action=show_replies" title="'.$lang_common['Show posted topics'].'">'.$lang_common['Posted topics'].'</a>';
		$page_topicsearches[] = '<a href="search.php?action=show_new" title="'.$lang_common['Show new posts'].'">'.$lang_common['New posts header'].'</a>';
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

// Generate quicklinks
if (!empty($page_topicsearches))
{
	$tpl_temp .= "\n\t\t\t".'<ul class="conr">';
	$tpl_temp .= "\n\t\t\t\t".'<li><span>'.$lang_common['Topic searches'].' '.implode(' | ', $page_topicsearches).'</span></li>';
	$tpl_temp .= "\n\t\t\t".'</ul>';
}

$tpl_temp .= "\n\t\t\t".'<div class="clearer"></div>'."\n\t\t".'</div>';

$tpl_main = str_replace('<pun_status>', $tpl_temp, $tpl_main);
// END SUBST - <pun_status>


// START SUBST - <pun_announcement>
if ($pun_user['g_read_board'] == '1' && $pun_config['o_announcement'] == '1')
{
	ob_start();

?>
<div id="announce" class="block">
	<div class="hd"><h2><span><?php echo $lang_common['Announcement'] ?></span></h2></div>
	<div class="box">
		<div id="announce-block" class="inbox">
			<div class="usercontent"><?php echo $pun_config['o_announcement_message'] ?></div>
		</div>
	</div>
    <div class="boxnews">
        <span>Derniers articles sur le site :</span><br />
        <ul>
<?php
$result_wp = $db->query("SELECT ID, post_date, post_name, post_title FROM wp_posts WHERE post_type='post' AND post_status='publish' ORDER BY post_date DESC LIMIT 3;");
$first = true;

while($assoc = $db->fetch_assoc($result_wp)) {
    $post_date = substr($assoc['post_date'],8,2)."/".substr($assoc['post_date'],5,2);
    $post_title = $assoc['post_title'];
    $url = "http://www.onenagros.org/".substr($assoc['post_date'],0,10)."-".$assoc['post_name'].".html";
    if($first)
        echo "            <li><b>$post_date</b> − <a href=\"$url\"><span style=\"color:#000;\">$post_title</span></a></li>\n";
    else
        echo "            <li><b>$post_date</b> − <a href=\"$url\">$post_title</a></li>\n";
    $first = false;
}

?>
        </ul>
    </div>
</div>
<?php

	$tpl_temp = trim(ob_get_contents());
	$tpl_main = str_replace('<pun_announcement>', $tpl_temp, $tpl_main);
	ob_end_clean();
}
else
	$tpl_main = str_replace('<pun_announcement>', '', $tpl_main);
// END SUBST - <pun_announcement>


// START SUBST - <pun_main>
ob_start();


define('PUN_HEADER', 1);
