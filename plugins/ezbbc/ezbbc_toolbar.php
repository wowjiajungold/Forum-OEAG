<!-- EZBCC Toolbar -->

<?php // Retrieving style folder
$config_content = trim(file_get_contents(PUN_ROOT.'plugins/ezbbc/config.php'));
$config_item = explode(";", $config_content);
$ezbbc_style_folder = $config_item[2];
?>

<span id="ezbbctoolbar">
<!-- text style -->
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/bold.png" title="<?php echo $lang_ezbbc['Bold'] ?>" alt="<?php echo $lang_ezbbc['Bold'] ?>" onclick="insertTag('[b]','[/b]','')" />
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/underline.png" title="<?php echo $lang_ezbbc['Underline'] ?>" alt="<?php echo $lang_ezbbc['Underline'] ?>" onclick="insertTag('[u]','[/u]','')" />
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/italic.png" title="<?php echo $lang_ezbbc['Italic'] ?>" alt="<?php echo $lang_ezbbc['Italic'] ?>" onclick="insertTag('[i]','[/i]','')" />
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/strike-through.png" title="<?php echo $lang_ezbbc['Strike-through'] ?>" alt="<?php echo $lang_ezbbc['Strike-through'] ?>" onclick="insertTag('[s]','[/s]','')" />
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/delete.png" title="<?php echo $lang_ezbbc['Delete'] ?>" alt="<?php echo $lang_ezbbc['Delete'] ?>" onclick="insertTag('[del]','[/del]','')" />
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/insert.png" title="<?php echo $lang_ezbbc['Insert'] ?>" alt="<?php echo $lang_ezbbc['Insert'] ?>" onclick="insertTag('[ins]','[/ins]','')" />
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/emphasis.png" title="<?php echo $lang_ezbbc['Emphasis'] ?>" alt="<?php echo $lang_ezbbc['Emphasis'] ?>" onclick="insertTag('[em]','[/em]','')" />
&#160;

<!-- Color and heading -->
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/color.png" title="<?php echo $lang_ezbbc['Colorize'] ?>" alt="<?php echo $lang_ezbbc['Colorize'] ?>" onclick="insertTag('[color]','[/color]','color')" />
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/heading.png" title="<?php echo $lang_ezbbc['Heading'] ?>" alt="<?php echo $lang_ezbbc['Heading'] ?>" onclick="insertTag('[h]','[/h]','heading')" />	
&#160;

<!-- Links and images -->
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/link.png" title="<?php echo $lang_ezbbc['URL'] ?>" alt="<?php echo $lang_ezbbc['URL'] ?>" onclick="insertTag('[url]','[/url]','link')" />
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/email.png" title="<?php echo $lang_ezbbc['E-mail'] ?>" alt="<?php echo $lang_ezbbc['E-mail'] ?>" onclick="insertTag('[email]','[/email]','email')" />
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/image.png" title="<?php echo $lang_ezbbc['Image'] ?>" alt="<?php echo $lang_ezbbc['Image'] ?>" onclick="insertTag('[img]','[/img]','img')" />
&#160;

<!-- Quote and code -->
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/quote.png" title="<?php echo $lang_ezbbc['Quote'] ?>" alt="<?php echo $lang_ezbbc['Quote'] ?>" onclick="insertTag('[quote]','[/quote]','quote')" />
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/code.png" title="<?php echo $lang_ezbbc['Code'] ?>" alt="<?php echo $lang_ezbbc['Code'] ?>" onclick="insertTag('[code]','[/code]','code')" />
&#160;

<!-- Lists -->
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/list-unordered.png" title="<?php echo $lang_ezbbc['Unordered List'] ?>" alt="<?php echo $lang_ezbbc['Unordered List'] ?>" onclick="insertTag('[list=*]','[/list]','unorderedlist')" />
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/list-ordered.png" title="<?php echo $lang_ezbbc['Ordered List'] ?>" alt="<?php echo $lang_ezbbc['Ordered List'] ?>" onclick="insertTag('[list=1]','[/list]','orderedlist')" />
<img class="button" src="plugins/ezbbc/style/<?php echo $ezbbc_style_folder ?>/images/list-ordered-alpha.png" title="<?php echo $lang_ezbbc['Alphabetical Ordered List'] ?>" alt="<?php echo $lang_ezbbc['Alphabetical Ordered List'] ?>" onclick="insertTag('[list=a]','[/list]','alphaorderedlist')" />
	
<!-- Smileys -->
<br />
<img class="smiley" src="img/smilies/smile.png" title=":)" alt=":)" onclick="insertTag(':)','','')" />
<img class="smiley" src="img/smilies/neutral.png" title=":|" alt=":|" onclick="insertTag(':|','','')" />
<img class="smiley" src="img/smilies/sad.png" title=":(" alt=":(" onclick="insertTag(':(','','')" />
<img class="smiley" src="img/smilies/big_smile.png" title=":D" alt=":D" onclick="insertTag(':D','','')" />
<img class="smiley" src="img/smilies/yikes.png" title=":o" alt=":o" onclick="insertTag(':o','','')" />
<img class="smiley" src="img/smilies/wink.png" title=";)" alt=";)" onclick="insertTag(';)','','')" />
<img class="smiley" src="img/smilies/hmm.png" title=":/" alt=":/" onclick="insertTag(':/','','')" />
<img class="smiley" src="img/smilies/beuh.gif" title=":beuh:" alt=":beuh:" onclick="insertTag(':beuh:','','')" />
<img class="smiley" src="img/smilies/huh.gif" title=":huh:" alt=":huh:" onclick="insertTag(':huh:','','')" />
<img class="smiley" src="img/smilies/tongue.png" title=":p" alt=":p" onclick="insertTag(':p','','')" />
<img class="smiley" src="img/smilies/lol.png" title=":lol:" alt=":lol:" onclick="insertTag(':lol:','','')" />
<img class="smiley" src="img/smilies/mad.png" title=":mad:" alt=":mad:" onclick="insertTag(':mad:','','')" />
<img class="smiley" src="img/smilies/roll.png" title=":rolleyes:" alt=":rolleyes:" onclick="insertTag(':rolleyes:','','')" />
<img class="smiley" src="img/smilies/cool.png" title=":cool:" alt=":cool:" onclick="insertTag(':cool:','','')" />
<img class="smiley" src="img/smilies/euh.gif" title=":beuh:" alt=":beuh:" onclick="insertTag(':euh:','','')" />
<img class="smiley" src="img/smilies/ironique.gif" title=":b" alt=":b" onclick="insertTag(':b','','')" />
<img class="smiley" src="img/smilies/hug.gif" title=":hug:" alt=":hug:" onclick="insertTag(':hug:','','')" />
<img class="smiley" src="img/smilies/love.gif" title=":love:" alt=":love:" onclick="insertTag(':love:','','')" />
</span>
<!-- EZBCC Toolbar end -->
