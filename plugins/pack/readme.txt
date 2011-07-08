##
##        Mod title:  Easy Poll +
##
##      Mod version:  2.1
##   Works on PunBB:  1.2.17, 1.2.18
##     Release date:  05-12-08 (May, 12th)
##          Creator:  Caleb Champlin (Mediator) [med_mediator@hotmail.com]
##           Author:  BN [bnmaster@la-bnbox.info] - [http://la-bnbox.info]
##       Contribute:  Romain9441 [webmaster.rg@gmail.com] - [http://boardfun.free.fr]
##					  vin100
##					  PascL [kokoala2k3@free.fr]
##
##      Description:  Poll system for PunBB/FluxBB
##
##       Affects DB:  Yes 
##
##       DISCLAIMER:  Please note that "mods" are not officially supported by
##                    PunBB/FluxBB. Installation of this modification is done at your
##                    own risk. Backup your forum database and any and all
##                    applicable files before proceeding.
##
##   Affected files:  viewforum.php
##		      		  viewtopic.php
##		      		  post.php
##					  edit.php
##		      		  admin_forum.php
##		      		  admin_groups.php
##		      		  moderate.php
##		      		  index.php
##                    search.php
##		      		  lang/LANG/index.php
##		      		  include/functions.php
##					  style/VOTRESTYLE.css
##
##			   Note:  1 readme for 2 languages / 1 lisezmoi pour 2 langues
##					  Be careful, some modifications are different according to the language (steps with OU/OR)
##					  Attention, certaines modifications sont differentes selon la langue (etapes avec OU/OR)
##

#
#---------[ 1. UPLOAD ]---------------------------------------------------
#

install_mod.php
vote.php
plugins/AP_Poll.php ou plugins/AP_Sondage.php
plugins/AMP_Poll.php ou plugins/AMP_Sondage.php
lang/LANGUAGE/polls.php
img/transparent.gif

#
#---------[ 2. RUN ]---------------------------------------------------
#

install_mod.php

#
#---------[ 3. DELETE ]---------------------------------------------------
#

install_mod.php

#
#---------[ 4. OPEN ]---------------------------------------------------
#

viewforum.php

#
#---------[ 5. FIND (ligne: 39) ]---------------------------------------------------
#

require PUN_ROOT.'lang/'.$pun_user['language'].'/forum.php';

#
#---------[ 6. ADD AFTER ]---------------------------------------------------
#

// Load poll language file
require PUN_ROOT.'lang/'.$pun_user['language'].'/polls.php';

#
#---------[ 7. FIND (line: 42) ]---------------------------------------------------
#

$result = $db->query('SELECT f.forum_name, f.redirect_url, f.moderators, f.num_topics, f.sort_by, fp.post_topics FROM '.$db->prefix.'forums AS f LEFT JOIN '.$db->prefix.'forum_perms AS fp ON (fp.forum_id=f.id AND fp.group_id='.$pun_user['g_id'].') WHERE (fp.read_forum IS NULL OR fp.read_forum=1) AND f.id='.$id) or error('Unable to fetch forum info', __FILE__, __LINE__, $db->error());

#
#---------[ 8. FIND ]---------------------------------------------------
#

f.sort_by, fp.post_topics

#
#---------[ 9. AFTER, INSERT ]---------------------------------------------------
#

, fp.post_polls

#
#---------[ 10. FIND (line: 62) ]---------------------------------------------------
#

// Can we or can we not post new topics?
if (($cur_forum['post_topics'] == '' && $pun_user['g_post_topics'] == '1') || $cur_forum['post_topics'] == '1' || $is_admmod)
	$post_link = "\t\t".'<p class="postlink conr"><a href="post.php?fid='.$id.'">'.$lang_forum['Post topic'].'</a></p>'."\n";
else
	$post_link = '';

#
#---------[ 11. ADD AFTER ]---------------------------------------------------
#

if (($cur_forum['post_polls'] == '' && $pun_user['g_post_polls'] == '1') || $cur_forum['post_polls'] == '1' || $is_admmod)
	$post_link .= "\t\t".'<p class="postlink conr"><a href="post.php?fid='.$id.'&amp;type=poll">'.$lang_polls['New poll'].'</a></p>'."\n";

#
#---------[ 12. FIND (line: 112) ]---------------------------------------------------
#

	// Without "the dot"
	$sql = 'SELECT id, poster, subject, posted, last_post, last_post_id, last_poster, num_views, num_replies, closed, sticky, moved_to FROM '.$db->prefix.'topics WHERE forum_id='.$id.' ORDER BY sticky DESC, '.(($cur_forum['sort_by'] == '1') ? 'posted' : 'last_post').' DESC LIMIT '.$start_from.', '.$pun_user['disp_topics'];

#
#---------[ 13. FIND ]---------------------------------------------------
#

sticky, moved_to

#
#---------[ 14. AFTER INSERT ]---------------------------------------------------
#

, question

#
#---------[ 15. FIND (line: 120) ]---------------------------------------------------
#

		case 'mysql':
		case 'mysqli':
			$sql = 'SELECT p.poster_id AS has_posted, t.id, t.subject, t.poster, t.posted, t.last_post, t.last_post_id, t.last_poster, t.num_views, t.num_replies, t.closed, t.sticky, t.moved_to FROM '.$db->prefix.'topics AS t LEFT JOIN '.$db->prefix.'posts AS p ON t.id=p.topic_id AND p.poster_id='.$pun_user['id'].' WHERE t.forum_id='.$id.' GROUP BY t.id ORDER BY sticky DESC, '.(($cur_forum['sort_by'] == '1') ? 'posted' : 'last_post').' DESC LIMIT '.$start_from.', '.$pun_user['disp_topics'];

#
#---------[ 16. FIND ]---------------------------------------------------
#

t.sticky, t.moved_to


#
#---------[ 17. AFTER INSERT ]---------------------------------------------------
#

, t.question

#
#---------[ 18. FIND (line: 125) ]---------------------------------------------------
#

		case 'sqlite':
			$sql = 'SELECT p.poster_id AS has_posted, t.id, t.subject, t.poster, t.posted, t.last_post, t.last_post_id, t.last_poster, t.num_views, t.num_replies, t.closed, t.sticky, t.moved_to FROM '.$db->prefix.'topics AS t LEFT JOIN '.$db->prefix.'posts AS p ON t.id=p.topic_id AND p.poster_id='.$pun_user['id'].' WHERE t.id IN(SELECT id FROM '.$db->prefix.'topics WHERE forum_id='.$id.' ORDER BY sticky DESC, '.(($cur_forum['sort_by'] == '1') ? 'posted' : 'last_post').' DESC LIMIT '.$start_from.', '.$pun_user['disp_topics'].') GROUP BY t.id ORDER BY t.sticky DESC, t.last_post DESC';

#
#---------[ 19. FIND ]---------------------------------------------------
#

t.sticky, t.moved_to


#
#---------[ 20. AFTER INSERT ]---------------------------------------------------
#

, t.question

#
#---------[ 21. FIND (line: 129) ]---------------------------------------------------
#

		default:
			$sql = 'SELECT p.poster_id AS has_posted, t.id, t.subject, t.poster, t.posted, t.last_post, t.last_post_id, t.last_poster, t.num_views, t.num_replies, t.closed, t.sticky, t.moved_to FROM '.$db->prefix.'topics AS t LEFT JOIN '.$db->prefix.'posts AS p ON t.id=p.topic_id AND p.poster_id='.$pun_user['id'].' WHERE t.forum_id='.$id.' GROUP BY t.id, t.subject, t.poster, t.posted, t.last_post, t.last_post_id, t.last_poster, t.num_views, t.num_replies, t.closed, t.sticky, t.moved_to, p.poster_id ORDER BY sticky DESC, '.(($cur_forum['sort_by'] == '1') ? 'posted' : 'last_post').' DESC LIMIT '.$start_from.', '.$pun_user['disp_topics'];

#
#---------[ 22. FIND ]---------------------------------------------------
#

t.sticky, t.moved_to


#
#---------[ 23. AFTER INSERT ]---------------------------------------------------
#

, t.question

#
#---------[ 24. FIND IN THE SAME LINE ]---------------------------------------------------
#

t.sticky, t.moved_to


#
#---------[ 25. AFTER INSERT ]---------------------------------------------------
#

, t.question

#
#---------[ 26. FIND (lign: 141) ]---------------------------------------------------
#

	while ($cur_topic = $db->fetch_assoc($result))
	{
		$icon_text = $lang_common['Normal icon'];
		$item_status = '';
		$icon_type = 'icon';

		if ($cur_topic['moved_to'] == null)
			$last_post = '<a href="viewtopic.php?pid='.$cur_topic['last_post_id'].'#p'.$cur_topic['last_post_id'].'">'.format_time($cur_topic['last_post']).'</a> <span class="byuser">'.$lang_common['by'].'&nbsp;'.pun_htmlspecialchars($cur_topic['last_poster']).'</span>';
		else
			$last_post = '&nbsp;';

		if ($pun_config['o_censoring'] == '1')
			$cur_topic['subject'] = censor_words($cur_topic['subject']);

#
#---------[ 27. ADD AFTER ]---------------------------------------------------
#

		if ($cur_topic['question'] != '') 
		{
			if ($pun_config['o_censoring'] == '1')
				$cur_topic['question'] = censor_words($cur_topic['question']);
		
		
			if ($cur_topic['moved_to'] != 0)
				$subject = $lang_forum['Moved'].': ' . $lang_polls['Poll'].': <a href="viewtopic.php?id='.$cur_topic['id'].'">'.pun_htmlspecialchars($cur_topic['subject']).'</a> <span class="byuser">'.$lang_common['by'].'&nbsp;'.pun_htmlspecialchars($cur_topic['poster']).'</span><br />[ '.pun_htmlspecialchars($cur_topic['question']).' ]';
			else if ($cur_topic['closed'] == '0')
				$subject = $lang_polls['Poll'].': <a href="viewtopic.php?id='.$cur_topic['id'].'">'.pun_htmlspecialchars($cur_topic['subject']).'</a> <span class="byuser">'.$lang_common['by'].'&nbsp;'.pun_htmlspecialchars($cur_topic['poster']).'</span><br />[ '.pun_htmlspecialchars($cur_topic['question']).' ]';
			else
			{
				$subject = $lang_polls['Poll'] . ': <a href="viewtopic.php?id='.$cur_topic['id'].'">'.pun_htmlspecialchars($cur_topic['subject']).'</a> <span class="byuser">'.$lang_common['by'].'&nbsp;'.pun_htmlspecialchars($cur_topic['poster']).'</span><br />[ '.pun_htmlspecialchars($cur_topic['question']).' ]';
				$icon_text = $lang_common['Closed icon'];
				$item_status = 'iclosed';
			}
	
			if (!$pun_user['is_guest'] && $cur_topic['last_post'] > $pun_user['last_visit'] && $cur_topic['moved_to'] == null)
			{
				$icon_text .= ' '.$lang_common['New icon'];
				$item_status .= ' inew';
				$icon_type = 'icon inew';

	
				$subject = '<strong>'.$subject.'</strong>';
				$subject_new_posts = '<span class="newtext">[&nbsp;<a href="viewtopic.php?id='.$cur_topic['id'].'&amp;action=new" title="'.$lang_common['New posts info'].'">'.$lang_common['New posts'].'</a>&nbsp;]</span>';
			}
			else
				$subject_new_posts = null;
	
			// Should we display the dot or not? :)
			if (!$pun_user['is_guest'] && $pun_config['o_show_dot'] == '1')
			{
				if ($cur_topic['has_posted'] == $pun_user['id'])
					$subject = '<strong>&middot;</strong>&nbsp;'.$subject;
				else
					$subject = '&nbsp;&nbsp;'.$subject;
			}
		}
		else 
		{

#
#---------[ 28. FIND (line: 177) ]---------------------------------------------------
#

		// Should we display the dot or not? :)
		if (!$pun_user['is_guest'] && $pun_config['o_show_dot'] == '1')
		{
			if ($cur_topic['has_posted'] == $pun_user['id'])
				$subject = '<strong>&middot;</strong>&nbsp;'.$subject;
			else
				$subject = '&nbsp;&nbsp;'.$subject;
		}

#
#---------[ 29. ADD AFTER ]---------------------------------------------------
#

		}

#
#---------[ 30. OPEN ]---------------------------------------------------
#

viewtopic.php

#
#---------[ 31. FIND (line: 96) ]---------------------------------------------------
#

// Fetch some info about the topic
if (!$pun_user['is_guest'])
	$result = $db->query('SELECT t.subject, t.closed, t.num_replies, t.sticky, f.id AS forum_id, f.forum_name, f.moderators, fp.post_replies, s.user_id AS is_subscribed FROM '.$db->prefix.'topics AS t INNER JOIN '.$db->prefix.'forums AS f ON f.id=t.forum_id LEFT JOIN '.$db->prefix.'subscriptions AS s ON (t.id=s.topic_id AND s.user_id='.$pun_user['id'].') LEFT JOIN '.$db->prefix.'forum_perms AS fp ON (fp.forum_id=f.id AND fp.group_id='.$pun_user['g_id'].') WHERE (fp.read_forum IS NULL OR fp.read_forum=1) AND t.id='.$id.' AND t.moved_to IS NULL') or error('Unable to fetch topic info', __FILE__, __LINE__, $db->error());

#
#---------[ 32. FIND ]---------------------------------------------------
#

, t.num_replies, t.sticky

#
#---------[ 33. AFTER INSERT ]---------------------------------------------------
#

, t.posted, t.question, t.yes, t.no

#
#---------[ 34. FIND (line: 99) ]---------------------------------------------------
#

else
	$result = $db->query('SELECT t.subject, t.closed, t.num_replies, t.sticky, f.id AS forum_id, f.forum_name, f.moderators, fp.post_replies, 0 FROM '.$db->prefix.'topics AS t INNER JOIN '.$db->prefix.'forums AS f ON f.id=t.forum_id LEFT JOIN '.$db->prefix.'forum_perms AS fp ON (fp.forum_id=f.id AND fp.group_id='.$pun_user['g_id'].') WHERE (fp.read_forum IS NULL OR fp.read_forum=1) AND t.id='.$id.' AND t.moved_to IS NULL') or error('Unable to fetch topic info', __FILE__, __LINE__, $db->error());

#
#---------[ 35. FIND ]---------------------------------------------------
#

, t.num_replies, t.sticky

#
#---------[ 36. AFTER INSERT ]---------------------------------------------------
#

, t.posted, t.question, t.yes, t.no

#
#---------[ 37. FIND (lign: 163) ]---------------------------------------------------
#

$page_title = pun_htmlspecialchars($pun_config['o_board_title'].' / '.$cur_topic['subject']);
define('PUN_ALLOW_INDEX', 1);
require PUN_ROOT.'header.php';

#
#---------[ 38. REPLACE BY ]---------------------------------------------------
#

if ($cur_topic['question'])
{
	if ($pun_config['o_censoring'] == '1')
		$cur_topic_question = censor_words($cur_topic['question']).' - ';
	else
		$cur_topic_question = $cur_topic['question'].' - ';
}
else
	$cur_topic_question = '';

$page_title = pun_htmlspecialchars($pun_config['o_board_title'].' / '.$cur_topic_question . $cur_topic['subject']);
define('PUN_ALLOW_INDEX', 1);
require PUN_ROOT.'header.php';

#
#---------[ 39. FIND (line: 185) ]---------------------------------------------------
#

// Retrieve the posts (and their respective poster/online status)
$result = $db->query('SELECT u.email, u.title, u.url, u.location, u.use_avatar, u.signature, u.email_setting, u.num_posts, u.registered, u.admin_note, p.id, p.poster AS username, p.poster_id, p.poster_ip, p.poster_email, p.message, p.hide_smilies, p.posted, p.edited, p.edited_by, g.g_id, g.g_user_title, o.user_id AS is_online FROM '.$db->prefix.'posts AS p INNER JOIN '.$db->prefix.'users AS u ON u.id=p.poster_id INNER JOIN '.$db->prefix.'groups AS g ON g.g_id=u.group_id LEFT JOIN '.$db->prefix.'online AS o ON (o.user_id=u.id AND o.user_id!=1 AND o.idle=0) WHERE p.topic_id='.$id.' ORDER BY p.id LIMIT '.$start_from.','.$pun_user['disp_posts'], true) or error('Unable to fetch post info', __FILE__, __LINE__, $db->error());
while ($cur_post = $db->fetch_assoc($result))

#
#---------[ 40. ADD BEFORE ]---------------------------------------------------
#

// Mod poll begin
if ($cur_topic['question'])
{
	require PUN_ROOT . 'lang/' . $pun_user['language'] . '/polls.php'; 
    // get the poll data, query modified by 2.1
    $result = $db->query('SELECT ptype, options, voters, votes, created, edited  FROM '.$db->prefix.'polls WHERE pollid='.$id) or error('Unable to fetch poll info', __FILE__, __LINE__, $db->error());

    if (!$db->num_rows($result))
        message($lang_common['Bad request']);

    $cur_poll = $db->fetch_assoc($result);

    $options = unserialize($cur_poll['options']);
    if (!empty($cur_poll['voters']))
        $voters = unserialize($cur_poll['voters']);
    else
        $voters = array();

    $ptype = $cur_poll['ptype']; 
    // yay memory!
    // $cur_poll = null;
    $firstcheck = false;
    ?>
<div class="blockform">
	<h2><span><?php echo $lang_polls['Poll'] ?></span></h2>
	<div class="box">
    	<?php
    if ((!$pun_user['is_guest']) && (!in_array($pun_user['id'], $voters)) && ($cur_topic['closed'] == '0') && (($cur_topic['post_replies'] == '1' || ($cur_topic['post_replies'] == '' && $pun_user['g_post_replies'] == '1')) || $is_admmod)) 
	{
		$showsubmit = true;
		?>
		<form id="post" method="post" action="vote.php">
			<div class="inform">
				<div class="rbox">
				<fieldset>
					<legend><?php echo pun_htmlspecialchars($cur_topic['question']) ?></legend>
					<div class="infldset txtarea">
						<input type="hidden" name="poll_id" value="<?php echo $id; ?>" />
						<input type="hidden" name="form_sent" value="1" />
						<input type="hidden" name="form_user" value="<?php echo (!$pun_user['is_guest']) ? pun_htmlspecialchars($pun_user['username']) : 'Guest'; ?>" />
	
						<?php
				        if ($ptype == 1) 
						{
							while (list($key, $value) = each($options)) 
							{
							?>
								<label><input name="vote" <?php if (!$firstcheck) { echo 'checked="checked"'; $firstcheck = true; }; ?> type="radio" value="<?php echo $key ?>" /> <span><?php echo pun_htmlspecialchars($value); ?></span></label>
							<?php
				            } 
				        } 
						elseif ($ptype == 2) 
						{
						    while (list($key, $value) = each($options)) 
							{         
							?>
								<label><input name="options[<?php echo $key ?>]" type="checkbox" value="1" /> <span><?php echo pun_htmlspecialchars($value); ?></span></label>
							<?php
				            } 
				        } 
						elseif ($ptype == 3) 
						{
							
							while (list($key, $value) = each($options)) 
							{
								echo pun_htmlspecialchars($value); ?>
								<label><input name="options[<?php echo $key ?>]" checked="checked" type="radio" value="yes" /> <?php echo $cur_topic['yes']; ?></label>
								<label><input name="options[<?php echo $key ?>]" type="radio" value="no" /> <?php echo $cur_topic['no']; ?></label>
								<br />
							<?php
				            } 
						} 
						else
						{
							message($lang_common['Bad request']);
						}
			?></div></fieldset><?php
    } 
	else 
	{
		$showsubmit = false;
		?>
		<div class="inform">
		<div class="rbox">
			
			<p class="poll_info"><strong><?php echo pun_htmlspecialchars($cur_topic['question']) ?></strong></p>			
			<?php
    		if (!empty($cur_poll['votes']))
    	    		$votes = unserialize($cur_poll['votes']);
    		else
          		$votes = array();
		
			if ($ptype == 1 || $ptype == 2) 
			{
				$total = 0;
				$percent = 0;
				$percent_int = 0;
				while (list($key, $val) = each($options)) 
				{
					if (isset($votes[$key]))
						$total += $votes[$key];
				}
				reset($options);
			}
			
		  	while (list($key, $value) = each($options)) {    

				if ($ptype == 1 || $ptype == 2)
				{ 
					if (isset($votes[$key]))
					{
						$percent =  $votes[$key] * 100 / $total;
						$percent_int = floor($percent);
					}
					?>
						<div class="poll_question"><?php echo pun_htmlspecialchars($value); ?></div>
						<div class="poll_result">
							<img src="img/transparent.gif" class="poll_bar" style="width:<?php if (isset($votes[$key])) echo $percent_int/2; else echo '0'; ?>%;" alt="" />
							<span><?php if (isset($votes[$key])) echo $percent_int . '% - ' . $votes[$key]; else echo '0% - 0'; ?></span>
						</div>
				<?php
				}
				else if ($ptype == 3) 
				{ 
					$total = 0;
					$yes_percent = 0;
					$no_percent = 0;
					$vote_yes = 0;
					$vote_no = 0;
					if (isset($votes[$key]['yes']))
					{
						$vote_yes = $votes[$key]['yes'];
					}

					if (isset($votes[$key]['no'])) {
						$vote_no += $votes[$key]['no'];
					}

					$total = $vote_yes + $vote_no;
					if (isset($votes[$key]))
					{
						$yes_percent =   floor($vote_yes * 100 / $total);
						$no_percent = floor($vote_no * 100 / $total);
					}
					?>
						<div class="poll_question"><?php echo pun_htmlspecialchars($value); ?></div>
						
						<div class="poll_result_yesno">
							<strong><?php echo $cur_topic['yes']; ?></strong>
								<img src="img/transparent.gif" class="poll_bar" style="width:<?php if (isset($votes[$key]['yes'])) { echo $yes_percent/2; } else { echo '0';  } ?>%;" alt="" />
								<span><?php if (isset($votes[$key]['yes'])) { echo $yes_percent . "% - " . $votes[$key]['yes']; } else { echo "0% - " . 0; } ?></span>
						</div>
						<div class="poll_result_yesno">						
							<strong><?php echo $cur_topic['no']; ?></strong>
								<img src="img/transparent.gif" class="poll_bar" style="width:<?php if (isset($votes[$key]['no'])) { echo $no_percent/2; } else { echo '0';  } ?>%;" alt="" />
								<span><?php if (isset($votes[$key]['no'])) { echo $no_percent . "% - " . $votes[$key]['no']; } else { echo "0% - " . 0; } ?></span>
						</div>
					<?php 
				}
				else
				message($lang_common['Bad request']);
            } 	
			?>
				<p class="poll_info">Total : <?php echo $total; ?></p>
			<?php
		} 
		?>
			</div>
			
			<?php
			// Start 2.1
			if($cur_topic['posted']!=$cur_poll['created'])
			{
				echo "\t\t\t\t\t".'<p class="postedit"><em>'.$lang_polls['Poll Creation'].' : '.format_time($cur_poll['created']);
				if ($cur_poll['edited'] != 0)
					echo '<br />'.$lang_polls['Poll Edition'].' ('.format_time($cur_poll['edited']).')';
				echo '</em></p>'."\n";
			}
			else
				if ($cur_poll['edited'] != 0)
					echo "\t\t\t\t\t".'<p class="postedit"><em>'.$lang_polls['Poll Edition'].' ('.format_time($cur_poll['edited']).')</em></p>'."\n";
			// End 2.1
			?>
				
			</div>

			<?php if ($showsubmit == true) 
			{ 
				echo '<p><input type="submit" name="submit" tabindex="2" value="' . $lang_common['Submit'] . '" accesskey="s" /> <input type="submit" name="null" tabindex="2" value="' . $lang_polls['Null vote']. '" accesskey="n" /></p>
				</form>';
			} 
			?>
	</div>
</div>
<?php
}
// Mod poll end

#
#---------[ 41. OPEN ]---------------------------------------------------
#

post.php

#
#---------[ 42. FIND (line: 34) ]---------------------------------------------------
#

$tid = isset($_GET['tid']) ? intval($_GET['tid']) : 0;
$fid = isset($_GET['fid']) ? intval($_GET['fid']) : 0;
if ($tid < 1 && $fid < 1 || $tid > 0 && $fid > 0)
	message($lang_common['Bad request']);

#
#---------[ 43. ADD BEFORE ]---------------------------------------------------
#

$ptype = isset($_POST['ptype']) ? intval($_POST['ptype']) : 0;

#
#---------[ 44. FIND (line: 39) ]---------------------------------------------------
#

// Fetch some info about the topic and/or the forum
if ($tid)
	$result = $db->query('SELECT f.id, f.forum_name, f.moderators, f.redirect_url, fp.post_replies, fp.post_topics, t.subject, t.closed FROM '.$db->prefix.'topics AS t INNER JOIN '.$db->prefix.'forums AS f ON f.id=t.forum_id LEFT JOIN '.$db->prefix.'forum_perms AS fp ON (fp.forum_id=f.id AND fp.group_id='.$pun_user['g_id'].') WHERE (fp.read_forum IS NULL OR fp.read_forum=1) AND t.id='.$tid) or error('Unable to fetch forum info', __FILE__, __LINE__, $db->error());

#
#---------[ 45. FIND ]---------------------------------------------------
#

, fp.post_replies, fp.post_topics

#
#---------[ 46. AFTER INSERT ]---------------------------------------------------
#

, fp.post_polls, t.question

#
#---------[ 47. FIND (line: 42) ]---------------------------------------------------
#

else
	$result = $db->query('SELECT f.id, f.forum_name, f.moderators, f.redirect_url, fp.post_replies, fp.post_topics FROM '.$db->prefix.'forums AS f LEFT JOIN '.$db->prefix.'forum_perms AS fp ON (fp.forum_id=f.id AND fp.group_id='.$pun_user['g_id'].') WHERE (fp.read_forum IS NULL OR fp.read_forum=1) AND f.id='.$fid) or error('Unable to fetch forum info', __FILE__, __LINE__, $db->error());

#
#---------[ 48. FIND ]---------------------------------------------------
#

, fp.post_replies, fp.post_topics

#
#---------[ 49. AFTER INSERT ]---------------------------------------------------
#

, fp.post_polls

#
#---------[ 50. FIND (line: 58) ]---------------------------------------------------
#

// Do we have permission to post?
if ((($tid && (($cur_posting['post_replies'] == '' && $pun_user['g_post_replies'] == '0') || $cur_posting['post_replies'] == '0')) ||
	($fid && (($cur_posting['post_topics'] == '' && $pun_user['g_post_topics'] == '0') || $cur_posting['post_topics'] == '0')) ||
	(isset($cur_posting['closed']) && $cur_posting['closed'] == '1')) &&
	!$is_admmod)
	message($lang_common['No permission']);

#
#---------[ 51. REPLACE BY ]---------------------------------------------------
#

// Do we have permission to post?
if ((($tid && (($cur_posting['post_replies'] == '' && $pun_user['g_post_replies'] == '0') || $cur_posting['post_replies'] == '0')) ||
	($fid && ((!isset($_GET['type']) && $ptype == '0')) && (($cur_posting['post_topics'] == '' && $pun_user['g_post_topics'] == '0') || $cur_posting['post_topics'] == '0')) ||
	($fid && (isset($_GET['type']) || $ptype != '0') && (($cur_posting['post_polls'] == '' && $pun_user['g_post_polls'] == '0') || $cur_posting['post_polls'] == '0')) ||
	(isset($cur_posting['closed']) && $cur_posting['closed'] == '1')) &&
	!$is_admmod)
	message($lang_common['No permission']);

#
#---------[ 52. FIND (line: 64) ]---------------------------------------------------
#

// Load the post.php language file
require PUN_ROOT.'lang/'.$pun_user['language'].'/post.php';

#
#---------[ 53. ADD AFTER ]---------------------------------------------------
#

require PUN_ROOT.'lang/'.$pun_user['language'].'/polls.php';

#
#---------[ 54. FIND (line: 83) ]---------------------------------------------------
#

	// If it's a new topic
	if ($fid)
	{
		$subject = pun_trim($_POST['req_subject']);

		if ($subject == '')
			$errors[] = $lang_post['No subject'];
		else if (pun_strlen($subject) > 70)
			$errors[] = $lang_post['Too long subject'];
		else if ($pun_config['p_subject_all_caps'] == '0' && strtoupper($subject) == $subject && $pun_user['g_id'] > PUN_MOD)
			$subject = ucwords(strtolower($subject));

#
#---------[ 55. ADD AFTER ]---------------------------------------------------
#

		// Mod poll begin
		if(isset($ptype) && $ptype != '0')
		{
		// Get the question
	        $question = pun_trim($_POST['req_question']);
	        if ($question == '')
	            $errors[] = $lang_polls['No question'];
	        else if (pun_strlen($question) > 70)
	            $errors[] = $lang_polls['Too long question'];
	        else if ($pun_config['p_subject_all_caps'] == '0' && strtoupper($question) == $question && ($pun_user['g_id'] > PUN_MOD && !$pun_user['g_global_moderation']))
	            $question = ucwords(strtolower($question)); 
	        // If its a multislect yes/no poll then we need to make sure they have the right values
	        if ($ptype == 3) 
			{
	            $yesval = pun_trim($_POST['poll_yes']);

	            if ($yesval == '')
	                $errors[] = $lang_polls['No yes'];
	            else if (pun_strlen($yesval) > 35)
	                $errors[] = $lang_polls['Too long yes'];
	            else if ($pun_config['p_subject_all_caps'] == '0' && strtoupper($yesval) == $yesval && ($pun_user['g_id'] > PUN_MOD && !$pun_user['g_global_moderation']))
	                $yesval = ucwords(strtolower($yesval));

	            $noval = pun_trim($_POST['poll_no']);

	            if ($noval == '')
	                $errors[] = $lang_polls['No no'];
	            else if (pun_strlen($noval) > 35)
	                $errors[] = $lang_polls['Too long no'];
	            else if ($pun_config['p_subject_all_caps'] == '0' && strtoupper($noval) == $noval && ($pun_user['g_id'] > PUN_MOD && !$pun_user['g_global_moderation']))
	                $noval = ucwords(strtolower($noval));
	        } 
	        // This isn't exactly a good way todo it, but it works. I may rethink this code later
	        $option = array();
	        $lastoption = "null";
	        while (list($key, $value) = each($_POST['poll_option'])) 
			{
				$value = pun_trim($value);
	            if ($value != "") 
				{
	                if ($lastoption == '')
	                    $errors[] = $lang_polls['Empty option'];

                    $option[$key] = pun_trim($value);
                    if (pun_strlen($option[$key]) > 55)
                        $errors[] = $lang_polls['Too long option'];
					else if ($key > $pun_config['poll_max_fields'])
						message($lang_common['Bad request']);
                    else if ($pun_config['p_subject_all_caps'] == '0' && strtoupper($option[$key]) == $option[$key] && ($pun_user['g_id'] > PUN_MOD && !$pun_user['g_global_moderation']))
                        $option[$key] = ucwords(strtolower($option[$key]));
	            } 
	            $lastoption = pun_trim($value);
	        } 

			// People are naughty
			if (empty($option))
				$errors[] = $lang_polls['No options'];

			if (!array_key_exists(2,$option))
				$errors[] = $lang_polls['Low options'];
		}
		// Mod poll end

#
#---------[ 56. FIND (line: 287) ]---------------------------------------------------
#

			// Create the topic
			$db->query('INSERT INTO '.$db->prefix.'topics (poster, subject, posted, last_post, last_poster, forum_id) VALUES(\''.$db->escape($username).'\', \''.$db->escape($subject).'\', '.$now.', '.$now.', \''.$db->escape($username).'\', '.$fid.')') or error('Unable to create topic', __FILE__, __LINE__, $db->error());
			$new_tid = $db->insert_id();

#
#---------[ 57. ADD BEFORE ]---------------------------------------------------
#

			if(isset($ptype)) // Si c'est un sondage
			{
				if ($ptype == 3) 
	                $db->query('INSERT INTO ' . $db->prefix . 'topics (poster, subject, posted, last_post, last_poster, forum_id, question, yes, no) VALUES(\'' . $db->escape($username) . '\', \'' . $db->escape($subject) . '\', ' . $now . ', ' . $now . ', \'' . $db->escape($username) . '\', ' . $fid . ', \'' . $db->escape($question) . '\', \'' . $db->escape($yesval) . '\', \'' . $db->escape($noval) . '\')') or error('Unable to create topic', __FILE__, __LINE__, $db->error());
	            else
	                $db->query('INSERT INTO ' . $db->prefix . 'topics (poster, subject, posted, last_post, last_poster, forum_id, question) VALUES(\'' . $db->escape($username) . '\', \'' . $db->escape($subject) . '\', ' . $now . ', ' . $now . ', \'' . $db->escape($username) . '\', ' . $fid . ', \'' . $db->escape($question) . '\')') or error('Unable to create topic', __FILE__, __LINE__, $db->error());

	            $new_tid = $db->insert_id();
	            // query modified by 2.1
				if ($ptype != 0) 
					$db->query('INSERT INTO ' . $db->prefix . 'polls (pollid, options, ptype, created) VALUES(' . $new_tid . ', \'' . $db->escape(serialize($option)) . '\', ' . $ptype . ', '.$now.')') or error('Unable to create poll', __FILE__, __LINE__, $db->error());
			}
			else
			{

#
#---------[ 58. ADD AFTER 56 ]---------------------------------------------------
#

			}

#
#---------[ 59. FIND (line: 393) ]---------------------------------------------------
#

$page_title = pun_htmlspecialchars($pun_config['o_board_title']).' / '.$action;
$required_fields = array('req_email' => $lang_common['E-mail'], 'req_subject' => $lang_common['Subject'], 'req_message' => $lang_common['Message']);
$focus_element = array('post');

#
#---------[ 60. ADD BEFORE ]---------------------------------------------------
#

// Mod poll begin
if((isset($_GET['type']) && $ptype == '0') || ($ptype == 1 || $ptype == 2 || $ptype == 3)) // Si c'est un sondage
{	
	$page_title = pun_htmlspecialchars($pun_config['o_board_title']) . ' / ' . $action;
	$cur_index = 1; 
	if ($fid)
	{
		if ($ptype == 0) 
		{
		    $form = '<form id="post" method="post" action="post.php?&amp;fid=' . $fid . '">';
			
			$required_fields = array('req_email' => $lang_common['E-mail'], 'req_question' => $lang_polls['Question'], 'req_subject' => $lang_common['Subject'], 'req_message' => $lang_common['Message']);
		    $focus_element = array('post');

		    if (!$pun_user['is_guest'])
		        $focus_element[] = 'req_question';
		    else {
		        $required_fields['req_username'] = $lang_post['Guest name'];
		        $focus_element[] = 'req_question';
		    } 
		    require PUN_ROOT . 'header.php';
			?>
			<div class="linkst">
					<div class="inbox">
						<ul>
							<li><a href="index.php"><?php echo $lang_common['Index'] ?></a></li><li>&nbsp;&raquo;&nbsp;<?php echo $forum_name ?></li>
						</ul>
					</div>
			</div>

			<div class="blockform">
				<h2><span><?php echo $action ?></span></h2>
				<div class="box">
					<?php echo $form . "\n" ?>
						<div class="inform">
							<fieldset>
								<legend><?php echo $lang_polls['Poll select'] ?></legend>
								<div class="infldset txtarea">
									<select tabindex="<?php echo $cur_index++ ?>" name="ptype">
										<option value="1"><?php echo $lang_polls['Regular'] ?></option>
										<option value="2"><?php echo $lang_polls['Multiselect'] ?></option>
										<option value="3"><?php echo $lang_polls['Yesno'] ?></option>
									</select>
								</div>
							</fieldset>
						</div>
						<p><input type="submit" name="submit" value="<?php echo $lang_common['Submit'] ?>" tabindex="<?php echo $cur_index++ ?>" accesskey="s" />&nbsp;<a href="javascript:history.go(-1)"><?php echo $lang_common['Go back'] ?></a></p>
					</form>
				</div>
			</div>
			<?php
		}
		elseif ($ptype == 1 || $ptype == 2 || $ptype == 3) 
		{
		    $required_fields = array('req_email' => $lang_common['E-mail'], 'req_question' => $lang_polls['Question'], 'req_subject' => $lang_common['Subject'], 'req_message' => $lang_common['Message']);
		    $focus_element = array('post');

		    if (!$pun_user['is_guest'])
		        $focus_element[] = 'req_question';
		    else {
		        $required_fields['req_username'] = $lang_post['Guest name'];
		        $focus_element[] = 'req_question';
		    } 
		    require PUN_ROOT . 'header.php';
			?>
			<div class="linkst">
					<div class="inbox">
						<ul>
							<li><a href="index.php"><?php echo $lang_common['Index'] ?></a></li><li>&nbsp;&raquo;&nbsp;<?php echo $forum_name ?></li>
						</ul>
					</div>
			</div>
			<?php 
			
		    // If there are errors, we display them
		    if (!empty($errors)) 
			{
			?>
			<div id="posterror" class="block">
					<h2><span><?php echo $lang_post['Post errors'] ?></span></h2>
					<div class="box">
						<div class="inbox">
							<p><?php echo $lang_post['Post errors info'] ?></p>
							<ul>
							<?php
					        while (list(, $cur_error) = each($errors))
					        echo "\t\t\t\t" . '<li><strong>' . $cur_error . '</strong></li>' . "\n";
							?>
							</ul>
						</div>
					</div>
			</div>
			<?php
			} 
			else if (isset($_POST['preview'])) 
			{
		        require_once PUN_ROOT . 'include/parser.php';
		        $preview_message = parse_message(trim($_POST['req_message']), $hide_smilies);
				?>
				<div id="postpreview" class="blockpost">
				<h2><span><?php echo $lang_polls['Poll preview'] ?></span></h2>
				<div class="box">
					<div class="inbox">
						<div class="postright">
							<div class="postmsg">
							<?php
							if ($ptype == 1) 
							{
								?>
								<form action="" method="POST">
									<fieldset>
										<legend><?php echo pun_htmlspecialchars($question); ?></legend>
										<?php
										while (list($key, $value) = each($option)) 
										{
											if (!empty($value)) 
											{
												echo '<br /><input type="radio" /><span>' . pun_htmlspecialchars($value) . '</span><br />';
											} 
										} 
										?><br />
									</fieldset>
								</form>
								<?php
							} 		
							elseif ($ptype == 2) 
							{
								?>
								<form action="" method="POST">
									<fieldset>
										<legend><?php echo pun_htmlspecialchars($question); ?></legend>
										<?php
										while (list($key, $value) = each($option)) 
										{
											if (!empty($value)) 
											{
												echo '<br /><input type="checkbox" /><span>' . pun_htmlspecialchars($value) . '</span><br />';
											} 
										} 
										?><br />
									</fieldset>
								</form>
								<?php	
							} 
							elseif ($ptype == 3) 
							{
								?>
								<form action="" method="POST">
									<fieldset>
										<legend><?php echo pun_htmlspecialchars($question); ?></legend>
										<?php
										while (list($key, $value) = each($option)) 
										{
											if (!empty($value)) 
											{
												echo '<br />' . pun_htmlspecialchars($value) . '<br />
												<input type="radio" /><span>' . pun_htmlspecialchars($yesval) . '</span><br />
												<input type="radio" /><span>' . pun_htmlspecialchars($noval) . '</span><br />';
											} 
										} 
										?><br />
									</fieldset>
								</form>
								<?php
							} 
							?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="postpreview" class="blockpost">
				<h2><span><?php echo $lang_post['Post preview'] ?></span></h2>
				<div class="box">
					<div class="inbox">
						<div class="postright">
							<div class="postmsg">
								<?php echo $preview_message . "\n" ?>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php

			} 
			?>
			<div class="blockform">
				<h2><span><?php echo $action ?></span></h2>
				<div class="box">
					<?php echo $form . "\n" ?>
						<div class="inform">
							<fieldset>
							<?php			
						    // Regular Poll Type
						    if ($ptype == 1) 
							{
							?>
								<legend><?php echo $lang_polls['New poll legend'] ?></legend>
								<div class="infldset">
									<input type="hidden" name="ptype" value="1" />
										<label><strong><?php echo $lang_polls['Question'] ?></strong><br /><input type="text" name="req_question" value="<?php if (isset($_POST['req_question'])) echo pun_htmlspecialchars($question); ?>" size="80" maxlength="70" tabindex="<?php echo $cur_index++ ?>" /><br /><br /></label>
										<?php
										for ($x = 1; $x <= $pun_config['poll_max_fields'] ;$x++) 
										{
										?>
											<label><strong><?php echo $lang_polls['Option'] ?></strong><br /> <input type="text" name="poll_option[<?php echo $x; ?>]" value="<?php if (isset($_POST['poll_option'][$x])) echo pun_htmlspecialchars($option[$x]); ?>" size="60" maxlength="55" tabindex="<?php echo $cur_index++ ?>" /><br /></label>
										<?php
										} 
										?></div></fieldset></div><?php   
						    } 
							// Multiselect poll type
							elseif ($ptype == 2) 
							{
							?>
								<legend><?php echo $lang_polls['New poll legend multiselect'] ?></legend>
								<div class="infldset">
								<input type="hidden" name="ptype" value="2" />
									<label><strong><?php echo $lang_polls['Question'] ?></strong><br /><input type="text" name="req_question" value="<?php if (isset($_POST['req_question'])) echo pun_htmlspecialchars($question); ?>" size="80" maxlength="70" tabindex="<?php echo $cur_index++ ?>" /><br /><br /></label>
									<?php
									for ($x = 1; $x <= $pun_config['poll_max_fields']; $x++) 
									{
										?>
										<label><strong><?php echo $lang_polls['Option'] ?></strong><br /> <input type="text" name="poll_option[<?php echo $x; ?>]" value="<?php if (isset($_POST['poll_option'][$x])) echo pun_htmlspecialchars($option[$x]); ?>" size="60" maxlength="55" tabindex="<?php echo $cur_index++ ?>" /><br /></label>
										<?php
									} 
									?></div></fieldset></div><?php
							} 
							elseif ($ptype == 3) 
							{
							?>
								<legend><?php echo $lang_polls['New poll legend yesno'] ?></legend>
								<div class="infldset">
								<input type="hidden" name="ptype" value="3" />
									<label><strong><?php echo $lang_polls['Question'] ?></strong><br /><input type="text" name="req_question" value="<?php if (isset($_POST['req_question'])) echo pun_htmlspecialchars($question); ?>" size="80" maxlength="70" tabindex="<?php echo $cur_index++ ?>" /><br /><br /></label>
									<label><strong><?php echo $lang_polls['Yes'] ?></strong><br /> <input type="text" name="poll_yes" value="<?php if (isset($_POST['poll_yes'])) echo pun_htmlspecialchars($yesval); ?>" size="40" maxlength="35" tabindex="<?php echo $cur_index++ ?>" /></label>
									<label><strong><?php echo $lang_polls['No'] ?></strong><br /> <input type="text" name="poll_no" value="<?php if (isset($_POST['poll_no'])) echo pun_htmlspecialchars($noval); ?>" size="40" maxlength="35" tabindex="<?php echo $cur_index++ ?>" /><br /><br /></label>
									<?php
									for ($x = 1; $x <= $pun_config['poll_max_fields']; $x++) 
									{
										?>
										<label><strong><?php echo $lang_polls['Option'] ?></strong><br /> <input type="text" name="poll_option[<?php echo $x; ?>]" value="<?php if (isset($_POST['poll_option'][$x])) echo pun_htmlspecialchars($option[$x]); ?>" size="60" maxlength="55" tabindex="<?php echo $cur_index++ ?>" /><br /></label>
										<?php
									} 
									?></div></fieldset></div><?php		
							} 
							else
						        message($lang_common['Bad request']);


		} else
			message($lang_common['Bad request']);
	} 
} 
else
{
// Mod poll end

#
#---------[ 61. FIND (line: 464) ]---------------------------------------------------
#

$cur_index = 1;

?>
<div class="blockform">
	<h2><span><?php echo $action ?></span></h2>
	<div class="box">
		<?php echo $form."\n" ?>

#
#---------[ 62. REPLACE BY ]---------------------------------------------------
#

}

if (!isset($_GET['type'])) 
{
	$cur_index = 100;

	if($ptype == '0')
	{
	?>
	<div class="blockform">
		<h2><span><?php echo $action ?></span></h2>
		<div class="box">
			<?php echo $form."\n" ?>
	<?php
	}
	?>

#
#---------[ 63. FIND (line: 535) ]---------------------------------------------------
#

			</div>
			<p><input type="submit" name="submit" value="<?php echo $lang_common['Submit'] ?>" tabindex="<?php echo $cur_index++ ?>" accesskey="s" /><input type="submit" name="preview" value="<?php echo $lang_post['Preview'] ?>" tabindex="<?php echo $cur_index++ ?>" accesskey="p" /><a href="javascript:history.go(-1)"><?php echo $lang_common['Go back'] ?></a></p>
		</form>
	</div>
</div>

<?php

#
#---------[ 64. ADD AFTER ]---------------------------------------------------
#

}

#
#---------[ 65. OPEN ]---------------------------------------------------
#

search.php

#
#---------[ 66. FIND (line: 34) ]---------------------------------------------------
#

// Load the search.php language file
require PUN_ROOT.'lang/'.$pun_user['language'].'/search.php';

#
#---------[ 67. ADD AFTER ]---------------------------------------------------
#

// Load poll language file
require PUN_ROOT.'lang/'.$pun_user['language'].'/polls.php';

#
#---------[ 68. FIND (line: 456) ]---------------------------------------------------
#

		if ($show_as == 'posts')
		{
			$substr_sql = ($db_type != 'sqlite') ? 'SUBSTRING' : 'SUBSTR';
			$sql = 'SELECT p.id AS pid, p.poster AS pposter, p.posted AS pposted, p.poster_id, '.$substr_sql.'(p.message, 1, 1000) AS message, t.id AS tid, t.poster, t.subject, t.last_post, t.last_post_id, t.last_poster, t.num_replies, t.forum_id FROM '.$db->prefix.'posts AS p INNER JOIN '.$db->prefix.'topics AS t ON t.id=p.topic_id WHERE p.id IN('.$search_results.') ORDER BY '.$sort_by_sql;

#
#---------[ 69. FIND ]---------------------------------------------------
#

, t.poster, t.subject

#
#---------[ 70. AFTER INSERT ]---------------------------------------------------
#

, t.question

#
#---------[ 71. FIND (line: 461) ]---------------------------------------------------
#

		else
			$sql = 'SELECT t.id AS tid, t.poster, t.subject, t.last_post, t.last_post_id, t.last_poster, t.num_replies, t.closed, t.forum_id FROM '.$db->prefix.'topics AS t WHERE t.id IN('.$search_results.') ORDER BY '.$sort_by_sql;

#
#---------[ 72. FIND ]---------------------------------------------------
#

, t.poster, t.subject

#
#---------[ 73. AFTER INSERT ]---------------------------------------------------
#

, t.question

#
#---------[ 74. FIND (line: 548) ]---------------------------------------------------
#

				$subject = '<a href="viewtopic.php?id='.$search_set[$i]['tid'].'">'.pun_htmlspecialchars($search_set[$i]['subject']).'</a>';

#
#---------[ 75. REPLACE BY ]---------------------------------------------------
#

				if ($search_set[$i]['question'] == "")
					$subject = '<a href="viewtopic.php?id='.$search_set[$i]['tid'].'">'.pun_htmlspecialchars($search_set[$i]['subject']).'</a>';
				else
					$subject = $lang_polls['Poll'] . ': <a href="viewtopic.php?id='.$search_set[$i]['tid'].'">'.pun_htmlspecialchars($search_set[$i]['subject']).'</a>';

#
#---------[ 76. FIND (lign: 596) ]---------------------------------------------------
#

			else
			{
				$icon = '<div class="icon"><div class="nosize">'.$lang_common['Normal icon'].'</div></div>'."\n";

				$icon_text = $lang_common['Normal icon'];
				$item_status = '';
				$icon_type = 'icon';


				$subject = '<a href="viewtopic.php?id='.$search_set[$i]['tid'].'">'.pun_htmlspecialchars($search_set[$i]['subject']).'</a> <span class="byuser">'.$lang_common['by'].'&nbsp;'.pun_htmlspecialchars($search_set[$i]['poster']).'</span>';

				if ($search_set[$i]['closed'] != '0')
				{
					$icon_text = $lang_common['Closed icon'];
					$item_status = 'iclosed';
				}

				if (!$pun_user['is_guest'] && $search_set[$i]['last_post'] > $pun_user['last_visit'])
				{
					$icon_text .= ' '.$lang_common['New icon'];
					$item_status .= ' inew';
					$icon_type = 'icon inew';
					$subject = '<strong>'.$subject.'</strong>';
					$subject_new_posts = '<span class="newtext">[&nbsp;<a href="viewtopic.php?id='.$search_set[$i]['tid'].'&amp;action=new" title="'.$lang_common['New posts info'].'">'.$lang_common['New posts'].'</a>&nbsp;]</span>';
				}
				else
					$subject_new_posts = null;

				$num_pages_topic = ceil(($search_set[$i]['num_replies'] + 1) / $pun_user['disp_posts']);

				if ($num_pages_topic > 1)
					$subject_multipage = '[ '.paginate($num_pages_topic, -1, 'viewtopic.php?id='.$search_set[$i]['tid']).' ]';
				else
					$subject_multipage = null;

				// Should we show the "New posts" and/or the multipage links?
				if (!empty($subject_new_posts) || !empty($subject_multipage))
				{
					$subject .= '&nbsp; '.(!empty($subject_new_posts) ? $subject_new_posts : '');
					$subject .= !empty($subject_multipage) ? ' '.$subject_multipage : '';
				}

?>
				<tr<?php if ($item_status != '') echo ' class="'.trim($item_status).'"'; ?>>
					<td class="tcl">
						<div class="intd">
							<div class="<?php echo $icon_type ?>"><div class="nosize"><?php echo trim($icon_text) ?></div></div>
							<div class="tclcon">
								<?php echo $subject."\n" ?>
							</div>
						</div>
					</td>
					<td class="tc2"><?php echo $forum ?></td>
					<td class="tc3"><?php echo $search_set[$i]['num_replies'] ?></td>
					<td class="tcr"><?php echo '<a href="viewtopic.php?pid='.$search_set[$i]['last_post_id'].'#p'.$search_set[$i]['last_post_id'].'">'.format_time($search_set[$i]['last_post']).'</a> '.$lang_common['by'].'&nbsp;'.pun_htmlspecialchars($search_set[$i]['last_poster']) ?></td>
				</tr>
<?php

#
#---------[ 77. REPLACE BY ]---------------------------------------------------
#

			else
			{
				$icon = '<div class="icon"><div class="nosize">'.$lang_common['Normal icon'].'</div></div>'."\n";

				$icon_text = $lang_common['Normal icon'];
				$item_status = '';
				$icon_type = 'icon';

				if ($search_set[$i]['question'] == "")
					$subject = '<a href="viewtopic.php?id='.$search_set[$i]['tid'].'">'.pun_htmlspecialchars($search_set[$i]['subject']).'</a> <span class="byuser">'.$lang_common['by'].'&nbsp;'.pun_htmlspecialchars($search_set[$i]['poster']).'</span>';
				else
					$subject = $lang_polls['Poll'] . ': <a href="viewtopic.php?id='.$search_set[$i]['tid'].'">'.pun_htmlspecialchars($search_set[$i]['subject']).'</a> <span class="byuser">'.$lang_common['by'].'&nbsp;'.pun_htmlspecialchars($search_set[$i]['poster']).'</span> [ '.pun_htmlspecialchars($search_set[$i]['question']).' ]';
				if ($search_set[$i]['closed'] != '0')
				{
					$icon_text = $lang_common['Closed icon'];
					$item_status = 'iclosed';
				}

				if (!$pun_user['is_guest'] && $search_set[$i]['last_post'] > $pun_user['last_visit'])
				{
					$icon_text .= ' '.$lang_common['New icon'];
					$item_status .= ' inew';
					$icon_type = 'icon inew';
					$subject = '<strong>'.$subject.'</strong>';
					$subject_new_posts = '<span class="newtext">[&nbsp;<a href="viewtopic.php?id='.$search_set[$i]['tid'].'&amp;action=new" title="'.$lang_common['New posts info'].'">'.$lang_common['New posts'].'</a>&nbsp;]</span>';
				}
				else
					$subject_new_posts = null;

				$num_pages_topic = ceil(($search_set[$i]['num_replies'] + 1) / $pun_user['disp_posts']);

				if ($num_pages_topic > 1)
					$subject_multipage = '[ '.paginate($num_pages_topic, -1, 'viewtopic.php?id='.$search_set[$i]['tid']).' ]';
				else
					$subject_multipage = null;

				// Should we show the "New posts" and/or the multipage links?
				if (!empty($subject_new_posts) || !empty($subject_multipage))
				{
					$subject .= '&nbsp; '.(!empty($subject_new_posts) ? $subject_new_posts : '');
					$subject .= !empty($subject_multipage) ? ' '.$subject_multipage : '';
				}

				?>
				<tr<?php if ($item_status != '') echo ' class="'.trim($item_status).'"'; ?>>
					<td class="tcl">
						<div class="intd">
							<div class="<?php echo $icon_type ?>"><div class="nosize"><?php echo trim($icon_text) ?></div></div>
							<div class="tclcon">
								<?php echo $subject."\n" ?>
							</div>
						</div>
					</td>
					<td class="tc2"><?php echo $forum ?></td>
					<td class="tc3"><?php echo $search_set[$i]['num_replies'] ?></td>
					<?php
					if ($search_set[$i]['question'] == "")
					{
						?><td class="tcr"><?php echo '<a href="viewtopic.php?pid='.$search_set[$i]['last_post_id'].'#p'.$search_set[$i]['last_post_id'].'">'.format_time($search_set[$i]['last_post']).'</a> '.$lang_common['by'].'&nbsp;'.pun_htmlspecialchars($search_set[$i]['last_poster']) ?></td><?php
					}
					else
					{
						?><td class="tcr"><?php echo '<a href="viewtopic.php?pid='.$search_set[$i]['last_post_id'].'#p'.$search_set[$i]['last_post_id'].'">'.format_time($search_set[$i]['last_post']).'</a> '.$lang_common['by'].'&nbsp;'.pun_htmlspecialchars($search_set[$i]['last_poster']) ?></td><?php
					} ?>
				</tr>
<?php

#
#---------[ 78. OPEN ]---------------------------------------------------
#

moderate.php

#
#---------[ 79. FIND (line: 426) ]---------------------------------------------------
#

		// Delete the topics and any redirect topics
		$db->query('DELETE FROM '.$db->prefix.'topics WHERE id IN('.$topics.') OR moved_to IN('.$topics.')') or error('Unable to delete topic', __FILE__, __LINE__, $db->error());

#
#---------[ 80. ADD AFTER ]---------------------------------------------------
#

		// Delete polls
		$db->query('DELETE FROM '.$db->prefix.'polls WHERE pollid IN('.$topics.')') or error('Unable to delete poll', __FILE__, __LINE__, $db->error());

#
#---------[ 81. OPEN ]---------------------------------------------------
#

edit.php

#
#---------[ 82. FIND (line: 38) ]---------------------------------------------------
#

// Fetch some info about the post, the topic and the forum
$result = $db->query('SELECT f.id AS fid, f.forum_name, f.moderators, f.redirect_url, fp.post_replies, fp.post_topics, t.id AS tid, t.subject, t.posted, t.closed, p.poster, p.poster_id, p.message, p.hide_smilies FROM '.$db->prefix.'posts AS p INNER JOIN '.$db->prefix.'topics AS t ON t.id=p.topic_id INNER JOIN '.$db->prefix.'forums AS f ON f.id=t.forum_id LEFT JOIN '.$db->prefix.'forum_perms AS fp ON (fp.forum_id=f.id AND fp.group_id='.$pun_user['g_id'].') WHERE (fp.read_forum IS NULL OR fp.read_forum=1) AND p.id='.$id) or error('Unable to fetch post info', __FILE__, __LINE__, $db->error());

#
#---------[ 83. FIND ]---------------------------------------------------
#

, fp.post_replies, fp.post_topics

#
#---------[ 84. AFTER INSERT ]---------------------------------------------------
#

, fp.post_polls, t.question

#
#---------[ 85. FIND (line: 53) ]---------------------------------------------------
#

$can_edit_subject = ($id == $topic_post_id && (($pun_user['g_edit_subjects_interval'] == '0' || (time() - $cur_post['posted']) < $pun_user['g_edit_subjects_interval']) || $is_admmod)) ? true : false;

#
#---------[ 86. ADD AFTER ]---------------------------------------------------
#

$can_add_poll = (($id == $topic_post_id) && ($cur_post['question']=='') && (($is_admmod) && (($cur_post['post_polls'] == '' && $pun_user['g_post_polls'] == '1') || $cur_post['post_polls'] == '1'))) ? true : false;

#
#---------[ 87. FIND (line: 62) ]---------------------------------------------------
#

// Load the post.php/edit.php language file
require PUN_ROOT.'lang/'.$pun_user['language'].'/post.php';

#
#---------[ 88. ADD AFTER ]---------------------------------------------------
#

require PUN_ROOT.'lang/'.$pun_user['language'].'/polls.php';


#
#---------[ 89. FIND (line: 248) ]---------------------------------------------------
#

<?php echo implode('</label>'."\n\t\t\t\t\t\t\t", $checkboxes).'</label>'."\n" ?>

#
#---------[ 90. REPLACE BY ]---------------------------------------------------
#

							<?php echo implode('</label>'."\n\t\t\t\t\t\t\t", $checkboxes).'</label>'."\n";
							if($can_add_poll)
								echo '<a href="admin_loader.php?plugin=AMP_Sondage.php&amp;add='.$cur_post['tid'].'">'.$lang_polls['Add poll'].'</a>'."\n";
							 ?>

#
#---------[ 91. OPEN ]---------------------------------------------------
#

include/functions.php

#
#---------[ 92. FIND (lign: 385) ]---------------------------------------------------
#

	// Delete the topic and any redirect topics
	$db->query('DELETE FROM '.$db->prefix.'topics WHERE id='.$topic_id.' OR moved_to='.$topic_id) or error('Unable to delete topic', __FILE__, __LINE__, $db->error());

#
#---------[ 93. ADD AFTER ]---------------------------------------------------
#

	// Delete the poll
	$db->query('DELETE FROM '.$db->prefix.'polls WHERE pollid='.$topic_id) or error('Unable to delete poll', __FILE__, __LINE__, $db->error());

#
#---------[ 94. OPEN ]---------------------------------------------------
#

index.php

#
#---------[ 95. FIND (line: 158) ]---------------------------------------------------
#

$result = $db->query('SELECT SUM(num_topics), SUM(num_posts) FROM '.$db->prefix.'forums') or error('Unable to fetch topic/post count', __FILE__, __LINE__, $db->error());
list($stats['total_topics'], $stats['total_posts']) = $db->fetch_row($result);

#
#---------[ 96. ADD AFTER ]---------------------------------------------------
#

$result = $db->query('SELECT COUNT(id) FROM '.$db->prefix.'polls') or error('Impossible de reFIND le nombre total de sondage', __FILE__, __LINE__, $db->error());
$stats['total_polls'] = $db->result($result);

#
#---------[ 97. FIND (line: 169) ]---------------------------------------------------
#

				<dd><?php echo $lang_index['No of topics'].': <strong>'.$stats['total_topics'] ?></strong></dd>

#
#---------[ 98. ADD AFTER ]---------------------------------------------------
#

				<dd><?php echo $lang_index['No of polls'].': <strong>'.$stats['total_polls'] ?></strong></dd>

#
#---------[ 99. OPEN ]---------------------------------------------------
#

lang/LANG/index.php

#
#---------[ 100. FIND (line: 14) ]---------------------------------------------------
#

'No of topics'			=>	'Nombre total de discussions',
OU/OR
'No of topics'			=>	'Total number of topics',

#
#---------[ 101. ADD AFTER ]---------------------------------------------------
#

'No of polls'			=>	'Nombre total de sondages',
OU/OR
'No of polls'			=>	'Total number of polls',

#
#---------[ 102. OPEN ]---------------------------------------------------
#

admin_forums.php

#
#---------[ 103. FIND (line: 183) ]---------------------------------------------------
#

		// Now let's deal with the permissions
		if (isset($_POST['read_forum_old']))
		{
			$result = $db->query('SELECT g_id, g_read_board, g_post_replies, g_post_topics FROM '.$db->prefix.'groups WHERE g_id!='.PUN_ADMIN) or error('Unable to fetch user group list', __FILE__, __LINE__, $db->error());
			while ($cur_group = $db->fetch_assoc($result))
			{
				$read_forum_new = ($cur_group['g_read_board'] == '1') ? isset($_POST['read_forum_new'][$cur_group['g_id']]) ? '1' : '0' : intval($_POST['read_forum_old'][$cur_group['g_id']]);
				$post_replies_new = isset($_POST['post_replies_new'][$cur_group['g_id']]) ? '1' : '0';
				$post_topics_new = isset($_POST['post_topics_new'][$cur_group['g_id']]) ? '1' : '0';

				// Check if the new settings differ from the old
				if ($read_forum_new != $_POST['read_forum_old'][$cur_group['g_id']] || $post_replies_new != $_POST['post_replies_old'][$cur_group['g_id']] || $post_topics_new != $_POST['post_topics_old'][$cur_group['g_id']])
				{
					// If the new settings are identical to the default settings for this group, delete it's row in forum_perms
					if ($read_forum_new == '1' && $post_replies_new == $cur_group['g_post_replies'] && $post_topics_new == $cur_group['g_post_topics'])
						$db->query('DELETE FROM '.$db->prefix.'forum_perms WHERE group_id='.$cur_group['g_id'].' AND forum_id='.$forum_id) or error('Unable to delete group forum permissions', __FILE__, __LINE__, $db->error());
					else
					{
						// Run an UPDATE and see if it affected a row, if not, INSERT
						$db->query('UPDATE '.$db->prefix.'forum_perms SET read_forum='.$read_forum_new.', post_replies='.$post_replies_new.', post_topics='.$post_topics_new.' WHERE group_id='.$cur_group['g_id'].' AND forum_id='.$forum_id) or error('Unable to insert group forum permissions', __FILE__, __LINE__, $db->error());
						if (!$db->affected_rows())
							$db->query('INSERT INTO '.$db->prefix.'forum_perms (group_id, forum_id, read_forum, post_replies, post_topics) VALUES('.$cur_group['g_id'].', '.$forum_id.', '.$read_forum_new.', '.$post_replies_new.', '.$post_topics_new.')') or error('Unable to insert group forum permissions', __FILE__, __LINE__, $db->error());
					}
				}
			}
		}

#
#---------[ 104. REPLACE BY ]---------------------------------------------------
#

		// Now let's deal with the permissions
		if (isset($_POST['read_forum_old']))
		{
			$result = $db->query('SELECT g_id, g_read_board, g_post_replies, g_post_topics, g_post_polls FROM '.$db->prefix.'groups WHERE g_id!='.PUN_ADMIN) or error('Unable to fetch user group list', __FILE__, __LINE__, $db->error());
			while ($cur_group = $db->fetch_assoc($result))
			{
				$read_forum_new = ($cur_group['g_read_board'] == '1') ? isset($_POST['read_forum_new'][$cur_group['g_id']]) ? $_POST['read_forum_new'][$cur_group['g_id']] : '0' : $_POST['read_forum_old'][$cur_group['g_id']];
				$post_replies_new = isset($_POST['post_replies_new'][$cur_group['g_id']]) ? $_POST['post_replies_new'][$cur_group['g_id']] : '0';
				$post_topics_new = isset($_POST['post_topics_new'][$cur_group['g_id']]) ? $_POST['post_topics_new'][$cur_group['g_id']] : '0';
				$post_polls_new = isset($_POST['post_polls_new'][$cur_group['g_id']]) ? $_POST['post_polls_new'][$cur_group['g_id']] : '0';

				// Check if the new settings differ from the old
				if ($read_forum_new != $_POST['read_forum_old'][$cur_group['g_id']] || $post_replies_new != $_POST['post_replies_old'][$cur_group['g_id']] || $post_topics_new != $_POST['post_topics_old'][$cur_group['g_id']] || $post_polls_new != $_POST['post_polls_old'][$cur_group['g_id']])
				{
					// If the new settings are identical to the default settings for this group, delete it's row in forum_perms
					if ($read_forum_new == '1' && $post_replies_new == $cur_group['g_post_replies'] && $post_topics_new == $cur_group['g_post_topics'] && $post_polls_new == $cur_group['g_post_polls'])
						$db->query('DELETE FROM '.$db->prefix.'forum_perms WHERE group_id='.$cur_group['g_id'].' AND forum_id='.$forum_id) or error('Unable to delete group forum permissions', __FILE__, __LINE__, $db->error());
					else
					{
						// Run an UPDATE and see if it affected a row, if not, INSERT
						$db->query('UPDATE '.$db->prefix.'forum_perms SET read_forum='.$read_forum_new.', post_replies='.$post_replies_new.', post_topics='.$post_topics_new.', post_polls='.$post_polls_new.' WHERE group_id='.$cur_group['g_id'].' AND forum_id='.$forum_id) or error('Unable to insert group forum permissions', __FILE__, __LINE__, $db->error());
						if (!$db->affected_rows())
							$db->query('INSERT INTO '.$db->prefix.'forum_perms (group_id, forum_id, read_forum, post_replies, post_topics, post_polls) VALUES('.$cur_group['g_id'].', '.$forum_id.', '.$read_forum_new.', '.$post_replies_new.', '.$post_topics_new.', '.$post_polls_new.')') or error('Unable to insert group forum permissions', __FILE__, __LINE__, $db->error());
					}
				}
			}
		}

#
#---------[ 105. FIND (line: 303) ]---------------------------------------------------
#

								<tr>
									<th class="atcl">&nbsp;</th>
									<th>Read forum</th>
									<th>Post replies</th>
									<th>Post topics</th>
OU/OR
								<tr>
									<th class="atcl">&nbsp;</th>
									<th>Lire le forum</th>
									<th>Écrire des réponses</th>
									<th>Lancer des discussions</th>

#
#---------[ 106. ADD AFTER ]---------------------------------------------------
#

									<th>Lancer des sondages</th>
OU/OR
									<th>Post polls</th>

#
#---------[ 107. FIND (line: 311) ]---------------------------------------------------
#

<?php

	$result = $db->query('SELECT g.g_id, g.g_title, g.g_read_board, g.g_post_replies, g.g_post_topics, fp.read_forum, fp.post_replies, fp.post_topics FROM '.$db->prefix.'groups AS g LEFT JOIN '.$db->prefix.'forum_perms AS fp ON (g.g_id=fp.group_id AND fp.forum_id='.$forum_id.') WHERE g.g_id!='.PUN_ADMIN.' ORDER BY g.g_id') or error('Unable to fetch group forum permission list', __FILE__, __LINE__, $db->error());

	while ($cur_perm = $db->fetch_assoc($result))
	{
		$read_forum = ($cur_perm['read_forum'] != '0') ? true : false;
		$post_replies = (($cur_perm['g_post_replies'] == '0' && $cur_perm['post_replies'] == '1') || ($cur_perm['g_post_replies'] == '1' && $cur_perm['post_replies'] != '0')) ? true : false;
		$post_topics = (($cur_perm['g_post_topics'] == '0' && $cur_perm['post_topics'] == '1') || ($cur_perm['g_post_topics'] == '1' && $cur_perm['post_topics'] != '0')) ? true : false;

		// Determine if the current sittings differ from the default or not
		$read_forum_def = ($cur_perm['read_forum'] == '0') ? false : true;
		$post_replies_def = (($post_replies && $cur_perm['g_post_replies'] == '0') || (!$post_replies && ($cur_perm['g_post_replies'] == '' || $cur_perm['g_post_replies'] == '1'))) ? false : true;
		$post_topics_def = (($post_topics && $cur_perm['g_post_topics'] == '0') || (!$post_topics && ($cur_perm['g_post_topics'] == '' || $cur_perm['g_post_topics'] == '1'))) ? false : true;

?>

#
#---------[ 108. REPLACE BY ]---------------------------------------------------
#

<?php

	$result = $db->query('SELECT g.g_id, g.g_title, g.g_read_board, g.g_post_replies, g.g_post_topics, g.g_post_polls, fp.read_forum, fp.post_replies, fp.post_topics, fp.post_polls FROM '.$db->prefix.'groups AS g LEFT JOIN '.$db->prefix.'forum_perms AS fp ON (g.g_id=fp.group_id AND fp.forum_id='.$forum_id.') WHERE g.g_id!='.PUN_ADMIN.' ORDER BY g.g_id') or error('Unable to fetch group forum permission list', __FILE__, __LINE__, $db->error());

	while ($cur_perm = $db->fetch_assoc($result))
	{
		$read_forum = ($cur_perm['read_forum'] != '0') ? true : false;
		$post_replies = (($cur_perm['g_post_replies'] == '0' && $cur_perm['post_replies'] == '1') || ($cur_perm['g_post_replies'] == '1' && $cur_perm['post_replies'] != '0')) ? true : false;
		$post_topics = (($cur_perm['g_post_topics'] == '0' && $cur_perm['post_topics'] == '1') || ($cur_perm['g_post_topics'] == '1' && $cur_perm['post_topics'] != '0')) ? true : false;
		$post_polls = (($cur_perm['g_post_polls'] == '0' && $cur_perm['post_polls'] == '1') || ($cur_perm['g_post_polls'] == '1' && $cur_perm['post_polls'] != '0')) ? true : false;

		// Determine if the current sittings differ from the default or not
		$read_forum_def = ($cur_perm['read_forum'] == '0') ? false : true;
		$post_replies_def = (($post_replies && $cur_perm['g_post_replies'] == '0') || (!$post_replies && ($cur_perm['g_post_replies'] == '' || $cur_perm['g_post_replies'] == '1'))) ? false : true;
		$post_topics_def = (($post_topics && $cur_perm['g_post_topics'] == '0') || (!$post_topics && ($cur_perm['g_post_topics'] == '' || $cur_perm['g_post_topics'] == '1'))) ? false : true;
		$post_polls_def = (($post_polls && $cur_perm['g_post_polls'] == '0') || (!$post_polls && ($cur_perm['g_post_polls'] == '' || $cur_perm['g_post_polls'] == '1'))) ? false : true;

?>

#
#---------[ 109. FIND (line: 330) ]---------------------------------------------------
#

									<td<?php if (!$post_topics_def && $cur_forum['redirect_url'] == '') echo ' class="nodefault"'; ?>>
										<input type="hidden" name="post_topics_old[<?php echo $cur_perm['g_id'] ?>]" value="<?php echo ($post_topics) ? '1' : '0'; ?>" />
										<input type="checkbox" name="post_topics_new[<?php echo $cur_perm['g_id'] ?>]" value="1"<?php echo ($post_topics) ? ' checked="checked"' : ''; ?><?php echo ($cur_forum['redirect_url'] != '') ? ' disabled="disabled"' : ''; ?> />
									</td>

#
#---------[ 110. ADD AFTER ]---------------------------------------------------
#

									<td<?php if (!$post_topics_def && $cur_forum['redirect_url'] == '') echo ' class="nodefault"'; ?>>
										<input type="hidden" name="post_polls_old[<?php echo $cur_perm['g_id'] ?>]" value="<?php echo ($post_polls) ? '1' : '0'; ?>" />
										<input type="checkbox" name="post_polls_new[<?php echo $cur_perm['g_id'] ?>]" value="1"<?php echo ($post_polls) ? ' checked="checked"' : ''; ?><?php echo ($cur_forum['redirect_url'] != '') ? ' disabled="disabled"' : ''; ?> />
									</td>

#
#---------[ 111. OPEN ]---------------------------------------------------
#

admin_groups.php

#
#---------[ 112. FIND (line: 115) ]---------------------------------------------------
#

								<tr>
									<th scope="row">Post topics</th>
									<td>
										<input type="radio" name="post_topics" value="1"<?php if ($group['g_post_topics'] == '1') echo ' checked="checked"' ?> tabindex="7" />&nbsp;<strong>Yes</strong>&nbsp;&nbsp;&nbsp;<input type="radio" name="post_topics" value="0"<?php if ($group['g_post_topics'] == '0') echo ' checked="checked"' ?> tabindex="8" />&nbsp;<strong>No</strong>
										<span>Allow users in this group to post new topics.</span>
									</td>
								</tr>
OU/OR
								<tr>
									<th scope="row">Lancer des discussions</th>
									<td>
										<input type="radio" name="post_topics" value="1"<?php if ($group['g_post_topics'] == '1') echo ' checked="checked"' ?> tabindex="7" />&nbsp;<strong>Oui</strong>&nbsp;&nbsp;&nbsp;<input type="radio" name="post_topics" value="0"<?php if ($group['g_post_topics'] == '0') echo ' checked="checked"' ?> tabindex="8" />&nbsp;<strong>Non</strong>
										<span>Autoriser les utilisateurs de ce groupe à lancer des nouveaux sujets.</span>
									</td>
								</tr>

#
#---------[ 113. ADD AFTER ]---------------------------------------------------
#

								<tr>
									<th scope="row">Post polls</th>
									<td>
										<input type="radio" name="post_polls" value="1"<?php if ($group['g_post_polls'] == '1') echo ' checked="checked"' ?> tabindex="7" />&nbsp;<strong>Yes</strong>&nbsp;&nbsp;&nbsp;<input type="radio" name="post_polls" value="0"<?php if ($group['g_post_polls'] == '0') echo ' checked="checked"' ?> tabindex="8" />&nbsp;<strong>No</strong>
										<span>Allow users in this group to post new polls.</span>
									</td>
								</tr>
OU/OR
								<tr>
									<th scope="row">Lancer des sondages</th>
									<td>
										<input type="radio" name="post_polls" value="1"<?php if ($group['g_post_polls'] == '1') echo ' checked="checked"' ?> tabindex="7" />&nbsp;<strong>Oui</strong>&nbsp;&nbsp;&nbsp;<input type="radio" name="post_polls" value="0"<?php if ($group['g_post_polls'] == '0') echo ' checked="checked"' ?> tabindex="8" />&nbsp;<strong>Non</strong>
										<span>Autoriser les utilisateurs de ce groupe à lancer des nouveaux sondages.</span>
									</td>
								</tr>

#
#---------[ 114. FIND (line: 214) ]---------------------------------------------------
#

	$post_topics = isset($_POST['post_topics']) ? intval($_POST['post_topics']) : '1';

#
#---------[ 115. ADD AFTER ]---------------------------------------------------
#

	$post_polls = isset($_POST['post_polls']) ? intval($_POST['post_polls']) : '1';

#
#---------[ 116. FIND (line: 236) ]---------------------------------------------------
#

		$db->query('INSERT INTO '.$db->prefix.'groups (g_title, g_user_title, g_read_board, g_post_replies, g_post_topics, g_edit_posts, g_delete_posts, g_delete_topics, g_set_title, g_search, g_search_users, g_edit_subjects_interval, g_post_flood, g_search_flood) VALUES(\''.$db->escape($title).'\', '.$user_title.', '.$read_board.', '.$post_replies.', '.$post_topics.', '.$edit_posts.', '.$delete_posts.', '.$delete_topics.', '.$set_title.', '.$search.', '.$search_users.', '.$edit_subjects_interval.', '.$post_flood.', '.$search_flood.')') or error('Unable to add group', __FILE__, __LINE__, $db->error());
		$new_group_id = $db->insert_id();

#
#---------[ 117. REPLACE BY ]---------------------------------------------------
#

		$db->query('INSERT INTO '.$db->prefix.'groups (g_title, g_user_title, g_read_board, g_post_replies, g_post_topics, g_post_polls, g_edit_posts, g_delete_posts, g_delete_topics, g_set_title, g_search, g_search_users, g_edit_subjects_interval, g_post_flood, g_search_flood) VALUES(\''.$db->escape($title).'\', '.$user_title.', '.$read_board.', '.$post_replies.', '.$post_topics.', '.$post_polls.', '.$edit_posts.', '.$delete_posts.', '.$delete_topics.', '.$set_title.', '.$search.', '.$search_users.', '.$edit_subjects_interval.', '.$post_flood.', '.$search_flood.')') or error('Unable to add group', __FILE__, __LINE__, $db->error());
		$new_group_id = $db->insert_id();

#
#---------[ 118. FIND (line: 239) ]---------------------------------------------------
#

		// Now lets copy the forum specific permissions from the group which this group is based on
		$result = $db->query('SELECT forum_id, read_forum, post_replies, post_topics FROM '.$db->prefix.'forum_perms WHERE group_id='.intval($_POST['base_group'])) or error('Unable to fetch group forum permission list', __FILE__, __LINE__, $db->error());
		while ($cur_forum_perm = $db->fetch_assoc($result))
			$db->query('INSERT INTO '.$db->prefix.'forum_perms (group_id, forum_id, read_forum, post_replies, post_topics) VALUES('.$new_group_id.', '.$cur_forum_perm['forum_id'].', '.$cur_forum_perm['read_forum'].', '.$cur_forum_perm['post_replies'].', '.$cur_forum_perm['post_topics'].')') or error('Unable to insert group forum permissions', __FILE__, __LINE__, $db->error());

#
#---------[ 119. REPLACE BY ]---------------------------------------------------
#

		// Now lets copy the forum specific permissions from the group which this group is based on
		$result = $db->query('SELECT forum_id, read_forum, post_replies, post_topics, post_polls FROM '.$db->prefix.'forum_perms WHERE group_id='.intval($_POST['base_group'])) or error('Unable to fetch group forum permission list', __FILE__, __LINE__, $db->error());
		while ($cur_forum_perm = $db->fetch_assoc($result))
			$db->query('INSERT INTO '.$db->prefix.'forum_perms (group_id, forum_id, read_forum, post_replies, post_topics, post_polls) VALUES('.$new_group_id.', '.$cur_forum_perm['forum_id'].', '.$cur_forum_perm['read_forum'].', '.$cur_forum_perm['post_replies'].', '.$cur_forum_perm['post_topics'].', '.$cur_forum_perm['post_polls'].')') or error('Unable to insert group forum permissions', __FILE__, __LINE__, $db->error());

#
#---------[ 120. FIND (line: 250) ]---------------------------------------------------
#

$db->query('UPDATE '.$db->prefix.'groups SET g_title=\''.$db->escape($title).'\', g_user_title='.$user_title.', g_read_board='.$read_board.', g_post_replies='.$post_replies.', g_post_topics='.$post_topics.', g_edit_posts='.$edit_posts.', g_delete_posts='.$delete_posts.', g_delete_topics='.$delete_topics.', g_set_title='.$set_title.', g_search='.$search.', g_search_users='.$search_users.', g_edit_subjects_interval='.$edit_subjects_interval.', g_post_flood='.$post_flood.', g_search_flood='.$search_flood.' WHERE g_id='.intval($_POST['group_id'])) or error('Unable to update group', __FILE__, __LINE__, $db->error());

#
#---------[ 121. REPLACE BY ]---------------------------------------------------
#

$db->query('UPDATE '.$db->prefix.'groups SET g_title=\''.$db->escape($title).'\', g_user_title='.$user_title.', g_read_board='.$read_board.', g_post_replies='.$post_replies.', g_post_topics='.$post_topics.', g_post_polls='.$post_polls.', g_edit_posts='.$edit_posts.', g_delete_posts='.$delete_posts.', g_delete_topics='.$delete_topics.', g_set_title='.$set_title.', g_search='.$search.', g_search_users='.$search_users.', g_edit_subjects_interval='.$edit_subjects_interval.', g_post_flood='.$post_flood.', g_search_flood='.$search_flood.' WHERE g_id='.intval($_POST['group_id'])) or error('Unable to update group', __FILE__, __LINE__, $db->error());

#
#---------[ 122. OPEN ]---------------------------------------------------
#

style/VOTRESTYLE.css

#
#---------[ 123. ADD, AT THE BOTTOM ]---------------------------------------------------
#

p.poll_info {
text-align: center;
clear: left;
margin: 7px;
}
div.poll_question {
float: left;
clear: both;
text-align: right;
width: 50%;
margin: 0.3em;
height: 1.2em;
}
div.poll_result,div.poll_result_yesno {
float: left;
text-align: left;
width: 45%;
margin: 0.3em;
padding: 0;
height: 1.2em;
line-height: 1.2em;
}
	div.poll_result_yesno {
	width: 22%;
	margin-right: 0.1em;
	}
img.poll_bar {
border-right: 2px solid #0066B9; /* teinte plus foncé que la couleur principale du forum */
background-color: #006FC9; /* Couleur principale du forum */
height: 1.2em;
margin: 0;
}
									
#
#---------[ 124. SAVE, UPLOAD ]---------------------------------------------------
#

viewforum.php
viewtopic.php
post.php
search.php
index.php
moderate.php
edit.php
admin_forum.php
admin_groups.php
lang/LANG/index.php
include/functions.php
style/VOTRESTYLE.css
