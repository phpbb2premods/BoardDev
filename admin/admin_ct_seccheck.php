<?php
/***************************************************************************
 *                            admin_ct_seccheck.php
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
		$module['ct_maintitle']['ct_seccheck'] = $filename;

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
  'body' => 'admin/ct_seccheck.tpl'
   	)
);

  // Reset of used Vars
  $table = array();
  $vchck = array();
  $ctinf = '';

  // Download Version Infos from CBACK Server, borrowed from phpBB.com Version Checker
  if ($fsock = @fsockopen('www.community.cback.de', 80, $errno, $errstr, 10))
  {
    @fputs($fsock, "GET /uplink/ctracker.txt HTTP/1.1\r\n");
	@fputs($fsock, "HOST: www.community.cback.de\r\n");
	@fputs($fsock, "Connection: close\r\n\r\n");

	$get_info1 = false;

    while (!@feof($fsock))
	{
	  if ($get_info1)
	  {
	    $ctinf .= @fread($fsock, 1024);
	  }
	  else
	  {
	    if (@fgets($fsock, 1024) == "\r\n")
		{
		  $get_info1 = true;
		}
	  }
	}

    @fclose($fsock);
    $vchck = explode('|', $ctinf);
  }
  else
  {
    for ($i=0; $i<=3; $i++)
    {
      $vchck[$i] = $lang['ct_s_ukn'];
    }
  }

  // Now let's do the Version Security Check
  for ($i=0; $i<=3; $i++)
  {

    if(@phpversion() >= '5.0.0' && $i == 1)
    {
      $i++;
    }

    if($i == 0)
    {
      $table[1] = $ctracker_config['version'];
    }
    else if($i == 1 || $i == 2)
    {
      $table[1] = @phpversion();
    }
    else if($i == 3)
    {
        $table[1] = '2' . $board_config['version'];
		$version_info = explode(".", $table[1]);
        $table[1] = (int) $version_info[2];
    }

    $table[0] = $lang['ct_s_t' . $i];
    $table[2] = $vchck[$i];

    if($vchck[$i] != $lang['ct_s_ukn'])
    {
      if($i == 3)
      {
		$version_info = explode(".", $table[2]);
        $table[2] = (int) $version_info[2];
      }

	  if($table[1] >= $table[2])
      {
        if($i == 3)
        {
          $table[1] = '2' . $board_config['version'];
          $table[2] = $vchck[3];
        }
        $table[3] = $lang['ct_s_ok'];
      }
      else
      {
        if($i == 3)
        {
          $table[1] = '2' . $board_config['version'];
          $table[2] = $vchck[3];
        }
        $table[3] = $lang['ct_s_ac'];
      }
    }
    else
    {
      $table[3] = $lang['ct_s_ukn'];
    }

    if(@phpversion() < '5.0.0' && $i == 1)
    {
      $i++;
    }

    $template->assign_block_vars('ctrack_seccheck', array(
      'L_TABLE_1' => $table[0],
      'L_TABLE_2' => $table[1],
      'L_TABLE_3' => $table[2],
      'L_TABLE_4' => $table[3]));
  }

  // Now lets do a little Server Check
  $cell1 = array();
  $cell1[0] = strtolower(@ini_get('safe_mode'));
  $cell1[1] = strtolower(@ini_get('register_globals'));
  $cell1[2] = $board_config['enable_confirm'];
  $cell1[3] = $board_config['require_activation'];

  for($i=0; $i<=count($cell1)-1; $i++)
  {

    $table[0] = $lang['ct_sc_v' . $i];

    // This part for all settings wich are safe if they are enabled
    if($i == 0 || $i == 2 || $i == 3)
    {
      if($cell1[$i] == 'on' || $cell1[$i] >= '1')
      {
        $table[2] = $lang['ct_s_ok'];
        $table[1] = $lang['ct_sc_on'];
      }
      else
      {
        $table[2] = $lang['ct_s_ac'];
        $table[1] = $lang['ct_sc_off'];
      }
    }
    // This part is for all settings wich are safe if they are disabled
    else
    {
      if($cell1[$i] == 'on' || $cell1[$i] == '1')
      {
        $table[2] = $lang['ct_s_ac'];
        $table[1] = $lang['ct_sc_on'];
      }
      else
      {
        $table[2] = $lang['ct_s_ok'];
        $table[1] = $lang['ct_sc_off'];
      }
    }

    $template->assign_block_vars('ctrack_servcheck', array(
      'L_TABLE2_1' => $table[0],
      'L_TABLE2_2' => $table[1],
      'L_TABLE2_3' => $table[2]));
  }

  $template->assign_vars(array(
    'L_SEC_HEAD' => $lang['ct_s_head'],
    'L_SEC_DESC' => $lang['ct_s_desc'],
    'L_HEAD_1'   => $lang['ct_s_hd1'],
    'L_HEAD_2'   => $lang['ct_s_hd2'],
    'L_HEAD_3'   => $lang['ct_s_hd3'],
    'L_HEAD_4'   => $lang['ct_s_hd4'],
    'L_HEAD_5'   => $lang['ct_s_hd5'],
    'L_INFOHEAD' => $lang['ct_s_infohe'],
    'L_INFO'     => $lang['ct_s_info'],
    'L_SYS_FOOTER' => $lang['ct_adm_foot'])
	);

  include('./page_header_admin.'.$phpEx);

  $template->pparse('body');

  include('./page_footer_admin.'.$phpEx);

?>