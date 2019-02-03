<?php
/***************************************************************************
 *                            admin_ct_logs.php
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
		$module['ct_maintitle']['ct_logs'] = $filename;

	return;
}

//
// Load Page Header
//
$no_page_header = TRUE;
$phpbb_root_path = './../';
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

  function ct_logdelete($logfile)
  {
    global $phpbb_root_path, $phpEx;
    // Function to delete one logfile
    $tempnum = 0;
    $tempnum = count(file($phpbb_root_path . "ctracker/logs/" . $logfile . ".txt")) - 1;

    $clog = fopen($phpbb_root_path . "ctracker/logs/" . $logfile . ".txt", "a");
    ftruncate($clog, '0');
    fwrite($clog, "0||" . time() . "||SYSTEM MESSAGE||MANUAL LOG FILE RESET||-||CRACKERTRACKER\n");
    fclose($clog);

    $ct_counter_val = 0;
    $countername    = $phpbb_root_path . "ctracker/logs/counter.txt";
    $ct_counter_val = @file_get_contents($countername);

    $ct_counter_val = $ct_counter_val + $tempnum;
    $cfp = fopen ($countername, 'a');

    ftruncate($cfp, '0');
    fwrite($cfp, $ct_counter_val);
    fclose($cfp);
  }

  function ct_deleteall()
  {
    global $phpbb_root_path, $phpEx;
    // Function to delete one logfile
    $logdateiname = array();
    $tempnumber   = array();

    $logdateiname[1] = 'logfile_worms';
    $logdateiname[2] = 'logfile_proxy';
    $logdateiname[3] = 'logfile_flood';

    for ($i=1; $i<=3; $i++)
    {
      $tempnumber[$i] = count(file($phpbb_root_path . "ctracker/logs/" . $logdateiname[$i] . ".txt")) - 1;
      $clog = fopen($phpbb_root_path . "ctracker/logs/" . $logdateiname[$i] . ".txt", "a");
      ftruncate($clog, '0');
      fwrite($clog, "0||" . time() . "||SYSTEM MESSAGE||MANUAL LOG FILE RESET||-||CRACKERTRACKER\n");
      fclose($clog);
    }

    $ct_counter_val = 0;
    $countername    = $phpbb_root_path . "ctracker/logs/counter.txt";
    $ct_counter_val = @file_get_contents($countername);

    $ct_counter_val = $ct_counter_val + $tempnumber[1] + $tempnumber[2] + $tempnumber[3];
    $cfp = fopen ($countername, 'a');
    ftruncate($cfp, '0');
    fwrite($cfp, $ct_counter_val);
    fclose($cfp);
  }

  $seite = '';
  $seite = addslashes(htmlspecialchars($_GET['seite']));

  switch($seite)
  {
    case 1: $templatedatei = 'ct_logs_1';
            break;
    case 2: $templatedatei = 'ct_logs_2';
            break;
    case 3: $templatedatei = 'ct_logs_3';
            break;
    default: $templatedatei = 'ct_logs_4';
            break;
  }

  $template->set_filenames(array(
	'body' => 'admin/' . $templatedatei . '.tpl'
	)
  );

  if(!empty($_GET['aktion']))
  {
    $aktion = addslashes(intval($_GET['aktion']));

    switch($aktion)
    {
      case 1: // Wurmlog löschen
    		  ct_logdelete("logfile_worms");
              break;
      case 2: // Proxylog löschen
    		  ct_logdelete("logfile_proxy");
              break;
      case 3: // Floodlog löschen
    		  ct_logdelete("logfile_flood");
              break;
      case 4: // Alle Logs löschen
    		  ct_deleteall();
              break;
      default: break;
    }
  }

  $anzahl_eintr = array();

  $ldateiname[1] = 'logfile_worms';
  $ldateiname[2] = 'logfile_proxy';
  $ldateiname[3] = 'logfile_flood';

  for ($i=1; $i<=3; $i++)
  {
    $anzahl_eintr[$i] = count(file($phpbb_root_path . "ctracker/logs/" . $ldateiname[$i] . ".txt"));
  }

  $zaehlerstand = 0;
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
  $zaehlerstand = $ct_count_val + $ct_count_val_1 + $ct_count_val_2 + $ct_count_val_3;

  $link[1] = "[ <a href=\"" . append_sid("admin_ct_logs." . $phpEx . "?aktion=1") . "\">" . $lang['ct_log_l2'] . "</a> ] [ <a href=\"" . append_sid("admin_ct_logs." . $phpEx . "?seite=1") . "\">" . $lang['ct_log_l1'] . "</a> ]";
  $link[2] = "[ <a href=\"" . append_sid("admin_ct_logs." . $phpEx . "?aktion=2") . "\">" . $lang['ct_log_l2'] . "</a> ] [ <a href=\"" . append_sid("admin_ct_logs." . $phpEx . "?seite=2") . "\">" . $lang['ct_log_l1'] . "</a> ]";
  $link[3] = "[ <a href=\"" . append_sid("admin_ct_logs." . $phpEx . "?aktion=3") . "\">" . $lang['ct_log_l2'] . "</a> ] [ <a href=\"" . append_sid("admin_ct_logs." . $phpEx . "?seite=3") . "\">" . $lang['ct_log_l1'] . "</a> ]";
  $link[4] = "[ <a href=\"" . append_sid("admin_ct_logs." . $phpEx . "?aktion=4") . "\">" . $lang['ct_log_l3'] . "</a> ]";

  switch($seite)
  {
    case 1: $nowfile = $countfile4;
            break;
    case 2: $nowfile = $countfile3;
            break;
    case 3: $nowfile = $countfile2;
            break;
    default: $nowfile = $countfile4;
            break;
  }

  $eintragszahl = '';

  if($seite == 1 || $seite == 2 || $seite == 3)
  {
    $a = 0;
    $filename = file($nowfile);
    for ($i = count($filename)-1; $i >= 0; $i--)
    {
      $row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];
      $line = explode('||', $filename[$i]);
      $a++;

      if($line[0] == 0)
      {
        $row_class = 'row3';
        $line[2] = '<center><b>&raquo; ' . $line[2] . ' &laquo;</b></center>';
        $line[3] = '<center><b>&raquo; ' . $line[3] . ' &laquo;</b></center>';
        $line[4] = '<center><b>&raquo; ' . $line[4] . ' &laquo;</b></center>';
        $line[5] = '<center><b>&raquo; ' . $line[5] . ' &laquo;</b></center>';
      }

    $template->assign_block_vars('ctrack_logoutput', array(
      'L_DATE_CELL'   => date('d.m.Y, H:i', $line[1]),
      'L_C1'          => $line[2],
      'L_C2'          => $line[3],
      'L_C3'          => $line[4],
      'L_C4'          => $line[5],
      'T_ROW_CLASS'   => $row_class));
    }

    if($a == 2)
    {
      $eintragszahl = $lang['ct_log_entr1'];
    }
    else
    {
      $eintragszahl = sprintf($lang['ct_log_entr'], $a-1);
    }
  }

  $template->assign_vars(array(
    'L_ANZ_ENTR'    => $eintragszahl,
    'L_HEAD'        => $lang['ct_log_head'],
    'L_LOG_DESC'    => $lang['ct_log_desc'],
    'L_LOG_CELL1'   => $lang['ct_log_cell1'],
    'L_LOG_CELL2'   => $lang['ct_log_cell2'],
    'L_LOG_CELL3'   => $lang['ct_log_cell3'],
    'L_LOG_F1'      => $lang['ct_log_f1'],
    'L_LOG_F2'      => $lang['ct_log_f2'],
    'L_LOG_F3'      => $lang['ct_log_f3'],
    'L_LOG_C1'      => $anzahl_eintr[1]-1,
    'L_LOG_C2'      => $anzahl_eintr[2]-1,
    'L_LOG_C3'      => $anzahl_eintr[3]-1,
    'U_LINK1'       => $link[1],
    'U_LINK2'       => $link[2],
    'U_LINK3'       => $link[3],
    'U_LINK4'       => $link[4],
    'L_LO_1'        => $lang['ct_log_tc1'],
    'L_LO_2'        => $lang['ct_log_tc2'],
    'L_LO_3'        => $lang['ct_log_tc3'],
    'L_LO_4'        => $lang['ct_log_tc4'],
    'L_LO_5'        => $lang['ct_log_tc5'],
    'L_BACK'        => $lang['ct_log_back'],
    'L_GLOB_FUNCT'  => $lang['ct_log_gl'],
    'L_GLOB_DESC'   => sprintf($lang['ct_log_gl1'], $zaehlerstand-3),
    'L_SYS_FOOTER'  => $lang['ct_adm_foot'],
    'S_FORM_ACTION' => append_sid("admin_ct_logs." . $phpEx))
	);

  include('./page_header_admin.'.$phpEx);

  $template->pparse('body');

  include('./page_footer_admin.'.$phpEx);

?>