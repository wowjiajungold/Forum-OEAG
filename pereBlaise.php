<?

define( 'PUN_ROOT', './' );
require PUN_ROOT . 'include/common.php';

global $oeag;

$todaystamp = date( "j/n/" );
$limitstamp = strtotime( date( "Y-m-d" ) ) - 7776000; // 90 jours

$now = time();
$poster = "Père Blaise";
$poster_id = 3;
$topic_id = 684;
$forum_id = 3;

$birthday_message = array(
    "\n\n\n[center]Aujourd\'hui c\'est l\'anniversaire de [b]%s[/b] qui fête ses %s ans, un très joyeux anniversaire %s !!\n\n\n[/center]",
    "\n\n\n[center]Aujourd\'hui c\'est l\'anniversaire de %s, un très joyeux anniversaire à eux !!\n\n\n[/center]"
);

$registirth_message = array(
    "\n\n\n[center]Aujourd\'hui c\'est l\'inscriptionniversaire de [b]%s[/b] qui fête ses %s ans de présence sur le forum, merci %s !!\n\n\n[/center]",
    "\n\n\n[center]Aujourd\'hui c\'est l\'inscriptionniversaire de %s, merci à eux !!\n\n\n[/center]"
);

$texte = '';

/******** Anniversaires ********/
$users = array();

$query = 'SELECT username, birthdate, sex FROM '.$db->prefix.'users WHERE birthdate LIKE \''.$todaystamp.'%\' AND last_visit >= \''.$limitstamp.'\' AND last_post >= \''.$limitstamp.'\'';
$result = $db->query( $query ) or error( 'Impossible de retrouver la liste des utilisateurs en ligne aujourd\'hui', __FILE__, __LINE__, $db->error() );
while ( $r = $db->fetch_row( $result ) )
    $users[] = $r;

parsePost( $users, 'anniversaire' );

/******** Inscriptionniversaires ********/
$users = array();
$query = array();
$forum_years = array( "2008", "2009", "2010", "2011", "2012", "2013" );

$date = date( "\-M\-d" );
//$date = "-05-20";

foreach ( $forum_years as $fy )
    $query[] = '"'.$fy.$date.'"';

$query = 'SELECT username, registered, sex, CAST(FROM_UNIXTIME(registered) AS date) AS date FROM users WHERE last_visit >= \''.$limitstamp.'\' AND last_post >= \''.$limitstamp.'\' HAVING date IN ('.implode( ', ', $query ).')'; //var_dump( $query );
$result = $db->query( $query ) or error('Impossible de retrouver la liste des utilisateurs en ligne aujourd\'hui', __FILE__, __LINE__, $db->error());
while ( $r = $db->fetch_row( $result ) )
    $users[] = $r;

parsePost( $users, 'inscription' );


function parsePost( $data, $type ) {
    global $db, $birthday_message, $registirth_message;
    $texte = array();
    
    if ( count( $data ) == 1 ) {
        if ( $type == 'anniversaire' )
            $texte[] = sprintf( $birthday_message[0], $db->escape( $data[0][0] ), $oeag->oeag_get_age( $data[0][1] ), ( $data[0][2] == 1 ? "à elle" : "à lui" ) );
        else if ( $type == 'inscription' )
            $texte[] = sprintf( $registirth_message[0], $db->escape( $data[0][0] ), $oeag->oeag_get_age( date( "j/n/Y", $data[0][1] ) ), ( $data[0][2] == 1 ? "à elle" : "à lui" ) );
    }
    else if ( count( $data ) > 1 ) {
        
        $list = array();
        foreach ( $data as $d )
            $list[] = "[b]".$d[0]."[/b] (".$oeag->oeag_get_age( $d[1] )." ans)";
        
        if ( $type == 'anniversaire' )
            $texte[] = sprintf( $birthday_message[1], implode( ', ', $list ) );
        else if ( $type == 'inscription' )
            $texte[] = sprintf( $registirth_message[1], implode( ', ', $list ) );
    }
    
    if ( count( $texte ) )
        sendPost( implode( "\n\n", $texte ) );
}

function sendPost( $texte ) {
    global $db, $poster, $poster_id, $now, $topic_id, $forum_id; 
    
    var_dump( $texte );
    
    $query = "INSERT INTO ".$db->prefix."posts (poster, poster_id, poster_ip, message, hide_smilies, posted, topic_id) VALUES('$poster', '$poster_id', '0.0.0.0', '$texte', 0, '$now', '$topic_id');";
    $db->query($query) or die(mysql_error());
    $pid = mysql_insert_id();

    $query = "UPDATE topics SET last_post='$now', last_post_id='$pid', last_poster='$poster', num_replies=num_replies+1 WHERE id='$topic_id';";
    $db->query($query) or die(mysql_error());

    $query = "UPDATE forums SET last_post='$now', last_post_id='$pid', last_poster='$poster', num_posts=num_posts+1 WHERE id='$forum_id';";
    $db->query($query) or die(mysql_error());
}
?>