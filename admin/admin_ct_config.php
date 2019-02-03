<?php
/***************************************************************************
 *                            admin_ct_config.php
 *                             -------------------
 *   copyright            : (C) 2005 Christian Knerr
 *   email                : webmaster@cback.de
 *   www                  : http://www.cback.de
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

define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$filename = basename(__FILE__);
		$module['ct_maintitle']['ct_config'] = $filename;

	return;
}

//
// Load Page Header
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

$template->set_filenames(array(
	'body' => 'admin/ct_config.tpl'
	)
);
  // Saving Settings
  $sql = "SELECT *
	FROM " . CTRACK;
  if(!$result = $db->sql_query($sql))
  {
   	message_die(CRITICAL_ERROR, "Could not query CrackerTracker settings", "", __LINE__, __FILE__, $sql);
  }
  else
  {
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['name'];
		$config_value = $row['value'];
		$default_config[$config_name] = isset($HTTP_POST_VARS['submit']) ? str_replace("'", "\'", $config_value) : $config_value;

		$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];

		if( isset($HTTP_POST_VARS['submit']) )
		{
			$sql = "UPDATE " . CTRACK . " SET
				value = '" . addslashes(str_replace("\'", "''", $new[$config_name])) . "'
				WHERE name = '$config_name'";
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update CrackerTracker configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}

	if( isset($HTTP_POST_VARS['submit']) )
	{
		$message = $lang['Config_updated'] . "<br /><br />" . sprintf($lang['Click_return_config'], "<a href=\"" . append_sid("admin_ct_config.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");
		message_die(GENERAL_MESSAGE, $message);
	}
}

  // Generating Select Boxes
  $numbselect     = array();
  $onoffselect    = array();
  $timelowselect  = array();
  $timehighselect = array();
  $permselect     = '';
  $countselect    = array();

  for ($i=10; $i<=400; $i++)
  {
    if($ctracker_config['floodlog'] == $i)
    {
      $numbselect[1] .= '<option value="' . $i . '" selected="true">' . $i . '</option>';
    }
    else
    {
      $numbselect[1] .= '<option value="' . $i . '">' . $i . '</option>';
    }


    if($ctracker_config['proxylog'] == $i)
    {
      $numbselect[2] .= '<option value="' . $i . '" selected="true">' . $i . '</option>';
    }
    else
    {
      $numbselect[2] .= '<option value="' . $i . '">' . $i . '</option>';
    }
  }

  if($ctracker_config['filter'] == 1)
  {
    $onoffselect[1]    = '<option value="1" selected="true">' . $lang['ct_conf_act'] . '</option><option value="0">' . $lang['ct_conf_dact'] . '</option>';
  }
  else
  {
    $onoffselect[1]    = '<option value="1">' . $lang['ct_conf_act'] . '</option><option value="0" selected="true">' . $lang['ct_conf_dact'] . '</option>';
  }

  if($ctracker_config['floodprot'] == 1)
  {
    $onoffselect[2]    = '<option value="1" selected="true">' . $lang['ct_conf_act'] . '</option><option value="0">' . $lang['ct_conf_dact'] . '</option>';
  }
  else
  {
    $onoffselect[2]    = '<option value="1">' . $lang['ct_conf_act'] . '</option><option value="0" selected="true">' . $lang['ct_conf_dact'] . '</option>';
  }

  if($ctracker_config['regblock'] == 1)
  {
    $onoffselect[3]    = '<option value="1" selected="true">' . $lang['ct_conf_act'] . '</option><option value="0">' . $lang['ct_conf_dact'] . '</option>';
  }
  else
  {
    $onoffselect[3]    = '<option value="1">' . $lang['ct_conf_act'] . '</option><option value="0" selected="true">' . $lang['ct_conf_dact'] . '</option>';
  }

  if($ctracker_config['autoban'] == 1)
  {
    $onoffselect[4]    = '<option value="1" selected="true">' . $lang['ct_conf_act'] . '</option><option value="0">' . $lang['ct_conf_dact'] . '</option>';
  }
  else
  {
    $onoffselect[4]    = '<option value="1">' . $lang['ct_conf_act'] . '</option><option value="0" selected="true">' . $lang['ct_conf_dact'] . '</option>';
  }

  if($ctracker_config['mailfeature'] == 1)
  {
    $onoffselect[5]    = '<option value="1" selected="true">' . $lang['ct_conf_act'] . '</option><option value="0">' . $lang['ct_conf_dact'] . '</option>';
  }
  else
  {
    $onoffselect[5]    = '<option value="1">' . $lang['ct_conf_act'] . '</option><option value="0" selected="true">' . $lang['ct_conf_dact'] . '</option>';
  }

  if($ctracker_config['pwreset'] == 1)
  {
    $onoffselect[6]    = '<option value="1" selected="true">' . $lang['ct_conf_act'] . '</option><option value="0">' . $lang['ct_conf_dact'] . '</option>';
  }
  else
  {
    $onoffselect[6]    = '<option value="1">' . $lang['ct_conf_act'] . '</option><option value="0" selected="true">' . $lang['ct_conf_dact'] . '</option>';
  }

  if($ctracker_config['loginfeature'] == 1)
  {
    $onoffselect[7]    = '<option value="1" selected="true">' . $lang['ct_conf_act'] . '</option><option value="0">' . $lang['ct_conf_dact'] . '</option>';
  }
  else
  {
    $onoffselect[7]    = '<option value="1">' . $lang['ct_conf_act'] . '</option><option value="0" selected="true">' . $lang['ct_conf_dact'] . '</option>';
  }

  for ($i=1; $i<=220; $i++)
  {
    if($ctracker_config['posttimespan'] == $i)
    {
      $timelowselect[1] .= '<option value="' . $i . '" selected="true">' . $i . '</option>';
    }
    else
    {
      $timelowselect[1] .= '<option value="' . $i . '">' . $i . '</option>';
    }
  }

  for ($i=10; $i<=60; $i++)
  {
    if($ctracker_config['searchtime'] == $i)
    {
      $timehighselect[1] .= '<option value="' . $i . '" selected="true">' . $i . '</option>';
    }
    else
    {
      $timehighselect[1] .= '<option value="' . $i . '">' . $i . '</option>';
    }

    if($ctracker_config['regtime'] == $i)
    {
      $timehighselect[2] .= '<option value="' . $i . '" selected="true">' . $i . '</option>';
    }
    else
    {
      $timehighselect[2] .= '<option value="' . $i . '">' . $i . '</option>';
    }
  }

  for ($i=1; $i<=100; $i++)
  {
    if($ctracker_config['maxsearch'] == $i)
    {
      $countselect[1] .= '<option value="' . $i . '" selected="true">' . $i . '</option>';
    }
    else
    {
      $countselect[1] .= '<option value="' . $i . '">' . $i . '</option>';
    }

    if($ctracker_config['postintime'] == $i)
    {
      $countselect[2] .= '<option value="' . $i . '" selected="true">' . $i . '</option>';
    }
    else
    {
      $countselect[2] .= '<option value="' . $i . '">' . $i . '</option>';
    }
  }


  $template->assign_vars(array(
    'L_CT_CONFIG'   => $lang['ct_conf_h'],
    'L_CT_CONFIG_D' => $lang['ct_conf_d'],

    'L_TABLE_1'     => $lang['ct_conf_tb1'],
    'L_TABLE_2'     => $lang['ct_conf_tb2'],
    'L_TABLE_3'     => $lang['ct_conf_tb3'],
    'L_TABLE_4'     => $lang['ct_conf_tb4'],

    'L_CONFIGP_P1'  => $lang['ct_conf_p1'],
    'L_CONFIGP_D1'  => $lang['ct_conf_d1'],
    'L_CONFIGP_P2'  => $lang['ct_conf_p2'],
    'L_CONFIGP_D2'  => $lang['ct_conf_d2'],
    'L_CONFIGP_P3'  => $lang['ct_conf_p3'],
    'L_CONFIGP_D3'  => $lang['ct_conf_d3'],
    'L_CONFIGP_P4'  => $lang['ct_conf_p4'],
    'L_CONFIGP_D4'  => $lang['ct_conf_d4'],
    'L_CONFIGP_P5'  => $lang['ct_conf_p5'],
    'L_CONFIGP_D5'  => $lang['ct_conf_d5'],
    'L_CONFIGP_P6'  => $lang['ct_conf_p6'],
    'L_CONFIGP_D6'  => $lang['ct_conf_d6'],
    'L_CONFIGP_P9'  => $lang['ct_conf_p9'],
    'L_CONFIGP_D9'  => $lang['ct_conf_d9'],
    'L_CONFIGP_P10' => $lang['ct_conf_p10'],
    'L_CONFIGP_D10' => $lang['ct_conf_d10'],
    'L_CONFIGP_P11' => $lang['ct_conf_p11'],
    'L_CONFIGP_D11' => $lang['ct_conf_d11'],
    'L_CONFIGP_P12' => $lang['ct_conf_p12'],
    'L_CONFIGP_D12' => $lang['ct_conf_d12'],
    'L_CONFIGP_P13' => $lang['ct_conf_p13'],
    'L_CONFIGP_D13' => $lang['ct_conf_d13'],
	'L_CONFIGP_P14' => $lang['ct_conf_p14'],
    'L_CONFIGP_D14' => $lang['ct_conf_d14'],
    'L_CONFIGP_P15' => $lang['ct_conf_p15'],
    'L_CONFIGP_D15' => $lang['ct_conf_d15'],
    'L_CONFIGP_P16' => $lang['ct_conf_p16'],
    'L_CONFIGP_D16' => $lang['ct_conf_d16'],

    'S_FORM_ACTION' => append_sid("admin_ct_config." . $phpEx),

    'S_SEL_NUMB1'      => $numbselect[1],
    'S_SEL_NUMB2'      => $numbselect[2],
    'S_SEL_ONOFF1'     => $onoffselect[1],
    'S_SEL_ONOFF2'     => $onoffselect[2],
    'S_SEL_ONOFF3'     => $onoffselect[3],
    'S_SEL_ONOFF4'     => $onoffselect[4],
    'S_SEL_ONOFF5'     => $onoffselect[5],
    'S_SEL_ONOFF6'     => $onoffselect[6],
    'S_SEL_ONOFF7'     => $onoffselect[7],
    'S_SEL_TIME_LOW'   => $timelowselect[1],
    'S_SEL_TIME_HIGH1' => $timehighselect[1],
    'S_SEL_TIME_HIGH2' => $timehighselect[2],
    'S_SEL_COUNT1'     => $countselect[1],
    'S_SEL_COUNT2'     => $countselect[2],

    'L_BUT_SUBMIT'  => $lang['ct_submit'],
    'L_SYS_FOOTER'  => $lang['ct_adm_foot'])
	);

  include('./page_header_admin.'.$phpEx);

  $template->pparse('body');

  include('./page_footer_admin.'.$phpEx);

?>