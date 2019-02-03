<?php
/***************************************************************************
 *                             ct_ipblocker.php
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
  //
  // Loading CTracker Configuration from the Database
  //
  $sql = "SELECT *
	FROM " . CTRACK;
  if( !($result = $db->sql_query($sql)) )
  {
	message_die(CRITICAL_ERROR, "Could not query CBACK CrackerTracker config information", "", __LINE__, __FILE__, $sql);
  }
  while ( $row = $db->sql_fetchrow($result) )
  {
	$ctracker_config[$row['name']] = $row['value'];
  }

  //
  // CTracker IP and Agent blocker
  //
  $ctb_remotead = $_SERVER['REMOTE_ADDR'];
  $ctb_agent    = $_SERVER['HTTP_USER_AGENT'];

  if(!empty($ctb_remotead) && !empty($ctb_agent) && $ctracker_config['filter'] == 1)
  {
    $sql = "SELECT *
	  FROM " . CTFILTER;
    if( !($result = $db->sql_query($sql)) )
    {
	  message_die(CRITICAL_ERROR, "Could not query CBACK CTracker ProxyBlocker information", "", __LINE__, __FILE__, $sql);
    }
    while ( $row = $db->sql_fetchrow($result) )
    {
	  if(stripslashes($row['list']) == $ctb_remotead || stripslashes($row['list']) == $ctb_agent)
      {
        //
        // Collecting information about the Attack and the Attacker
        //
        $ctl_pmeld    = 1;
        $ctl_stamp    = time();
        $ctl_remotead = $_SERVER['REMOTE_ADDR'];
        $ctl_referrer = $_SERVER['HTTP_REFERER'];
        $ctl_agent    = $_SERVER['HTTP_USER_AGENT'];

        //
        // Now we built the Line for the Logfile
        //
        $ctr_logfile  = $ctl_pmeld . '||' . $ctl_stamp . '||' . $ctl_remotead . '||' . $ctl_referrer . '||' . $ctl_agent;

        //
        // How many entrys are into the Logfile and how much entrys (default 100) are allowed?
        // I hardcoded this value because I won't contact the Database during an attack. ;)
        //
        $ctr_logsize  = count(file($phpbb_root_path . "ctracker/logs/logfile_proxy.txt"));
        $ctr_maxlogs  = $ctracker_config['proxylog'];

        //
        // Now logging and Counting. The Counter here is asymmetric so just if CTracker has to delete the Log
        // it writes something to the Counter. This is better because during an DoS Like attack we only have to
        // do one file operation. Into the footer the Counter will also count how many entrys are in the log
        // so the Counter-Value is always correct.
        //
        if ($ctr_logsize > $ctr_maxlogs)
        {
          $clog = fopen($phpbb_root_path . "ctracker/logs/logfile_proxy.txt", "a");
          ftruncate($clog, '0');
          fwrite($clog, "0||" . $ctl_stamp . "||SYSTEM MESSAGE||AUTOMATIC LOG FILE RESET||-||CRACKERTRACKER\n" . $ctr_logfile . "\n");
          fclose($clog);

          //
          // Because we deleted the Logfile we will now write our new value for the Counter.
          //
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
          $clog = fopen($phpbb_root_path . 'ctracker/logs/logfile_proxy.txt', 'a');
          fwrite($clog, $ctr_logfile . "\n");
          fclose($clog);
        }

        //
        // Output a warning message and stop the script
        //
        die("<br><hr width=\"40%\" align=\"left\"><font color=\"#FF0F0F\" face=\"Verdana\" size=\"5\"><b>- SECURITY ALERT -</b></font><hr width=\"40%\" align=\"left\">
    	     <font color=\"#000000\" face=\"Verdana\" size=\"2\"><br>The CBACK CrackerTracker Security System detected,
             that you are<br>using a blocked IP Adress or a Proxy Server to watch this site
             or<br>you are Using Harvester or Spider Tools or the Admin blocked your<br>User Agent.<br><br>
             So your access to this site has been blocked.
             <br></font>
             <br><hr width=\"40%\" align=\"left\"><font color=\"#6B6B6B\" face=\"Verdana\" size=\"3\"><b>CBACK CrackerTracker v4</b></font>");
      }
    }
  }

  //
  // Response Var for System-Check
  //
  $cresponse300 = 'cbackctr';

?>