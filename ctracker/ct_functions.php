<?php
/***************************************************************************
 *                             ct_functions.php
 *                            -------------------
 *   copyright            : (C) 2005 by Christian Knerr (CBACK)
 *   homepage             : http://www.cback.de
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
}

  function ct_filllog()
  {
    global $phpbb_root_path, $ctracker_config, $lang, $userdata;

    //
    // Collecting information about the Attack and the Attacker
    //
    $ctl_pmeld    = 1;
    $ctl_stamp    = time();
    $ctl_remotead = $_SERVER['REMOTE_ADDR'] . " - (" . $userdata['username'] . ")";
    $ctl_referrer = $_SERVER['HTTP_REFERER'];
    $ctl_agent    = $_SERVER['HTTP_USER_AGENT'];

    $ctr_logfile  = $ctl_pmeld . '||' . $ctl_stamp . '||' . $ctl_remotead . '||' . $ctl_referrer . '||' . $ctl_agent;

    $ctr_logsize  = count(file($phpbb_root_path . "ctracker/logs/logfile_flood.txt"));
    $ctr_maxlogs  = $ctracker_config['floodlog'];

    if ($ctr_logsize > $ctr_maxlogs)
    {
      $clog = fopen($phpbb_root_path . "ctracker/logs/logfile_flood.txt", "a");
      ftruncate($clog, '0');
      fwrite($clog, "0||" . $ctl_stamp . "||SYSTEM MESSAGE||AUTOMATIC LOG FILE RESET||-||CRACKERTRACKER\n" . $ctr_logfile . "\n");
      fclose($clog);

      $ct_counter_val = 0;
      $countername    = $phpbb_root_path . "ctracker/logs/counter.txt";
      $ct_counter_val = @file_get_contents($countername);
      $ct_counter_val = $ct_counter_val + $ctr_maxlogs;

      $cfp = fopen ($countername, 'a');
      ftruncate($cfp, '0');
      fwrite($cfp, $ct_counter_val);
      fclose($cfp);
    }
    else
    {
      $clog = fopen($phpbb_root_path . 'ctracker/logs/logfile_flood.txt', 'a');
      fwrite($clog, $ctr_logfile . "\n");
      fclose($clog);
    }
  }


  //
  // Response Var for System-Check
  //
  $cresponse100 = 'cbackctr';

?>