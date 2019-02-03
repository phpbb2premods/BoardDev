<?php
/***************************************************************************
 * class_bdays.php
 * --------------
 * begin	: Wednesday, October 19, 2005
 * copyright	: reddog - http://www.reddevboard.com/
 * version	: 0.0.3 - 11/11/2005
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

class bdays
{
	var $data_age;
	var $data_cake;

	function get_user_bday($user_bday, $username, $topics='')
	{
		global $board_config, $lang, $images, $template;

		$now = getdate();
		if ( !empty($user_bday) )
		{
			list($bday_day, $bday_month, $bday_year) = explode('-', $user_bday);
		}

		$bday_tmp = date($lang['bday_date_format'], mktime(0, 0, 1, $bday_month, $bday_day, 1971));
		$bday_born = str_replace('1971', $bday_year, $bday_tmp);
		$bday_born = strtr($bday_born, $lang['datetime']);

		$bday_sum = ($bday_month == $now['mon']) ? ( ($bday_day <= $now['mday']) ? true : false ) : ( $bday_month < $now['mon'] ? true : false );
		$user_date = ( !empty($user_bday) ) ? sprintf($lang['bday_born'], $bday_born) : $lang['bday_none'];
		$user_age = ($age = (int)substr($user_bday, -4)) ? ( (!empty($topics)) ? $lang['bday_age'] . ': ' : ' (' ) . ($now['year'] - ($bday_sum ? $age : ($age+1))) .  ( (!empty($topics)) ? '' : ')' ) : '';

		$bday_check = array($now['mday'], $now['mon']);
		$bday_greeting = (int)substr($board_config['birthday_settings'], 2, 1);
		$user_cake = ( (substr($user_bday, 0, -5) == implode('-', $bday_check)) && $bday_greeting ) ? '&nbsp;<img alt="" src="' . $images['bday_mini_cake'] . '" title="' . sprintf($lang['bday_happy'], $username) . '" /><br />' : ( (!empty($user_age) && !empty($topics) ) ? '<br />' : '' );

		$template->assign_vars(array(
			'L_BDAY_BIRTHDATE' => $lang['bday_birthdate'],
			'BDAY_DATE' => $user_date,
			'BDAY_AGE' => $user_age,
			'BDAY_CAKE' => $user_cake,
		));

		if (!empty($topics))
		{
			$this->data_age = $user_age;
			$this->data_cake = $user_cake;
		}
	}

	function select_birthdate()
	{
		global $board_config, $lang, $template;
		global $bday_day, $bday_month, $bday_year;

		// birthday settings
		$bday_required = $bday_greeting = $bday_min_age = $bday_max_age = 0;
		if (!empty($board_config['birthday_settings']))
		{
			list($bday_required, $bday_greeting, $bday_min_age, $bday_max_age) = explode('-', $board_config['birthday_settings']);
		}

		// day select
		$s_birthday_day_options = '<select name="bday_day"><option value="0"' . ((!$bday_day) ? ' selected="selected"' : '') . '>--</option>';
		for ($i = 1; $i < 32; $i++)
		{
			$selected = ($i == $bday_day) ? ' selected="selected"' : '';
			$s_birthday_day_options .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
		}
		$s_birthday_day_options .= '</select>';

		// month select
		$month_name = array(
			' ------------ ', $lang['datetime']['January'], $lang['datetime']['February'], $lang['datetime']['March'], $lang['datetime']['April'], $lang['datetime']['May'], $lang['datetime']['June'],
			$lang['datetime']['July'], $lang['datetime']['August'], $lang['datetime']['September'], $lang['datetime']['October'], $lang['datetime']['November'], $lang['datetime']['December'],
		);
		$s_birthday_month_options = '<select name="bday_month">';
		for ($i = 0; $i <= 12; $i++)
		{
			$selected = ($i == $bday_month) ? ' selected="selected"' : '';
			$s_birthday_month_options .= '<option value="' . $i . '"' . $selected . '>' . $month_name[$i] . '</option>';
		}
		$s_birthday_month_options .= '</select>';

		// year select
		$s_birthday_year_options = '';
		$min_user_age = ( !empty($min_user_age) ) ? $min_user_age : 5;
		$max_user_age = ( !empty($max_user_age) ) ? $max_user_age : 100;
		$now = getdate();

		$s_birthday_year_options = '<select name="bday_year"><option value="0"' . ((!$bday_year) ? ' selected="selected"' : '') . '>--</option>';
		for ($i = $now['year'] - $bday_max_age; $i < $now['year'] - $bday_min_age; $i++)
		{
			$selected = ($i == $bday_year) ? ' selected="selected"' : '';
			$s_birthday_year_options .= '<option value="' . $i . '"' . $selected . '>' . $i . '</option>';
		}
		$s_birthday_year_options .= '</select>';

		unset($now);

		$template->assign_vars(array(
			'L_BDAY_BIRTHDATE' => $lang['bday_birthdate'],
			'L_BDAY_REQUIRED' => ($bday_required) ? ' *' : '',
			'L_BDAY_DAY' => $lang['bday_day'],
			'L_BDAY_MONTH' => $lang['bday_month'],
			'L_BDAY_YEAR' => $lang['bday_year'],
		
			'S_BDAY_DAY_OPTIONS' => $s_birthday_day_options,
			'S_BDAY_MONTH_OPTIONS' => $s_birthday_month_options,
			'S_BDAY_YEAR_OPTIONS' => $s_birthday_year_options,
		));
	}

	function display_bdays()
	{
		global $db, $board_config, $lang, $images, $template;
		global $phpEx;

		$cnt_bdays = '';
		$now = getdate();
		$bday_greeting = (int)substr($board_config['birthday_settings'], 2, 1);
		if ($bday_greeting)
		{
			$now = getdate();
			$bday_check = array($now['mday'], $now['mon']);
			$sql = 'SELECT user_id, username, user_birthday
				FROM ' . USERS_TABLE . '
				WHERE user_birthday LIKE \'' . implode('-', $bday_check) . '-%\'
				ORDER BY username';
			$result = $db->sql_query($sql);
			$cnt = $db->sql_numrows($result);

			if ( !empty($cnt))
			{
				$template->assign_block_vars('birthday', array());
			}

			// birthdays today
			while ($row = $db->sql_fetchrow($result))
			{
				$template->assign_block_vars('birthday.list', array(
					'U_BDAY' => append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']),
					'BDAY_NAME' => $row['username'],
					'BDAY_AGE' => ($age = (int)substr($row['user_birthday'], -4)) ? ' (' . ($now['year'] - $age) . ')' : '',
					'L_BDAY_HAPPY' => sprintf($lang['bday_happy'], $row['username']),
					'L_SEP' => empty($cnt_bdays) ? '' : ', ',
				));
				$cnt_bdays++;
			}
			$db->sql_freeresult($result);

			// constants
			$template->assign_vars(array(
				'L_BDAY_BIRTHDAYS' => $lang['bday_birthdays'],
				'L_BDAY_DETAILS' => (!empty($cnt_bdays)) ? $lang['bday_greeting'] : $lang['bday_no_today'],
				'I_BDAY_CAKE' => $images['bday_cake'],
			));

			// send to template
			$template->set_filenames(array('bdays_box' => 'index_bdays_box.tpl'));
			$template->assign_var_from_handle('BDAYS_BOX', 'bdays_box');
		}
	}
}

?>
