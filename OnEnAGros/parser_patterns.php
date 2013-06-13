<?php

$pattern[] = '#\[acronym\](.*?)\[/acronym\]#ms';
$pattern[] = '#\[acronym=(.*?)\](.*?)\[/acronym\]#ms';
$pattern[] = '#\[q\](.*?)\[/q\]#ms';
$pattern[] = '#\[sup\](.*?)\[/sup\]#ms';
$pattern[] = '#\[sub\](.*?)\[/sub\]#ms';
$pattern[] = '#\[left\](.*?)\[/left\]#ms';
$pattern[] = '#\[right\](.*?)\[/right\]#ms';
$pattern[] = '#\[center\](.*?)\[/center\]#ms';
$pattern[] = '#\[justify\](.*?)\[/justify\]#ms';
$pattern[] = '#\[video\]([^\[<]*?)/video/([^_\[<]*?)_([^\[<]*?)\[/video\]#ms';
$pattern[] = '#\[video=([0-9]+),([0-9]+)\]([^\[<]*?)/video/([^_\[<]*?)_([^\[<]*?)\[/video\]#ms';
$pattern[] = '#\[video\]([^\[<]*?)/(v/|watch\?v=)([^\[<]*?)\[/video\]#ms';
$pattern[] = '#\[video=([0-9]+),([0-9]+)\]([^\[<]*?)/(v/|watch\?v=)([^\[<]*?)\[/video\]#ms';

$pattern[] = '#\[scenario\](.*?)\[/scenario\]#ms';
$pattern[] = '#\[titre\](.*?)\[/titre\]#ms';
$pattern[] = '#\[intro\](.*?)\[/intro\]#ms';
$pattern[] = '#\[texte\](.*?)\[/texte\]#ms';
$pattern[] = '#\[perso\](.*?)\[/perso\]#ms';
$pattern[] = '#\[perso=(.*?)\](.*?)\[/perso\]#ms';
$pattern[] = '#\[didascalie\](.*?)\[/didascalie\]#ms';
$pattern[] = '#\[noir\](.*?)\[/noir\]#ms';

$pattern[] = '#\[L51\](.*?)\[/L51\]#ms';
$pattern[] = '#\[L52\](.*?)\[/L52\]#ms';
$pattern[] = '#\[L1\](.*?)\[/L1\]#ms';
$pattern[] = '#\[L2\](.*?)\[/L2\]#ms';

$pattern[] = '#\[table\](.*?)\[/table\]#ms';
$pattern[] = '#\[tr\](.*?)\[/tr\]#ms';
$pattern[] = '#\[th\](.*?)\[/th\]#ms';
$pattern[] = '#\[td\]{{oui}}\[/td\]#ms';
$pattern[] = '#\[td\]{{non}}\[/td\]#ms';
$pattern[] = '#\[td\]{{partiel}}\[/td\]#ms';
$pattern[] = '#\[td\](.*?)\[/td\]#ms';

$pattern[] = '#\[size=([0-9]{1}|[0-9]{2})](.*?)\[/size\]#e';

$pattern[] = '#\[spoiler\]\s*#';
$pattern[] = '#\[spoiler=(&quot;|"|\'|)(.*?)\\1\]#se';
$pattern[] = '#\s*\[\/spoiler\]#S';

$replace[] = '<acronym>$1</acronym>';
$replace[] = '<acronym title="$1">$2</acronym>';
$replace[] = '<q>$1</q>';
$replace[] = '<sup>$1</sup>';
$replace[] = '<sub>$1</sub>';
$replace[] = '</p><p style="text-align: left">$1</p><p>';
$replace[] = '</p><p style="text-align: right">$1</p><p>';
$replace[] = '</p><p style="text-align: center">$1</p><p>';
$replace[] = '</p><p style="text-align: justify">$1</p><p>';
$replace[] = '<object type="application/x-shockwave-flash" data="http://www.dailymotion.com/swf/video/$2" width="480" height="384"><param name="movie" value="http://www.dailymotion.com/swf/video/$2" /><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><p>Flash required</p></object>';
$replace[] = '<object type="application/x-shockwave-flash" data="http://www.dailymotion.com/swf/video/$4" width="$1" height="$2"><param name="movie" value="http://www.dailymotion.com/swf/video/$4" /><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><p>Flash required</p></object>';
$replace[] = '<object type="application/x-shockwave-flash" data="http://www.youtube.com/v/$3" width="425" height="344"><param name="movie" value="http://www.youtube.com/v/$3" /><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><p>Flash required</p></object>';
$replace[] = '<object type="application/x-shockwave-flash" data="http://www.youtube.com/v/$5" width="$1" height="$2"><param name="movie" value="http://www.youtube.com/v/$5" /><param name="allowFullScreen" value="true" /><param name="allowScriptAccess" value="always" /><p>Flash required</p></object>';

$replace[] = '</p><div class="scenario">$1</div><p>';
$replace[] = '<div class="titre">$1</div>';
$replace[] = '<div class="intro">$1</div>';
$replace[] = '<dl class="texte">$1</dl>';
$replace[] = ' </dd><dt class="perso" style="position:relative;padding:0;top:0;width:auto;">$1</dt><dd>';
$replace[] = ' </dd><dt class="perso" style="position:relative;padding:0;top:0;width:auto;">$2 <span>($1)</span></dt><dd>';
$replace[] = '<span class="didascalie">$1</span>';
$replace[] = '<dt class="noir" style="position:relative;padding:25px;top:0;width:auto;height:auto;">$1</dt>';

$replace[] = '<div class="postimg" style="text-align:center;"><img src="img/L5-1.jpg" alt="" /></div>';
$replace[] = '<div class="postimg" style="text-align:center;"><img src="img/L5-2.jpg" alt="" /></div>';
$replace[] = '<div class="postimg" style="text-align:center;"><img src="img/L234-1.jpg" alt="" /></div>';
$replace[] = '<div class="postimg" style="text-align:center;"><img src="img/L234-2.jpg" alt="" /></div>';

$replace[] = '<table class="postable">$1</table>';
$replace[] = '<tr>$1</tr>';
$replace[] = '<th>$1</th>';
$replace[] = '<td style="background-color:#ccffcc;font-weight:bold;">Oui</td>';
$replace[] = '<td style="background-color:#ff99bb;font-weight:bold;">Non</td>';
$replace[] = '<td style="background-color:#ffffdd;font-weight:bold;">Partiel</td>';
$replace[] = '<td>$1</td>';

$replace[] = '$oeag->oeag_size_bbcode(\'$1\', \'$2\')';

$replace[] = '</p><div class="quotebox" onclick="pchild=this.getElementsByTagName(\'p\'); if(pchild[0].style.visibility!=\'hidden\'){pchild[0].style.visibility=\'hidden\';}else{pchild[0].style.visibility=\'\';}"><cite>Spoiler :<br /><span style="font-weight:normal;font-size:80%;">(Cliquez pour afficher)</span></cite><blockquote><div><p style="visibility:hidden;">';
$replace[] = '"</p><div class=\"quotebox\" onclick=\"pchild=this.getElementsByTagName(\'p\'); if(pchild[0].style.visibility!=\'hidden\'){pchild[0].style.visibility=\'hidden\';}else{pchild[0].style.visibility=\'\';}\"><cite>Spoiler : ".str_replace(array(\'[\', \'\\"\'), array(\'&#91;\', \'"\'), \'$2\')." <br /><span style=\"font-weight:normal;font-size:80%;\">(Cliquez pour afficher)</span></cite><blockquote><div><p style=\"visibility:hidden;\">$1"';
$replace[] = '</p></div></blockquote></div><p>';

?>