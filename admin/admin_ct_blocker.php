<?php
/***************************************************************************
 *                            admin_ct_blocker.php
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
		$module['ct_maintitle']['ct_blocker'] = $filename;

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
	'body' => 'admin/ct_blocker.tpl'
	)
);

  if(!empty($_GET['ctrun']))
  {
    $loeschnummer = 0;
    $loeschnummer = addslashes(intval($_GET['ctrun']));

    $sql = "DELETE FROM " . CTFILTER . " WHERE id = " . $loeschnummer . ";";
    if( !($result = $db->sql_query($sql)) )
    {
	  message_die(CRITICAL_ERROR, "Could not delete from CrackerTracker Blacklist information", "", __LINE__, __FILE__, $sql);
    }
  }

  if(isset($HTTP_POST_VARS['submit']) && !empty($HTTP_POST_VARS['entry']))
  {
    $neueintrag = '';
    $neueintrag = htmlspecialchars(addslashes($HTTP_POST_VARS['entry']));
    $sql = "INSERT INTO " . CTFILTER . " (`id`, `list`) VALUES ('', '" . $neueintrag . "')";
    if( !($result = $db->sql_query($sql)) )
    {
	  message_die(CRITICAL_ERROR, "Could not save CrackerTracker Blacklist information", "", __LINE__, __FILE__, $sql);
    }
  }

  $sql = "SELECT * FROM " . CTFILTER . " ORDER BY 'list' ASC";
  if( !($result = $db->sql_query($sql)) )
  {
	message_die(CRITICAL_ERROR, "Could not query CrackerTracker Blacklist information", "", __LINE__, __FILE__, $sql);
  }

  while ( $row = $db->sql_fetchrow($result) )
  {
    $dellist = $row['list'];
    $dellink = append_sid("admin_ct_blocker.php?ctrun=" . $row['id']);

    $template->assign_block_vars('ctrack_datalist', array(
      'L_DELDESC'     => $dellist,
      'U_DELLINK'     => $dellink,
      'L_DELETE'      => $lang['ct_pf_del']));
  }

  $template->assign_vars(array(
    'L_PF_HEAD2'    => $lang['ct_pf_head2'],
    'L_PF_DESC2'    => $lang['ct_pf_desc2'],
    'L_PF_DESC1'    => $lang['ct_pf_desc1'],
    'L_PF_DESC'     => $lang['ct_pf_desc'],
    'L_PF_HEAD1'    => $lang['ct_pf_head1'],
    'L_PF_HEAD'     => $lang['ct_pf_head'],
    'L_ADD'         => $lang['ct_pf_add'],
    'S_FORM_ACTION' => append_sid("admin_ct_blocker." . $phpEx),
    'L_SYS_FOOTER'  => $lang['ct_adm_foot'])
	);

  include('./page_header_admin.'.$phpEx);

  $template->pparse('body');

  include('./page_footer_admin.'.$phpEx);

?>