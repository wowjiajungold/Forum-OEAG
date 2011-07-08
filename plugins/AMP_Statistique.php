<?php
/***********************************************************************

  Copyright (C) 2002-2005  Rickard Andersson (rickard@punbb.org)

  This file is part of PunBB.

  PunBB is free software; you can redistribute it and/or modify it
  under the terms of the GNU General Public License as published
  by the Free Software Foundation; either version 2 of the License,
  or (at your option) any later version.

  PunBB is distributed in the hope that it will be useful, but
  WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 59 Temple Place, Suite 330, Boston,
  MA  02111-1307  USA

************************************************************************/

##
##
##  Voici quelques notes intérressantes pour les aspirants auteurs de plugin :
##
##  1. Si vous voulez afficher un message par l'intermédiaire de la fonction 
##     message(), vous devez le faire avant d'appeler generate_admin_menu($plugin).
##
##  2. Les plugins sont chargés par admin_loader.php et ne doivent pas être terminés 
##     (par exemple en appelant exit()). Après que le script du plugin ait fini, le 
##     script du chargeur affiche le pied de page, ainsi inutil de vous souciez de cela. 
##     Cependant veuillez noter que terminer un plugin en appelant message() ou 
##     redirect() est très bien.
##
##  3. L'attribut action de toute balise <forme> et l'URL cible pour la fonction 
##     redirect() doit être placé à la valeur de $_SERVER['REQUEST_URI']. Cette 
##     URL peut cependant être étendue pour inclure des variables supplémentaires 
##     (comme l'ajout de &foo=bar dans le plugin exemple).
##
##  4. Si votre plugin est pour les administrateurs seulement, le nom de fichier 
##     doit avoir le préfixe AP_. S'il est pour les administrateurs et les modérateurs, 
##     utilisez le préfixe AMP_. Le plugin exemple a le préfixe AMP_ et est donc 
##     disponible dans le menu de navigation aux administrateurs et aux modérateurs.
##
##  5. Utilisez _ au lieu des espaces dans le nom de fichier.
##
##  6. Tant que les scripts de plugin sont inclus depuis le scripts admin_loader.php 
##     de PunBB, vous avez accès toutes les fonctions et variables globales de PunBB 
##     (par exemple $db, $pun_config, $pun_user etc.).
##
##  7. Faites de votre mieux pour garder l'aspect et l'ergonomie de votre interface 
##     utilisateur de plugins semblable au reste des scripts d'administration. 
##     N'hésitez pas à emprunter le marquage et le code aux scripts d'admin pour 
##     l'employer dans vos plugins.
##
##  8. Les plugins doivent êtres délivrés sous la licence d'utilisation GNU/GPL ou 
##     une licence compatible. Recopiez le préambule GPL (situé en haut des scripts 
##     de PunBB) dans votre script de plugin et changez l e copyright pour qu'il 
##     corresponde à l'auteur du plugin (c'est à dire vous).
##
##


// Make sure no one attempts to run this script "directly"
if (!defined('PUN'))
    exit;

// Tell admin_loader.php that this is indeed a plugin and that it is loaded
define('PUN_PLUGIN_LOADED', 1);

    generate_admin_menu($plugin);
	
// Quelques tableaux de données
$mois = array('01' => 'Janvier', '02' => 'Février', '03' => 'Mars', '04' => 'Avril', '05' => 'Mai', '06' => 'Juin', '07' => 'Juillet', '08' => 'Août', '09' => 'Septembre', '10' => 'Octobre', '11' => 'Novembre', '12' => 'Décembre');
$jour = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');
$heure = array('00' => '00h', '01' => '01h', '02' => '02h', '03' => '03h', '04' => '04h', '05' => '05h', '06' => '06h', '07' => '07h', '08' => '08h', '09' => '09h', '10' => '10h', '11' => '11h', '12' => '12h', '13' => '13h', '14' => '14h', '15' => '15h', '16' => '16h', '17' => '17h', '18' => '18h', '19' => '19h', '20' => '20h', '21' => '21h', '22' => '22h', '23' => '23h');

// Quelques fonctions
function aff_top_users($result)
	{
	global $db;
	$user = array();
	$id_user = array();
	$nb_post = array();
		
	while($reponse = $db->fetch_assoc($result))
		{
		$user[] = $reponse['poster'];
		$id_user[] = $reponse['poster_id'];
		$nb_post[] = $reponse['num_posts'];
		}
	
	echo "\n\t\t\t\t\t\t<table>";
	for($i = 0; $i < 10; $i++)
		{
		if(isset($user[$i]))
			{
			echo "\n\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t<td>".($i+1).". <a href='profile.php?id=".$id_user[$i]."'>".$user[$i]."</a> : ".$nb_post[$i]." posts</td>";
			
			if(isset($user[$i+10]))
				echo "\n\t\t\t\t\t\t\t\t<td>".($i+11).". <a href='profile.php?id=".$id_user[$i+10]."'>".$user[$i+10]."</a> : ".$nb_post[$i+10]." posts</td></tr>";
			else
				echo "\n\t\t\t\t\t\t\t\t<td></td>\n\t\t\t\t\t\t\t</tr>";
			}
		}
	echo "\n\t\t\t\t\t\t</table>";
	}
	
function aff_top_topics($result)
	{
	global $db;
	$user = array();
	$nb_post = array();
		
	while($reponse = $db->fetch_assoc($result))
		{
		$user[] = $reponse['poster'];
		$nb_post[] = $reponse['num_topics'];
		}
	
	echo "\n\t\t\t\t\t\t<table>";
	for($i = 0; $i < 10; $i++)
		{
		if(isset($user[$i]))
			{
			echo "\n\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t<td>".($i+1).". ".$user[$i]." : ".$nb_post[$i]." topics</td>";
			
			if(isset($user[$i+10]))
				echo "\n\t\t\t\t\t\t\t\t<td>".($i+11).". ".$user[$i+10]." : ".$nb_post[$i+10]." topics</td></tr>";
			else
				echo "\n\t\t\t\t\t\t\t\t<td></td>\n\t\t\t\t\t\t\t</tr>";
			}
		}
	echo "\n\t\t\t\t\t\t</table>";
	}
	
function aff_records($result, $type)
	{
	global $db;
	global $jour;
	global $mois;
	
	$posted = array();
	$nb_type = array();
		
	while($reponse = $db->fetch_assoc($result))
		{
		$posted[] = $jour[date('w', $reponse['posted'])]." ".date('j', $reponse['posted'])." ".strtolower($mois[date('m', $reponse['posted'])])." ".date('Y', $reponse['posted']);
		$nb_type[] = $reponse['num_type'];
		}
	
	echo "\n\t\t\t\t\t\t<table>";
	for($i = 0; $i < 10; $i++)
		{
		if(isset($posted[$i]))
			{
			echo "\n\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t<td>".($i+1).". ".$posted[$i]." : ".$nb_type[$i]." ".$type."</td>";
			
			if(isset($posted[$i+10]))
				echo "\n\t\t\t\t\t\t\t\t<td>".($i+11).". ".$posted[$i+10]." : ".$nb_type[$i+10]." ".$type."</td></tr>";
			else
				echo "\n\t\t\t\t\t\t\t\t<td></td>\n\t\t\t\t\t\t\t</tr>";
			}
		}
	echo "\n\t\t\t\t\t\t</table>";
	}
	
	
?>
    <div class="block"> 
        <h2><span>Statistiques du forum</span></h2> 
        <div class="box"> 
            <div class="inbox"> 
                <p>Connaître certaines statistiques sur votre forum :
				<p><a href='admin_loader.php?plugin=AMP_Statistique.php&evolution'>Evolution :</a> voir l'évolution du nombre de messages, de discussions et de membres.</p>
				<p><a href='admin_loader.php?plugin=AMP_Statistique.php&top'>Meilleurs posteurs :</a> top des meilleurs posteurs du jour, de la semaine, du mois, de l'année et depuis le début.</p>
				<p><a href='admin_loader.php?plugin=AMP_Statistique.php&top_crea'>Meilleurs créateurs :</a> top des plus grands créateurs de topics de la semaine, du mois, de l'année et depuis le début.</p>
				<p><a href='admin_loader.php?plugin=AMP_Statistique.php&record'>Records :</a> connaîtres les jours ayant eu le plus de messages postés, de discussions créées et d'inscriptions.</p>
				<p><a href='admin_loader.php?plugin=AMP_Statistique.php&repartition'>Répartition :</a> répartition des messages / topics / membres en fonction du mois de l'année, du jour de la semaine ou de l'heure (ne prends pas en compte les fuseaux horaires des membres).</p>
			</div> 
        </div> 
    </div> 
	<?php
	if(isset($_GET['evolution']))
		{
	?>
	<div class="block">
        <h2 class="block2">Évolution du forum</span></h2>
        <div class="box">
            <div class="inbox">
					<div class="infldset">
						<?php
						$r_mess = array();
						$r_date = array();
						$r_topic = array();
						$r_users = array();
						
						$result = $db->query('SELECT count(*) AS num_type, FROM_UNIXTIME(posted, "%Y-%m") AS type FROM '. $db->prefix .'posts GROUP BY type DESC', true)or error('Database error', __FILE__, __LINE__, $db->error());
						
						while($reponse = $db->fetch_assoc($result))
							{
							$r_mess[$reponse['type']] = $reponse['num_type'];
							$r_date[$reponse['type']] = '';
							}
												
						$result = $db->query('SELECT count(*) AS num_type, FROM_UNIXTIME(posted, "%Y-%m") AS type FROM '. $db->prefix .'topics GROUP BY type DESC', true)or error('Database error', __FILE__, __LINE__, $db->error());
						
						while($reponse = $db->fetch_assoc($result))
							{
							$r_topic[$reponse['type']] = $reponse['num_type'];
							$r_date[$reponse['type']] = '';
							}
							
						$result = $db->query('SELECT count(*) AS num_type, FROM_UNIXTIME(registered, "%Y-%m") AS type FROM '. $db->prefix .'users GROUP BY type DESC', true)or error('Database error', __FILE__, __LINE__, $db->error());
						
						while($reponse = $db->fetch_assoc($result))
							{
							if($reponse['type'] != '1970-01')
								{
								$r_users[$reponse['type']] = $reponse['num_type'];
								$r_date[$reponse['type']] = '';
								}
							}
												
						echo "\n\t\t\t\t\t\t<table>";
						
						echo "\n\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t<th></th>\n\t\t\t\t\t\t\t\t<th>Messages</th>";
						
						echo "\n\t\t\t\t\t\t\t\t<th>Topics</th>\n\t\t\t\t\t\t\t\t<th>Membres</th>\n\t\t\t\t\t\t\t</tr>";
						
						foreach($r_date AS $i=>$lib)
							{
							$tab_date = explode('-', $i);
							
							echo "\n\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t<td>".$mois[$tab_date[1]]." ".$tab_date[0]."</td>";
								
							if(isset($r_mess[$i]))
									echo "\n\t\t\t\t\t\t\t\t<td>".$r_mess[$i]."</td>";
								else
									echo "\n\t\t\t\t\t\t\t\t<td>0</td>";
							
							if(isset($r_topic[$i]))
									echo "\n\t\t\t\t\t\t\t\t<td>".$r_topic[$i]."</td>";
								else
									echo "\n\t\t\t\t\t\t\t\t<td>0</td>";
							
							if(isset($r_users[$i]))
									echo "\n\t\t\t\t\t\t\t\t<td>".$r_users[$i]." </td>";
								else
									echo "\n\t\t\t\t\t\t\t\t<td>0</td>";
							
							echo "\n\t\t\t\t\t\t\t</tr>";
							}
						echo "\n\t\t\t\t\t\t</table>";
						?>						
											
					</div>
				</div>
        </div>
    </div>
	<?php
		}
	else if(isset($_GET['record']))
		{
		?>
    <div class="block">
        <h2 class="block2"><span>Journées comptabilisant le plus de nouveaux messages</span></h2>
        <div class="box">
            <div class="inbox">
					<div class="infldset">
						<?php
						$result = $db->query('SELECT count(*) AS num_type, posted FROM '. $db->prefix .'posts GROUP BY FROM_UNIXTIME(posted, "%d-%m-%Y") ORDER BY num_type DESC, posted DESC LIMIT 20', true)or error('Database error', __FILE__, __LINE__, $db->error());
						aff_records($result, 'messages');
						?>						
											
					</div>
				</div>
        </div>
    </div>
	
	<div class="block">
        <h2 class="block2"><span>Journées comptabilisant le plus de nouvelles discussions</span></h2>
        <div class="box">
            <div class="inbox">
					<div class="infldset">
						<?php
						$result = $db->query('SELECT count(*) AS num_type, posted FROM '. $db->prefix .'topics GROUP BY FROM_UNIXTIME(posted, "%d-%m-%Y") ORDER BY num_type DESC, posted DESC LIMIT 20', true)or error('Database error', __FILE__, __LINE__, $db->error());
						
						aff_records($result, 'topics');
						?>						
											
					</div>
				</div>
        </div>
    </div>
	
	<div class="block">
        <h2 class="block2"><span>Journées comptabilisant le plus de nouveaux membres</span></h2>
        <div class="box">
            <div class="inbox">
					<div class="infldset">
						<?php
						$result = $db->query('SELECT count(*) AS num_type, registered AS posted FROM '. $db->prefix .'users GROUP BY FROM_UNIXTIME(registered, "%d-%m-%Y") ORDER BY num_type DESC, registered LIMIT 20', true)or error('Database error', __FILE__, __LINE__, $db->error());
						aff_records($result, 'membres');
						?>						
											
					</div>
				</div>
        </div>
    </div>
		<?php
		}
	else if(isset($_GET['repartition']))
		{
		$format_unix = array("%m", "%w", "%H");
		$titre_h2 = array("Réparition en fonction du mois de l'année", "Réparition des messages en fonction du jour de la semaine", "Réparition des messages en fonction de l'heure");
		$tab_val = array('mois', 'jour', 'heure');
		
		foreach($tab_val AS $id=>$lib)
		{
		?>
	<div class="block">
        <h2 class="block2"><span><?php echo $titre_h2[$id]; ?></span></h2>
        <div class="box">
            <div class="inbox">
					<div class="infldset">
						<?php
						$r_mess = array();
						$r_topic = array();
						$r_users = array();
											
						$result = $db->query('SELECT count(*) AS num_type, FROM_UNIXTIME(posted, "'.$format_unix[$id].'") AS type FROM '. $db->prefix .'posts GROUP BY type ORDER BY num_type DESC', true)or error('Database error', __FILE__, __LINE__, $db->error());
						$i = 0;
						while($reponse = $db->fetch_assoc($result))
							{
							$i++;
							$r_mess[$reponse['type']] = $reponse['num_type']." (".$i."°)";
							}
							
						$result = $db->query('SELECT count(*) AS num_type, FROM_UNIXTIME(posted, "'.$format_unix[$id].'") AS type FROM '. $db->prefix .'topics GROUP BY type ORDER BY num_type DESC', true)or error('Database error', __FILE__, __LINE__, $db->error());
						$i = 0;
						while($reponse = $db->fetch_assoc($result))
							{
							$i++;
							$r_topic[$reponse['type']] = $reponse['num_type']." (".$i."°)";
							}
							
						$result = $db->query('SELECT count(*) AS num_type, FROM_UNIXTIME(registered, "'.$format_unix[$id].'") AS type FROM '. $db->prefix .'users GROUP BY type ORDER BY num_type DESC', true)or error('Database error', __FILE__, __LINE__, $db->error());
						$i = 0;
						while($reponse = $db->fetch_assoc($result))
							{
							$i++;
							$r_users[$reponse['type']] = $reponse['num_type']." (".$i."°)";
							}
												
						echo "\n\t\t\t\t\t\t<table style=\"text-align: left;\">";
						
						echo "\n\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t<th></th>\n\t\t\t\t\t\t\t\t<th>Messages</th>";
						
						echo "\n\t\t\t\t\t\t\t\t<th>Topics</th>\n\t\t\t\t\t\t\t\t<th>Membres</th>\n\t\t\t\t\t\t\t</tr>";
						
						foreach(${$tab_val[$id]} AS $i=>$lib)
							{
							echo "\n\t\t\t\t\t\t\t<tr>\n\t\t\t\t\t\t\t\t<td>".${$tab_val[$id]}[$i]."</td>";
								
							if(isset($r_mess[$i]))
									echo "\n\t\t\t\t\t\t\t\t<td>".$r_mess[$i]."</td>";
								else
									echo "\n\t\t\t\t\t\t\t\t<td>0</td>";
							
							if(isset($r_topic[$i]))
									echo "\n\t\t\t\t\t\t\t\t<td>".$r_topic[$i]."</td>";
								else
									echo "\n\t\t\t\t\t\t\t\t<td>0</td>";
							
							if(isset($r_users[$i]))
									echo "\n\t\t\t\t\t\t\t\t<td>".$r_users[$i]." </td>";
								else
									echo "\n\t\t\t\t\t\t\t\t<td>0</td>";
							
							echo "\n\t\t\t\t\t\t\t</tr>";
							}
						echo "\n\t\t\t\t\t\t</table>";
						?>						
											
					</div>
				</div>
        </div>
    </div>
	<?php
		}
	
		}
	else if(isset($_GET['top']))
		{
		$format_date = array(date('Y-m-d'), date('Y-W'), date('Y-m'), date('Y'));
		$format_unix = array("%Y-%m-%d", "%Y-%u", "%Y-%m", "%Y");
		$titre_h2 = array("Meilleurs posteurs de la journée", "Meilleurs posteurs de la semaine", "Meilleurs posteurs du mois", "Meilleurs posteurs de l'année");
		
		$j = 0;
		while(isset($format_date[$j]))
			{
		?>
	<div class="block">
        <h2 class="block2"><span><?php echo $titre_h2[$j] ;?></span></h2>
        <div class="box">
            <div class="inbox">
					<div class="infldset">
						<?php
											
						$result = $db->query('SELECT count(*) AS num_posts, poster, poster_id FROM '. $db->prefix .'posts WHERE FROM_UNIXTIME(posted, "'.$format_unix[$j].'") = \''.$format_date[$j].'\' GROUP BY poster_id ORDER BY num_posts DESC, poster LIMIT 20', true)or error('Database error', __FILE__, __LINE__, $db->error());
						
						aff_top_users($result);
						?>						
											
					</div>
				</div>
        </div>
    </div>
			<?php	
			$j++;
			}
		?>
	<div class="block">
        <h2 class="block2"><span>Meilleurs posteurs depuis le début</span></h2>
        <div class="box">
            <div class="inbox">
					<div class="infldset">
						<?php
						$result = $db->query('SELECT id AS poster_id, username AS poster, num_posts FROM '. $db->prefix .'users ORDER BY num_posts DESC LIMIT 20', true)or error('Database error', __FILE__, __LINE__, $db->error());
						
						aff_top_users($result);
						?>						
											
					</div>
				</div>
        </div>
    </div>
		<?php
		}
	else if(isset($_GET['top_crea']))
		{
		$format_date = array(date('Y-W'), date('Y-m'), date('Y'));
		$format_unix = array("%Y-%u", "%Y-%m", "%Y");
		$titre_h2 = array("Meilleurs créateurs de topics de la semaine", "Meilleurs créateurs de topics du mois", "Meilleurs créateurs de topics de l'année");
		
		$j = 0;
		while(isset($format_date[$j]))
			{
		?>
	<div class="block">
        <h2 class="block2"><span><?php echo $titre_h2[$j] ;?></span></h2>
        <div class="box">
            <div class="inbox">
					<div class="infldset">
						<?php
											
						$result = $db->query('SELECT COUNT(*) AS num_topics, poster FROM '. $db->prefix .'topics WHERE FROM_UNIXTIME(posted, "'.$format_unix[$j].'") = \''.$format_date[$j].'\' GROUP BY poster ORDER BY num_topics DESC LIMIT 20', true)or error('Database error', __FILE__, __LINE__, $db->error());
						
						aff_top_topics($result);
						?>						
											
					</div>
				</div>
        </div>
    </div>
			<?php	
			$j++;
			}
		?>
	<div class="block">
        <h2 class="block2"><span>Meilleurs créateurs de topics depuis le début</span></h2>
        <div class="box">
            <div class="inbox">
					<div class="infldset">
						<?php
						$result = $db->query('SELECT COUNT(*) AS num_topics, poster FROM '. $db->prefix .'topics GROUP BY poster ORDER BY num_topics DESC LIMIT 20', true)or error('Database error', __FILE__, __LINE__, $db->error());
						
						aff_top_topics($result);
						?>						
											
					</div>
				</div>
        </div>
    </div>
		<?php
		}
		?>