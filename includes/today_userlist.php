<?php
/***************************************************************************
 * today_userlist.php
 * ------------------
 * begin	: Saturday, May 20, 2005
 * copyright	: reddog - http://www.reddevboard.com/
 * version	: 1.0.3 - 23/06/2005
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

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
	exit;
}

//
// Parse dateformat to get time format
//
$time_reg = '([gh][[:punct:][:space:]]{1,2}[i][[:punct:][:space:]]{0,2}[a]?[[:punct:][:space:]]{0,2}[S]?)';
eregi($time_reg, $board_config['default_dateformat'], $regs);
$board_config['default_timeformat'] = $regs[1];
unset($time_reg);
unset($regs);

//
// Get the time today and yesterday
//
$today_ary = explode('|', create_date('m|d|Y', time(),$board_config['board_timezone']));
$board_config['time_today'] = gmmktime(0 - $board_config['board_timezone'] - $board_config['dstime'],0,0,$today_ary[0],$today_ary[1],$today_ary[2]);
$board_config['time_yesterday'] = $board_config['time_today'] - 86400;
unset($today_ary);

// ------------------
// Begin function block
//
function date_regen_format($config_field)
{
	global $db, $board_config, $lang;

	if ( $board_config['time_today'] < $config_field )
	{
		return sprintf($lang['Today_last_visit_alt'], create_date($board_config['default_timeformat'], $config_field, $board_config['board_timezone']));
	}
	else if ( $board_config['time_yesterday'] < $config_field )
	{
		return sprintf($lang['Yesterday_last_visit_alt'], create_date($board_config['default_timeformat'], $config_field, $board_config['board_timezone']));
	}
	else
	{
		// if selected timerange is greater than 24 hours
		return sprintf($lang['Other_last_visit_alt'], create_date($board_config['default_dateformat'], $config_field, $board_config['board_timezone']));
	}
}
//
// End function block
// ------------------

//
// Main process
//

// get time
$today_this_timestamp_array = getdate();
$today_when_from = mktime ( 0 , 0 , 0 , $today_this_timestamp_array[mon] , $today_this_timestamp_array[mday] , $today_this_timestamp_array[year] );

$now = time();
$current_hour = mktime(date('H', $now), 0, 0, date('m', $now), date('d', $now), date('Y', $now));
$hour_timerange = $now - $current_hour;
$yesterday = mktime(date('H', $now) + 1, 0, 0, date('m', $now), date('d', $now) - 1, date('Y', $now));
$selected_timerange = $now - $yesterday;

// select time method
$select_range = ($board_config['today_day_selected']) ? $today_when_from : ($now - $selected_timerange);

// reset data
$today_visible_online = $lasthour_visible_online = $today_hidden_online = $lasthour_hidden_online = 0;
$today_userlist = $l_today_users = $l_lasthour_users = '';

// main request
$sql = 'SELECT user_id, username, user_allow_viewonline, user_level, user_session_time
		FROM ' . USERS_TABLE . '
		WHERE user_id <> ' . ANONYMOUS . '
			AND user_session_time >= ' . $select_range . '
		ORDER BY user_level DESC, username';
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not obtain user/online information', '', __LINE__, __FILE__, $sql);
}

// prepare data
$prev_today_user_id = 0;

while( $row = $db->sql_fetchrow($result) )
{
	// Skip multiple sessions for one user
	if ( $row['user_id'] != $prev_today_user_id )
	{
		$style_color = '';
		switch ( $row['user_level'] )
		{
			case ADMIN:
				$username_today = '<b>' . $row['username'] . '</b>';
				$style_color = ' style="color:#' . $theme['fontcolor3'] . '"';
				break;
			case MOD:
				$username_today = '<b>' . $row['username'] . '</b>';
				$style_color = ' style="color:#' . $theme['fontcolor2'] . '"';
				break;
			default:
				$username_today = '<b>' . $row['username'] . '</b>';
				$style_color = '';
				break;
		}

		if ( $row['user_allow_viewonline'] )
		{
			$today_last_visit_alt = date_regen_format($row['user_session_time']);
			$user_today_link = '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '" title="' . $today_last_visit_alt . '"' . $style_color .'>' . $username_today . '</a>';
			$today_visible_online++;
			if ( $row['user_session_time'] >= ($now - $hour_timerange) )
			{
				$lasthour_visible_online++;
			}
		}
		else
		{
			$today_last_visit_alt = date_regen_format($row['user_session_time']);
			$user_today_link = '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '" title="' . $today_last_visit_alt . '"' . $style_color .'><i>' . $username_today . '</i></a>';
			$today_hidden_online++;
			if ( $row['user_session_time'] >= ($now - $hour_timerange) )
			{
				$lasthour_hidden_online++;
			}
		}

		if ( $row['user_allow_viewonline'] || $userdata['user_level'] == ADMIN )
		{
			$today_userlist .= ( $today_userlist != '' ) ? ', ' . $user_today_link : $user_today_link;
		}
	}

	$prev_today_user_id = $row['user_id'];
}
$db->sql_freeresult($result);

// display the selected method
$l_selected_method = ($board_config['today_day_selected']) ? $lang['Today_today_selected'] : $lang['Today_hours_selected'];

// get name and number of registered users during the selected method
if ( empty($today_userlist) )
{
	$today_userlist = $lang['None'];
}
$today_userlist = $lang['Registered_users'] . ' ' . $today_userlist;

$total_today_users = $today_visible_online + $today_hidden_online + $guests_today;

$l_t_today_s = ($total_today_users) ? ( ( $total_today_users == 1 )? $lang['Today_user_total'] : $lang['Today_users_total'] ) : $lang['Today_users_zero_total'];
$l_r_today_s = ($today_visible_online) ? ( ( $today_visible_online == 1 ) ? $lang['Today_reg_user_total'] : $lang['Today_reg_users_total'] ) : $lang['Today_reg_users_zero_total'];
$l_h_today_s = ($today_hidden_online) ? (($today_hidden_online == 1) ? $lang['Today_hidden_user_total'] : $lang['Today_hidden_users_total'] ) : $lang['Today_hidden_users_zero_total'];

$l_today_users = ($total_today_users) ? sprintf($l_t_today_s, $total_today_users, $l_selected_method) : sprintf($l_t_today_s, $l_selected_method);
$l_today_users .= sprintf($l_r_today_s, $today_visible_online);
$l_today_users .= sprintf($l_h_today_s, $today_hidden_online);

// get number of registered users during the current hour
$total_lasthour_users = $lasthour_visible_online + $lasthour_hidden_online + $guests_lasthour;

$l_t_lasthour_s = ($total_lasthour_users) ? ( ( $total_lasthour_users == 1 ) ? $lang['Lasthour_user_total'] : $lang['Lasthour_user_total'] ) : $lang['Lasthour_users_zero_total'];
$l_r_lasthour_s = ($lasthour_visible_online) ? ( ( $lasthour_visible_online == 1 ) ? $lang['Today_reg_user_total'] : $lang['Today_reg_users_total'] ) : $lang['Today_reg_users_zero_total'];
$l_h_lasthour_s = ($lasthour_hidden_online) ? ( ( $lasthour_hidden_online == 1 ) ? $lang['Today_hidden_user_total'] : $lang['Today_hidden_users_total'] ) : $lang['Today_hidden_users_zero_total'];

$l_lasthour_users = sprintf($l_t_lasthour_s, $total_lasthour_users);
$l_lasthour_users .= sprintf($l_r_lasthour_s, $lasthour_visible_online);
$l_lasthour_users .= sprintf($l_h_lasthour_s, $lasthour_hidden_online);

// send to template
$template->assign_vars(array(
	'TOTAL_TODAY_ONLINE' => $l_today_users,
	'TODAY_IN_USER_LIST' => $today_userlist,
	'TOTAL_LASTHOUR_ONLINE' => sprintf($lang['Lasthour'], $l_lasthour_users)
));

?>