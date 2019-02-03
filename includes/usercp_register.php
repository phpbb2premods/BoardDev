<?php
/***************************************************************************
 *                            usercp_register.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: usercp_register.php,v 1.20.2.66 2005/10/10 20:54:40 grahamje Exp $
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
 *
 ***************************************************************************/

/*

	This code has been modified from its original form by psoTFX @ phpbb.com
	Changes introduce the back-ported phpBB 2.2 visual confirmation code. 

	NOTE: Anyone using the modified code contained within this script MUST include
	a relevant message such as this in usercp_register.php ... failure to do so 
	will affect a breach of Section 2a of the GPL and our copyright

	png visual confirmation system : (c) phpBB Group, 2003 : All Rights Reserved

*/

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
	exit;
}

$unhtml_specialchars_match = array('#&gt;#', '#&lt;#', '#&quot;#', '#&amp;#');
$unhtml_specialchars_replace = array('>', '<', '"', '&');

// ---------------------------------------
// Load agreement template since user has not yet
// agreed to registration conditions/coppa
//

//
// CBACK CrackerTracker Register Flood Protection
//
  if($ctracker_config['regblock'] == 1 && $HTTP_GET_VARS['mode'] == 'register')
  {
    if($ctracker_config['lastreg'] >= time())
    {
      $lregtimestamp = $ctracker_config['lastreg'];
      $waittime = 0;
      $waittime = $lregtimestamp - time();
      $waitmsg  = '';
      $waitmsg  = sprintf($lang['ct_forum_rfl'], $waittime);
      message_die(GENERAL_MESSAGE, $waitmsg);
    }

    if(!empty($HTTP_SERVER_VARS['REMOTE_ADDR']) && $ctracker_config['lastreg_ip'] == $HTTP_SERVER_VARS['REMOTE_ADDR'])
    {
      // If the same IP wants to register we block this for 400 Seconds
      if($ctracker_config['lastreg'] + 400 >= time())
      {
        message_die(GENERAL_MESSAGE, $lang['ct_forum_ifl']);
      }
    }
  }

function show_coppa()
{
	global $userdata, $template, $lang, $phpbb_root_path, $phpEx;

	$template->set_filenames(array(
		'body' => 'agreement.tpl')
	);

	$template->assign_vars(array(
		'REGISTRATION' => $lang['Registration'],
		'AGREEMENT' => $lang['Reg_agreement'],
		"AGREE_OVER_13" => $lang['Agree_over_13'],
		"AGREE_UNDER_13" => $lang['Agree_under_13'],
		'DO_NOT_AGREE' => $lang['Agree_not'],

		"U_AGREE_OVER13" => append_sid("profile.$phpEx?mode=register&amp;agreed=true"),
		"U_AGREE_UNDER13" => append_sid("profile.$phpEx?mode=register&amp;agreed=true&amp;coppa=true"))
	);

	$template->pparse('body');

}
//
// ---------------------------------------

$error = FALSE;
$page_title = ( $mode == 'editprofile' ) ? $lang['Edit_profile'] : $lang['Register'];

// DEBUT MOD Désactiver les inscriptions
if( $mode == 'register' && $board_config['registration_disable'] && $userdata['user_level'] != ADMIN )
{
	$message = $board_config['lang_registration_disable'] . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
	message_die(GENERAL_MESSAGE, $message);
}
// FIN MOD Désactiver les inscriptions

if ( $mode == 'register' && !isset($HTTP_POST_VARS['agreed']) && !isset($HTTP_GET_VARS['agreed']) )
{
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);

	show_coppa();

	include($phpbb_root_path . 'includes/page_tail.'.$phpEx);
}

$coppa = ( empty($HTTP_POST_VARS['coppa']) && empty($HTTP_GET_VARS['coppa']) ) ? 0 : TRUE;

//
// Check and initialize some variables if needed
//
// Start add - Signatures control MOD
if ( !file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_sig_control.' . $phpEx)) ) 
{ 
	include_once($phpbb_root_path . 'language/lang_english/lang_sig_control.' . $phpEx); 
} else 
{ 
	include_once($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_sig_control.' . $phpEx); 
} 
// End add - Signatures control MOD
if (
	isset($HTTP_POST_VARS['submit']) ||
	isset($HTTP_POST_VARS['avatargallery']) ||
	isset($HTTP_POST_VARS['submitavatar']) ||
	isset($HTTP_POST_VARS['cancelavatar']) ||
	$mode == 'register' )
{
	include($phpbb_root_path . 'includes/functions_validate.'.$phpEx);
	include($phpbb_root_path . 'includes/bbcode.'.$phpEx);
	include($phpbb_root_path . 'includes/functions_post.'.$phpEx);

	if ( $mode == 'editprofile' )
	{
		$user_id = intval($HTTP_POST_VARS['user_id']);
		$current_email = trim(htmlspecialchars($HTTP_POST_VARS['current_email']));
	}

	$strip_var_list = array('email' => 'email', 'icq' => 'icq', 'aim' => 'aim', 'msn' => 'msn', 'yim' => 'yim', 'website' => 'website', 'location' => 'location', 'occupation' => 'occupation', 'interests' => 'interests', 'confirm_code' => 'confirm_code');


	// Strip all tags from data ... may p**s some people off, bah, strip_tags is
	// doing the job but can still break HTML output ... have no choice, have
	// to use htmlspecialchars ... be prepared to be moaned at.
	while( list($var, $param) = @each($strip_var_list) )
	{
		if ( !empty($HTTP_POST_VARS[$param]) )
		{
			$$var = trim(htmlspecialchars($HTTP_POST_VARS[$param]));
		}
	}

	$username = ( !empty($HTTP_POST_VARS['username']) ) ? phpbb_clean_username($HTTP_POST_VARS['username']) : '';


	$trim_var_list = array('cur_password' => 'cur_password', 'new_password' => 'new_password', 'password_confirm' => 'password_confirm', 'signature' => 'signature');

	while( list($var, $param) = @each($trim_var_list) )
	{
		if ( !empty($HTTP_POST_VARS[$param]) )
		{
			$$var = trim($HTTP_POST_VARS[$param]);
		}
	}

	$signature = (isset($signature)) ? str_replace('<br />', "\n", $signature) : '';
	$signature_bbcode_uid = '';


	// Run some validation on the optional fields. These are pass-by-ref, so they'll be changed to
	// empty strings if they fail.
	validate_optional_fields($icq, $aim, $msn, $yim, $website, $location, $occupation, $interests, $signature);

	$viewemail = ( isset($HTTP_POST_VARS['viewemail']) ) ? ( ($HTTP_POST_VARS['viewemail']) ? TRUE : 0 ) : 0;
	$allowviewonline = ( isset($HTTP_POST_VARS['hideonline']) ) ? ( ($HTTP_POST_VARS['hideonline']) ? 0 : TRUE ) : TRUE;
	$notifyreply = ( isset($HTTP_POST_VARS['notifyreply']) ) ? ( ($HTTP_POST_VARS['notifyreply']) ? TRUE : 0 ) : 0;
	$notifypm = ( isset($HTTP_POST_VARS['notifypm']) ) ? ( ($HTTP_POST_VARS['notifypm']) ? TRUE : 0 ) : TRUE;
	$popup_pm = ( isset($HTTP_POST_VARS['popup_pm']) ) ? ( ($HTTP_POST_VARS['popup_pm']) ? TRUE : 0 ) : TRUE;

	if ( $mode == 'register' )
	{
		$attachsig = ( isset($HTTP_POST_VARS['attachsig']) ) ? ( ($HTTP_POST_VARS['attachsig']) ? TRUE : 0 ) : $board_config['allow_sig'];

		$allowhtml = ( isset($HTTP_POST_VARS['allowhtml']) ) ? ( ($HTTP_POST_VARS['allowhtml']) ? TRUE : 0 ) : $board_config['allow_html'];
		$allowbbcode = ( isset($HTTP_POST_VARS['allowbbcode']) ) ? ( ($HTTP_POST_VARS['allowbbcode']) ? TRUE : 0 ) : $board_config['allow_bbcode'];
		$allowsmilies = ( isset($HTTP_POST_VARS['allowsmilies']) ) ? ( ($HTTP_POST_VARS['allowsmilies']) ? TRUE : 0 ) : $board_config['allow_smilies'];
	}
	else
	{
		$attachsig = ( isset($HTTP_POST_VARS['attachsig']) ) ? ( ($HTTP_POST_VARS['attachsig']) ? TRUE : 0 ) : $userdata['user_attachsig'];

		$allowhtml = ( isset($HTTP_POST_VARS['allowhtml']) ) ? ( ($HTTP_POST_VARS['allowhtml']) ? TRUE : 0 ) : $userdata['user_allowhtml'];
		$allowbbcode = ( isset($HTTP_POST_VARS['allowbbcode']) ) ? ( ($HTTP_POST_VARS['allowbbcode']) ? TRUE : 0 ) : $userdata['user_allowbbcode'];
		$allowsmilies = ( isset($HTTP_POST_VARS['allowsmilies']) ) ? ( ($HTTP_POST_VARS['allowsmilies']) ? TRUE : 0 ) : $userdata['user_allowsmile'];
	}

	$user_style = ( isset($HTTP_POST_VARS['style']) ) ? intval($HTTP_POST_VARS['style']) : $board_config['default_style'];

	if ( !empty($HTTP_POST_VARS['language']) )
	{
		if ( preg_match('/^[a-z_]+$/i', $HTTP_POST_VARS['language']) )
		{
			$user_lang = htmlspecialchars($HTTP_POST_VARS['language']);
		}
		else
		{
			$error = true;
			$error_msg = $lang['Fields_empty'];
		}
	}
	else
	{
		$user_lang = $board_config['default_lang'];
	}

	$user_timezone = ( isset($HTTP_POST_VARS['timezone']) ) ? doubleval($HTTP_POST_VARS['timezone']) : $board_config['board_timezone'];

	$sql = "SELECT config_value
		FROM " . CONFIG_TABLE . "
		WHERE config_name = 'default_dateformat'";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not select default dateformat', '', __LINE__, __FILE__, $sql);
	}
	$row = $db->sql_fetchrow($result);
	$board_config['default_dateformat'] = $row['config_value'];
	$user_dateformat = ( !empty($HTTP_POST_VARS['dateformat']) ) ? trim(htmlspecialchars($HTTP_POST_VARS['dateformat'])) : $board_config['default_dateformat'];
	$user_colortext = ( $board_config['allow_colortext'] ) ? $HTTP_POST_VARS['colortext'] : '';

	$user_avatar_local = ( isset($HTTP_POST_VARS['avatarselect']) && !empty($HTTP_POST_VARS['submitavatar']) && $board_config['allow_avatar_local'] ) ? htmlspecialchars($HTTP_POST_VARS['avatarselect']) : ( ( isset($HTTP_POST_VARS['avatarlocal'])  ) ? htmlspecialchars($HTTP_POST_VARS['avatarlocal']) : '' );

	$user_avatar_remoteurl = ( !empty($HTTP_POST_VARS['avatarremoteurl']) ) ? trim(htmlspecialchars($HTTP_POST_VARS['avatarremoteurl'])) : '';
	$user_avatar_upload = ( !empty($HTTP_POST_VARS['avatarurl']) ) ? trim($HTTP_POST_VARS['avatarurl']) : ( ( $HTTP_POST_FILES['avatar']['tmp_name'] != "none") ? $HTTP_POST_FILES['avatar']['tmp_name'] : '' );
	$user_avatar_name = ( !empty($HTTP_POST_FILES['avatar']['name']) ) ? $HTTP_POST_FILES['avatar']['name'] : '';
	$user_avatar_size = ( !empty($HTTP_POST_FILES['avatar']['size']) ) ? $HTTP_POST_FILES['avatar']['size'] : 0;
	$user_avatar_filetype = ( !empty($HTTP_POST_FILES['avatar']['type']) ) ? $HTTP_POST_FILES['avatar']['type'] : '';

	$user_avatar = ( empty($user_avatar_local) && $mode == 'editprofile' ) ? $userdata['user_avatar'] : '';
	$user_avatar_type = ( empty($user_avatar_local) && $mode == 'editprofile' ) ? $userdata['user_avatar_type'] : '';
//-- mod : quick post es -------------------------------------------------------
//-- add
	// config data
	if (!empty($board_config['users_qp_settings']))
	{
		list($board_config['user_qp'], $board_config['user_qp_show'], $board_config['user_qp_subject'], $board_config['user_qp_bbcode'], $board_config['user_qp_smilies'], $board_config['user_qp_more']) = explode('-', $board_config['users_qp_settings']);
	}
	$params = array('user_qp', 'user_qp_show', 'user_qp_subject', 'user_qp_bbcode', 'user_qp_smilies', 'user_qp_more');
	for($i = 0; $i < count($params); $i++)
	{
		$qp_config = ( $mode == 'register' ) ? $board_config[$params[$i]] : $userdata[$params[$i]];
		$$params[$i] = ( isset($HTTP_POST_VARS[$params[$i]]) ) ? ( ($HTTP_POST_VARS[$params[$i]]) ? intval($HTTP_POST_VARS[$params[$i]]) : $$params[$i] ) : $qp_config;
	}
//-- fin mod : quick post es ---------------------------------------------------
//-- mod : birthday ------------------------------------------------------------
//-- add
	$bday_day = ( isset($HTTP_POST_VARS['bday_day']) ) ? intval($HTTP_POST_VARS['bday_day']) : $bday_day;
	$bday_month = ( isset($HTTP_POST_VARS['bday_month']) ) ? intval($HTTP_POST_VARS['bday_month']) : $bday_month;
	$bday_year = ( isset($HTTP_POST_VARS['bday_year']) ) ? intval($HTTP_POST_VARS['bday_year']) : $bday_year;
//-- fin mod : birthday --------------------------------------------------------

	if ( (isset($HTTP_POST_VARS['avatargallery']) || isset($HTTP_POST_VARS['submitavatar']) || isset($HTTP_POST_VARS['cancelavatar'])) && (!isset($HTTP_POST_VARS['submit'])) )
	{
		$username = stripslashes($username);
		$email = stripslashes($email);
		$cur_password = htmlspecialchars(stripslashes($cur_password));
		$new_password = htmlspecialchars(stripslashes($new_password));
		$password_confirm = htmlspecialchars(stripslashes($password_confirm));

		$icq = stripslashes($icq);
		$aim = stripslashes($aim);
		$msn = stripslashes($msn);
		$yim = stripslashes($yim);

		$website = stripslashes($website);
		$location = stripslashes($location);
		$occupation = stripslashes($occupation);
		$interests = stripslashes($interests);
		$signature = stripslashes($signature);

		$user_lang = stripslashes($user_lang);
		$user_dateformat = stripslashes($user_dateformat);

		if ( !isset($HTTP_POST_VARS['cancelavatar']))
		{
			$user_avatar = $user_avatar_local;
			$user_avatar_type = USER_AVATAR_GALLERY;
		}
	}
}

//
// Let's make sure the user isn't logged in while registering,
// and ensure that they were trying to register a second time
// (Prevents double registrations)
//
if ($mode == 'register' && ($userdata['session_logged_in'] || $username == $userdata['username']))
{
	message_die(GENERAL_MESSAGE, $lang['Username_taken'], '', __LINE__, __FILE__);
}

//
// Did the user submit? In this case build a query to update the users profile in the DB
//
if ( isset($HTTP_POST_VARS['submit']) )
{
	include($phpbb_root_path . 'includes/usercp_avatar.'.$phpEx);

	$passwd_sql = '';
	if ( $mode == 'editprofile' )
	{
		if ( $user_id != $userdata['user_id'] )
		{
			$error = TRUE;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Wrong_Profile'];
		}
	}
	else if ( $mode == 'register' )
	{
		if ( empty($username) || empty($new_password) || empty($password_confirm) || empty($email) )
		{
			$error = TRUE;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Fields_empty'];
		}
	}

	if ($board_config['enable_confirm'] && $mode == 'register')
	{
		if (empty($HTTP_POST_VARS['confirm_id']))
		{
			$error = TRUE;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Confirm_code_wrong'];
		}
		else
		{
			$confirm_id = htmlspecialchars($HTTP_POST_VARS['confirm_id']);
			if (!preg_match('/^[A-Za-z0-9]+$/', $confirm_id))
			{
				$confirm_id = '';
			}
			
			$sql = 'SELECT code 
				FROM ' . CONFIRM_TABLE . " 
				WHERE confirm_id = '$confirm_id' 
					AND session_id = '" . $userdata['session_id'] . "'";
			if (!($result = $db->sql_query($sql)))
			{
				message_die(GENERAL_ERROR, 'Could not obtain confirmation code', __LINE__, __FILE__, $sql);
			}

			if ($row = $db->sql_fetchrow($result))
			{





				if ($row['code'] != $confirm_code)
				{
					$error = TRUE;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Confirm_code_wrong'];
				}
				else
				{
					$sql = 'DELETE FROM ' . CONFIRM_TABLE . " 
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
				$error = TRUE;
				$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Confirm_code_wrong'];
			}
			$db->sql_freeresult($result);
		}
	}

	$passwd_sql = '';
	if ( !empty($new_password) && !empty($password_confirm) )
	{


		if ( $new_password != $password_confirm )
		{
			$error = TRUE;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Password_mismatch'];
		}
		else if ( strlen($new_password) > 32 )
		{
			$error = TRUE;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Password_long'];
		}
		else
		{
			if ( $mode == 'editprofile' )
			{
				$sql = "SELECT user_password
					FROM " . USERS_TABLE . "
					WHERE user_id = $user_id";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain user_password information', '', __LINE__, __FILE__, $sql);
				}

				$row = $db->sql_fetchrow($result);

				if ( $row['user_password'] != md5($cur_password) )
				{
					$error = TRUE;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Current_password_mismatch'];
				}
			}

			if ( !$error )
			{
				$new_password = md5($new_password);
				$passwd_sql = "user_password = '$new_password', ";
			}
		}
	}
	else if ( ( empty($new_password) && !empty($password_confirm) ) || ( !empty($new_password) && empty($password_confirm) ) )
	{
		$error = TRUE;
		$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Password_mismatch'];
	}

	//
	// Do a ban check on this email address
	//
	if ( $email != $userdata['user_email'] || $mode == 'register' )
	{
		$result = validate_email($email);
		if ( $result['error'] )
		{
			$email = $userdata['user_email'];

			$error = TRUE;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $result['error_msg'];
		}

		if ( $mode == 'editprofile' )
		{
			$sql = "SELECT user_password
				FROM " . USERS_TABLE . "
				WHERE user_id = $user_id";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain user_password information', '', __LINE__, __FILE__, $sql);
			}

			$row = $db->sql_fetchrow($result);

			if ( $row['user_password'] != md5($cur_password) )
			{
				$email = $userdata['user_email'];

				$error = TRUE;
				$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Current_password_mismatch'];
			}
		}
	}

	$username_sql = '';
	if ( $board_config['allow_namechange'] || $mode == 'register' )
	{
		if ( empty($username) )
		{
			// Error is already triggered, since one field is empty.
			$error = TRUE;
		}
		else if ( $username != $userdata['username'] || $mode == 'register')
		{
			if (strtolower($username) != strtolower($userdata['username']) || $mode == 'register')
			{
				$result = validate_username($username);
				if ( $result['error'] )
				{
					$error = TRUE;
					$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $result['error_msg'];
				}
			}

			if (!$error)
			{
				$username_sql = "username = '" . str_replace("\'", "''", $username) . "', ";
			}
		}
	}

	if ( $signature != '' && $userdata['user_allowsignature'] == 1 )
	{
		// Start replacement - Signatures control MOD
		$signature_no_bbcode = preg_replace("#\[img\].*?\[/img\]|\[\/?(size.*?|b|i|u|color.*?|quote.*?|code|list.*?|url.*?)\]#si", "", $signature);
		if ( strlen($signature_no_bbcode) > $board_config['max_sig_chars'] && $board_config['max_sig_chars'] )
		// End replacement - Signatures control MOD
		{
			$error = TRUE;
			$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['Signature_too_long'];
		}

		// Start add - Signatures control MOD
		$sig_error_list = '';

		// BBCodes control
		$bbcode_error_list = '';
		$bbcode_error_list .= ( !$board_config['sig_allow_font_sizes'] && substr_count(strtolower($signature), '[/size]') > 0 ) ? '[size]' : '';
		$bbcode_error_list .= ( !$board_config['sig_allow_bold'] && substr_count(strtolower($signature), '[/b]') > 0 ) ? '[b]' : '';
		$bbcode_error_list .= ( !$board_config['sig_allow_italic'] && substr_count(strtolower($signature), '[/i]') > 0 ) ? '[i]' : '';
		$bbcode_error_list .= ( !$board_config['sig_allow_underline'] && substr_count(strtolower($signature), '[/u]') > 0 ) ? '[u]' : '';
		$bbcode_error_list .= ( !$board_config['sig_allow_colors'] && substr_count(strtolower($signature), '[/color]') > 0 ) ? '[color]' : '';
		$bbcode_error_list .= ( !$board_config['sig_allow_quote'] && substr_count(strtolower($signature), '[/quote]') > 0 ) ? '[quote]' : '';
		$bbcode_error_list .= ( !$board_config['sig_allow_code'] && substr_count(strtolower($signature), '[/code]') > 0 ) ? '[code]' : '';
		$bbcode_error_list .= ( !$board_config['sig_allow_list'] && substr_count(strtolower($signature), '[/list]') > 0 ) ? '[list]' : '';
		$bbcode_error_list .= ( !$board_config['sig_allow_url'] && substr_count(strtolower($signature), '[/url]') > 0 ) ? '[url]' : '';
		$bbcode_error_list .= ( !$board_config['sig_allow_images'] && substr_count(strtolower($signature), '[/img]') > 0 ) ? '[img]' : '';

		$exotic_bbcodes_list = explode(",", $board_config['sig_exotic_bbcodes_disallowed']);
		while ( list($bbckey, $exotic_bbcode) = @each($exotic_bbcodes_list) )
		{
			$exotic_bbcode = trim(strtolower($exotic_bbcode));
			if ( $exotic_bbcode != '' )
			{
				$bbcode_error_list .= ( substr_count(strtolower($signature), '[/'.$exotic_bbcode.']') > 0 ) ? '['.$exotic_bbcode.']' : '';
			}
		}

		if ( $bbcode_error_list != '' )
		{
			$error = TRUE;
			$sig_error_list .= '<br />' . sprintf($lang['sig_error_bbcode'], '<span style="color: #800000">' . $bbcode_error_list . '</span>');
		}

		// Number of lines control
		if ( $board_config['sig_max_lines'] )
		{
			if ( count(explode("\n", $signature)) > $board_config['sig_max_lines'] ) 
			{ 
				$error = TRUE;
				$sig_error_list .= '<br />' . sprintf($lang['sig_error_max_lines'], count(explode("\n", $signature)), $board_config['sig_max_lines']);
			}
		}	

		// Wordwrap control
		if ( $board_config['sig_wordwrap'] )
		{
			$signature_no_bbcode = preg_replace("#\[img\].*?\[/img\]|\[\/?(size.*?|b|i|u|color.*?|quote.*?|code|list.*?|url.*?)\]#si", "", $signature);
			$signature_splited = preg_split("/[\s,]+/", $signature_no_bbcode);

			foreach($signature_splited as $key => $word)
			{
				$length = strlen($word);
				if( $length > $board_config['sig_wordwrap'] )
				{
					$words[$key] = $word;
				}
			}

			if ( count($words) ) 
			{ 
				$error = TRUE;
				$sig_error_list .= '<br />' . sprintf($lang['sig_error_wordwrap'], count($words), $board_config['sig_wordwrap']);
			}
		}

		// Font size limit control (imposed font size is managed in viewtopic.php)
		if ( $board_config['sig_allow_font_sizes'] == 2 )
		{
			if( preg_match_all("#\[size=([0-9]+?)\](.*?)\[/size\]#si", $signature, $sig_sizes_list) )
			{
				if ( $board_config['sig_min_font_size'] && min($sig_sizes_list[1]) < $board_config['sig_min_font_size'] )
				{
					$error = TRUE;
					$sig_error_list .= '<br />' . sprintf($lang['sig_error_font_size_min'], min($sig_sizes_list[1]), $board_config['sig_min_font_size']);
				}
				if ( $board_config['sig_max_font_size'] && max($sig_sizes_list[1]) > $board_config['sig_max_font_size'] )
				{
					$error = TRUE;
					$sig_error_list .= '<br />' . sprintf($lang['sig_error_font_size_max'], max($sig_sizes_list[1]), $board_config['sig_max_font_size']);
				}
			}
		}

		// Images control (except file the size error message)
		$total_image_files_size = 0;

		if( $board_config['sig_allow_images'] && preg_match_all("#\[img\]((ht|f)tp://)([^\r\n\t<\"]*?)\[/img\]#si", $signature, $sig_images_list) )
		{
			if( count($sig_images_list[0]) > $board_config['sig_max_images'] && $board_config['sig_max_images'] != 0 )
			{
				$error = TRUE;
				$sig_error_list .= '<br />' . sprintf($lang['sig_error_num_images'], count($sig_images_list[0]), $board_config['sig_max_images']);
			}

			for( $i = 0; $i < count($sig_images_list[0]); $i++ )
			{
				$image_url = $sig_images_list[1][$i].$sig_images_list[3][$i];
	
				preg_match('/^(http:\/\/)?([\w\-\.]+)\:?([0-9]*)\/(.*)$/', $image_url, $image_url_ary);

				if ( empty($image_url_ary[4]) )
				{
					$error = true;
					$sig_error_list .= '<br />' . $lang['Incomplete_URL'] . ': ' . '<span style="color: #800000">' . $image_url . '"</span>';
				} 
				else
				{
					$image_size_control = false;
					if ( $board_config['sig_max_img_height'] != 0 || $board_config['sig_max_img_width'] != 0 )
					{
						usleep(1500);
						if ( list($image_width, $image_height) = @getimagesize($image_url) )
						{
							$image_size_control = true;
							if( ($board_config['sig_max_img_height'] != 0 && $image_height > $board_config['sig_max_img_height']) || ($board_config['sig_max_img_width'] != 0 && $image_width > $board_config['sig_max_img_width']) )
							{
								$error = TRUE;
								$sig_error_list .= '<br />' . sprintf($lang['sig_error_images_size'], '<span style="color: #800000">' . $image_url . '"</span>', $image_height, $image_width, ( $board_config['sig_max_img_height'] ) ? $board_config['sig_max_img_height'] : $lang['sig_unlimited'], ( $board_config['sig_max_img_width'] ) ? $board_config['sig_max_img_width'] : $lang['sig_unlimited']);
							}
						}
					}
	
					$image_data = '';
					$image_file_size_control = 0;
					if( $board_config['sig_max_img_files_size'] != 0 || $board_config['sig_max_img_av_files_size'] != 0 || (($board_config['sig_max_img_height'] != 0 || $board_config['sig_max_img_width'] != 0) && $image_size_control == false) )
					{
						if( $image_fd = @fopen($image_url, "rb") )
						{
							while (!feof($image_fd))
							{
								$image_data .= fread($image_fd, 1024);
							}
							fclose($image_fd);

							$total_image_files_size += strlen($image_data);
							$image_file_size_control = 3;
						} 
						else		
						{
							$base_get = '/' . $image_url_ary[4];
							$port = ( !empty($image_url_ary[3]) ) ? $image_url_ary[3] : 80;

							if ( !($image_fsock = @fsockopen($image_url_ary[2], $port, $errno, $errstr)) )
							{
								$error = true;
								$sig_error_list .= '<br />' . $lang['No_connection_URL'] . ': ' . '<span style="color: #800000">' . $image_url . '"</span>';
							} 
							else
							{
								@fputs($image_fsock, "GET $base_get HTTP/1.1\r\n");
								@fputs($image_fsock, "HOST: " . $image_url_ary[2] . "\r\n");
								@fputs($image_fsock, "Connection: close\r\n\r\n");

								while( !@feof($image_fsock) )
								{
									$image_data .= @fread($image_fsock, 1024);
								}
								@fclose($image_fsock);		

								if ( preg_match('#Content-Length\: ([0-9]+)[^ /][\s]+#i', $image_data, $image_file_data) )
								{
									$total_image_files_size += $image_file_data[1]; 
									$image_file_size_control = 2;
								} 
								else
								{
									$total_image_files_size += strlen($image_data)-307; 
									$image_file_size_control = 1;
								}
							}	
						}
					}	

					if( ($board_config['sig_max_img_height'] != 0 || $board_config['sig_max_img_width'] != 0) && $image_size_control == false )
					{
						if( $image_file_size_control == 2 )
						{
							$image_data = substr($image_data, strlen($image_data) - $image_file_data[1], $image_file_data[1]);
						}

						if( function_exists('ImageCreateFromString') )
						{
							if( $image_string = @ImageCreateFromString($image_data) )
							{
								$image_width = ImageSX($image_string);
								$image_height = ImageSY($image_string);

								if( ($board_config['sig_max_img_height'] != 0 && $image_height > $board_config['sig_max_img_height']) || ($board_config['sig_max_img_width'] != 0 && $image_width > $board_config['sig_max_img_width']) )
								{
									$error = TRUE;
									$sig_error_list .= '<br />' . sprintf($lang['sig_error_images_size'], '<span style="color: #800000">' . $image_url . '"</span>', $image_height, $image_width, ( $board_config['sig_max_img_height'] ) ? $board_config['sig_max_img_height'] : $lang['sig_unlimited'], ( $board_config['sig_max_img_width'] ) ? $board_config['sig_max_img_width'] : $lang['sig_unlimited']);
								}

								ImageDestroy($image_string);
							} 
							else
							{
								if( $board_config['sig_allow_on_max_img_size_fail'] == 0 )
								{
									$error = TRUE;
									$sig_error_list .= '<br />' . sprintf($lang['sig_error_images_size_control'], '<span style="color: #800000">' . $image_url . '"</span>');
								}
							}
						}
						else
						{
							if( $board_config['sig_allow_on_max_img_size_fail'] == 0 )
							{
								$error = TRUE;
								$sig_error_list .= '<br />' . sprintf($lang['sig_error_images_size_control'], '<span style="color: #800000">' . $image_url . '"</span>');
							}
						}
					}
				}
			}
		}
	}

	if ( $signature != '' )
	{
	// End add - Signatures control MOD
		if ( $signature_bbcode_uid == '' )
		{
			$signature_bbcode_uid = ( $allowbbcode ) ? make_bbcode_uid() : '';
		}
		$signature = prepare_message($signature, $allowhtml, $allowbbcode, $allowsmilies, $signature_bbcode_uid);
	}

	if ( $website != '' )
	{
		rawurlencode($website);
	}

	// Start add - Signatures control MOD
	if ( $board_config['sig_max_img_av_files_size'] != 0 && ($board_config['allow_avatar_upload'] || $board_config['allow_avatar_remote'] || $board_config['allow_avatar_local']) )
	{
		if ( !empty($user_avatar_name) && $board_config['allow_avatar_upload'] )
		{
			$avatar_file_size = $user_avatar_size;
		} 
		else
		{
			if ( !empty($user_avatar_upload) && $board_config['allow_avatar_upload'] )
			{
				$avatar_url = $user_avatar_upload;
			} 
			else if ( !empty($user_avatar_remoteurl) && $board_config['allow_avatar_remote'] )
			{
				$avatar_url = $user_avatar_remoteurl;
			} 
			else if ( !empty($user_avatar_local) && $board_config['allow_avatar_local'] )
			{
				$avatar_url = $board_config['avatar_gallery_path'] . '/' . $user_avatar_local;
			} 
			elseif ( $user_avatar_type && !isset($HTTP_POST_VARS['avatardel']) )
			{
				switch( $user_avatar_type )
				{
					case USER_AVATAR_UPLOAD:
						$avatar_url = ( $board_config['allow_avatar_upload'] ) ? $board_config['avatar_path'] . '/' . $user_avatar : '';
						break;
					case USER_AVATAR_REMOTE:
						$avatar_url = ( $board_config['allow_avatar_remote'] ) ? $user_avatar : '';
						break;
					case USER_AVATAR_GALLERY:
						$avatar_url = ( $board_config['allow_avatar_local'] ) ? $board_config['avatar_gallery_path'] . '/' . $user_avatar : '';
						break;
				}
			} 
			else
			{
				$avatar_url = '';	
			}

			if ( $avatar_url != '' )
			{
				preg_match('/^(http:\/\/)?([\w\-\.]+)\:?([0-9]*)\/(.*)$/', $avatar_url, $avatar_url_ary);

				if ( empty($avatar_url_ary[4]) )
				{
					$error = true;
					$sig_error_list .= '<br />' . $lang['Incomplete_URL'] . ': ' . '<span style="color: #800000">' . $avatar_url . '"</span>';
				} 
				else
				{
					$avatar_data = '';

					if( $avatar_fd = @fopen($avatar_url, "rb") )
					{
						while (!feof($avatar_fd))
						{
							$avatar_data .= fread($avatar_fd, 1024);
						}
						fclose($avatar_fd);

						$avatar_file_size = strlen($avatar_data);
					} 
					else		
					{
						$base_get = '/' . $avatar_url_ary[4];
						$port = ( !empty($avatar_url_ary[3]) ) ? $avatar_url_ary[3] : 80;

						if ( !($avatar_fsock = @fsockopen($avatar_url_ary[2], $port, $errno, $errstr)) )
						{
							$error = true;
							$sig_error_list .= '<br />' . $lang['No_connection_URL'] . ': ' . '<span style="color: #800000">' . $avatar_url . '"</span>';
						} 
						else
						{
							@fputs($avatar_fsock, "GET $base_get HTTP/1.1\r\n");
							@fputs($avatar_fsock, "HOST: " . $avatar_url_ary[2] . "\r\n");
							@fputs($avatar_fsock, "Connection: close\r\n\r\n");

							while( !@feof($avatar_fsock) )
							{
								$avatar_data .= @fread($avatar_fsock, 1024);
							}
							@fclose($avatar_fsock);		
	
							if ( preg_match('#Content-Length\: ([0-9]+)[^ /][\s]+#i', $avatar_data, $avatar_file_data) )
							{
								$avatar_file_size = $avatar_file_data[1]; 
							} 
							else
							{
								$avatar_file_size = strlen($avatar_data)-307; 
							}
						}
					}
				}
			}
		}		

		if( round(($total_image_files_size+$avatar_file_size)/1024, 2) > $board_config['sig_max_img_av_files_size'] )
		{
			$error = TRUE;
			$sig_error_list .= '<br />' . sprintf($lang['sig_error_img_av_files_size'], round($total_image_files_size/1024, 2), round($avatar_file_size/1024, 2), $board_config['sig_max_img_av_files_size']);
			$user_avatar_local = '';
		}
	} 
	else
	{
		if( $board_config['sig_max_img_files_size'] != 0 && (round($total_image_files_size/1024, 2) > $board_config['sig_max_img_files_size']) )
		{
			$error = TRUE;
			$sig_error_list .= '<br />' . sprintf($lang['sig_error_img_files_size'], round($total_image_files_size/1024, 2), $board_config['sig_max_img_files_size']);
		}
	}

	if ( $error == TRUE && $sig_error_list )
	{
		$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $lang['sig_error'] . '<br />' . $sig_error_list;
	}
	// End add - Signatures control MOD
	$avatar_sql = '';

	if ( isset($HTTP_POST_VARS['avatardel']) && $mode == 'editprofile' )
	{
		$avatar_sql = user_avatar_delete($userdata['user_avatar_type'], $userdata['user_avatar']);
	}
	else
	if ( ( !empty($user_avatar_upload) || !empty($user_avatar_name) ) && $board_config['allow_avatar_upload'] )
	{
		if ( !empty($user_avatar_upload) )
		{
			$avatar_mode = (empty($user_avatar_name)) ? 'remote' : 'local';
			$avatar_sql = user_avatar_upload($mode, $avatar_mode, $userdata['user_avatar'], $userdata['user_avatar_type'], $error, $error_msg, $user_avatar_upload, $user_avatar_name, $user_avatar_size, $user_avatar_filetype);
			$allowavatar = ( $board_config['disable_avatar_approve'] == TRUE || $userdata['user_level'] == ADMIN ) ? 1 : 0;
		}
		else if ( !empty($user_avatar_name) )
		{
			$l_avatar_size = sprintf($lang['Avatar_filesize'], round($board_config['avatar_filesize'] / 1024));

			$error = true;
			$error_msg .= ( ( !empty($error_msg) ) ? '<br />' : '' ) . $l_avatar_size;
		}
	}
	else if ( $user_avatar_remoteurl != '' && $board_config['allow_avatar_remote'] )
	{
		if ( @file_exists(@phpbb_realpath('./' . $board_config['avatar_path'] . '/' . $userdata['user_avatar'])) )
		{
			@unlink(@phpbb_realpath('./' . $board_config['avatar_path'] . '/' . $userdata['user_avatar']));
		}
		$avatar_sql = user_avatar_url($mode, $error, $error_msg, $user_avatar_remoteurl);
		$allowavatar = ( $board_config['disable_avatar_approve'] == TRUE || $userdata['user_level'] == ADMIN ) ? 1 : 0;
	}
	else if ( $user_avatar_local != '' && $board_config['allow_avatar_local'] )
	{
		if ( @file_exists(@phpbb_realpath('./' . $board_config['avatar_path'] . '/' . $userdata['user_avatar'])) )
		{
			@unlink(@phpbb_realpath('./' . $board_config['avatar_path'] . '/' . $userdata['user_avatar']));
		}
		$avatar_sql = user_avatar_gallery($mode, $error, $error_msg, $user_avatar_local);
		$allowavatar = 1;

	}
	else
	{
	
		$allowavatar = $userdata['user_allowavatar'];
	}

	if ( $allowavatar == 0 && !$error )
	{
		include($phpbb_root_path . 'includes/emailer.'.$phpEx);

		$sql_mail = "SELECT username, user_email, user_lang
			FROM " . USERS_TABLE . "
			WHERE user_level = 1;";
		if ( !$result = $db->sql_query($sql_mail) )
		{
			message_die(GENERAL_ERROR, "Couldn't notify admins", "", __LINE__, __FILE__, $sql);
		}
		while ( $row = $db->sql_fetchrow($result) )
		{
			$emailer = new emailer($board_config['smtp_delivery']);

			$emailer->from($board_config['board_email']);
			$emailer->replyto($board_config['board_email']);
			$emailer->set_subject($lang['New_avatar_activation']);

			$emailer->use_template('avatar_approve', stripslashes($row['user_lang']));
			$emailer->email_address($row['user_email']);

			$emailer->assign_vars(array(
				'USERNAME' => $row['username'],
				'POSTER_USERNAME' => $userdata['username'],
				'U_PROFILE_LINK' => $server_url . '?mode=viewprofile&u=' . $userdata['user_id'],
				'U_ADMIN_LINK' => str_replace("profile.$phpEx", "admin/admin_users.$phpEx", $server_url) . '?mode=edit&u=' . $userdata['user_id'] . '#approve_avatar',
				'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '')
			);
			$emailer->send();
			$emailer->reset();
		}
	}
//-- mod : birthday ------------------------------------------------------------
//-- add
	$bday_required = (int)substr($config->data['birthday_settings'], 0, 1);
	if ( (empty($bday_day) || empty($bday_month) || empty($bday_year)) && $bday_required )
	{
		$error = true;
		$error_msg .= ( ( isset($error_msg) ) ? '<br />' : '' ) . $user->lang('bday_required');
	}
//-- fin mod : birthday --------------------------------------------------------

	if ( !$error )
	{
		if ( $avatar_sql == '' )
		{
			$avatar_sql = ( $mode == 'editprofile' ) ? '' : "'', " . USER_AVATAR_NONE;
		}

		if ( $mode == 'editprofile' )
		{
			if ( $email != $userdata['user_email'] && $board_config['require_activation'] != USER_ACTIVATION_NONE && $userdata['user_level'] != ADMIN )
			{
				$user_active = 0;

				$user_actkey = gen_rand_string(true);
				$key_len = 54 - ( strlen($server_url) );
				$key_len = ( $key_len > 6 ) ? $key_len : 6;
				$user_actkey = substr($user_actkey, 0, $key_len);

				if ( $userdata['session_logged_in'] )
				{
					session_end($userdata['session_id'], $userdata['user_id']);
				}
			}
			else
			{
				$user_active = 'user_active'; 
				$user_actkey = 'user_actkey'; 
			}


//-- mod : birthday ------------------------------------------------------------
//-- add
			$bday_data = array($bday_day, $bday_month, $bday_year);
			$user_birthday = ($bday_day && $bday_month && $bday_year) ? implode('-', $bday_data) : '';
// here we added
//	, user_birthday = '" . $user_birthday . "'
//-- modify
//-- mod : quick post es -------------------------------------------------------
//-- add
			$qp_data = array($user_qp, $user_qp_show, $user_qp_subject, $user_qp_bbcode, $user_qp_smilies, $user_qp_more);
			$user_qp_settings = implode('-', $qp_data);
// here we added
//	, user_qp_settings = '" . $user_qp_settings . "'
//-- modify
			// Start add - Signatures control MOD
			if ( $board_config['allow_sig'] && $userdata['user_allowsignature'] != 0 )
			{
				$sig_update = "user_sig = '" . str_replace("\'", "''", $signature) . "', user_sig_bbcode_uid = '$signature_bbcode_uid',";
				$attachsig_update = "user_attachsig = $attachsig,";
			} 
			else
			{
				$sig_update = "";
				$attachsig_update = "";
			}
			// End add - Signatures control MOD
			$sql = "UPDATE " . USERS_TABLE . "
				SET " . $username_sql . $passwd_sql . "user_email = '" . str_replace("\'", "''", $email) ."', user_icq = '" . str_replace("\'", "''", $icq) . "', user_website = '" . str_replace("\'", "''", $website) . "', user_occ = '" . str_replace("\'", "''", $occupation) . "', user_from = '" . str_replace("\'", "''", $location) . "', user_interests = '" . str_replace("\'", "''", $interests) . "', user_birthday = '" . $user_birthday . "', " . $sig_update . " user_viewemail = $viewemail, user_aim = '" . str_replace("\'", "''", str_replace(' ', '+', $aim)) . "', user_yim = '" . str_replace("\'", "''", $yim) . "', user_msnm = '" . str_replace("\'", "''", $msn) . "', " . $attachsig_update . " user_qp_settings = '" . $user_qp_settings . "', user_colortext = '$user_colortext', user_allowsmile = $allowsmilies, user_allowhtml = $allowhtml, user_allowbbcode = $allowbbcode, user_allow_viewonline = $allowviewonline, user_notify = $notifyreply, user_notify_pm = $notifypm, user_popup_pm = $popup_pm, user_timezone = $user_timezone, user_dateformat = '" . str_replace("\'", "''", $user_dateformat) . "', user_lang = '" . str_replace("\'", "''", $user_lang) . "', user_style = $user_style, user_active = $user_active, user_actkey = '$user_actkey'" . $avatar_sql . ", user_allowavatar = $allowavatar
				WHERE user_id = $user_id";
//-- fin mod : quick post es ---------------------------------------------------
//-- fin mod : birthday --------------------------------------------------------
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not update users table', '', __LINE__, __FILE__, $sql);
			}
			// We remove all stored login keys since the password has been updated
			// and change the current one (if applicable)
			if ( !empty($passwd_sql) )
			{
				session_reset_keys($user_id, $user_ip);
			}



			if ( !$user_active )
			{
				//
				// The users account has been deactivated, send them an email with a new activation key
				//
				include($phpbb_root_path . 'includes/emailer.'.$phpEx);
				$emailer = new emailer($board_config['smtp_delivery']);

 				if ( $board_config['require_activation'] != USER_ACTIVATION_ADMIN )
 				{
 					$emailer->from($board_config['board_email']);
 					$emailer->replyto($board_config['board_email']);
 
 					$emailer->use_template('user_activate', stripslashes($user_lang));
 					$emailer->email_address($email);
 					$emailer->set_subject($lang['Reactivate']);
  
 					$emailer->assign_vars(array(
 						'SITENAME' => $board_config['sitename'],
 						'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
 						'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '',
  
 						'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey)
 					);
 					$emailer->send();
 					$emailer->reset();
 				}
 				else if ( $board_config['require_activation'] == USER_ACTIVATION_ADMIN )
 				{
 					$sql = 'SELECT user_email, user_lang 
 						FROM ' . USERS_TABLE . '
 						WHERE user_level = ' . ADMIN;
 					
 					if ( !($result = $db->sql_query($sql)) )
 					{
 						message_die(GENERAL_ERROR, 'Could not select Administrators', '', __LINE__, __FILE__, $sql);
 					}
 					
 					while ($row = $db->sql_fetchrow($result))
 					{
 						$emailer->from($board_config['board_email']);
 						$emailer->replyto($board_config['board_email']);
 						
 						$emailer->email_address(trim($row['user_email']));
 						$emailer->use_template("admin_activate", $row['user_lang']);
 						$emailer->set_subject($lang['Reactivate']);
 
 						$emailer->assign_vars(array(
 							'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
 							'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']),
 
 							'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey)
 						);
 						$emailer->send();
 						$emailer->reset();
 					}
 					$db->sql_freeresult($result);
 				}

				$message = $lang['Profile_updated_inactive'] . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
			}
			else
			{
				$message = (($allowavatar == 0) ? $lang['Profile_updated_avatar'] : $lang['Profile_updated']) . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid("index.$phpEx") . '">', '</a>');
			}

			$template->assign_vars(array(
				"META" => '<meta http-equiv="refresh" content="5;url=' . append_sid("index.$phpEx") . '">')
			);

			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$sql = "SELECT MAX(user_id) AS total
				FROM " . USERS_TABLE;
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
			}

			if ( !($row = $db->sql_fetchrow($result)) )
			{
				message_die(GENERAL_ERROR, 'Could not obtain next user_id information', '', __LINE__, __FILE__, $sql);
			}
			$user_id = $row['total'] + 1;

			// CBACK CrackerTracker Register Flood Protection
            $stime = time() + $ctracker_config['regtime'];
            $sql = "UPDATE " . CTRACK . " SET value = " . $stime . " WHERE name = 'lastreg'";
    	    $db->sql_query($sql);

            if(!empty($HTTP_SERVER_VARS['REMOTE_ADDR']))
            {
	          $sql = "UPDATE " . CTRACK . " SET value = '" . $HTTP_SERVER_VARS['REMOTE_ADDR'] . "' WHERE name = 'lastreg_ip'";
              $db->sql_query($sql);
            }
            // END CBACK CrackerTracker Register Flood Protection

			//
			// Get current date
			//
//-- mod : birthday ------------------------------------------------------------
//-- add
			$bday_data = array($bday_day, $bday_month, $bday_year);
			$user_birthday = ($bday_day && $bday_month && $bday_year) ? implode('-', $bday_data) : '';
// here we added
//	, user_birthday
//	, '" . $user_birthday . "'
//-- modify
//-- mod : quick post es -------------------------------------------------------
//-- add
			$qp_data = array($user_qp, $user_qp_show, $user_qp_subject, $user_qp_bbcode, $user_qp_smilies, $user_qp_more);
			$user_qp_settings = implode('-', $qp_data);
// here we added
//	, user_qp_settings
//	, '" . $user_qp_settings . "'
//-- modify
			$sql = "INSERT INTO " . USERS_TABLE . "	(user_id, username, user_regdate, user_regip, user_password, user_email, user_icq, user_website, user_occ, user_from, user_interests, user_birthday, user_sig, user_qp_settings, user_sig_bbcode_uid, user_avatar, user_avatar_type, user_viewemail, user_aim, user_yim, user_msnm, user_attachsig, user_colortext, user_allowsmile, user_allowhtml, user_allowbbcode, user_allow_viewonline, user_notify, user_notify_pm, user_popup_pm, user_timezone, user_dateformat, user_lang, user_style, user_level, user_allow_pm, user_active, user_actkey)
				VALUES ($user_id, '" . str_replace("\'", "''", $username) . "', " . time() . ", '" . $userdata['session_ip'] . "', '" . str_replace("\'", "''", $new_password) . "', '" . str_replace("\'", "''", $email) . "', '" . str_replace("\'", "''", $icq) . "', '" . str_replace("\'", "''", $website) . "', '" . str_replace("\'", "''", $occupation) . "', '" . str_replace("\'", "''", $location) . "', '" . str_replace("\'", "''", $interests) . "', '" . $user_birthday . "', '" . str_replace("\'", "''", $signature) . "', '" . $user_qp_settings . "', '$signature_bbcode_uid', $avatar_sql, $viewemail, '" . str_replace("\'", "''", str_replace(' ', '+', $aim)) . "', '" . str_replace("\'", "''", $yim) . "', '" . str_replace("\'", "''", $msn) . "', $attachsig, '" . str_replace("\'", "''", $user_colortext) . "', $allowsmilies, $allowhtml, $allowbbcode, $allowviewonline, $notifyreply, $notifypm, $popup_pm, $user_timezone, '" . str_replace("\'", "''", $user_dateformat) . "', '" . str_replace("\'", "''", $user_lang) . "', $user_style, 0, 1, ";
//-- fin mod : quick post es ---------------------------------------------------
//-- fin mod : birthday --------------------------------------------------------
			if ( $board_config['require_activation'] == USER_ACTIVATION_SELF || $board_config['require_activation'] == USER_ACTIVATION_ADMIN || $coppa )
			{
				$user_actkey = gen_rand_string(true);
				$key_len = 54 - (strlen($server_url));
				$key_len = ( $key_len > 6 ) ? $key_len : 6;
				$user_actkey = substr($user_actkey, 0, $key_len);
				$sql .= "0, '" . str_replace("\'", "''", $user_actkey) . "')";
			}
			else
			{
				$sql .= "1, '')";
			}

			if ( !($result = $db->sql_query($sql, BEGIN_TRANSACTION)) )
			{
				message_die(GENERAL_ERROR, 'Could not insert data into users table', '', __LINE__, __FILE__, $sql);
			}

			$sql = "INSERT INTO " . GROUPS_TABLE . " (group_name, group_description, group_single_user, group_moderator)
				VALUES ('', 'Personal User', 1, 0)";
			if ( !($result = $db->sql_query($sql)) )
			{
				message_die(GENERAL_ERROR, 'Could not insert data into groups table', '', __LINE__, __FILE__, $sql);
			}

			$group_id = $db->sql_nextid();

			$sql = "INSERT INTO " . USER_GROUP_TABLE . " (user_id, group_id, user_pending)
				VALUES ($user_id, $group_id, 0)";
			if( !($result = $db->sql_query($sql, END_TRANSACTION)) )
			{
				message_die(GENERAL_ERROR, 'Could not insert data into user_group table', '', __LINE__, __FILE__, $sql);
			}

			if ( $coppa )
			{
				$message = $lang['COPPA'];
				$email_template = 'coppa_welcome_inactive';
			}
			else if ( $board_config['require_activation'] == USER_ACTIVATION_SELF )
			{
				$message = $lang['Account_inactive'];
				$email_template = 'user_welcome_inactive';
			}
			else if ( $board_config['require_activation'] == USER_ACTIVATION_ADMIN )
			{
				$message = $lang['Account_inactive_admin'];
				$email_template = 'admin_welcome_inactive';
			}
			else
			{
				$message = $lang['Account_added'];
				$email_template = 'user_welcome';
			}

			include($phpbb_root_path . 'includes/emailer.'.$phpEx);
			$emailer = new emailer($board_config['smtp_delivery']);

			$emailer->from($board_config['board_email']);
			$emailer->replyto($board_config['board_email']);

			$emailer->use_template($email_template, stripslashes($user_lang));
			$emailer->email_address($email);
			$emailer->set_subject(sprintf($lang['Welcome_subject'], $board_config['sitename']));

			if( $coppa )
			{
				$emailer->assign_vars(array(
					'SITENAME' => $board_config['sitename'],
					'WELCOME_MSG' => sprintf($lang['Welcome_subject'], $board_config['sitename']),
					'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
					'PASSWORD' => $password_confirm,
					'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']),

					'FAX_INFO' => $board_config['coppa_fax'],
					'MAIL_INFO' => $board_config['coppa_mail'],
					'EMAIL_ADDRESS' => $email,
					'ICQ' => $icq,
					'AIM' => $aim,
					'YIM' => $yim,
					'MSN' => $msn,
					'WEB_SITE' => $website,
					'FROM' => $location,
					'OCC' => $occupation,
					'INTERESTS' => $interests,
					'SITENAME' => $board_config['sitename']));
			}
			else
			{
				$emailer->assign_vars(array(
					'SITENAME' => $board_config['sitename'],
					'WELCOME_MSG' => sprintf($lang['Welcome_subject'], $board_config['sitename']),
					'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
					'PASSWORD' => $password_confirm,
					'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']),

					'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey)
				);
			}

			$emailer->send();
			$emailer->reset();

			if ( $board_config['require_activation'] == USER_ACTIVATION_ADMIN )
			{
				$sql = "SELECT user_email, user_lang 
					FROM " . USERS_TABLE . "
					WHERE user_level = " . ADMIN;
				
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not select Administrators', '', __LINE__, __FILE__, $sql);
				}
				
				while ($row = $db->sql_fetchrow($result))
				{
					$emailer->from($board_config['board_email']);
					$emailer->replyto($board_config['board_email']);
					
					$emailer->email_address(trim($row['user_email']));
					$emailer->use_template("admin_activate", $row['user_lang']);
					$emailer->set_subject($lang['New_account_subject']);

					$emailer->assign_vars(array(
						'USERNAME' => preg_replace($unhtml_specialchars_match, $unhtml_specialchars_replace, substr(str_replace("\'", "'", $username), 0, 25)),
						'EMAIL_SIG' => str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']),

						'U_ACTIVATE' => $server_url . '?mode=activate&' . POST_USERS_URL . '=' . $user_id . '&act_key=' . $user_actkey)
					);
					$emailer->send();
					$emailer->reset();
				}
				$db->sql_freeresult($result);
			}

			$message = $message . '<br /><br />' . sprintf($lang['Click_return_index'],  '<a href="' . append_sid("index.$phpEx") . '">', '</a>');

			message_die(GENERAL_MESSAGE, $message);
		} // if mode == register
	}
} // End of submit


if ( $error )
{
	//
	// If an error occured we need to stripslashes on returned data
	//
	$username = stripslashes($username);
	$email = stripslashes($email);
	$cur_password = '';

	$new_password = '';
	$password_confirm = '';

	$icq = stripslashes($icq);
	$aim = str_replace('+', ' ', stripslashes($aim));
	$msn = stripslashes($msn);
	$yim = stripslashes($yim);

	$website = stripslashes($website);
	$location = stripslashes($location);
	$occupation = stripslashes($occupation);
	$interests = stripslashes($interests);
	$signature = stripslashes($signature);
	$signature = ($signature_bbcode_uid != '') ? preg_replace("/:(([a-z0-9]+:)?)$signature_bbcode_uid(=|\])/si", '\\3', $signature) : $signature;
	$user_colortext = stripslashes($user_colortext);

	$user_lang = stripslashes($user_lang);
	$user_dateformat = stripslashes($user_dateformat);

}
else if ( $mode == 'editprofile' && !isset($HTTP_POST_VARS['avatargallery']) && !isset($HTTP_POST_VARS['submitavatar']) && !isset($HTTP_POST_VARS['cancelavatar']) )
{
	$user_id = $userdata['user_id'];
	$username = $userdata['username'];
	$email = $userdata['user_email'];
	$cur_password = '';

	$new_password = '';
	$password_confirm = '';

	$icq = $userdata['user_icq'];
	$aim = str_replace('+', ' ', $userdata['user_aim']);
	$msn = $userdata['user_msnm'];
	$yim = $userdata['user_yim'];

	$website = $userdata['user_website'];
	$location = $userdata['user_from'];
	$occupation = $userdata['user_occ'];
	$interests = $userdata['user_interests'];
	$signature_bbcode_uid = $userdata['user_sig_bbcode_uid'];
	$signature = ($signature_bbcode_uid != '') ? preg_replace("/:(([a-z0-9]+:)?)$signature_bbcode_uid(=|\])/si", '\\3', $userdata['user_sig']) : $userdata['user_sig'];
	$user_colortext = $userdata['user_colortext'];

	$viewemail = $userdata['user_viewemail'];
	$notifypm = $userdata['user_notify_pm'];
	$popup_pm = $userdata['user_popup_pm'];
	$notifyreply = $userdata['user_notify'];
	$attachsig = $userdata['user_attachsig'];
//-- mod : quick post es -------------------------------------------------------
//-- add
	$user_qp = $user_qp_show = $user_qp_subject = $user_qp_bbcode = $user_qp_smilies = $user_qp_more = 0;
	if (!empty($userdata['user_qp_settings']))
	{
		list($user_qp, $user_qp_show, $user_qp_subject, $user_qp_bbcode, $user_qp_smilies, $user_qp_more) = explode('-', $userdata['user_qp_settings']);
	}
//-- fin mod : quick post es ---------------------------------------------------
//-- mod : birthday ------------------------------------------------------------
//-- add
	$bday_day = $bday_month = $bday_year = 0;
	if (!empty($userdata['user_birthday']))
	{
		list($bday_day, $bday_month, $bday_year) = explode('-', $userdata['user_birthday']);
	}
//-- fin mod : birthday --------------------------------------------------------
	$allowhtml = $userdata['user_allowhtml'];
	$allowbbcode = $userdata['user_allowbbcode'];
	$allowsmilies = $userdata['user_allowsmile'];
	$allowviewonline = $userdata['user_allow_viewonline'];

	$user_avatar = $userdata['user_avatar'];
	$user_avatar_type = $userdata['user_avatar_type'];

	$user_style = $userdata['user_style'];
	$user_lang = $userdata['user_lang'];
	$user_timezone = $userdata['user_timezone'];
	$user_dateformat = $userdata['user_dateformat'];
}

//
// Default pages
//
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

make_jumpbox('viewforum.'.$phpEx);

if ( $mode == 'editprofile' )
{
	if ( $user_id != $userdata['user_id'] )
	{
		$error = TRUE;
		$error_msg = $lang['Wrong_Profile'];
	}
}

if( isset($HTTP_POST_VARS['avatargallery']) && !$error )
{
	include($phpbb_root_path . 'includes/usercp_avatar.'.$phpEx);

	$avatar_category = ( !empty($HTTP_POST_VARS['avatarcategory']) ) ? htmlspecialchars($HTTP_POST_VARS['avatarcategory']) : '';

	$template->set_filenames(array(
		'body' => 'profile_avatar_gallery.tpl')
	);

	$allowviewonline = !$allowviewonline;

//-- mod : birthday ------------------------------------------------------------
//-- add
	$bday_data = array($bday_day, $bday_month, $bday_year);
	$bday_date = implode('-', $bday_data);
// here we added
//	, $bday_date
//-- modify
//-- mod : quick post es -------------------------------------------------------
//-- add
	$qp_data = array($user_qp, $user_qp_show, $user_qp_subject, $user_qp_bbcode, $user_qp_smilies, $user_qp_more);
	$user_qp_settings = implode('-', $qp_data);
// here we added
//	, $user_qp_settings
//-- modify
	display_avatar_gallery($mode, $avatar_category, $user_id, $email, $current_email, $coppa, $username, $email, $new_password, $cur_password, $password_confirm, $icq, $aim, $msn, $yim, $website, $location, $occupation, $interests, $bday_date, $signature, $viewemail, $notifypm, $popup_pm, $notifyreply, $attachsig, $user_colortext, $allowhtml, $allowbbcode, $allowsmilies, $allowviewonline, $user_style, $user_lang, $user_timezone, $user_dateformat, $userdata['session_id'], $user_qp_settings);
//-- fin mod : quick post es ---------------------------------------------------
//-- fin mod : birthday --------------------------------------------------------
}
else
{
	include($phpbb_root_path . 'includes/functions_selects.'.$phpEx);

	if ( !isset($coppa) )
	{
		$coppa = FALSE;
	}

	if ( !isset($user_style) )
	{
		$user_style = $board_config['default_style'];
	}

	$avatar_img = '';
	if ( $user_avatar_type )
	{
		switch( $user_avatar_type )
		{
			case USER_AVATAR_UPLOAD:
				$avatar_img = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $user_avatar . '" alt="" />' : '';
				break;
			case USER_AVATAR_REMOTE:
				$avatar_img = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $user_avatar . '" alt="" />' : '';
				break;
			case USER_AVATAR_GALLERY:
				$avatar_img = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $user_avatar . '" alt="" />' : '';
				break;
		}
	}

	$s_hidden_fields = '<input type="hidden" name="mode" value="' . $mode . '" /><input type="hidden" name="agreed" value="true" /><input type="hidden" name="coppa" value="' . $coppa . '" />';
	if( $mode == 'editprofile' )
	{
		$s_hidden_fields .= '<input type="hidden" name="user_id" value="' . $userdata['user_id'] . '" />';
		//
		// Send the users current email address. If they change it, and account activation is turned on
		// the user account will be disabled and the user will have to reactivate their account.
		//
		$s_hidden_fields .= '<input type="hidden" name="current_email" value="' . $userdata['user_email'] . '" />';
	}

	if ( !empty($user_avatar_local) )
	{
		$s_hidden_fields .= '<input type="hidden" name="avatarlocal" value="' . $user_avatar_local . '" />';
	}

	$html_status =  ( $userdata['user_allowhtml'] && $board_config['allow_html'] ) ? $lang['HTML_is_ON'] : $lang['HTML_is_OFF'];
	// Start replacement - Signatures control MOD
	if ( $board_config['sig_max_lines'] )
	{
		$sig_explain_max_lines = sprintf($lang['sig_explain_max_lines'], $board_config['sig_max_lines']);
	} 
	else
	{
		$sig_explain_max_lines = '';
	}

	if ( $board_config['sig_allow_font_sizes'] == 2 && !(!$board_config['sig_min_font_size'] && !$board_config['sig_max_font_size']) )
	{
		if ( $board_config['sig_min_font_size'] )
		{
			if ( $board_config['sig_max_font_size'] )
			{
				$sig_explain_font_size_limit = sprintf($lang['sig_explain_font_size_limit'], $board_config['sig_min_font_size'], $board_config['sig_max_font_size']);
			} 
			else
			{
				$sig_explain_font_size_limit = sprintf($lang['sig_explain_font_size_limit'], $board_config['sig_min_font_size'], '29');
			}
		} 
		else
		{
			$sig_explain_font_size_limit = sprintf($lang['sig_explain_font_size_max'], $board_config['sig_max_font_size']);
		}
	} 
	else
	{
		$sig_explain_font_size_limit = '';
	}

	if ( $board_config['sig_allow_images'] )
	{
		if ( $board_config['sig_max_images'] )
		{
			$sig_explain_images_limit = sprintf($lang['sig_explain_images_limit'], $board_config['sig_max_images'], ( $board_config['sig_max_img_height'] ) ? $board_config['sig_max_img_height'] : $lang['sig_explain_unlimited'], ( $board_config['sig_max_img_width'] ) ? $board_config['sig_max_img_width'] : $lang['sig_explain_unlimited'], ( $board_config['sig_max_img_av_files_size'] ) ? $board_config['sig_max_img_av_files_size'] : $board_config['sig_max_img_files_size']);
		} 
		else
		{
			$sig_explain_images_limit = sprintf($lang['sig_explain_unlimited_images'] , ( $board_config['sig_max_img_height'] ) ? $board_config['sig_max_img_height'] : $lang['sig_explain_unlimited'], ( $board_config['sig_max_img_width'] ) ? $board_config['sig_max_img_width'] : $lang['sig_explain_unlimited'], ( $board_config['sig_max_img_av_files_size'] ) ? $board_config['sig_max_img_av_files_size'] : $board_config['sig_max_img_files_size']);
		}	
	} 
	else
	{
		$sig_explain_images_limit = $lang['sig_explain_no_image'];
	}

	if ( $board_config['sig_max_img_av_files_size'] )
	{
		$sig_explain_images_limit .= $lang['sig_explain_avatar_included'];
	}

	$signature_explain = $lang['sig_explain'];

	if ( $userdata['user_allowsignature'] != 2 )
	{
		$signature_explain .= ' ' . sprintf($lang['sig_explain_limits'], $board_config['max_sig_chars'], $sig_explain_font_size_limit, $sig_explain_max_lines, $sig_explain_images_limit);

		if ( $board_config['sig_wordwrap'] )
		{
			$signature_explain .= ' ' . sprintf($lang['sig_explain_wordwrap'], $board_config['sig_wordwrap']);
		}
	}

	if ( $userdata['user_allowbbcode'] && $board_config['allow_bbcode']  )
	{
		if (($board_config['sig_allow_font_sizes'] && $board_config['sig_allow_bold'] && $board_config['sig_allow_italic'] && $board_config['sig_allow_underline'] && $board_config['sig_allow_colors'] && $board_config['sig_allow_quote'] && $board_config['sig_allow_code'] && $board_config['sig_allow_list'] && $board_config['sig_allow_url'] && $board_config['sig_allow_images'] && $board_config['sig_exotic_bbcodes_disallowed']=='') || $userdata['user_allowsignature'] == 2)
		{
			$lang['sig_bbcodes_off'] .= $lang['sig_none'];
			$lang['sig_bbcodes_on'] .= $lang['sig_all'];
			$bbcode_status = sprintf($lang['sig_bbcodes_on'], '<a href="' . append_sid("faq.$phpEx?mode=bbcode") . '" target="_phpbbcode">', '</a>') . '<br />' . sprintf($lang['sig_bbcodes_off'], '', '');
		} 
		else
		{
			$lang['sig_bbcodes_off'] .= '<span style="color: #800000">';
			$lang['sig_bbcodes_off'] .= ( !$board_config['sig_allow_font_sizes'] ) ? '[size]' : '';
			$lang['sig_bbcodes_off'] .= ( !$board_config['sig_allow_bold'] ) ? '[b]' : '';
			$lang['sig_bbcodes_off'] .= ( !$board_config['sig_allow_italic'] ) ? '[i]' : '';
			$lang['sig_bbcodes_off'] .= ( !$board_config['sig_allow_underline'] ) ? '[u]' : '';
			$lang['sig_bbcodes_off'] .= ( !$board_config['sig_allow_colors'] ) ? '[color]' : '';
			$lang['sig_bbcodes_off'] .= ( !$board_config['sig_allow_quote'] ) ? '[quote]' : '';
			$lang['sig_bbcodes_off'] .= ( !$board_config['sig_allow_code'] ) ? '[code]' : '';
			$lang['sig_bbcodes_off'] .= ( !$board_config['sig_allow_list'] ) ? '[list]' : '';
			$lang['sig_bbcodes_off'] .= ( !$board_config['sig_allow_url'] ) ? '[url]' : '';
			$lang['sig_bbcodes_off'] .= ( !$board_config['sig_allow_images'] ) ? '[img]' : '';

			$exotic_bbcodes_list = explode(",", $board_config['sig_exotic_bbcodes_disallowed']);
			while ( list($bbckey, $exotic_bbcode) = @each($exotic_bbcodes_list) )
			{
				$exotic_bbcode = trim(strtolower($exotic_bbcode));
				if ( $exotic_bbcode != '' )
				{
					$lang['sig_bbcodes_off'] .= '['.$exotic_bbcode.']';
				}
			}

			$lang['sig_bbcodes_off'] .= '</span>';
			$bbcode_status = sprintf($lang['sig_bbcodes_off'], '<a href="' . append_sid("faq.$phpEx?mode=bbcode") . '" target="_phpbbcode">', '</a>');
		}
	} 
	else
	{
		$bbcode_status = $lang['sig_BBCodes_are_OFF'];
	}

	if ( $board_config['allow_sig'] && $userdata['user_allowsignature'] )
	{
		$template->assign_block_vars('switch_signature_allowed', array());
	}
	// End replacement - Signatures control MOD
	$smilies_status = ( $userdata['user_allowsmile'] && $board_config['allow_smilies'] && $board_config['sig_allow_smilies']  ) ? $lang['Smilies_are_ON'] : $lang['Smilies_are_OFF'];

	if ( $error )
	{
		$template->set_filenames(array(
			'reg_header' => 'error_body.tpl')
		);
		$template->assign_vars(array(
			'ERROR_MESSAGE' => $error_msg)
		);
		$template->assign_var_from_handle('ERROR_BOX', 'reg_header');
	}

	$template->set_filenames(array(
		'body' => 'profile_add_body.tpl')
	);

	if ( $mode == 'editprofile' )
	{
		$template->assign_block_vars('switch_edit_profile', array());
	}

	if ( ($mode == 'register') || ($board_config['allow_namechange']) )
	{
		$template->assign_block_vars('switch_namechange_allowed', array());
	}
	else
	{
		$template->assign_block_vars('switch_namechange_disallowed', array());
	}


	// Visual Confirmation
	$confirm_image = '';
	if (!empty($board_config['enable_confirm']) && $mode == 'register')
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
		
			$sql = 'DELETE FROM ' .  CONFIRM_TABLE . " 
				WHERE session_id NOT IN ($confirm_sql)";
			if (!$db->sql_query($sql))
			{
				message_die(GENERAL_ERROR, 'Could not delete stale confirm data', '', __LINE__, __FILE__, $sql);
			}
		}
		$db->sql_freeresult($result);

		$sql = 'SELECT COUNT(session_id) AS attempts 
			FROM ' . CONFIRM_TABLE . " 
			WHERE session_id = '" . $userdata['session_id'] . "'";
		if (!($result = $db->sql_query($sql)))
		{
			message_die(GENERAL_ERROR, 'Could not obtain confirm code count', '', __LINE__, __FILE__, $sql);
		}

		if ($row = $db->sql_fetchrow($result))
		{
			if ($row['attempts'] > 3)
			{
				message_die(GENERAL_MESSAGE, $lang['Too_many_registers']);
			}
		}
		$db->sql_freeresult($result);
		
		// Generate the required confirmation code
		// NB 0 (zero) could get confused with O (the letter) so we make change it
		$code = dss_rand();
		$code = strtoupper(str_replace('0', 'o', substr($code, 6)));


		$confirm_id = md5(uniqid($user_ip));

		$sql = 'INSERT INTO ' . CONFIRM_TABLE . " (confirm_id, session_id, code) 
			VALUES ('$confirm_id', '". $userdata['session_id'] . "', '$code')";
		if (!$db->sql_query($sql))
		{
			message_die(GENERAL_ERROR, 'Could not insert new confirm code information', '', __LINE__, __FILE__, $sql);
		}

		unset($code);
		
		$confirm_image = (@extension_loaded('zlib')) ? '<img src="' . append_sid("profile.$phpEx?mode=confirm&amp;id=$confirm_id") . '" alt="" title="" />' : '<img src="' . append_sid("profile.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=1") . '" alt="" title="" /><img src="' . append_sid("profile.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=2") . '" alt="" title="" /><img src="' . append_sid("profile.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=3") . '" alt="" title="" /><img src="' . append_sid("profile.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=4") . '" alt="" title="" /><img src="' . append_sid("profile.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=5") . '" alt="" title="" /><img src="' . append_sid("profile.$phpEx?mode=confirm&amp;id=$confirm_id&amp;c=6") . '" alt="" title="" />';
		$s_hidden_fields .= '<input type="hidden" name="confirm_id" value="' . $confirm_id . '" />';

		$template->assign_block_vars('switch_confirm', array());
	}


if ( $board_config['allow_colortext'] )
	{
		$template->assign_block_vars('switch_colortext', array());
	}

	//
	// Let's do an overall check for settings/versions which would prevent
	// us from doing file uploads....
	//
	$ini_val = ( phpversion() >= '4.0.0' ) ? 'ini_get' : 'get_cfg_var';
	$form_enctype = ( @$ini_val('file_uploads') == '0' || strtolower(@$ini_val('file_uploads') == 'off') || phpversion() == '4.0.4pl1' || !$board_config['allow_avatar_upload'] || ( phpversion() < '4.0.3' && @$ini_val('open_basedir') != '' ) ) ? '' : 'enctype="multipart/form-data"';

	$template->assign_vars(array(
		'USERNAME' => isset($username) ? $username : '',
		'CUR_PASSWORD' => isset($cur_password) ? $cur_password : '',
		'NEW_PASSWORD' => isset($new_password) ? $new_password : '',
		'PASSWORD_CONFIRM' => isset($password_confirm) ? $password_confirm : '',
		'EMAIL' => isset($email) ? $email : '',

		'CONFIRM_IMG' => $confirm_image, 
		'YIM' => $yim,
		'ICQ' => $icq,
		'MSN' => $msn,
		'AIM' => $aim,
		'OCCUPATION' => $occupation,
		'INTERESTS' => $interests,
		'LOCATION' => $location,
		'WEBSITE' => $website,
		'SIGNATURE' => str_replace('<br />', "\n", $signature),
//-- mod : quick post es -------------------------------------------------------
//-- add
		'L_QP_SETTINGS' => $lang['qp_settings'],
//-- fin mod : quick post es ---------------------------------------------------
		'COLORTEXT' => $user_colortext,
		'VIEW_EMAIL_YES' => ( $viewemail ) ? 'checked="checked"' : '',
		'VIEW_EMAIL_NO' => ( !$viewemail ) ? 'checked="checked"' : '',
		'HIDE_USER_YES' => ( !$allowviewonline ) ? 'checked="checked"' : '',
		'HIDE_USER_NO' => ( $allowviewonline ) ? 'checked="checked"' : '',
		'NOTIFY_PM_YES' => ( $notifypm ) ? 'checked="checked"' : '',
		'NOTIFY_PM_NO' => ( !$notifypm ) ? 'checked="checked"' : '',
		'POPUP_PM_YES' => ( $popup_pm ) ? 'checked="checked"' : '',
		'POPUP_PM_NO' => ( !$popup_pm ) ? 'checked="checked"' : '',
		'ALWAYS_ADD_SIGNATURE_YES' => ( $attachsig ) ? 'checked="checked"' : '',
		'ALWAYS_ADD_SIGNATURE_NO' => ( !$attachsig ) ? 'checked="checked"' : '',
		'NOTIFY_REPLY_YES' => ( $notifyreply ) ? 'checked="checked"' : '',
		'NOTIFY_REPLY_NO' => ( !$notifyreply ) ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_BBCODE_YES' => ( $allowbbcode ) ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_BBCODE_NO' => ( !$allowbbcode ) ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_HTML_YES' => ( $allowhtml ) ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_HTML_NO' => ( !$allowhtml ) ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_SMILIES_YES' => ( $allowsmilies ) ? 'checked="checked"' : '',
		'ALWAYS_ALLOW_SMILIES_NO' => ( !$allowsmilies ) ? 'checked="checked"' : '',
		'ALLOW_AVATAR' => $board_config['allow_avatar_upload'],
		'AVATAR' => $avatar_img,
		'AVATAR_SIZE' => $board_config['avatar_filesize'],
		'LANGUAGE_SELECT' => language_select($user_lang, 'language'),
		'STYLE_SELECT' => style_select($user_style, 'style'),
		'TIMEZONE_SELECT' => tz_select($user_timezone, 'timezone'),
		'DATE_FORMAT' => $user_dateformat,
		'HTML_STATUS' => $html_status,
		// Start replacement - Signatures control MOD
		'BBCODE_STATUS' => $bbcode_status,
		// End replacement - Signatures control MOD
		'SMILIES_STATUS' => $smilies_status,

		'L_CURRENT_PASSWORD' => $lang['Current_password'],
		'L_NEW_PASSWORD' => ( $mode == 'register' ) ? $lang['Password'] : $lang['New_password'],
		'L_CONFIRM_PASSWORD' => $lang['Confirm_password'],
		'L_CONFIRM_PASSWORD_EXPLAIN' => ( $mode == 'editprofile' ) ? $lang['Confirm_password_explain'] : '',
		'L_PASSWORD_IF_CHANGED' => ( $mode == 'editprofile' ) ? $lang['password_if_changed'] : '',
		'L_PASSWORD_CONFIRM_IF_CHANGED' => ( $mode == 'editprofile' ) ? $lang['password_confirm_if_changed'] : '',
		'L_SUBMIT' => $lang['Submit'],
		'L_RESET' => $lang['Reset'],
		'L_ICQ_NUMBER' => $lang['ICQ'],
		'L_MESSENGER' => $lang['MSNM'],
		'L_YAHOO' => $lang['YIM'],
		'L_WEBSITE' => $lang['Website'],
		'L_AIM' => $lang['AIM'],
		'L_LOCATION' => $lang['Location'],
		'L_OCCUPATION' => $lang['Occupation'],
		'L_BOARD_LANGUAGE' => $lang['Board_lang'],
		'L_BOARD_STYLE' => $lang['Board_style'],
		'L_TIMEZONE' => $lang['Timezone'],
		'L_DATE_FORMAT' => $lang['Date_format'],
		'L_DATE_FORMAT_EXPLAIN' => $lang['Date_format_explain'],
		'L_YES' => $lang['Yes'],
		'L_NO' => $lang['No'],
		'L_INTERESTS' => $lang['Interests'],
		'L_ALWAYS_ALLOW_SMILIES' => $lang['Always_smile'],
		'L_ALWAYS_ALLOW_BBCODE' => $lang['Always_bbcode'],
		'L_ALWAYS_ALLOW_HTML' => $lang['Always_html'],
		'L_HIDE_USER' => $lang['Hide_user'],
		'L_ALWAYS_ADD_SIGNATURE' => $lang['Always_add_sig'],
		'L_COLORTEXT' => $lang['Colortext'],
		'L_COLORTEXT_EXPLAIN' => $lang['Colortext_Explain'],

		'L_AVATAR_PANEL' => $lang['Avatar_panel'],
		// Start replacement - Signatures control MOD
		'L_AVATAR_EXPLAIN' => sprintf($lang['Avatar_explain'], $board_config['avatar_max_width'], $board_config['avatar_max_height'], ( $board_config['sig_max_img_av_files_size'] ) ? $board_config['sig_max_img_av_files_size'] : round($board_config['avatar_filesize']/1024) ),
		// End replacement - Signatures control MOD
		'L_UPLOAD_AVATAR_FILE' => $lang['Upload_Avatar_file'],
		'L_UPLOAD_AVATAR_URL' => $lang['Upload_Avatar_URL'],
		'L_UPLOAD_AVATAR_URL_EXPLAIN' => $lang['Upload_Avatar_URL_explain'],
		'L_AVATAR_GALLERY' => $lang['Select_from_gallery'],
		'L_SHOW_GALLERY' => $lang['View_avatar_gallery'],
		'L_LINK_REMOTE_AVATAR' => $lang['Link_remote_Avatar'],
		'L_LINK_REMOTE_AVATAR_EXPLAIN' => $lang['Link_remote_Avatar_explain'],
		'L_DELETE_AVATAR' => $lang['Delete_Image'],
		'L_CURRENT_IMAGE' => $lang['Current_Image'],

		'L_SIGNATURE' => $lang['Signature'],
		// Start replacement - Signatures control MOD
		'L_SIGNATURE_EXPLAIN' => $signature_explain,
		// End replacement - Signatures control MOD
		'L_NOTIFY_ON_REPLY' => $lang['Always_notify'],
		'L_NOTIFY_ON_REPLY_EXPLAIN' => $lang['Always_notify_explain'],
		'L_NOTIFY_ON_PRIVMSG' => $lang['Notify_on_privmsg'],
		'L_POPUP_ON_PRIVMSG' => $lang['Popup_on_privmsg'],
		'L_POPUP_ON_PRIVMSG_EXPLAIN' => $lang['Popup_on_privmsg_explain'],
		'L_PREFERENCES' => $lang['Preferences'],
		'L_PUBLIC_VIEW_EMAIL' => $lang['Public_view_email'],
		'L_ITEMS_REQUIRED' => $lang['Items_required'],
		'L_REGISTRATION_INFO' => $lang['Registration_info'],
		'L_PROFILE_INFO' => $lang['Profile_info'],
		'L_PROFILE_INFO_NOTICE' => $lang['Profile_info_warn'],
		'L_EMAIL_ADDRESS' => $lang['Email_address'],
		'L_NO_AVATAR_POSTS' => sprintf($lang['No_avatar_posts'], $board_config['avatar_posts']),

		'L_CONFIRM_CODE_IMPAIRED'	=> sprintf($lang['Confirm_code_impaired'], '<a href="mailto:' . $board_config['board_email'] . '">', '</a>'), 
		'L_CONFIRM_CODE'			=> $lang['Confirm_code'], 
		'L_CONFIRM_CODE_EXPLAIN'	=> $lang['Confirm_code_explain'], 

		'S_ALLOW_AVATAR_UPLOAD' => $board_config['allow_avatar_upload'],
		'S_ALLOW_AVATAR_LOCAL' => $board_config['allow_avatar_local'],
		'S_ALLOW_AVATAR_REMOTE' => $board_config['allow_avatar_remote'],
		'S_HIDDEN_FIELDS' => $s_hidden_fields,
		'S_FORM_ENCTYPE' => $form_enctype,
		'S_PROFILE_ACTION' => append_sid("profile.$phpEx"))
	);
//-- mod : quick post es -------------------------------------------------------
//-- add
	display_qpes_data();
//-- fin mod : quick post es ---------------------------------------------------
//-- mod : birthday ------------------------------------------------------------
//-- add
	$bdays->select_birthdate();
//-- fin mod : birthday --------------------------------------------------------

	//
	// This is another cheat using the block_var capability
	// of the templates to 'fake' an IF...ELSE...ENDIF solution
	// it works well :)
	//
	if ( $mode != 'register' )
	{
		if ( $board_config['allow_avatar_upload'] || $board_config['allow_avatar_local'] || $board_config['allow_avatar_remote'] )
		{
			$template->assign_block_vars('switch_avatar_block', array() );

			if ( ($board_config['avatar_posts'] <= $userdata['user_posts'] || $userdata['user_level'] == ADMIN) && $board_config['allow_avatar_upload'] && file_exists(@phpbb_realpath('./' . $board_config['avatar_path'])) )
			{
				if ( $form_enctype != '' )
				{
					$template->assign_block_vars('switch_avatar_block.switch_avatar_local_upload', array() );
				}
				$template->assign_block_vars('switch_avatar_block.switch_avatar_remote_upload', array() );
			}

			if ( ($board_config['avatar_posts'] <= $userdata['user_posts'] || $userdata['user_level'] == ADMIN) && $board_config['allow_avatar_remote'] )
			{
				$template->assign_block_vars('switch_avatar_block.switch_avatar_remote_link', array() );
			}

			if ( $board_config['allow_avatar_local'] && file_exists(@phpbb_realpath('./' . $board_config['avatar_gallery_path'])) )
			{
				$template->assign_block_vars('switch_avatar_block.switch_avatar_local_gallery', array() );
			}

			if ( $board_config['avatar_posts'] > $userdata['user_posts'] && $userdata['user_level'] != ADMIN && ($board_config['allow_avatar_upload'] || $board_config['allow_avatar_remote']) )
			{
				$template->assign_block_vars('switch_avatar_block.switch_avatar_posts_block', array() );
			}
		}
	}
}

$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
