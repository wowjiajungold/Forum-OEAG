<?php
	if ($pun_user['g_pm'] == '1' && $pun_user['use_pm'] == '1' && $pun_config['o_pms_enabled'] == '1')
	{
		$result_messages = $db->query('SELECT COUNT(id) FROM '.$db->prefix.'messages WHERE showed=0 AND show_message=1 AND owner='.$pun_user['id']) or error('Unable to check the availibility of new messages', __FILE__, __LINE__, $db->error());
		$num_new_pm = $db->result($result_messages);
		
		if ($num_new_pm > 0)
			$page_statusinfo[] = '<span class="user_mp user_mp_on gotapm"><a class="brdmenu" href="pms_inbox.php"><strong>'.$lang_pms['PM'].'</strong></a><sup><acronym title="'.($num_new_pm == '1' ? $lang_pms['New message'] : sprintf($lang_pms['New messages'],$num_new_pm)).'">'.$num_new_pm.'</acronym></sup></span>';
		else
			$page_statusinfo[] = '<span class="user_mp empty"><a class="brdmenu" href="pms_inbox.php">'.$lang_pms['PM'].'</a></span>';
	}