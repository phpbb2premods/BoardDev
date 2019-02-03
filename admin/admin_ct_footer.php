<?php
/***************************************************************************
 *                            admin_ct_footer.php
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
		$module['ct_maintitle']['ct_footer'] = $filename;

	return;
}

//
// Load Page Header
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

$ct_f_sel = array();
$ct_f_sta = array();

$template->set_filenames(array(
	'body' => 'admin/ct_footer.tpl'
	)
);

  $status = '';
  $selfot = 3;

  if(isset($HTTP_POST_VARS['submit']) && !empty($HTTP_POST_VARS['footer']))
  {
    switch($HTTP_POST_VARS['footer'])
    {
      case 1: $selfot = 1;
              $ctracker_config['footer'] = 1;
              break;
      case 2: $selfot = 2;
              $ctracker_config['footer'] = 2;
              break;
      case 3: $selfot = 3;
              $ctracker_config['footer'] = 3;
              break;
      case 4: $selfot = 4;
              $ctracker_config['footer'] = 4;
              break;
      case 5: $selfot = 5;
              $ctracker_config['footer'] = 5;
              break;
      case 6: $selfot = 6;
              $ctracker_config['footer'] = 6;
              break;
      case 7: $selfot = 7;
              $ctracker_config['footer'] = 7;
              break;
      case 8: $selfot = 8;
              $ctracker_config['footer'] = 8;
              break;
      default: $selfot = 3;
               $ctracker_config['footer'] = 3;
               break;
    }

    $sql = "UPDATE " . CTRACK . " SET
	        value = '" . addslashes($selfot) . "'
				WHERE name = 'footer'";
	if( !$db->sql_query($sql) )
	{
	  message_die(GENERAL_ERROR, "Failed to update Footer Configuration for CrackerTracker", "", __LINE__, __FILE__, $sql);
	}

    $status = ' - ' . $lang['ct_f_ass'];
  }


  $ct_f_sta[$ctracker_config['footer']] = ' checked="true"';


  // Counterfiles
  $countfile1 = $phpbb_root_path . "ctracker/logs/counter.txt";
  $countfile2 = $phpbb_root_path . "ctracker/logs/logfile_flood.txt";
  $countfile3 = $phpbb_root_path . "ctracker/logs/logfile_proxy.txt";
  $countfile4 = $phpbb_root_path . "ctracker/logs/logfile_worms.txt";

  // Generate Counter Value
  $ct_count_val   = @file_get_contents($countfile1);
  $ct_count_val_1 = count(file($countfile2));
  $ct_count_val_2 = count(file($countfile3));
  $ct_count_val_3 = count(file($countfile4));
  $ct_count_value = $ct_count_val + $ct_count_val_1 + $ct_count_val_2 + $ct_count_val_3 - 3;

  $ctrack_miniic = '<a href="http://www.cback.de" target="_blank"><img src="' . $phpbb_root_path . 'ctracker/images/cback_ctracker_mini.gif" border="0" alt="' . $lang['ctr_footer_i'] . '" title="' . $lang['ctr_footer_i'] . '"></a>';
  $ctrack_button = '<a href="http://www.cback.de" target="_blank"><img src="' . $phpbb_root_path . 'ctracker/images/cback_ctracker_button.gif" border="0" alt="' . $lang['ctr_footer_i'] . '" title="' . $lang['ctr_footer_i'] . '"></a>';

  $ct_f_sel[1] = $ctrack_miniic;
  $ct_f_sel[2] = $ctrack_button;
  $ct_f_sel[3] = $ctrack_miniic . "<br><br>" . sprintf($lang['ctr_footer_g'], $ct_count_value);
  $ct_f_sel[4] = $ctrack_button . "<br><br>" . sprintf($lang['ctr_footer_g'], $ct_count_value);
  $ct_f_sel[5] = $lang['ctr_footer_n'];
  $ct_f_sel[6] = sprintf($lang['ctr_footer_c'], $ct_count_value);
  $ct_f_sel[7] = 'CrackerTracker &copy; 2004 - ' . date(Y) . ' <a href="http://www.cback.de" target="_blank">CBACK.de</a>';
  $ct_f_sel[8] = sprintf($lang['ctr_footer_g'], $ct_count_value);

  $template->assign_vars(array(
    'S_FORM_ACTION'    => append_sid("admin_ct_footer." . $phpEx),
    'L_FOOTER_1'       => $ct_f_sel[1],
    'L_FOOTER_2'       => $ct_f_sel[2],
    'L_FOOTER_3'       => $ct_f_sel[3],
    'L_FOOTER_4'       => $ct_f_sel[4],
    'L_FOOTER_5'       => $ct_f_sel[5],
    'L_FOOTER_6'       => $ct_f_sel[6],
    'L_FOOTER_7'       => $ct_f_sel[7],
    'L_FOOTER_8'       => $ct_f_sel[8],
    'S_SEL_O1'         => $ct_f_sta[1],
    'S_SEL_O2'         => $ct_f_sta[2],
    'S_SEL_O3'         => $ct_f_sta[3],
    'S_SEL_O4'         => $ct_f_sta[4],
    'S_SEL_O5'         => $ct_f_sta[5],
    'S_SEL_O6'         => $ct_f_sta[6],
    'S_SEL_O7'         => $ct_f_sta[7],
    'S_SEL_O8'         => $ct_f_sta[8],
    'L_FOOTER_SUBHEAD' => $lang['ct_foot_sh'],
    'L_FOOTER_DESC'    => $lang['ct_foot_d'],
    'L_FOOTER_HEAD'    => $lang['ct_foot_h'] . $status,
    'L_SUBMIT'         => $lang['ct_submit'],
    'L_SYS_FOOTER'     => $lang['ct_adm_foot'])
	);

  include('./page_header_admin.'.$phpEx);

  $template->pparse('body');

  include('./page_footer_admin.'.$phpEx);

?>