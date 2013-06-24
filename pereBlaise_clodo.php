<?php

define('PUN_ROOT', './');
define('DELAY', 60);

require PUN_ROOT.'include/common.php';

$todaystamp = date('j/m');
$limitstamp = strtotime(date('Y-m-d'))-(86400*DELAY);

$now = time();
$poster = "Père Blaise";
$poster_id = 3;
$topic_id = 1029;
$forum_id = 15;

$query = "SELECT id, email, username, registered, last_visit FROM ".$db->prefix."users WHERE last_visit <= '$limitstamp' AND num_posts='0' AND id != '1';";

$result = $db->query($query) or error('Erreur : '.mysql_error(), __FILE__, __LINE__, $db->error());
while($membre = $db->fetch_assoc($result))
    $clodos[] = $membre;

$num_clodos = count($clodos);

if($num_clodos > 0)
{
    $texte = "[h]Liste des candidats à la porte ($num_clodos comptes)[/h]\n";
    $texte .= "(aucun message, dernière connexion il y a plus de ".DELAY." jours)\n\n";
    $texte .= "[table]\n";
    $texte .= "[tr]\n";
    $texte .= "[th]Pseudo[/th]\n";
    $texte .= "[th]Mail[/th]\n";
    $texte .= "[th]Inscription[/th]\n";
    $texte .= "[th]Dernière visite[/th]\n";
    $texte .= "[/tr]\n";
    
    foreach($clodos as $clodo)
    {
        $texte .= "[tr]\n";
        $texte .= "[td][b][url=http://forum.onenagros.org/profile.php?section=admin&id=".$clodo['id']."]".$clodo['username']."[/url][/b][/td]\n";
        $texte .= "[td]".$clodo['email']."[/td]\n";
        $texte .= "[td]".date('d/m/Y - h:i', $clodo['registered'])."[/td]\n";
        $texte .= "[td]".date('d/m/Y - h:i', $clodo['last_visit'])."[/td]\n";
        $texte .= "[/tr]\n";
    }
    
    $texte .= "[/table]\n";
    
    sendPost();
}

//echo "<pre>"; print_r($clodos); echo "</pre>";

function sendPost() {
    global $db, $poster, $poster_id, $texte, $now, $topic_id, $forum_id; 
    
    $query = "INSERT INTO ".$db->prefix."posts (poster, poster_id, poster_ip, message, hide_smilies, posted, topic_id) VALUES('$poster', '$poster_id', '0.0.0.0', '".$db->escape($texte)."', 0, '$now', '$topic_id');";
    $db->query($query) or die(mysql_error());
    $pid = mysql_insert_id();

    $query = "UPDATE topics SET last_post='$now', last_post_id='$pid', last_poster='$poster', num_replies=num_replies+1 WHERE id='$topic_id';";
    $db->query($query) or die(mysql_error());

    $query = "UPDATE forums SET last_post='$now', last_post_id='$pid', last_poster='$poster', num_posts=num_posts+1 WHERE id='$forum_id';";
    $db->query($query) or die(mysql_error());
}
?>