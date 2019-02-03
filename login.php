<?php
/***************************************************************************
 *                                login.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: login.php,v 1.47.2.20 2005/10/30 15:17:13 acydburn Exp $
 *
 *
 ***************************************************************************/

/***************************************************************************
 *
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version.
 *
 ***************************************************************************/

//
// Allow people to reach login page if
// board is shut down
//
define("IN_LOGIN", true);

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Set page ID for session management
//
$userdata = session_pagestart($user_ip, PAGE_LOGIN);
init_userprefs($userdata);
//
// End session management
//

// session id check
if (!empty($HTTP_POST_VARS['sid']) || !empty($HTTP_GET_VARS['sid']))
{
	$sid = (!empty($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : $HTTP_GET_VARS['sid'];
}
else
{
	$sid = '';
}

//
			// CBACK CrackerTracker Visual Login Confirmation
			// visual confirmation code Generator taken from phpBB (c) phpBB Group
			//
			if ( isset($HTTP_GET_VARS['mode']) || isset($HTTP_POST_VARS['mode']) )
			{
				$mode = ( isset($HTTP_GET_VARS['mode']) ) ? $HTTP_GET_VARS['mode'] : $HTTP_POST_VARS['mode'];
				$mode = htmlspecialchars($mode);

				if ( $mode == 'confirm' )
				{
					if ( $userdata['session_logged_in'] )
					{
						exit;
					}
					include($phpbb_root_path . 'ctracker/ct_confirm.'.$phpEx);
					exit;
			    }
			}
			
			//
			// Now we check if the User is trying to Log in if he already has used one attempt or not
			// if not we disable the Visual Confirmation Code and with this we allow a normal login without any Confirmation
			// if the User tried to log in once we just continue with the normal Script and then we show the Visible Code every time the user
			// tries to log in before checking Password or anything.
			// Well OK its more DB gaming but many users want comfort AND security so let's do it ;-)
			//
			if(!empty($HTTP_POST_VARS['username']) && $ctracker_config['loginfeature'] == 1)
			{
			  $secure_username = '';
			  $secure_username = isset($HTTP_POST_VARS['username']) ? phpbb_clean_username($HTTP_POST_VARS['username']) : '';
			  $sql = "SELECT ct_logintry FROM " . USERS_TABLE . " WHERE username = '" . str_replace("\\'", "''", $secure_username) . "'";
			  if ( !($result = $db->sql_query($sql)) )
			  {
			    message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
			  }
			  if( $row = $db->sql_fetchrow($result) )
		      {
			    if($row['ct_logintry'] == 0)
				{
				  $ctracker_config['loginfeature'] = 0;
				}
		      }
			}
			else
			{
			  $ctracker_config['loginfeature'] = 0;
			}

            if ( $ctracker_config['loginfeature'] == 1 && !$userdata['session_logged_in'] && !empty($HTTP_POST_VARS['confirm_id']) && !empty($HTTP_POST_VARS['confirm_code']))
			{
                    $confirm_id = htmlspecialchars($HTTP_POST_VARS['confirm_id']);					if (!preg_match('/^[A-Za-z0-9]+$/', $confirm_id))
					{
						$confirm_id = '';
					}

					$sql = 'SELECT code
						FROM ' . CTVISKEY . "
						WHERE confirm_id = '$confirm_id'
							AND session_id = '" . $userdata['session_id'] . "'";
					if (!($result = $db->sql_query($sql)))
					{
						message_die(GENERAL_ERROR, 'Could not obtain confirmation code', __LINE__, __FILE__, $sql);
					}

					if ($row = $db->sql_fetchrow($result))
					{
						if ($row['code'] != $HTTP_POST_VARS['confirm_code'])
						{
                            message_die(GENERAL_MESSAGE, $lang['ct_forum_sl1']);
						}
						else
						{
							$sql = 'DELETE FROM ' . CTVISKEY . "
								WHERE confirm_id = '$confirm_id'
									AND session_id = '" . $userdata['session_id'] . "'";
							if (!$db->sql_query($sql))
							{
								message_die(GENERAL_ERROR, 'Could not delete confirmation code', __LINE__, __FILE__, $sql);
							}
						}
					}
					else
					{
                            message_die(GENERAL_MESSAGE, $lang['ct_forum_sl1']);
					}
					$db->sql_freeresult($result);
			}

            $vcheck_need  = FALSE;
            $vcheck_login = TRUE;
            if($ctracker_config['loginfeature'] == 1 )
            {
              $vcheck_need = TRUE;
              $vcheck_login = FALSE;
            }

            if (($vcheck_need = FALSE || $userdata['session_logged_in']) or (isset($HTTP_GET_VARS['logout']) || !empty($HTTP_POST_VARS['confirm_id']) && !empty($HTTP_POST_VARS['confirm_code'])))
		  	{
			  $vcheck_login = TRUE;
		  	}

if(( $vcheck_login == TRUE ) and( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) || isset($HTTP_POST_VARS['logout']) || isset($HTTP_GET_VARS['logout']) ))
{
	if( ( isset($HTTP_POST_VARS['login']) || isset($HTTP_GET_VARS['login']) ) && (!$userdata['session_logged_in'] || isset($HTTP_POST_VARS['admin'])) )
	{
		$username = isset($HTTP_POST_VARS['username']) ? phpbb_clean_username($HTTP_POST_VARS['username']) : '';
		$password = isset($HTTP_POST_VARS['password']) ? $HTTP_POST_VARS['password'] : '';

		$sql = "SELECT user_id, username, user_password, user_active, user_level, user_login_tries, user_last_login_try

			FROM " . USERS_TABLE . "
			WHERE username = '" . str_replace("\\'", "''", $username) . "'";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Error in obtaining userdata', '', __LINE__, __FILE__, $sql);
		}

		if( $row = $db->sql_fetchrow($result) )
		{
			if( $row['user_level'] != ADMIN && $board_config['board_disable'] )
			{
				redirect(append_sid("index.$phpEx", true));
			}
			else
			{

				// If the last login is more than x minutes ago, then reset the login tries/time
				if ($row['user_last_login_try'] && $board_config['login_reset_time'] && $row['user_last_login_try'] < (time() - ($board_config['login_reset_time'] * 60)))
				{
					$db->sql_query('UPDATE ' . USERS_TABLE . ' SET user_login_tries = 0, user_last_login_try = 0 WHERE user_id = ' . $row['user_id']);
					$row['user_last_login_try'] = $row['user_login_tries'] = 0;
				}
				
				// Check to see if user is allowed to login again... if his tries are exceeded
				if ($row['user_last_login_try'] && $board_config['login_reset_time'] && $board_config['max_login_attempts'] && 
					$row['user_last_login_try'] >= (time() - ($board_config['login_reset_time'] * 60)) && $row['user_login_tries'] >= $board_config['max_login_attempts'] && $userdata['user_level'] != ADMIN)

				{
					message_die(GENERAL_MESSAGE, sprintf($lang['Login_attempts_exceeded'], $board_config['max_login_attempts'], $board_config['login_reset_time']));
				}


				if( md5($password) == $row['user_password'] && $row['user_active'] )
				{
					$autologin = ( isset($HTTP_POST_VARS['autologin']) ) ? TRUE : 0;

					$admin = (isset($HTTP_POST_VARS['admin'])) ? 1 : 0;
					$session_id = session_begin($row['user_id'], $user_ip, PAGE_INDEX, FALSE, $autologin, $admin);
					$db->sql_query('UPDATE ' . USERS_TABLE . ' SET ct_logintry = 0 WHERE user_id = ' . $row['user_id']);
					// Reset login tries
					$db->sql_query('UPDATE ' . USERS_TABLE . ' SET user_login_tries = 0, user_last_login_try = 0 WHERE user_id = ' . $row['user_id']);



					if( $session_id )
					{

						$url = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "index.$phpEx";

						redirect(append_sid($url, true));
					}
					else
					{
						message_die(CRITICAL_ERROR, "Couldn't start session : login", "", __LINE__, __FILE__);
					}
				}
				// Only store a failed login attempt for an active user - inactive users can't login even with a correct password
				elseif( $row['user_active'] )

				{
					// Save login tries and last login
					if ($row['user_id'] != ANONYMOUS)
					{
						$sql = 'UPDATE ' . USERS_TABLE . '
							SET user_login_tries = user_login_tries + 1, user_last_login_try = ' . time() . '
							WHERE user_id = ' . $row['user_id'];
						$db->sql_query($sql);
					}
					
					if ($row['user_id'] != ANONYMOUS)
					{
						$sql = 'UPDATE ' . USERS_TABLE . '
							SET ct_logintry = 1
							WHERE user_id = ' . $row['user_id'];
						$db->sql_query($sql);
					}

					$redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : '';
					$redirect = str_replace('?', '&', $redirect);

					if (strstr(urldecode($redirect), "\n") || strstr(urldecode($redirect), "\r"))
					{
						message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
					}

					$template->assign_vars(array(
						'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.$phpEx?redirect=$redirect\">")
					);


					$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.$phpEx?redirect=$redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

					message_die(GENERAL_MESSAGE, $message);
				}

			}
		}
		else
		{
			$redirect = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "";
			$redirect = str_replace("?", "&", $redirect);

			if (strstr(urldecode($redirect), "\n") || strstr(urldecode($redirect), "\r"))
			{
				message_die(GENERAL_ERROR, 'Tried to redirect to potentially insecure url.');
			}

			$template->assign_vars(array(
				'META' => "<meta http-equiv=\"refresh\" content=\"3;url=login.$phpEx?redirect=$redirect\">")
			);

			$message = $lang['Error_login'] . '<br /><br />' . sprintf($lang['Click_return_login'], "<a href=\"login.$phpEx?redirect=$redirect\">", '</a>') . '<br /><br />' .  sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		}
	}
	else if( ( isset($HTTP_GET_VARS['logout']) || isset($HTTP_POST_VARS['logout']) ) && $userdata['session_logged_in'] )
	{
		// session id check
		if ($sid == '' || $sid != $userdata['session_id'])
		{
			message_die(GENERAL_ERROR, 'Invalid_session');
		}

		if( $userdata['session_logged_in'] )
		{
			session_end($userdata['session_id'], $userdata['user_id']);
		}

		if (!empty($HTTP_POST_VARS['redirect']) || !empty($HTTP_GET_VARS['redirect']))
		{
			$url = (!empty($HTTP_POST_VARS['redirect'])) ? htmlspecialchars($HTTP_POST_VARS['redirect']) : htmlspecialchars($HTTP_GET_VARS['redirect']);
			$url = str_replace('&amp;', '&', $url);
			redirect(append_sid($url, true));
		}
		else
		{
			redirect(append_sid("index.$phpEx", true));
		}
	}
	else
	{
		$url = ( !empty($HTTP_POST_VARS['redirect']) ) ? str_replace('&amp;', '&', htmlspecialchars($HTTP_POST_VARS['redirect'])) : "index.$phpEx";

		redirect(append_sid($url, true));
	}
}
else
{
	//
	// Do a full login page dohickey if
	// user not already logged in
	//
	if( !$userdata['session_logged_in'] || (isset($HTTP_GET_VARS['admin']) && $userdata['session_logged_in'] && $userdata['user_level'] == ADMIN))
	{
		$page_title = $lang['Login'];
		include($phpbb_root_path . 'includes/page_header.'.$phpEx);

		$template->set_filenames(array(
			'body' => 'login_body.tpl')
		);

		$forward_page = '';
		
		//
    // CBACK CrackerTracker Login Confirmation
    // Confirmation Generator Taken from phpBB (C) phpBB Group
    //
    $confirm_image = '';
    if( $ctracker_config['loginfeature'] == 1 && !$userdata['session_logged_in'])
    {
	    $sql = 'SELECT session_id
		    FROM ' . SESSIONS_TABLE;
	    if (!($result = $db->sql_query($sql)))
	    {
			message_die(GENERAL_ERROR, 'Could not select session data', '', __LINE__, __FILE__, $sql);
		}

		if ($row = $db->sql_fetchrow($result))
		{
			$confirm_sql = '';
			do
			{
				$confirm_sql .= (($confirm_sql != '') ? ', ' : '') . "'" . $row['session_id'] . "'";
			}
			while ($row = $db->sql_fetchrow($result));

			$sql = 'DELETE FROM ' .  CTVISKEY . "
				WHERE session_id NOT IN ($confirm_sql)";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete stale confirm data', '', __LINE__, __FILE__, $sql);
			}
		}
		$db->sql_freeresult($result);

		$confirm_chars = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J',  'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T',  'U', 'V', 'W', 'X', 'Y', 'Z', '1', '2', '3', '4', '5', '6', '7', '8', '9');

		list($usec, $sec) = explode(' ', microtime());
		mt_srand($sec * $usec);

		$max_chars = count($confirm_chars) - 1;
		$code = '';
		for ($i = 0; $i < 6; $i++)
		{
			$code .= $confirm_chars[mt_rand(0, $max_chars)];
		}

		$confirm_id = md5(uniqid($user_ip));

		$sql = 'INSERT INTO ' . CTVISKEY . " (confirm_id, session_id, code)
			VALUES ('$confirm_id', '". $userdata['session_id'] . "', '$code')";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not insert new confirm code information', '', __LINE__, __FILE__, $sql);
		}

		unset($code);

		$confirm_image = (@extension_loaded('zlib')) ? '<img src="' . append_sid("login.$phpEx?mode=confirm&amp;id=$confirm_id") . '" alt="" title="" />' : '<img src="' . append_sid("login.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=1") . '" alt="" title="" /><img src="' . append_sid("login.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=2") . '" alt="" title="" /><img src="' . append_sid("login.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=3") . '" alt="" title="" /><img src="' . append_sid("login.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=4") . '" alt="" title="" /><img src="' . append_sid("login.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=5") . '" alt="" title="" /><img src="' . append_sid("login.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=6") . '" alt="" title="" />';
		$hidden_form_fields .= '<input type="hidden" name="confirm_id" value="' . $confirm_id . '" />';

		$template->assign_block_vars('switch_confirm', array());
	}

		if( isset($HTTP_POST_VARS['redirect']) || isset($HTTP_GET_VARS['redirect']) )
		{
			$forward_to = $HTTP_SERVER_VARS['QUERY_STRING'];

			if( preg_match("/^redirect=([a-z0-9\.#\/\?&=\+\-_]+)/si", $forward_to, $forward_matches) )
			{
				$forward_to = ( !empty($forward_matches[3]) ) ? $forward_matches[3] : $forward_matches[1];
				$forward_match = explode('&', $forward_to);

				if(count($forward_match) > 1)
				{
					for($i = 1; $i < count($forward_match); $i++)
					{
						if( !ereg("sid=", $forward_match[$i]) )
						{
							if( $forward_page != '' )
							{
								$forward_page .= '&';
							}
							$forward_page .= $forward_match[$i];
						}
					}
					$forward_page = $forward_match[0] . '?' . $forward_page;
				}
				else
				{
					$forward_page = $forward_match[0];
				}
			}
		}

		$username = ( $userdata['user_id'] != ANONYMOUS ) ? $userdata['username'] : '';

		$s_hidden_fields = '<input type="hidden" name="redirect" value="' . $forward_page . '" />';
		$s_hidden_fields .= (isset($HTTP_GET_VARS['admin'])) ? '<input type="hidden" name="admin" value="1" />' : '';

		make_jumpbox('viewforum.'.$phpEx);
		$template->assign_vars(array(
			'USERNAME' => $username,

			'L_ENTER_PASSWORD' => (isset($HTTP_GET_VARS['admin'])) ? $lang['Admin_reauthenticate'] : $lang['Enter_password'],
			'L_SEND_PASSWORD' => $lang['Forgotten_password'],
			'CONFIRM_IMG' => $confirm_image,
			'L_CONFIRM_CODE' => $lang['ct_forum_slo'],

			'U_SEND_PASSWORD' => append_sid("profile.$phpEx?mode=sendpassword"),

			'S_HIDDEN_FIELDS' => $s_hidden_fields . $hidden_form_fields)
		);

		$template->pparse('body');

		include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
	}
	else
	{
		redirect(append_sid("index.$phpEx", true));
	}

}

?>
