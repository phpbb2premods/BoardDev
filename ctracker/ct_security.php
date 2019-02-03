<?php
/***************************************************************************
 *                             ct_security.php
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

 //
 // First we set the detection rules for the engine
 // Please note: You will find some well known Search Machine Providers in this list. I add them because
 // there are some tunneled requests wich try to start a process with a Search Machine for example. To stop this we added
 // the URL of these search engines. This will not affect to use the Search engines on your board naturally. ;)
 // Also we can block here some file extensions wich try to use exploit legs to download files from external sources.
 // And naturally we block all Worm Exploit Checks
 //
 $ct_rules = array('chr(', 'chr=', 'chr%20', '%20chr', 'wget%20', '%20wget', 'wget(',
 			       'cmd=', '%20cmd', 'cmd%20', 'rush=', '%20rush', 'rush%20',
                   'union%20', '%20union', 'union(', 'union=', 'echr(', '%20echr', 'echr%20', 'echr=',
                   'esystem(', 'esystem%20', 'cp%20', '%20cp', 'cp(', 'mdir%20', '%20mdir', 'mdir(',
                   'mcd%20', 'mrd%20', 'rm%20', '%20mcd', '%20mrd', '%20rm',
                   'mcd(', 'mrd(', 'rm(', 'mcd=', 'mrd=', 'mv%20', 'rmdir%20', 'mv(', 'rmdir(',
                   'chmod(', 'chmod%20', '%20chmod', 'chmod(', 'chmod=', 'chown%20', 'chgrp%20', 'chown(', 'chgrp(',
                   'locate%20', 'grep%20', 'locate(', 'grep(', 'diff%20', 'kill%20', 'kill(', 'killall',
                   'passwd%20', '%20passwd', 'passwd(', 'telnet%20', 'vi(', 'vi%20',
                   'insert%20into', 'select%20', 'nigga(', '%20nigga', 'nigga%20', 'fopen', 'fwrite', '%20like', 'like%20',
                   '$_request', '$_get', '$request', '$get', '.system', 'http_php', '&aim', '%20getenv', 'getenv%20',
                   'new_password', '&icq','/etc/password','/etc/shadow', '/etc/groups', '/etc/gshadow',
                   'http_user_agent', 'http_host', '/bin/ps', 'wget%20', 'uname\x20-a', '/usr/bin/id',
                   '/bin/echo', '/bin/kill', '/bin/', '/chgrp', '/chown', '/usr/bin', 'g\+\+', 'bin/python',
                   'bin/tclsh', 'bin/nasm', 'perl%20', 'traceroute%20', 'ping%20', '.pl', '/usr/x11r6/bin/xterm', 'lsof%20',
                   '/bin/mail', '.conf', 'motd%20', 'http/1.', '.inc.php', 'config.php', 'cgi-', '.eml',
                   'file\://', 'window.open', '<script>', 'javascript\://','img src', 'img%20src','.jsp','ftp.exe',
                   'xp_enumdsn', 'xp_availablemedia', 'xp_filelist', 'xp_cmdshell', 'nc.exe', '.htpasswd',
                   'servlet', '/etc/passwd', 'wwwacl', '~root', '~ftp', '.js', '.jsp', '.history',
                   'bash_history', '.bash_history', '~nobody', 'server-info', 'server-status', 'reboot%20', 'halt%20',
                   'powerdown%20', '/home/ftp', '/home/www', 'secure_site, ok', 'chunked', 'org.apache', '/servlet/con',
                   '<script', '/robot.txt' ,'/perl' ,'mod_gzip_status', 'db_mysql.inc', '.inc', 'select%20from',
                   'select from', 'drop%20', '.system', 'getenv', 'http_', '_php', 'php_', 'phpinfo()', '<?php', '?>', 'sql=',
                   '_global', 'global_', 'global[', '_server', 'server_', 'server[', '/modules', 'modules/', 'phpadmin',
                   'root_path', '_globals', 'globals_', 'globals[', 'ISO-8859-1', 'http://www.google.de/search', '?hl=',
				   '.txt', '.exe', 'google.de/search', 'yahoo.de', 'lycos.de', 'fireball.de', 'ISO-');


  //
  // Now the URL Protection Engine
  //
  $cracktrack = $_SERVER['QUERY_STRING'];
  $cracktrack = strtolower($cracktrack);
  $checkworm  = str_replace($ct_rules, '*', $cracktrack);

  if($cracktrack != $checkworm)
  {
    //
    // Collecting information about the Attack and the Attacker
    //
    $ctl_pmeld    = 1;
    $ctl_stamp    = time();
    $ctl_remotead = $_SERVER['REMOTE_ADDR'];
    $ctl_query    = $_SERVER['QUERY_STRING'];
    $ctl_referrer = $_SERVER['HTTP_REFERER'];
    $ctl_agent    = $_SERVER['HTTP_USER_AGENT'];

    //
    // Now we built the Line for the Logfile
    //
    $ctr_logfile  = $ctl_pmeld . '||' . $ctl_stamp . '||' . $ctl_remotead . '||' . $ctl_query . '||' . ctl_referrer . '||' . $ctl_agent;

    //
    // How many entrys are into the Logfile and how much entrys (default 100) are allowed?
    // I hardcoded this value because I won't contact the Database during an attack. ;)
    //
    $ctr_logsize  = count(file($phpbb_root_path . "ctracker/logs/logfile_worms.txt"));
    $ctr_maxlogs  = 100;

    //
    // Now logging and Counting. The Counter here is asymmetric so just if CTracker has to delete the Log
    // it writes something to the Counter. This is better because during an DoS Like attack we only have to
    // do one file operation. Into the footer the Counter will also count how many entrys are in the log
    // so the Counter-Value is always correct.
    //
    if ($ctr_logsize > $ctr_maxlogs)
    {
      $clog = fopen($phpbb_root_path . "ctracker/logs/logfile_worms.txt", "a");
      ftruncate($clog, '0');
      fwrite($clog, "0||" . $ctl_stamp . "||SYSTEM MESSAGE||AUTOMATIC LOG FILE RESET||-||CRACKERTRACKER\n" . $ctr_logfile . "\n");
      fclose($clog);

      //
      // Because we deleted the Logfile we will now write our new value for the Counter.
      //
      $ct_counter_val = 0;
      $countername    = $phpbb_root_path . "ctracker/logs/counter.txt";
      $ct_counter_val = @file_get_contents($countername);

      $ct_counter_val = $ct_counter_val + 100;

      $cfp = fopen ($countername, 'a');
      ftruncate($cfp, '0');
      fwrite($cfp, $ct_counter_val);
      fclose($cfp);
    }
    else
    {
      $clog = fopen($phpbb_root_path . 'ctracker/logs/logfile_worms.txt', 'a');
      fwrite($clog, $ctr_logfile . "\n");
      fclose($clog);
    }

    //
    // Output a warning message and stop the script
    //
    die("<br><hr width=\"40%\" align=\"left\"><font color=\"#FF0F0F\" face=\"Verdana\" size=\"5\"><b>- SECURITY ALERT -</b></font><hr width=\"40%\" align=\"left\">
    	 <font color=\"#000000\" face=\"Verdana\" size=\"2\"><br>The Board Security System has detected, that you wanted to
         bring bad<br>Code to this Forum or you have tried to exploit something here or maybe<br>another attack linke this.
         <br><br>
         <b>This attempt was blocked and we logged all information about this.</b>
         <br><br><br>
         If you see this message after including a new MOD to your Forum or if<br>
         you have reached this site over a normal Forum Link, please contact<br>
         the Board Administrator to fix this Problem.<br></font>
         <br><hr width=\"40%\" align=\"left\"><font color=\"#6B6B6B\" face=\"Verdana\" size=\"3\"><b>CBACK CrackerTracker v4</b></font>");
  }


  //
  // Our 2nd Protection Engine: To prevent some DBase Tricks or predefinied Vars
  // Little bit paranoid because we set this values later with config.php and overwrite other things
  // but you never know.
  //
  unset($dbname);
  unset($dbuser);
  unset($dbpasswd);
  unset($message);
  unset($highlight);
  unset($sql);
  $ctracker_config = array();

  //
  // Response Var for System-Check
  //
  $cresponse200 = 'cbackctr';

?>