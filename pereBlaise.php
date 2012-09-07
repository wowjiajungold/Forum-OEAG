<?

define('PUN_ROOT', './');
require PUN_ROOT.'include/common.php';

$todaystamp = date('j/n/');
$limitstamp = strtotime(date('Y-m-d'))-7776000;

$now = time();
$poster = "Père Blaise";
$poster_id = 3;
$topic_id = 684;
$forum_id = 3;

$query = 'SELECT username, birthdate, sex FROM '.$db->prefix.'users WHERE birthdate LIKE \''.$todaystamp.'%\' AND last_visit >= \''.$limitstamp.'\' AND last_post >= \''.$limitstamp.'\'';
$result = $db->query($query) or error('Impossible de retrouver la liste des utilisateurs en ligne aujourd\'hui', __FILE__, __LINE__, $db->error());
while($birth = $db->fetch_row($result))
    $birthdays[] = $birth;

echo $query."<br /><br />";

$num_birthday = count($birthdays);

if($num_birthday==1)
{
    echo "Un annif !";
    $texte = "\n\n\n[center]";
    $texte .= "Aujourd\'hui c\'est l\'anniversaire de [b]".$db->escape( $birthdays[0][0] )."[/b]";
    $texte .= " qui fête ses ".calculAge($birthdays[0][1]);
    $texte .= " ans, un très joyeux anniversaire";
    $texte .= " ".($birthdays[0][2]==1 ? "à elle" : "à lui" )." !!";
    $texte .= "\n\n\n[/center]";

    sendPost();
}
else if($num_birthday>=1)
{
    echo "Plusieurs annif !";
    $texte = "\n\n\n[center]";
    $texte .= "Aujourd\'hui c\'est l\'anniversaire de ";
    foreach($birthdays as $birthday)
    {
        $texte .= "[b]".$birthday[0]."[/b] (".calculAge($birthday[1])." ans), ";
    }
    $texte .= "un très joyeux anniversaire à eux !!";
    $texte .= "\n\n\n[/center]";

    sendPost();
}
else
{
    echo "Pas d'annif :(";
}

function sendPost() {
    global $db, $poster, $poster_id, $texte, $now, $topic_id, $forum_id; 
    
    $query = "INSERT INTO ".$db->prefix."posts (poster, poster_id, poster_ip, message, hide_smilies, posted, topic_id) VALUES('$poster', '$poster_id', '0.0.0.0', '$texte', 0, '$now', '$topic_id');";
    $db->query($query) or die(mysql_error());
    $pid = mysql_insert_id();

    $query = "UPDATE topics SET last_post='$now', last_post_id='$pid', last_poster='$poster', num_replies=num_replies+1 WHERE id='$topic_id';";
    $db->query($query) or die(mysql_error());

    $query = "UPDATE forums SET last_post='$now', last_post_id='$pid', last_poster='$poster', num_posts=num_posts+1 WHERE id='$forum_id';";
    $db->query($query) or die(mysql_error());
}
?>