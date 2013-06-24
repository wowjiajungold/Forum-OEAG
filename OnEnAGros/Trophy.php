<?php
 
// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
	exit;

/**
 * OnEnAGros custom FluxBB trophy module
 * 
 * @Package FluxBB
 * @version 1.0
 */
class Trophy {
    
    var $db;
    var $user;
    var $user_ID;
    var $config;
    var $lang;
    
    var $trophies;
    var $trophies_detail;
    
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
        
        include PUN_ROOT.'OnEnAGros/Stats.php';
        $this->stats = new Stats();
        
        $this->trophy_detail();
    }
    
    private function __lang() {
        global $pun_user;
        if (file_exists(PUN_ROOT.'OnEnAGros/lang/'.$pun_user['language'].'/trophy.php'))
            include PUN_ROOT.'OnEnAGros/lang/'.$pun_user['language'].'/trophy.php';
        else
            include PUN_ROOT.'OnEnAGros/lang/English/trophy.php';
        $this->lang = $lang_trophy;
    }
    
    /** **********************************************************
     *                          Trophies
     * ***********************************************************/
    
    private function trophy_detail() {
        
        $this->trophies_detail = array(
            'oldy' => array(
                'title'         => 'Fidèle parmi les fidèles',
                'description'   => 'Vous êtes là depuis tellement longtemps que vous faîtes presque parti du décor : on imagnine pas le forum sans vous.',
                'icon'          => 'time',
            ),
            'warlord' => array(
                'title'         => 'Chef de Guerre',
                'description'   => 'Vous organisez les opérations et ne rechignez pas à prendre les commandes des troupes pour orienter le discussion.',
                'icon'          => 'compass',
            ),
            'smiler' => array(
                'title'         => 'Saltimbanque',
                'description'   => 'Vous n’hésitez pas à recourir à la profusion de smilies pour égayer vos messages et mettre de l’ambiance.',
                'icon'          => 'smile',
            ),
            'autoquote' => array(
                'title'         => 'Onanisme',
                'description'   => 'Vous aimez vous auto-citer, petit canaillou.',
                'icon'          => 'lemon',
            ),
            'player' => array(
                'title'         => 'Œil de Taupe',
                'description'   => 'Vous ne l’avez peut-être pas tous les jours, mais vous jouez suffisamment pour prétendre au titre !',
                'icon'          => 'gamepad',
            ),
            'gamer' => array(
                'title'         => 'Le tricheur',
                'description'   => 'Vous aimez jouer, mais vous aimez faire jouer les autres également, et cela fait de vous un Tricheur potentiel. Gare à l’Œil de Taupe !',
                'icon'          => 'dashboard',
            ),
            'writer' => array(
                'title'         => 'Scribe',
                'description'   => 'Vous maîtrisez votre plume et la mettez à profit pour partager des scénarios. Bravo !',
                'icon'          => 'leaf',
            ),
            'reader' => array(
                'title'         => 'Érudit',
                'description'   => 'Vous aimez les des scénarios et vous le fait savoir, cela mérite d’être mentionné !',
                'icon'          => 'book',
            ),
            'editor' => array(
                'title'         => 'Gribouilleur',
                'description'   => 'L’édition de messages est votre amies, vous l’avez bien compris !',
                'icon'          => 'edit',
            ),
            'friendly' => array(
                'title'         => 'Bienveillance',
                'description'   => 'Vous n’hésitez pas à accueuillir gaiement les nouveaux arrivants.',
                'icon'          => 'heart',
            ),
            'debater' => array(
                'title'         => 'Polémiste',
                'description'   => 'Vos messages sont souvent très longs, preuve que vous appréciez particulièrement les discussions sérieuses !',
                'icon'          => 'align-justify',
            ),
//             '' => array(
//                 'title'         => '',
//                 'description'   => '',
//                 'icon'          => '',
//             ),
        );
    
    }
    
    public function trophy_get_trophies() {
        
        $query = 'SELECT trophy, date
                  FROM trophies
                  WHERE user_id = '.$this->user_ID;
        
        $results = $this->db->query( $query ) or error( 'Unable to fetch user trophies', __FILE__, __LINE__, $this->db->error() );
        while ( $result = $this->db->fetch_assoc( $results ) )
            $this->trophies[] = $result;
    }
    
    public function trophy_display_trophies() {
        
        $this->trophy_get_trophies();
        
        foreach ( $this->trophies as $trophy ) {
        
?>
					<div class="trophy">
						<div class="trophy-icon">
							<i class="icon-4x icon-trophy icon-trophy-gold" title="&#xF091;"></i>
							<i class="icon-trophy-medal icon-<?php echo $this->trophies_detail[$trophy['trophy']]['icon']; ?>"></i>
						</div>
						<h5><?php echo $this->trophies_detail[$trophy['trophy']]['title']; ?></h5>
						<p><?php echo $this->trophies_detail[$trophy['trophy']]['description']; ?></p>
					</div>
<?php
        
        }
?>
					<div style="clear:both"></div>
<?php
        
        
        
    }
    
    /**
     * Profile new trophy view
     * 
     * @since Stats 1.0
     */
    public function trophy_profile_view() {
        
        global $lang_profile;
        
?>
	<div class="blockform">
		<h2><span><?php echo $this->lang['Trophy']; ?></span></h2>
		<div class="box">
			<form id="profile7" method="post" action="profile.php?section=admin&amp;id=<?php echo $id ?>">
				<div class="infldset">
					<p>Au cours de vos pérégrinations sur le forum vous avez accomplis, sans le savoir, un certain nombre de quêtes… Vous trouverez ici les trophés qui récompensent ces quêtes oubliés ;)</p>
				</div>
				<br />
				<div class="infldset">
					<h4>Vos trophés</h4>
					<?php $this->trophy_display_trophies(); ?>
				</div>
			</form>
		</div>
	</div>
<?php
	
        
    }
    
    
    
}