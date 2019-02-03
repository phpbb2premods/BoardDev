<?php
/***************************************************************************
 *                             ct_footer.php
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

  $ct_footer_art  = 0;
  $ct_count_val   = 0;
  $ct_count_val_1 = 0;
  $ct_count_val_2 = 0;
  $ct_count_val_3 = 0;
  $ct_count_value = 0;
  $ct_footer_art  = $ctracker_config['footer'];

  if($ctracker_config['footer'] == 3 || $ctracker_config['footer'] == 4 || $ctracker_config['footer'] == 6 || $ctracker_config['footer'] == 8)
    {
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
  }

  $ctrack_footer = '';
  $ctrack_miniic = '<a href="http://www.cback.de" target="_blank"><img src="' . $phpbb_root_path . 'ctracker/images/cback_ctracker_mini.gif" border="0" alt="' . $lang['ctr_footer_i'] . '" title="' . $lang['ctr_footer_i'] . '"></a>';
  $ctrack_button = '<a href="http://www.cback.de" target="_blank"><img src="' . $phpbb_root_path . 'ctracker/images/cback_ctracker_button.gif" border="0" alt="' . $lang['ctr_footer_i'] . '" title="' . $lang['ctr_footer_i'] . '"></a>';

  // Generate Footer
  switch($ct_footer_art)
  {
    case 1:
      $ctrack_footer = $ctrack_miniic;
      break;
    case 2:
      $ctrack_footer = $ctrack_button;
      break;
    case 3:
      $ctrack_footer = $ctrack_miniic . "<br><br>" . sprintf($lang['ctr_footer_g'], $ct_count_value);
      break;
    case 4:
      $ctrack_footer = $ctrack_button . "<br><br>" . sprintf($lang['ctr_footer_g'], $ct_count_value);
      break;
    case 5:
      $ctrack_footer = $lang['ctr_footer_n'];
      break;
    case 6:
      $ctrack_footer = sprintf($lang['ctr_footer_c'], $ct_count_value);
      break;
    case 7:
      $ctrack_footer = 'CrackerTracker &copy; 2004 - ' . date(Y) . ' <a href="http://www.cback.de" target="_blank">CBACK.de</a>';
      break;
    case 8:
      $ctrack_footer = '<a href="http://www.cback.de" target="_blank">' . sprintf($lang['ctr_footer_g'], $ct_count_value) . '</a>';
      break;
    default:
      $ctrack_footer = $ctrack_miniic . '<br>CrackerTracker &copy; 2004 - ' . date(Y) . ' <a href="http://www.cback.de" target="_blank">CBACK.de</a>';
      break;
  }

  // Send Footer to Template
  $template->assign_block_vars('cback_cracker_tracker', array(
    'CTRACKER_FOOTER' => $ctrack_footer)
    );

?>