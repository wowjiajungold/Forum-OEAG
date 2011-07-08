<?php

/**
 * Copyright (C) 2008-2010 FluxBB
 * based on code by Rickard Andersson copyright (C) 2002-2008 PunBB
 * License: http://www.gnu.org/licenses/gpl.html GPL version 2 or higher
 */

##
##
##  A few notes of interest for aspiring plugin authors:
##
##  1. If you want to display a message via the message() function, you
##     must do so before calling generate_admin_menu($plugin).
##
##  2. Plugins are loaded by admin_loader.php and must not be
##     terminated (e.g. by calling exit()). After the plugin script has
##     finished, the loader script displays the footer, so don't worry
##     about that. Please note that terminating a plugin by calling
##     message() or redirect() is fine though.
##
##  3. The action attribute of any and all <form> tags and the target
##     URL for the redirect() function must be set to the value of
##     $_SERVER['REQUEST_URI']. This URL can however be extended to
##     include extra variables (like the addition of &amp;foo=bar in
##     the form of this example plugin).
##
##  4. If your plugin is for administrators only, the filename must
##     have the prefix "AP_". If it is for both administrators and
##     moderators, use the prefix "AMP_". This example plugin has the
##     prefix "AMP_" and is therefore available for both admins and
##     moderators in the navigation menu.
##
##  5. Use _ instead of spaces in the file name.
##
##  6. Since plugin scripts are included from the FluxBB script
##     admin_loader.php, you have access to all FluxBB functions and
##     global variables (e.g. $db, $pun_config, $pun_user etc).
##
##  7. Do your best to keep the look and feel of your plugins' user
##     interface similar to the rest of the admin scripts. Feel free to
##     borrow markup and code from the admin scripts to use in your
##     plugins. If you create your own styles they need to be added to
##     the "base_admin" style sheet.
##
##  8. Plugins must be released under the GNU General Public License or
##     a GPL compatible license. Copy the GPL preamble at the top of
##     this file into your plugin script and alter the copyright notice
##     to refrect the author of the plugin (i.e. you).
##
##


// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
	exit;

// Load the language file
file_exists (PUN_ROOT.'plugins/ezbbc/lang/'.$admin_language.'/ezbbc_plugin.php') ? require PUN_ROOT.'plugins/ezbbc/lang/'.$admin_language.'/ezbbc_plugin.php' : require PUN_ROOT.'plugins/ezbbc/lang/English/ezbbc_plugin.php';

// Tell admin_loader.php that this is indeed a plugin and that it is loaded
define('PUN_PLUGIN_LOADED', 1);

//
// Core of the EZBBC Toolbar Plugin
//

// Load the language file
$ezbbc_language_folder = file_exists (PUN_ROOT.'plugins/ezbbc/lang/'.$admin_language.'/ezbbc_plugin.php') ? $admin_language : 'English';
require PUN_ROOT.'plugins/ezbbc/lang/'.$ezbbc_language_folder.'/ezbbc_plugin.php';

// Getting the config data
$plugin_version = "1.1.2";
$config_content = trim(file_get_contents(PUN_ROOT.'plugins/ezbbc/config.php'));
$config_item = explode(";", $config_content);
$ezbbc_install = $config_item[0];
$ezbbc_status = $config_item[1];
$ezbbc_style_folder = $config_item[2];
if ($ezbbc_install != 0) {
        $first_install = false;
        $ezbbc_install_date = date($lang_ezbbc['Date format'], $config_item[0]);
}
else { 
        $first_install = true;
}
if ($ezbbc_status == 0) {
        $ezbbc_plugin_status = '<span style="color: red; font-weight: bold;">'.$lang_ezbbc['Plugin disabled'].'</span>';
} else { // Looking first if all is really installed
        $header_file_content = file_get_contents(PUN_ROOT.'header.php');
        $viewtopic_file_content = file_get_contents(PUN_ROOT.'viewtopic.php');
        $post_file_content = file_get_contents(PUN_ROOT.'post.php');
        $edit_file_content = file_get_contents(PUN_ROOT.'edit.php');
	if (strpos($header_file_content, 'ezbbc') === false || strpos($viewtopic_file_content, 'ezbbc') === false || strpos($post_file_content, 'ezbbc') === false || strpos($edit_file_content, 'ezbbc') === false) {
	        $ezbbc_plugin_status = '<span style="color: orange; font-weight: bold;">'.$lang_ezbbc['Plugin wrong installation'].'</span>';
	}
	else {
	        $ezbbc_plugin_status = '<span style="color: green; font-weight: bold;">'.$lang_ezbbc['Plugin in action'].'</span>';
	}
}

/* If the change style button was clicked */
if (isset($_POST['style_change'])) {
                $new_style = $_POST['ezbbc_style'];
                $ezbbc_style_folder = $new_style;
                // Changing config data
                $config_content = trim(file_get_contents(PUN_ROOT.'plugins/ezbbc/config.php'));
                $config_item = explode(";", $config_content);
                $ezbbc_install = $config_item[0];
                $ezbbc_status = $config_item[1];
                $config_new_content = $ezbbc_install.';'.$ezbbc_status.';'.$new_style;
                $fp = fopen(PUN_ROOT.'plugins/ezbbc/config.php', 'wb');
                fwrite($fp, $config_new_content);
                fclose($fp);
                // Message to display
                 $ezbbc_style_changed = '<span style="color: green;">'.$lang_ezbbc['Style changed'].'</span>';
}               

/* If the install button was clicked or the plugin was newly installed */
if (isset($_POST['enable']) || $first_install){
	
        /* Getting the content of the header.php file */
	$file_content = file_get_contents(PUN_ROOT.'header.php');
	if (strpos($file_content, 'ezbbc') === false) {
	        //Inserting the EZBBC code by replacing an existing line
	        $search = '<link rel="stylesheet" type="text/css" href="style/<?php echo $pun_user[\'style\'].\'.css\' ?>" />';
	        $insert = "<?php require PUN_ROOT.'plugins/ezbbc/ezbbc_head.php'; ?>";
	        $replacement = $search."\n".$insert;
	        $new_file_content = str_replace ($search, $replacement, $file_content);
	        $fp = fopen (PUN_ROOT.'header.php', 'wb');
	        fwrite ($fp, $new_file_content);
	        fclose ($fp);
	}
	
	/* Getting the content of the post.php file */
	$file_content = file_get_contents(PUN_ROOT.'post.php');
	if (strpos($file_content, 'ezbbc') === false) {
	        //Inserting the EZBBC code by replacing an existing line
	        $search = '<textarea name="req_message" rows="20" cols="95" tabindex="<?php echo $cur_index++ ?>"><?php echo isset($_POST[\'req_message\']) ? pun_htmlspecialchars($message) : (isset($quote) ? $quote : \'\'); ?></textarea>';
	        $insert = "<?php require PUN_ROOT.'plugins/ezbbc/ezbbc_toolbar.php'; ?>";
	        $replacement = $insert."\n".$search;
	        $new_file_content = str_replace ($search, $replacement, $file_content);
	        $fp = fopen (PUN_ROOT.'post.php', 'wb');
	        fwrite ($fp, $new_file_content);
	        fclose ($fp);
	}
	
	/* Getting the content of the edit.php file */
	$file_content = file_get_contents(PUN_ROOT.'edit.php');
	if (strpos($file_content, 'ezbbc') === false) {
	        //Inserting the EZBBC code by replacing an existing line
	        $search = '<textarea name="req_message" rows="20" cols="95" tabindex="<?php echo $cur_index++ ?>"><?php echo pun_htmlspecialchars(isset($_POST[\'req_message\']) ? $message : $cur_post[\'message\']) ?></textarea>';
	        $insert = "<?php require PUN_ROOT.'plugins/ezbbc/ezbbc_toolbar.php'; ?>";
	        $replacement = $insert."\n".$search;
	        $new_file_content = str_replace ($search, $replacement, $file_content);
	        $fp = fopen (PUN_ROOT.'edit.php', 'wb');
	        fwrite ($fp, $new_file_content);
	        fclose ($fp);
	}
	
	/* Getting the content of the viewtopic.php file */
	$file_content = file_get_contents(PUN_ROOT.'viewtopic.php');
	if (strpos($file_content, 'ezbbc') === false) {
	        //Inserting the EZBBC code by replacing an existing line
	        $search = '<textarea name="req_message" rows="7" cols="75" tabindex="1"></textarea>';
	        $insert = "<?php require PUN_ROOT.'plugins/ezbbc/ezbbc_toolbar.php'; ?>";
	        $replacement = $insert."\n".$search;
	        $new_file_content = str_replace ($search, $replacement, $file_content);
	        $fp = fopen (PUN_ROOT.'viewtopic.php', 'wb');
	        fwrite ($fp, $new_file_content);
	        fclose ($fp);
	}
        
        /* Updating config and display datas */
        if ($first_install) {
                $ezbbc_install = time();
                $ezbbc_install_date = date($lang_ezbbc['Date format'], $ezbbc_install);
        }
        
	// Adding new data to config file
        $config_new_content = $ezbbc_install.';1;'.$ezbbc_style_folder;
        $fp = fopen(PUN_ROOT.'plugins/ezbbc/config.php', 'wb');
	fwrite($fp, $config_new_content);
	fclose($fp);
	// New status message
	$ezbbc_plugin_status = '<span style="color: green; font-weight: bold;">'.$lang_ezbbc['Plugin in action'].'</span>';
}

/* If the remove button was clicked */
if (isset($_POST['disable'])){
	/* Getting the content of the header.php file */
	$header_content = file_get_contents(PUN_ROOT.'header.php');
	//Searching for ezbbc code and replacing it with nothing
	$search = "\n<?php require PUN_ROOT.'plugins/ezbbc/ezbbc_head.php'; ?>";
	$replacement = '';
	$new_header_file_content = str_replace ($search, $replacement, $header_content);
	$fp = fopen (PUN_ROOT.'header.php', 'wb');
	fwrite ($fp, $new_header_file_content);
	fclose ($fp);
	
	/* Getting the content of the post.php file */
	$header_content = file_get_contents(PUN_ROOT.'post.php');
	//Searching for ezbbc code and replacing it with nothing
	$search = "<?php require PUN_ROOT.'plugins/ezbbc/ezbbc_toolbar.php'; ?>\n";
	$replacement = '';
	$new_post_file_content = str_replace ($search, $replacement, $header_content);
	$fp = fopen (PUN_ROOT.'post.php', 'wb');
	fwrite ($fp, $new_post_file_content);
	fclose ($fp);
	
	/* Getting the content of the edit.php file */
	$header_content = file_get_contents(PUN_ROOT.'edit.php');
	//Searching for ezbbc code and replacing it with nothing
	$search = "<?php require PUN_ROOT.'plugins/ezbbc/ezbbc_toolbar.php'; ?>\n";
	$replacement = '';
	$new_edit_file_content = str_replace ($search, $replacement, $header_content);
	$fp = fopen (PUN_ROOT.'edit.php', 'wb');
	fwrite ($fp, $new_edit_file_content);
	fclose ($fp);
	
	/* Getting the content of the viewtopic.php file */
	$header_content = file_get_contents(PUN_ROOT.'viewtopic.php');
	//Searching for ezbbc code and replacing it with nothing
	$search = "<?php require PUN_ROOT.'plugins/ezbbc/ezbbc_toolbar.php'; ?>\n";
	$replacement = '';
	$new_edit_file_content = str_replace ($search, $replacement, $header_content);
	$fp = fopen (PUN_ROOT.'viewtopic.php', 'wb');
	fwrite ($fp, $new_edit_file_content);
	fclose ($fp);
	
	 // Adding new data to config file
        $config_new_content = $ezbbc_install.';0;'.$ezbbc_style_folder;
        $fp = fopen(PUN_ROOT.'plugins/ezbbc/config.php', 'wb');
	fwrite($fp, $config_new_content);
	fclose($fp);
	// New status message
	$ezbbc_plugin_status = '<span style="color: red; font-weight: bold;">'.$lang_ezbbc['Plugin disabled'].'</span>';
}

// Display the admin navigation menu
	generate_admin_menu($plugin);

?>
	<div id="ezbbc" class="plugin blockform">
		<h2><span><?php echo $lang_ezbbc['Plugin title'] ?></span></h2>
		<h3><span><?php echo $lang_ezbbc['Description title'] ?></span></h3>
		<div class="box">
			<p><?php echo ($lang_ezbbc['Explanation']) ?></p>
		</div>

		<h3><span><?php echo $lang_ezbbc['Form title'] ?></span></h3>
		<div class="box">
			<form id="ezbbcform" method="post" action="<?php echo pun_htmlspecialchars($_SERVER['REQUEST_URI']) ?>#ezbbcform">
				<div class="inform">
				        
					<fieldset>
						<legend><?php echo $lang_ezbbc['Legend status'] ?></legend>
						<div class="infldset">
						<ul>
						        <li><?php echo $lang_ezbbc['Plugin version'].' '.$plugin_version ?></li>
							<li><?php echo $lang_ezbbc['Installation date'] ?> <?php echo $ezbbc_install_date ?></li>
							<li><?php echo $lang_ezbbc['Plugin status'] ?> <?php echo $ezbbc_plugin_status ?></li>
						</ul>
						<p><input type="submit" name="enable" value="<?php echo $lang_ezbbc['Enable'] ?>" /><input type="submit" name="disable" value="<?php echo $lang_ezbbc['Disable'] ?>" /></p>
						</div>
					</fieldset>
					
					<fieldset>
						<legend><?php echo $lang_ezbbc['Legend style'] ?></legend>
						<div class="infldset">
						<?php if (isset($ezbbc_style_changed)) echo '<p>'.$ezbbc_style_changed.'</p>'."\n"; ?>
						
						<?php
						$style_folders = glob('plugins/ezbbc/style/*');
						foreach ($style_folders as $style_folder) {
						        if (file_exists(PUN_ROOT.$style_folder.'/ezbbc.css')) {// Looking if folder contains a css file to validate it
						                $style_folder = substr ($style_folder, 20);
						                echo '<dl style="border-bottom: #DDD dashed 1px">'."\n";
						                if ($style_folder == $ezbbc_style_folder) {
						                        echo '<dt><input type="radio" value="'.$style_folder.'" name="ezbbc_style" checked="checked" /><strong>'.$style_folder.'</strong></dt>'."\n";
						                } else {
						                        echo '<dt><input type="radio" value="'.$style_folder.'" name="ezbbc_style" /><span style="color: grey;">'.$style_folder.'</span></dt>'."\n";
						                }
						                if (file_exists(PUN_ROOT.'plugins/ezbbc/style/'.$style_folder.'/images/preview.png')) {// Preview screenshot available ?
						                        echo '<dd><img src="'.PUN_ROOT.'plugins/ezbbc/style/'.$style_folder.'/images/preview.png" alt="'.$lang_ezbbc['Toolbar preview'].'" style="width: 520px; height: 80px; border: #DDD 1px groove;"/></dd>'."\n";
						                } else {
						                        echo '<dd>'.$lang_ezbbc['No preview'].'</dd>'."\n";
						                }
						                echo '</dl>'."\n";
						        }
						}
						?>
						 <p>
						 <input type="submit" name="style_change" value="<?php echo $lang_ezbbc['Change style'] ?>" />
						 </p>
						 </div>
					</fieldset>
				</div>
			</form>
		</div>
	</div>
<?php

// Note that the script just ends here. The footer will be included by admin_loader.php
