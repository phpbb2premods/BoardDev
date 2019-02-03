<?php

/***************************************************************************
 *                             xs_frame_top.php
 *                             ----------------
 *   copyright            : (C) 2003 - 2005 CyberAlien
 *   support              : http://www.phpbbstyles.com
 *
 *   version              : 2.3.1
 *
 *   file revision        : 70
 *   project revision     : 78
 *   last modified        : 05 Dec 2005  13:54:54
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
$phpbb_root_path = "./../";
$no_page_header = true;
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

define('IN_XS', true);
define('NO_XS_HEADER', true);
include_once('xs_include.' . $phpEx);

$template->set_filenames(array('body' => XS_TPL_PATH . 'frame_top.tpl'));

$template->assign_block_vars('left_nav', array(
	'URL'	=> append_sid('xs_index.'.$phpEx),
	'TEXT'	=> $lang['xs_menu_lc']
	));
/* $template->assign_block_vars('left_nav', array(
	'URL'	=> append_sid('xs_download.'.$phpEx),
	'TEXT'	=> $lang['xs_download_styles_lc']
	)); */
$template->assign_block_vars('left_nav', array(
	'URL'	=> append_sid('xs_import.'.$phpEx),
	'TEXT'	=> $lang['xs_import_styles_lc']
	));
$template->assign_block_vars('left_nav', array(
	'URL'	=> append_sid('xs_install.'.$phpEx),
	'TEXT'	=> $lang['xs_install_styles_lc']
	));
$template->assign_block_vars('left_nav', array(
	'URL'	=> 'http://www.phpbbstyles.com',
	'TEXT'	=> $lang['xs_support_forum_lc']
	));


$template->pparse('body');
xs_exit();

?>