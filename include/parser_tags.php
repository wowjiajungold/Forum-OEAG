<?php
	// List of all the tags
	$old_tags = array('quote', 'code', 'b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'img', 'list', '*', 'h', 'topic', 'post', 'forum', 'user');
	$new_tags = array('size', 'spoiler', 'acronym', 'q', 'sup', 'sub', 'left', 'right', 'center', 'justify', 'video', 'scenario', 'titre', 'intro', 'texte', 'perso', 'didascalie', 'noir', 'table', 'tr', 'th', 'td');
    $tags = array_merge($old_tags, $new_tags);
	
	// List of tags that we need to check are open (You could not put b,i,u in here then illegal nesting like [b][i][/b][/i] would be allowed)
    $tags_opened = $tags;
    // and tags we need to check are closed (the same as above, added it just in case)
    $tags_closed = $tags;
    // Tags we can nest and the depth they can be nested to (only quotes)
    $tags_nested = array('quote' => $pun_config['o_quote_depth'], 'list' => 5, '*' => 5);
    // Tags to ignore the contents of completely (just code)
    $tags_ignore = array('code');
	
    // Block tags, block tags can only go within another block tag, they cannot be in a normal tag
    $old_tags = array('quote', 'code', 'list', 'h', '*');
    $new_tags = array('spoiler', 'left', 'right', 'center', 'justify', 'scenario', 'intro', 'texte', 'didascalie');
    $tags_block = array_merge($old_tags, $new_tags);
	
    // Inline tags, we do not allow new lines in these
    $old_tags = array('b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'h', 'topic', 'post', 'forum', 'user');
    $new_tags = array('acronym', 'q', 'sup', 'sub', 'video', 'titre', 'perso');
    $tags_inline = array_merge($old_tags, $new_tags);
	
    // Tags we trim interior space
    $tags_trim = array('img', 'video'/*, 'table', 'tr', 'th', 'td'*/);
    // Tags we remove quotes from the argument
    $tags_quotes = array('url', 'email', 'img', 'topic', 'post', 'forum', 'user', 'video');
    // Tags we limit bbcode in
    $tags_limit_bbcode = array(
//      '*' 	=> array('b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'list', 'img', 'code', 'topic', 'post', 'forum', 'user'),
        '*'     => array('b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'list', 'img', 'code', 'topic', 'post', 'forum', 'user', 'acronym', 'q', 'sup', 'sub', 'video'),
//      'list' 	=> array('*'),
        'list'  => array('*'),
//      'url' 	=> array('img'),
        'url'   => array('img', 'b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'acronym', 'q', 'sup', 'sub'),
//      'email' => array('img'),
        'email' => array('img', 'b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'acronym', 'q', 'sup', 'sub'),
//      'img' 	=> array(),
        'img'   => array(),
//      'h'		=> array('b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'topic', 'post', 'forum', 'user'),
        'h'     => array('b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'topic', 'post', 'forum', 'user'),
//      * new ones *
        'size'   => array('b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'img', 'acronym', 'q', 'sup', 'sub'),
        'video'  => array(),
        'scenario'  => array('b', 'i', 'u', 's', 'em', 'color', 'colour', 'titre', 'intro', 'texte'),
        'texte'  => array('b', 'i', 'u', 's', 'em', 'color', 'colour', 'perso', 'didascalie', 'noir'),
        'noir'  => array('b', 'i', 'u', 's', 'em', 'color', 'colour', 'didascalie'),
        'table' => array('tr'),
        'tr' => array('th', 'td'),
        'th' => array('b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'list', 'img', 'code', 'acronym', 'q', 'sup', 'sub', 'video'),
        'td' => array('b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'list', 'img', 'code', 'acronym', 'q', 'sup', 'sub', 'video')
    );
	// Tags we can automatically fix bad nesting
	$old_tags = array('quote', 'b', 'i', 'u', 's', 'ins', 'del', 'em', 'color', 'colour', 'url', 'email', 'h', 'topic', 'post', 'forum', 'user');
	$new_tags = array('spoiler'); 
	$tags_fix = array_merge($old_tags, $new_tags);
?>