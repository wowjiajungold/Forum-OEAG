<?php
 
// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
	exit;

/**
 * OnEnAGros custom FluxBB stats module
 * 
 * Allow user to get knowledge of their activity on the forum
 * 
 * @Package FluxBB
 * @version 1.0
 */
class Stats {
    
    var $db;
    var $user;
    var $user_ID;
    var $config;
    var $lang;
    
    var $c_data;
    var $c_posts;
    var $c_topics;
    
    var $r_data;
    
    var $_y_data;
    var $_m_data;
    var $_data;
    
    /**
     * Class constructor
     * 
     * @since Stats 1.0
     */
    public function __construct() {
        
        $this->__init();
        $this->__lang();
    }

    /**
     * Initialize class' vars
     * 
     * @since Stats 1.0
     */
    private function __init() {
        
        global $db, $id, $user;
        
        $this->db      = &$db;
        $this->user    = &$user;
        $this->user_ID = &$id;
        
    }
    
    private function __lang() {
        global $pun_user;
        if (file_exists(PUN_ROOT.'OnEnAGros/lang/'.$this->user['language'].'/stats.php'))
            include PUN_ROOT.'OnEnAGros/lang/'.$this->user['language'].'/stats.php';
        else
            include PUN_ROOT.'OnEnAGros/lang/English/stats.php';
        $this->lang = $lang_stats;
    }
    
    public function stats_get_user_registration() {
        return date( "Y-m-d", $this->user['registered'] );
    }
    
    public function stats_get_stats_day() {
        return date( "Y-m-d", strtotime( '-1d', time() ) );
    }
    
    /** **********************************************************
     *                          Get data
     * ***********************************************************/
    
    public function stats_get_forum_list() {
        
        $forums = array();
        
        $query = 'SELECT `id`, `forum_name` AS title FROM `forums`';
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch forum list', __FILE__, __LINE__, $this->db->error() );
        while ( $result = $this->db->fetch_assoc( $results ) )
            $forums[$result['id']] = $result['title'];
        
        return $forums;
        
        
    }
    
    public function stats_get_word_count() {
        
        $c_word = 0;
        
        $query = 'SELECT `message` AS m FROM `posts` WHERE `poster_id` = '.$this->user_ID;
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user posts', __FILE__, __LINE__, $this->db->error() );
        while ( $result = $this->db->fetch_row( $results ) )
            $c_word += str_word_count( $result[0], 0, 'àáäâéèëêïîôôuùüûç0123456789' );
        
        return $c_word;
    }
    
    public function stats_get_user_debater_level() {
        
        $query = 'SELECT AVG( LENGTH( message ) ) AS len
                  FROM posts
                  WHERE poster_id = '.$this->user_ID.'
                  ORDER BY len DESC
                  LIMIT 0, '.ceil( $this->stats_get_user_posts_count() / 100 );
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user debater level'.$query, __FILE__, __LINE__, $this->db->error() );
        
        return (int) $this->db->result( $results );
    }
    
    public function stats_get_smilies_count() {
        
        $c_smilies = 0;
        
        $query = 'SELECT `message` AS m FROM `posts` WHERE `poster_id` = '.$this->user_ID;
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user smilies', __FILE__, __LINE__, $this->db->error() );
        while ( $result = $this->db->fetch_row( $results ) ) {
            //$c = preg_match_all( '/\:(\w+)\:|\:(\w+)|\:[\p{P}]+/', $result[0], $m );
            $c = preg_match_all( '/\:\)|\=\|\:\|\=\||\:\(|\=\(|\:D|\=D|\:o|\:O|\;\)|\:\/\ |\:P|\:p|\:lol\:|\:mad\:|\:rolleyes\:|\:cool\:|\:beuh\:|o\_O|O\_o|\:beuh\:|\:huh\:|\:siffle\:|\:euh\:|\:fete\:|\:hug\:|\:b|\:B|\:love\:|\:luv\:|\:hs\:|\:dehors\:/', $result[0], $m );
            $c_smilies += ( $c ? $c : 0 );
        }
        
        return $c_smilies;
    }
    
    public function stats_get_selfquote_count() {
        
        $query = 'SELECT 
                    LENGTH( message )
                  - LENGTH( REPLACE( LOWER( message ) , "[quote='.strtolower( $this->user['username'] ).']",  "" ) ) AS c
                 FROM `posts`
                 WHERE `poster_id` = '.$this->user_ID.'
                 HAVING c >0';
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user selfquote', __FILE__, __LINE__, $this->db->error() );
        
        return $this->db->affected_rows();
    }
    
    public function stats_get_post_repartition() {
        
        $r_posts = array();
        
        $query = 'SELECT p.id AS p_id, f.id AS f_id
                  FROM posts AS p, `forums` AS f
                  WHERE p.poster_id = '.$this->user_ID.'
                    AND f.id = ( 
                      SELECT forum_id
                      FROM topics
                      WHERE id = p.topic_id
                  )';
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user posts repartition', __FILE__, __LINE__, $this->db->error() );
        while ( $result = $this->db->fetch_assoc( $results ) )
            $r_posts[$result['f_id']] += 1;
        
        arsort( $r_posts );
        
        return $r_posts;
    }
    
    public function stats_get_busiest_forum() {
        return array_slice( $this->stats->stats_get_post_repartition(), 0, 1, true );
    }
    
    // 8-10-2007 -> 1191794401
    
    public function stats_get_user_scenarii_count() {
        
        $query = 'SELECT COUNT( DISTINCT t.id )
                  FROM topics AS t, posts AS p
                  WHERE t.poster = "'.$this->user['username'].'"
                    AND t.forum_id = 10
                    AND p.message LIKE  "%[scenario]%[/scenario]%"
                    AND p.id = t.first_post_id';
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user scenarii count', __FILE__, __LINE__, $this->db->error() );
        
        return (int) $this->db->result( $results );
    }
    
    public function stats_get_user_scenarii_comment() {
        
        $query = 'SELECT COUNT( DISTINCT t.id )
                  FROM posts AS p, topics AS t
                  WHERE t.poster !=  "'.$this->user['username'].'"
                    AND t.forum_id = 10
                    AND p.topic_id = t.id
                    AND p.poster_id = '.$this->user_ID;
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user scenarii comment', __FILE__, __LINE__, $this->db->error() );
        
        return (int) $this->db->result( $results );
    }
    
    public function stats_get_user_topics_count() {
        
        $query = 'SELECT COUNT(*) AS c
                  FROM topics AS t
                  WHERE t.poster = "'.$this->user['username'].'"';
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user topics count', __FILE__, __LINE__, $this->db->error() );
        
        return (int) $this->db->result( $results );
    }
    
    public function stats_get_user_posts_count() {
        
        $query = 'SELECT COUNT(*) AS c
                  FROM posts AS p
                  WHERE p.poster_id = "'.$this->user_ID.'"';
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user posts count', __FILE__, __LINE__, $this->db->error() );
        
        return $this->db->result( $results );
    }
    
    public function stats_get_user_topics_avg_posts() {
        
        $query = 'SELECT AVG( num_replies ) AS replies,
                         AVG( num_views ) AS views
                  FROM topics AS t
                  WHERE t.poster =  "'.$this->user['username'].'"';
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user topics avg post', __FILE__, __LINE__, $this->db->error() );
        
        return $this->db->fetch_assoc( $results );
    }
    
    public function stats_get_user_topics_avg_posts_replies() {
        $a = $this->stats_get_user_topics_avg_posts();
        return round( $a['replies'], 2 );
    }
    
    public function stats_get_user_topics_avg_posts_views() {
        $a = $this->stats_get_user_topics_avg_posts();
        return round( $a['views'], 2 );
    }
    
    public function stats_get_user_games_count() {
        
        $query = 'SELECT COUNT(*) AS c
                  FROM topics AS t
                  WHERE t.poster = "'.$this->user['username'].'"
                    AND t.forum_id = 6';
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user games count', __FILE__, __LINE__, $this->db->error() );
        
        return (int) $this->db->result( $results );
    }
    
    public function stats_get_user_play_count() {
        
        $query = 'SELECT COUNT(*) AS c
                  FROM posts AS p, topics AS t
                  WHERE t.poster !=  "'.$this->user['username'].'"
                    AND t.forum_id = 6
                    AND p.topic_id = t.id
                    AND p.poster_id = '.$this->user_ID;
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user play count', __FILE__, __LINE__, $this->db->error() );
        
        return (int) $this->db->result( $results );
    }
    
    public function stats_get_user_edit_count() {
        
        $query = 'SELECT COUNT(*) AS c
                  FROM posts AS p
                  WHERE p.edited_by = "'.$this->user['username'].'"
                    AND p.edited IS NOT NULL 
                    AND p.poster_id = '.$this->user_ID;
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user edit count', __FILE__, __LINE__, $this->db->error() );
        
        return (int) $this->db->result( $results );
    }
    
    public function stats_get_user_welcome_comment() {
        
        $query = 'SELECT COUNT( DISTINCT t.id ) AS c
                  FROM posts AS p, topics AS t
                  WHERE t.poster !=  "'.$this->user['username'].'"
                    AND t.forum_id = 2
                    AND p.topic_id = t.id
                    AND p.poster_id = '.$this->user_ID;
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user welcome comment', __FILE__, __LINE__, $this->db->error() );
        
        return (int) $this->db->result( $results );
    }
    
    /** **********************************************************
     *                          Get lists
     * ***********************************************************/
    
    /**
     * Get complete user posts list
     * 
     * @since Stats 1.0
     */
    private function stats_get_user_post_count() {
        
        $posts = array();
        
        $query = 'SELECT `id`, FROM_UNIXTIME(`posted`, "%Y-%m-%d") AS posted
                  FROM `posts`
                  WHERE `poster_id` = '.$this->user_ID.'
                  ORDER BY posted';
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user post count', __FILE__, __LINE__, $this->db->error() );
        while ( $result = $this->db->fetch_row( $results ) )
            $posts[$result[1]][] = $result[0];
        
        foreach ( $posts as $d => $p )
            $this->c_data[$d]['posts'] = count( $p );
    }
    
    /**
     * Get complete user topics list
     * 
     * @since Stats 1.0
     */
    private function stats_get_user_topic_count() {
        
        $topics = array();
        
        $query = 'SELECT `id`, FROM_UNIXTIME(`posted`, "%Y-%m-%d") AS posted
                  FROM `topics`
                  WHERE `poster` = "'.$this->user['username'].'"
                  ORDER BY posted';
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user topic count', __FILE__, __LINE__, $this->db->error() );
        while ( $result = $this->db->fetch_row( $results ) )
            $topics[$result[1]][] = $result[0];
        
        foreach ( $topics as $d => $t )
            $this->c_data[$d]['topics'] = count( $t );
    }
    
    /** **********************************************************
     *                       Prepare lists
     * ***********************************************************/
    
    /**
     * Parse user data list to be read by JS graph
     * 
     * @since Stats 1.0
     */
    private function stats_prepare_graph_data() {
        
        $_y_data = $_m_data = $r_data = $_data = array();
        
        foreach ( $this->c_data as $date => $c_data ) {
            
            $date = explode( '-', $date );
            $y    = $date[0];
            $m    = $date[1];
            $d    = $date[2];
            
            $_y_data[$y]['posts']         = $_y_data[$y]['posts']        + $c_data['posts'];
            $_m_data["$y-$m"]['posts']    = $_m_data["$y-$m"]['posts']   + $c_data['posts'];
            $_data["$y-$m-$d"]['posts']   = $_data["$y-$m-$d"]['posts']  + $c_data['posts'];
            
            $_y_data[$y]['topics']        = $_y_data[$y]['topics']       + $c_data['topics'];
            $_m_data["$y-$m"]['topics']   = $_m_data["$y-$m"]['topics']  + $c_data['topics'];
            $_data["$y-$m-$d"]['topics']  = $_data["$y-$m-$d"]['topics'] + $c_data['topics'];
        }
        
        $forums = $this->stats_get_forum_list();
        
        foreach ( $this->stats_get_post_repartition() as $fid => $forum )
            $r_data[$fid] = array(
                'posts'  => $forum,
                'title'  => $forums[$fid],
            );
        
        $this->_y_data = $_y_data;
        $this->_m_data = $_m_data;
        $this->r_data  = $r_data;
        $this->_data   = $_data;
    }
    
    /**
     * Set up and display user data graph
     * 
     * @since Stats 1.0
     */
    private function stats_get_user_graph_data() {
        
        $this->stats_prepare_graph_data();
        
        $c_data = $this->_m_data;
        $r_data = $this->r_data;
        
?>
    c_data = [
<?php
        foreach ( $c_data as $date => $data )
            printf( '        {date: "%s", posts: "%s", topics: "%s"},', $date, ( $data['posts'] ? $data['posts'] : '0' ) , ( $data['topics'] ? $data['topics'] : '0' ) );
?>
    ];

    r_data = [
<?php
        foreach ( $r_data as $date => $data )
            printf( '        {forum: "%s", posts: "%s"},', $data['title'], ( $data['posts'] ? $data['posts'] : '0' ) );
?>
    ];
<?php
        
    }
    
    /**
     * Profile new stats view
     * 
     * @since Stats 1.0
     */
    public function stats_profile_view() {
        
        global $lang_profile;
        
        $this->stats_get_user_post_count();
        $this->stats_get_user_topic_count();
        
?>
	<link rel="stylesheet" href="http://cdn.oesmith.co.uk/morris-0.4.1.min.css">
	<!--<link rel="stylesheet" href="OnEnAGros/css/jquery-ui-1.10.2.custom.min.css">-->
	<link rel="stylesheet" href="OnEnAGros/css/stats.css">

	<script src="//cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>
	<script src="//cdn.oesmith.co.uk/morris-0.4.1.min.js"></script>
	<!--<script src="OnEnAGros/js/jquery-ui-1.10.2.custom.min.js"></script>-->

	<script type="text/javascript">

$(function () {
    
<?php $this->stats_get_user_graph_data(); ?>
    
    Morris.Area({
        element: 'hero-area',
        data: c_data,
        xkey: 'date',
        ykeys: ['topics', 'posts'],
        labels: ['Discussions', 'Messages'],
        pointSize: 2,
        hideHover: 'auto'
    });

    Morris.Bar({
        element: 'hero-bar',
        data: r_data,
        xkey: 'forum',
        ykeys: ['posts'],
        labels: ['Forums'],
        barRatio: 0.4,
        xLabelAngle: 35,
        hideHover: 'auto'
    });
    
//     $('#from').datepicker({
//         dateFormat: 'yy-mm-dd',
//         changeMonth: true,
//         changeYear: true,
//         minDate: new Date(<?php echo $this->stats_get_user_registration(); ?>),
//         maxDate: new Date(<?php echo $this->stats_get_stats_day(); ?>),
//         onClose: function(date) {
//             $('#to').datepicker('option', 'minDate', date);
//         }
//     });
//     $( '#to' ).datepicker({
//         dateFormat: 'yy-mm-dd',
//         changeMonth: true,
//         changeYear: true,
//         minDate: new Date(<?php echo $this->stats_get_user_registration(); ?>),
//         maxDate: new Date(<?php echo $this->stats_get_stats_day(); ?>),
//         onClose: function(date) {
//             $('#from').datepicker('option', 'maxDate', date);
//         }
//     });
});
	</script>

	<div class="blockform">
		<h2><span><?php echo $this->lang['Statistics']; ?></span></h2>
		<div class="box">
			<div id="viewprofile">
				<fieldset>
					<div class="infldset">
						<h4>Évolution du nombre de messages et discussions depuis votre inscription</h4>
						<div class="graph-container">
							<div id="hero-area" class="graph"></div>
						</div>
						<div class="graph-controler" style="margin-top:2em">
							<!--<label for="from">Du </label><input type="text" id="from" class="datepicker" name="from" />
							<label for="to"> au </label><input type="text" id="to" class="datepicker" name="to" />-->
						</div>
					</div>
				</fieldset>
				<br />
				<fieldset>
					<div class="infldset">
						<h4>Répartition des messages</h4>
						<div class="graph-container">
							<div id="hero-bar" class="graph" style="position: relative;"></div>
						</div>
					</div>
				</fieldset>
				<br />
				<fieldset>
					<legend><h3>Statistiques diverses<?php //echo $this->lang['Various stats'] ?></h3></legend>
					<div class="infldset">
						<h4>Messages</h4>
						<dl>
							<dt>Nombre de messages publiés : </dt>
							<dd><?php echo $this->user['num_posts']; ?> messages</dd>
						</dl>
						<dl>
							<dt>Nombre total de mots : </dt>
							<dd><?php echo $this->stats_get_word_count(); ?> mots</dd>
						</dl>
						<dl>
							<dt>Longueur moyenne d’un message : </dt>
							<dd><?php echo round( ( $this->stats_get_word_count() / $this->user['num_posts'] ), 2 ); ?> mots</dd>
						</dl>
						<dl>
							<dt>Nombre d’émoticônes utilisées : </dt>
							<dd><?php echo $this->stats_get_smilies_count(); ?> émoticônes</dd>
						</dl>
						
						<h4>Discussions</h4>
						<dl>
							<dt>Nombre de discussions : </dt>
							<dd><?php echo $this->stats_get_user_topics_count(); ?> discussions</dd>
						</dl>
						<dl>
							<dt>Popularité de vos discussions : </dt>
							<dd><?php echo $this->stats_get_user_topics_avg_posts_views(); ?> lectures</dd>
						</dl>
						<dl>
							<dt>Moyenne de vos discussions : </dt>
							<dd><?php echo $this->stats_get_user_topics_avg_posts_replies(); ?> réponses</dd>
						</dl>
					</div>
				</fieldset>
			</div>
		</div>
	</div>
<?php
	
        
    }
    
    
    
    
}


/**
 * Decorator class, used to cache stats on a daily basis.
 * 
 * @since OnEnAGros 5.0 
 */
class Decorator 
{
    protected $stats;

    function __construct( Stats $stats ) {
       $this->stats = $stats;
    }

    function __call( $method_name, $args ) {
        
        global $id, $user;
        
        $date     = date("Y-m-d");
        $filename = PUN_ROOT.'cache/'.$id.'_'.$method_name.'.php';
        
        ob_start();
        call_user_func_array( array( $this->stats, $method_name ), $args );
        $return = ob_get_contents();
        ob_end_clean();
        
        if ( file_exists( $filename ) )
            if ( fgets( fopen( $filename, 'r' ) ) == $date )
                $return = file_get_contents( $filename );
        else {
            $content  = $date."\n";
            $content .= $return;
            file_put_contents( $filename, $content );
        }
        
        echo $return;
    }
}



























