<?php
/***************************************************************************
 *                                 mph.php
 *                            -------------------
 *   begin                : Sunday, August 28, 2005
 *   copyright            : (C) 2005 -=ET=- http://www.golfexpert.net/phpbb
 *   email                : n/a
 *
 *   $Id: mph.php,v 1.0.1 2005/09/11 12:00:00 -=ET=- Exp $
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


define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);


//
// Obtain initial var settings
//
if ( isset($HTTP_GET_VARS[POST_POST_URL]) || isset($HTTP_POST_VARS[POST_POST_URL]) )
{
	$post_id = (isset($HTTP_POST_VARS[POST_POST_URL])) ? intval($HTTP_POST_VARS[POST_POST_URL]) : intval($HTTP_GET_VARS[POST_POST_URL]);
}

if ( isset($HTTP_GET_VARS[POST_USERS_URL]) || isset($HTTP_POST_VARS[POST_USERS_URL]) )
{
	$user1_id = (isset($HTTP_POST_VARS[POST_USERS_URL])) ? intval($HTTP_POST_VARS[POST_USERS_URL]) : intval($HTTP_GET_VARS[POST_USERS_URL]);
}

if ( isset($HTTP_GET_VARS['u2']) || isset($HTTP_POST_VARS['u2']) )
{
	$user2_id = (isset($HTTP_POST_VARS['u2'])) ? intval($HTTP_POST_VARS['u2']) : intval($HTTP_GET_VARS['u2']);
}

if ( isset($HTTP_GET_VARS['i']) || isset($HTTP_POST_VARS['i']) )
{
	$ip_to_detail = (isset($HTTP_POST_VARS['i'])) ? htmlspecialchars($HTTP_POST_VARS['i']) : htmlspecialchars($HTTP_GET_VARS['i']);
}

if (!empty($HTTP_POST_VARS['sid']) || !empty($HTTP_GET_VARS['sid']))
{
	$sid = (!empty($HTTP_POST_VARS['sid'])) ? $HTTP_POST_VARS['sid'] : $HTTP_GET_VARS['sid'];
}
else
{
	$sid = '';
}


//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//


//
// Load the language strings
//
if ( !file_exists(@phpbb_realpath($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_mph.' . $phpEx)) ) 
{ 
	include($phpbb_root_path . 'language/lang_english/lang_mph.' . $phpEx); 
} else 
{ 
	include($phpbb_root_path . 'language/lang_' . $board_config['default_lang'] . '/lang_mph.' . $phpEx); 
} 


//
// session id check
//
if ($sid == '' || $sid != $userdata['session_id'])
{
	message_die(GENERAL_ERROR, $lang['mph_err_session']);
}


//
// admin/mod check
//
if ( $userdata['user_level'] == USER )
{
	message_die(GENERAL_ERROR, $lang['mph_err_not_admin_mod']);
}


//
// Start to build the page
//
$page_title = $lang['mph_title'];
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

$template->set_filenames(array(
	'mph' => 'mph.tpl')
);


//
// If run from a post, get the poster ID & the IP address used
//
if ( isset($post_id) )
{
	// Look up relevent data for this post
	$sql = "SELECT poster_id, poster_ip
		FROM " . POSTS_TABLE . " 
		WHERE post_id = $post_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, $lang['mph_err_post_info'], '', __LINE__, __FILE__, $sql);
	}

	if ( !($post_row = $db->sql_fetchrow($result)) )
	{
		message_die(GENERAL_MESSAGE, $lang['No_such_post']);
	}

	$user1_id = $post_row['poster_id'];
	unset($user2_id);
	$ip_to_detail = $post_row['poster_ip'];
}


//
// In any case, get information about the user(s)
//

// If there is no second user, don't deal with its ghost!
// For sure he will crash the request and urls! lol ;-)
if ( isset($user2_id) )
{
	$where2 = " OR user_id = " . $user2_id;
	$add_to_url .= "&amp;u2=" . $user2_id;
} else
{
	$where2 = "";
	$add_to_url = "";
}


$sql = "SELECT user_id, username, user_regdate, user_regip, user_email, user_icq, user_website, user_from, user_sig, user_sig_bbcode_uid, user_aim, user_yim, user_msnm, user_occ, user_interests
	FROM " . USERS_TABLE . " 
	WHERE user_id = $user1_id" . $where2;
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, $lang['mph_err_user_info'], '', __LINE__, __FILE__, $sql);
}

// Make both users info available in an array
while ( $row = $db->sql_fetchrow($result) )
{
	if ( $row['user_id'] == $user1_id )
	{
		$user[1] = $row;
	} else
	{
		if ( isset($user2_id) )
		{
			if ( $row['user_id'] == $user2_id )
			{
				$user[2] = $row;
			}
		}
	}
}


////////////////////////////////////////////////////////////////////
// Prepare the data for user1
////////////////////////////////////////////////////////////////////
$username1 = $user[1]['username'];
$user_profile1 = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user1_id");
$user_search1 = append_sid("search.$phpEx?search_author=$username1");
$mph_regdate1 = create_date($lang['mph_ip_date_format'], $user[1]['user_regdate'], $board_config['board_timezone']);
$mph_regip1 = ( $user[1]['user_regip'] != '' ) ? decode_ip($user[1]['user_regip']) : $lang['mph_no_ip'];
$regip_date_min1 = $user[1]['user_regdate'];
$regip_date_max1 = $user[1]['user_regdate'];
$regip_post_count1 = 1;
$mph_reghost1 = ( $user[1]['user_regip'] != '' ) ? @gethostbyaddr($mph_regip1) : $lang['mph_no_host'];
$mph_email1 = $user[1]['user_email'];
$mph_msnm1 = $user[1]['user_msnm'];
$mph_yim1 = $user[1]['user_yim'];
$mph_aim1 = $user[1]['user_aim'];
$mph_icq1 = $user[1]['user_icq'];
$mph_location1 = $user[1]['user_from'];
$mph_website1 = '<a href='.$user[1]['user_website'].'>'.$user[1]['user_website'].'</a>';
$mph_occupation1 = $user[1]['user_occ'];
$mph_interests1 = $user[1]['user_interests'];
$mph_user_sig_bbcode_uid1 = $user[1]['user_sig_bbcode_uid'];
$mph_signature1 = ($mph_user_sig_bbcode_uid1 != '') ? preg_replace("/:(([a-z0-9]+:)?)$mph_user_sig_bbcode_uid1(=|\])/si", '\\3', $user[1]['user_sig']) : $user[1]['user_sig'];


//
// Get the IP addresses user1 has posted under
//
$sql = "SELECT poster_ip, MIN(post_time) AS ip_uses_date_min, MAX(post_time) AS ip_uses_date_max, COUNT(*) AS ip_uses_count 
	FROM " . POSTS_TABLE . " 
	WHERE poster_id = $user1_id 
	GROUP BY poster_ip 
	ORDER BY " . (( SQL_LAYER == 'msaccess' ) ? 'MAX(post_time)' : 'ip_uses_date_max' ) . ' DESC';
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, sprintf($lang['mph_err_ip_info'], $username1), '', __LINE__, __FILE__, $sql);
}

$ip_used1 = array();
while ( $row = $db->sql_fetchrow($result) )
{
	$ip_used1[$row['poster_ip']] = $row;
}


//
// Add or update the result with the reg IP use
//
if ( array_key_exists($user[1]['user_regip'], $ip_used1) )
{
	$ip_used1[$user[1]['user_regip']]['ip_uses_date_min'] = $user[1]['user_regdate'];
	$ip_used1[$user[1]['user_regip']]['ip_uses_count'] = $ip_used1[$user[1]['user_regip']]['ip_uses_count']+1;
} else
{
	$ip_used1[$user[1]['user_regip']] = array(
		'poster_ip' => $user[1]['user_regip'],
		'ip_uses_date_min' => $user[1]['user_regdate'],
		'ip_uses_date_max' => $user[1]['user_regdate'],
		'ip_uses_count' => 1
	);
}


//
// For each IP address, analyse the uses
//
while ( list($ip1, $ip_use1) = each($ip_used1) )
{
	$ip = decode_ip($ip_use1['poster_ip']);
	$ip_uses_date_min = $ip_use1['ip_uses_date_min'];
	$ip_uses_date_max = $ip_use1['ip_uses_date_max'];
	$ip_uses_count = $ip_use1['ip_uses_count'];

	//
	// If it's the reg IP, prepare the reg IP info
	//
	if ( $ip_use1['poster_ip'] == $user[1]['user_regip'] && $user[1]['user_regip'] != '')
	{
		if ( $ip_use1['poster_ip'] == $ip_to_detail )
		{
			$mph_regip1_info = '<b><a href="http://network-tools.com/default.asp?host=' . $mph_regip1 . '" title="' . $lang['mph_ip_more_info'] . '" target="_blank" class="gen">' . $mph_regip1 . '</a></b><br />' . sprintf($lang['mph_ip_post_count_dates'], $ip_uses_count, create_date($lang['mph_ip_date_format'], $ip_uses_date_min, $board_config['board_timezone']), create_date($lang['mph_ip_date_format'], $ip_uses_date_max, $board_config['board_timezone']));
		} else
		{
			$mph_regip1_info = '<a href="' . append_sid("mph.$phpEx?" . POST_USERS_URL . "=$user1_id$add_to_url&amp;i=" . $ip_use1['poster_ip'] . "&amp;sid=" . $userdata['session_id'])  . '" title="' . create_date($lang['mph_ip_date_format'], $ip_uses_date_min, $board_config['board_timezone']) . ' -> ' . create_date($lang['mph_ip_date_format'], $ip_uses_date_max, $board_config['board_timezone']) . '" class="gen">' . $mph_regip1 . '</a>';
		}
	}

	
	//
	// Build the main IP address line information (detailed or not)
	//
	if ( $ip_use1['poster_ip'] == $ip_to_detail )
	{
		$post_ip = '<b><a href="http://network-tools.com/default.asp?host=' . $ip . '" title="' . $lang['mph_ip_more_info'] . '" target="_blank" class="gen">' . $ip . '</a></b><br />' . sprintf($lang['mph_ip_host'], @gethostbyaddr($ip)) . '<br />' . sprintf($lang['mph_ip_post_count_dates'], $ip_uses_count, create_date($lang['mph_ip_date_format'], $ip_uses_date_min, $board_config['board_timezone']), create_date($lang['mph_ip_date_format'], $ip_uses_date_max, $board_config['board_timezone']));
	} else
	{
		$post_ip = sprintf($lang['mph_ip_post_count'], $ip_uses_count) . ' ' . '<a href="' . append_sid("mph.$phpEx?" . POST_USERS_URL . "=$user1_id$add_to_url&amp;i=" . $ip_use1['poster_ip'] . "&amp;sid=" . $userdata['session_id'])  . '" title="' . create_date($lang['mph_ip_date_format'], $ip_uses_date_min, $board_config['board_timezone']) . ' -> ' . create_date($lang['mph_ip_date_format'], $ip_uses_date_max, $board_config['board_timezone']) . '" class="gen">' . $ip . '</a>';
	}


	//
	// Get the other users who have posted under this IP address
	//
	$sql = "SELECT u.user_id, u.username, u.user_regdate, u.user_regip, MIN(p.post_time) AS ip_other_uses_date_min, MAX(p.post_time) AS ip_other_uses_date_max, COUNT(*) AS ip_other_uses_count
		FROM " . USERS_TABLE ." u, " . POSTS_TABLE . " p 
		WHERE p.poster_id = u.user_id 
			AND p.poster_ip = '" . $ip_use1['poster_ip'] . "'
		GROUP BY u.username
		ORDER BY " . (( SQL_LAYER == 'msaccess' ) ? 'MAX(p.post_time)' : 'ip_other_uses_date_max' ) . ' DESC';
	if ( !($result1 = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['mph_err_other_posters_info'], $ip_use1['poster_ip']), '', __LINE__, __FILE__, $sql);
	}

	$ip_other_uses1 = array();
	while ( $row1 = $db->sql_fetchrow($result1) )
	{
		$ip_other_uses1[$row1['user_id']] = $row1;
	}


	//
	// Get the other users who have registered under this IP address
	//
	$sql = "SELECT user_id, username, user_regdate, user_regip
		FROM " . USERS_TABLE ." 
		WHERE user_regip = '" . $ip_use1['poster_ip'] . "'
		GROUP BY username
		ORDER BY user_regdate DESC";
	if ( !($result1 = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['mph_err_other_posters_info'], $ip_use1['poster_ip']), '', __LINE__, __FILE__, $sql);
	}


	//
	// Merge the 2 results to get all the other uses of this IP address
	//
	while ( $row1 = $db->sql_fetchrow($result1) )
	{
		if ( array_key_exists($row1['user_id'], $ip_other_uses1) )
		{
			$ip_other_uses1[$row1['user_id']]['ip_other_uses_date_min'] = $row1['user_regdate'];
			$ip_other_uses1[$row1['user_id']]['ip_other_uses_count'] = $ip_other_uses1[$row1['user_id']]['ip_other_uses_count']+1;
		} else
		{
			$ip_other_uses1[$row1['user_id']] = array(
				'user_id' => $row1['user_id'],
				'username' => $row1['username'],
				'user_regdate' => $row1['user_regdate'],
				'user_regip' => $row1['user_regip'],
				'ip_other_uses_date_min' => $row1['user_regdate'],
				'ip_other_uses_date_max' => $row1['user_regdate'],
				'ip_other_uses_count' => 1
			);
		}
	}


	//
	// Build the other users who have used this IP address list (detailed or not)
	//
	$other_users_list = '';
	while ( list($id1, $ip_other_user1) = each($ip_other_uses1) )
	{
		// Don't add in the "other users list" the main user
		if ( $ip_other_user1['user_id'] != $user[1]['user_id'] )
		{
			$other_user_id = $ip_other_user1['user_id'];
			$other_user_date_min = $ip_other_user1['ip_other_uses_date_min'];
			$other_user_date_max = $ip_other_user1['ip_other_uses_date_max'];
			$other_user_count = $ip_other_user1['ip_other_uses_count'];
			$other_user_color = ( $other_user_date_min < $ip_uses_date_max && $other_user_date_max > $ip_uses_date_min ) ? ' style="color:red"' : '';

			if ( $ip_use1['poster_ip'] == $ip_to_detail )
			{
				$other_users_list = $other_users_list . '<br />' . sprintf($lang['mph_ip_post_count_dates_detailed'], $other_user_count, create_date($lang['mph_ip_date_format'], $other_user_date_min, $board_config['board_timezone']), create_date($lang['mph_ip_date_format'], $other_user_date_max, $board_config['board_timezone'])) . ' - <a href="' . append_sid("mph.$phpEx?" . POST_USERS_URL . "=$user1_id&amp;u2=$other_user_id&amp;i=$ip_to_detail&amp;sid=" . $userdata['session_id'])  . '"' . $other_user_color . ' class="gensmall">' . $ip_other_user1['username'] . '</a>';
			} else
			{
				$other_user_separator = ( $other_users_list != '' ) ? ', ' : '';
				$other_users_list = $other_users_list . $other_user_separator . '<a href="' . append_sid("mph.$phpEx?" . POST_USERS_URL . "=$user1_id&amp;u2=$other_user_id&amp;sid=" . $userdata['session_id'])  . '"' . $other_user_color . ' title="' . create_date($lang['mph_ip_date_format'], $other_user_date_min, $board_config['board_timezone']) . ' -> ' . create_date($lang['mph_ip_date_format'], $other_user_date_max, $board_config['board_timezone']) . '" class="gensmall">' . $ip_other_user1['username'] . '</a>';
			}

			if ( $ip_use1['poster_ip'] == $user[1]['user_regip'] && $user[1]['user_regip'] != '')
			{
				$reg_other_users_list1 = $other_users_list;
			}
		}
	}
	
	//
	// If it's a post IP, display the post IP info
	//
	if ( $ip_uses_count > 1 || ($ip_uses_count <= 1 && $ip1 != $user[1]['user_regip']) )
	{
		$ip_line_count1++;
		$template->assign_block_vars('user_ips_row1', array(
			'POSTS_IP_CLASS' => ( isset($ip_to_detail) && $ip_use1['poster_ip'] == $ip_to_detail ) ? $theme['td_class2'] : $theme['td_class1'],
			'LINE' => ( $ip_line_count1 > 1 ) ? '<tr><td><hr /></td></tr>' : '',
			'POSTS_IP' => $post_ip,
			'OTHER_USERS' => ( $other_users_list != '' ) ? '<br />' . (( $ip_to_detail == $ip_use1['poster_ip'] ) ? '--------------- ' . $lang['mph_ip_users_detailed'] . ' --' : $lang['mph_ip_users']) . ' ' . $other_users_list : '')
		);
	}
}



////////////////////////////////////////////////////////////////////
// Prepare the data for user2
////////////////////////////////////////////////////////////////////
if ( isset($user2_id) )
{
	$username2 = $user[2]['username'];
	$user_profile2 = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$user2_id");
	$user_search2 = append_sid("search.$phpEx?search_author=$username2");
	$mph_regdate2 = create_date($lang['mph_ip_date_format'], $user[2]['user_regdate'], $board_config['board_timezone']);
	$mph_regip2 = ( $user[2]['user_regip'] != '' ) ? decode_ip($user[2]['user_regip']) : $lang['mph_no_ip'];
	$regip_date_min2 = $user[2]['user_regdate'];
	$regip_date_max2 = $user[2]['user_regdate'];
	$regip_post_count2 = 1;
	$mph_reghost2 = ( $user[2]['user_regip'] != '' ) ? @gethostbyaddr($mph_regip2) : $lang['mph_no_host'];
	$mph_email2 = $user[2]['user_email'];
	$mph_msnm2 = $user[2]['user_msnm'];
	$mph_yim2 = $user[2]['user_yim'];
	$mph_aim2 = $user[2]['user_aim'];
	$mph_icq2 = $user[2]['user_icq'];
	$mph_location2 = $user[2]['user_from'];
	$mph_website2 = '<a href='.$user[2]['user_website'].'>'.$user[2]['user_website'].'</a>';
	$mph_occupation2 = $user[2]['user_occ'];
	$mph_interests2 = $user[2]['user_interests'];
	$mph_user_sig_bbcode_uid2 = $user[2]['user_sig_bbcode_uid'];
	$mph_signature2 = ($mph_user_sig_bbcode_uid2 != '') ? preg_replace("/:(([a-z0-9]+:)?)$mph_user_sig_bbcode_uid2(=|\])/si", '\\3', $user[2]['user_sig']) : $user[2]['user_sig'];


	//
	// Get the IP addresses user2 has posted under
	//
	$sql = "SELECT poster_ip, MIN(post_time) AS ip_uses_date_min, MAX(post_time) AS ip_uses_date_max, COUNT(*) AS ip_uses_count 
		FROM " . POSTS_TABLE . " 
		WHERE poster_id = $user2_id 
		GROUP BY poster_ip 
		ORDER BY " . (( SQL_LAYER == 'msaccess' ) ? 'MAX(post_time)' : 'ip_uses_date_max' ) . ' DESC';
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, sprintf($lang['mph_err_ip_info'], $username2), '', __LINE__, __FILE__, $sql);
	}

	$ip_used2 = array();
	while ( $row = $db->sql_fetchrow($result) )
	{
		$ip_used2[$row['poster_ip']] = $row;
	}


	//
	// Add or update the result with the reg IP use
	//
	if ( array_key_exists($user[2]['user_regip'], $ip_used2) )
	{
		$ip_used2[$user[2]['user_regip']]['ip_uses_date_min'] = $user[2]['user_regdate'];
		$ip_used2[$user[2]['user_regip']]['ip_uses_count'] = $ip_used2[$user[2]['user_regip']]['ip_uses_count']+1;
	} else
	{
		$ip_used2[$user[2]['user_regip']] = array(
			'poster_ip' => $user[2]['user_regip'],
			'ip_uses_date_min' => $user[2]['user_regdate'],
			'ip_uses_date_max' => $user[2]['user_regdate'],
			'ip_uses_count' => 1
		);
	}


	//
	// For each IP address, analyse the uses
	//
	while ( list($ip2, $ip_use2) = each($ip_used2) )
	{
		$ip = decode_ip($ip_use2['poster_ip']);
		$ip_uses_date_min = $ip_use2['ip_uses_date_min'];
		$ip_uses_date_max = $ip_use2['ip_uses_date_max'];
		$ip_uses_count = $ip_use2['ip_uses_count'];


		//
		// If it's the reg IP, prepare the reg IP info
		//
		if ( $ip_use2['poster_ip'] == $user[2]['user_regip'] && $user[2]['user_regip'] != '')
		{
			if ( $ip_use2['poster_ip'] == $ip_to_detail )
			{
				$mph_regip2_info = '<b><a href="http://network-tools.com/default.asp?host=' . $mph_regip2 . '" title="' . $lang['mph_ip_more_info'] . '" target="_blank" class="gen">' . $mph_regip2 . '</a></b><br />' . sprintf($lang['mph_ip_post_count_dates'], $ip_uses_count, create_date($lang['mph_ip_date_format'], $ip_uses_date_min, $board_config['board_timezone']), create_date($lang['mph_ip_date_format'], $ip_uses_date_max, $board_config['board_timezone']));
			} else
			{
				$mph_regip2_info = '<a href="' . append_sid("mph.$phpEx?" . POST_USERS_URL . "=$user1_id$add_to_url&amp;i=" . $ip_use2['poster_ip'] . "&amp;sid=" . $userdata['session_id']) . '" title="' . create_date($lang['mph_ip_date_format'], $ip_uses_date_min, $board_config['board_timezone']) . ' -> ' . create_date($lang['mph_ip_date_format'], $ip_uses_date_max, $board_config['board_timezone']) . '" class="gen">' . $mph_regip2 . '</a>';
			}
		}

		
		//
		// Build the IP address line (detailed or not)
		//
		if ( $ip_use2['poster_ip'] == $ip_to_detail )
		{
			$post_ip = '<b><a href="http://network-tools.com/default.asp?host=' . $ip . '" title="' . $lang['mph_ip_more_info'] . '" target="_blank" class="gen">' . $ip . '</a></b><br />' . sprintf($lang['mph_ip_host'], @gethostbyaddr($ip)) . '<br />' . sprintf($lang['mph_ip_post_count_dates'], $ip_uses_count, create_date($lang['mph_ip_date_format'], $ip_uses_date_min, $board_config['board_timezone']), create_date($lang['mph_ip_date_format'], $ip_uses_date_max, $board_config['board_timezone']));
		} else
		{
			$post_ip = '<a href="' . append_sid("mph.$phpEx?" . POST_USERS_URL . "=$user1_id$add_to_url&amp;i=" . $ip_use2['poster_ip'] . "&amp;sid=" . $userdata['session_id']) . '" title="' . create_date($lang['mph_ip_date_format'], $ip_uses_date_min, $board_config['board_timezone']) . ' -> ' . create_date($lang['mph_ip_date_format'], $ip_uses_date_max, $board_config['board_timezone']) . '" class="gen">' . $ip . '</a>' . ' ' . sprintf($lang['mph_ip_post_count'], $ip_uses_count);
		}

		
		//
		// Get the other users who have posted under this IP address
		//
		$sql = "SELECT u.user_id, u.username, u.user_regdate, u.user_regip, MIN(p.post_time) AS ip_other_uses_date_min, MAX(p.post_time) AS ip_other_uses_date_max, COUNT(*) AS ip_other_uses_count
			FROM " . USERS_TABLE ." u, " . POSTS_TABLE . " p 
			WHERE p.poster_id = u.user_id 
				AND p.poster_ip = '" . $ip_use2['poster_ip'] . "'
			GROUP BY u.username
			ORDER BY " . (( SQL_LAYER == 'msaccess' ) ? 'MAX(p.post_time)' : 'ip_other_uses_date_max' ) . ' DESC';
		if ( !($result1 = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['mph_err_other_posters_info'], $ip_use2['poster_ip']), '', __LINE__, __FILE__, $sql);
		}

		$ip_other_uses2 = array();
		while ( $row1 = $db->sql_fetchrow($result1) )
		{
			$ip_other_uses2[$row1['user_id']] = $row1;
		}


		//
		// Get the other users who have registered under this IP address
		//
		$sql = "SELECT user_id, username, user_regdate, user_regip
			FROM " . USERS_TABLE ." 
			WHERE user_regip = '" . $ip_use2['poster_ip'] . "'
			GROUP BY username
			ORDER BY user_regdate DESC";
		if ( !($result1 = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, sprintf($lang['mph_err_other_posters_info'], $ip_use2['poster_ip']), '', __LINE__, __FILE__, $sql);
		}


		//
		// Merge the 2 results to get all the other uses of this IP address
		//
		while ( $row1 = $db->sql_fetchrow($result1) )
		{
			if ( array_key_exists($row1['user_id'], $ip_other_uses2) )
			{
				$ip_other_uses2[$row1['user_id']]['ip_other_uses_date_min'] = $row1['user_regdate'];
				$ip_other_uses2[$row1['user_id']]['ip_other_uses_count'] = $ip_other_uses2[$row1['user_id']]['ip_other_uses_count']+1;
			} else
			{
				$ip_other_uses2[$row1['user_id']] = array(
					'user_id' => $row1['user_id'],
					'username' => $row1['username'],
					'user_regdate' => $row1['user_regdate'],
					'user_regip' => $row1['user_regip'],
					'ip_other_uses_date_min' => $row1['user_regdate'],
					'ip_other_uses_date_max' => $row1['user_regdate'],
					'ip_other_uses_count' => 1
				);
			}
		}


		//
		// Build the other users who have used this IP address list (detailed or not)
		//
		$other_users_list = '';
		while ( list($id2, $ip_other_user2) = each($ip_other_uses2) )
		{
			// Don't add in the "other users list" the main user
			if ( $ip_other_user2['user_id'] != $user[2]['user_id'] )
			{
				$other_user_id = $ip_other_user2['user_id'];
				$other_user_date_min = $ip_other_user2['ip_other_uses_date_min'];
				$other_user_date_max = $ip_other_user2['ip_other_uses_date_max'];
				$other_user_count = $ip_other_user2['ip_other_uses_count'];
				$other_user_color = ( $other_user_date_min < $ip_uses_date_max && $other_user_date_max > $ip_uses_date_min ) ? ' style="color:red"' : '';

				if ( $ip_use2['poster_ip'] == $ip_to_detail )
				{
					$other_users_list = $other_users_list . '<br /><a href="' . append_sid("mph.$phpEx?" . POST_USERS_URL . "=$other_user_id&amp;u2=$user2_id&amp;i=$ip_to_detail&amp;sid=" . $userdata['session_id'])  . '"' . $other_user_color . ' class="gensmall">' . $ip_other_user2['username'] . '</a> - ' . sprintf($lang['mph_ip_post_count_dates_detailed'], $other_user_count, create_date($lang['mph_ip_date_format'], $other_user_date_min, $board_config['board_timezone']), create_date($lang['mph_ip_date_format'], $other_user_date_max, $board_config['board_timezone']));
				} else
				{
					$other_user_separator = ( $other_users_list != '' ) ? ', ' : '';
					$other_users_list = $other_users_list . $other_user_separator . '<a href="' . append_sid("mph.$phpEx?" . POST_USERS_URL . "=$other_user_id&amp;u2=$user2_id&amp;sid=" . $userdata['session_id'])  . '"' . $other_user_color . ' title="' . create_date($lang['mph_ip_date_format'], $other_user_date_min, $board_config['board_timezone']) . ' -> ' . create_date($lang['mph_ip_date_format'], $other_user_date_max, $board_config['board_timezone']) . '" class="gensmall">' . $ip_other_user2['username'] . '</a>';
				}

				if ( $ip_use2['poster_ip'] == $user[2]['user_regip'] && $user[2]['user_regip'] != '')
				{
					$reg_other_users_list2 = $other_users_list;
				}
			}
		}


		//
		// If it's a post IP, display the post IP info
		//
		if ( $ip_uses_count > 1 || ($ip_uses_count <= 1 && $ip2 != $user[2]['user_regip']) )
		{
			$ip_line_count2++;
			$template->assign_block_vars('user_ips_row2', array(
				'POSTS_IP_CLASS' => ( isset($ip_to_detail) && $ip_use2['poster_ip'] == $ip_to_detail ) ? $theme['td_class2'] : $theme['td_class1'],
				'LINE' => ( $ip_line_count2 > 1 ) ? '<tr><td><hr /></td></tr>' : '',
				'POSTS_IP' => $post_ip,
				'OTHER_USERS' => ( $other_users_list != '' ) ? '<br />' . (( $ip_to_detail == $ip_use2['poster_ip'] ) ? '-- ' . $lang['mph_ip_users_detailed'] . ' ---------------' : $lang['mph_ip_users']) . ' ' . $other_users_list : '')
			);
		}
	}
}


$template->assign_vars(array(
	'L_MPH_TITLE' => $lang['mph_title'],
	'L_MPH_REGDATE' => $lang['mph_regdate'],
	'L_MPH_REGIP' => $lang['mph_regip'],
	'L_MPH_REGHOST' => $lang['mph_reghost'],
	'L_MPH_EMAIL' => $lang['mph_email'],
	'L_MPH_MSNM' => $lang['mph_msnm'],
	'L_MPH_YIM' => $lang['mph_yim'],
	'L_MPH_AIM' => $lang['mph_aim'],
	'L_MPH_ICQ' => $lang['mph_icq'],
	'L_MPH_LOCATION' => $lang['mph_location'],
	'L_MPH_WEBSITE' => $lang['mph_website'],
	'L_MPH_OCCUPATION' => $lang['mph_occupation'],
	'L_MPH_INTERESTS' => $lang['mph_interests'],
	'L_MPH_SIGNATURE' => $lang['mph_signature'],
	'L_MPH_POSTS_IP' => $lang['mph_posts_ip'],
	'L_MPH_POSTS_IP_TEXT' => $lang['mph_posts_ip_text'],

	'MPH_USER1' => '<b><a href="' . $user_profile1 . '" class="gen" target="_blank" style="vertical-align: 3">' . $username1 . '</a></b>',
	'MPH_SEARCH1' => '&nbsp;&nbsp;&nbsp;<a href="' . $user_search1 . '" class="gen" target="_blank"><img src="' . $images['icon_search'] . '" border="0" alt="' . $lang['Search'] . '" /></a>',
	'MPH_REGDATE1' => $mph_regdate1,
	'MPH_REGIP1' => ( $user[1]['user_regip'] != '' ) ? $mph_regip1_info : $mph_regip1,
	'MPH_REGIP1_CLASS' => ( isset($ip_to_detail) && $ip_to_detail == $user[1]['user_regip'] ) ? $theme['td_class2'] : $theme['td_class1'],
	'MPH_REGIP_OTHER_USERS1' => ( $reg_other_users_list1 != '' ) ? '<br />' . (( $ip_to_detail == $user[1]['user_regip'] ) ? '--------------- ' . $lang['mph_ip_users_detailed'] . ' --' : $lang['mph_ip_users']) . ' ' . $reg_other_users_list1 : '',
	'MPH_REGHOST1' => $mph_reghost1,
	'MPH_EMAIL1' => $mph_email1,
	'MPH_MSNM1' => $mph_msnm1,
	'MPH_YIM1' => $mph_yim1,
	'MPH_AIM1' => $mph_aim1,
	'MPH_ICQ1' => $mph_icq1,
	'MPH_LOCATION1' => $mph_location1,
	'MPH_WEBSITE1' => $mph_website1,
	'MPH_OCCUPATION1' => $mph_occupation1,
	'MPH_INTERESTS1' => $mph_interests1,
	'MPH_SIGNATURE1' => $mph_signature1,

	'MPH_USER2' => '<b><a href="' . $user_profile2 . '" class="gen" target="_blank" style="vertical-align: 3">' . $username2 . '</a></b>',
	'MPH_SEARCH2' => ( isset($user2_id) ) ? '&nbsp;&nbsp;&nbsp;<a href="' . $user_search2 . '" class="gen" target="_blank"><img src="' . $images['icon_search'] . '" border="0" alt="' . $lang['Search'] . '" /></a>' : '',
	'MPH_REGDATE2' => $mph_regdate2,
	'MPH_REGIP2' => ( $user[2]['user_regip'] != '' ) ? $mph_regip2_info : $mph_regip2,
	'MPH_REGIP2_CLASS' => ( isset($ip_to_detail) && $ip_to_detail == $user[2]['user_regip'] ) ? $theme['td_class2'] : $theme['td_class1'],
	'MPH_REGIP_OTHER_USERS2' => ( $reg_other_users_list2 != '' ) ? '<br />' . (( $ip_to_detail == $user[2]['user_regip'] ) ? '-- ' . $lang['mph_ip_users_detailed'] . ' ---------------' : $lang['mph_ip_users']) . ' ' . $reg_other_users_list2 : '',
	'MPH_REGHOST2' => $mph_reghost2,
	'MPH_EMAIL2' => $mph_email2,
	'MPH_MSNM2' => $mph_msnm2,
	'MPH_YIM2' => $mph_yim2,
	'MPH_AIM2' => $mph_aim2,
	'MPH_ICQ2' => $mph_icq2,
	'MPH_LOCATION2' => $mph_location2,
	'MPH_WEBSITE2' => $mph_website2,
	'MPH_OCCUPATION2' => $mph_occupation2,
	'MPH_INTERESTS2' => $mph_interests2,
	'MPH_SIGNATURE2' => $mph_signature2)
);

$template->pparse('mph');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>