<?php
/***************************************************************************
 * def_bbc_box.php
 * ---------------
 * begin	: 11/06/2005
 * copyright	: reddog
 *
 * version	: 1.0.0 - 11/06/2005
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
	die('Hacking attempt');
	exit;
}

//--------------------------------------------------------------------------------------------------
//
// $bbc_config : templates
// -----------
//--------------------------------------------------------------------------------------------------

$bbc_config = array(
	'1' => array('bbc_id' => '1', 'bbc_name' => 'strike', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 's', 'bbc_after' => 's', 'bbc_helpline' => 'strike', 'bbc_img' => 'strike', 'bbc_divider' => '0', 'bbc_order' => '10'),
	'2' => array('bbc_id' => '2', 'bbc_name' => 'spoiler', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'spoil', 'bbc_after' => 'spoil', 'bbc_helpline' => 'spoiler', 'bbc_img' => 'spoiler', 'bbc_divider' => '0', 'bbc_order' => '20'),
	'3' => array('bbc_id' => '3', 'bbc_name' => 'fade', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'fade', 'bbc_after' => 'fade', 'bbc_helpline' => 'fade', 'bbc_img' => 'fade', 'bbc_divider' => '0', 'bbc_order' => '30'),
	'4' => array('bbc_id' => '4', 'bbc_name' => 'rainbow', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'rainbow', 'bbc_after' => 'rainbow', 'bbc_helpline' => 'rainbow', 'bbc_img' => 'rainbow', 'bbc_divider' => '1', 'bbc_order' => '40'),
	'5' => array('bbc_id' => '5', 'bbc_name' => 'justify', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'align=justify', 'bbc_after' => 'align', 'bbc_helpline' => 'justify', 'bbc_img' => 'justify', 'bbc_divider' => '0', 'bbc_order' => '50'),
	'6' => array('bbc_id' => '6', 'bbc_name' => 'right', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'align=right', 'bbc_after' => 'align', 'bbc_helpline' => 'right', 'bbc_img' => 'right', 'bbc_divider' => '0', 'bbc_order' => '60'),
	'7' => array('bbc_id' => '7', 'bbc_name' => 'center', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'align=center', 'bbc_after' => 'align', 'bbc_helpline' => 'center', 'bbc_img' => 'center', 'bbc_divider' => '0', 'bbc_order' => '70'),
	'8' => array('bbc_id' => '8', 'bbc_name' => 'left', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'align=left', 'bbc_after' => 'align', 'bbc_helpline' => 'left', 'bbc_img' => 'left', 'bbc_divider' => '1', 'bbc_order' => '80'),
	'9' => array('bbc_id' => '9', 'bbc_name' => 'link', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'link=', 'bbc_after' => 'link', 'bbc_helpline' => 'link', 'bbc_img' => 'alink', 'bbc_divider' => '0', 'bbc_order' => '90'),
	'10' => array('bbc_id' => '10', 'bbc_name' => 'target', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'target=', 'bbc_after' => 'target', 'bbc_helpline' => 'target', 'bbc_img' => 'atarget', 'bbc_divider' => '1', 'bbc_order' => '100'),
	'11' => array('bbc_id' => '11', 'bbc_name' => 'marqd', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'marq=down', 'bbc_after' => 'marq', 'bbc_helpline' => 'marqd', 'bbc_img' => 'marqd', 'bbc_divider' => '0', 'bbc_order' => '110'),
	'12' => array('bbc_id' => '12', 'bbc_name' => 'marqu', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'marq=up', 'bbc_after' => 'marq', 'bbc_helpline' => 'marqu', 'bbc_img' => 'marqu', 'bbc_divider' => '0', 'bbc_order' => '120'),
	'13' => array('bbc_id' => '13', 'bbc_name' => 'marql', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'marq=left', 'bbc_after' => 'marq', 'bbc_helpline' => 'marql', 'bbc_img' => 'marql', 'bbc_divider' => '0', 'bbc_order' => '130'),
	'14' => array('bbc_id' => '14', 'bbc_name' => 'marqr', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'marq=right', 'bbc_after' => 'marq', 'bbc_helpline' => 'marqr', 'bbc_img' => 'marqr', 'bbc_divider' => '1', 'bbc_order' => '140'),
	'15' => array('bbc_id' => '15', 'bbc_name' => 'email', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'email', 'bbc_after' => 'email', 'bbc_helpline' => 'email', 'bbc_img' => 'email', 'bbc_divider' => '0', 'bbc_order' => '150'),
	'16' => array('bbc_id' => '16', 'bbc_name' => 'flash', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'flash width=250 height=250', 'bbc_after' => 'flash', 'bbc_helpline' => 'flash', 'bbc_img' => 'flash', 'bbc_divider' => '0', 'bbc_order' => '160'),
	'17' => array('bbc_id' => '17', 'bbc_name' => 'video', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'video width=400 height=350', 'bbc_after' => 'video', 'bbc_helpline' => 'video', 'bbc_img' => 'video', 'bbc_divider' => '0', 'bbc_order' => '170'),
	'18' => array('bbc_id' => '18', 'bbc_name' => 'stream', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'stream', 'bbc_after' => 'stream', 'bbc_helpline' => 'stream', 'bbc_img' => 'stream', 'bbc_divider' => '0', 'bbc_order' => '180'),
	'19' => array('bbc_id' => '19', 'bbc_name' => 'real', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'ram width=220 height=140', 'bbc_after' => 'ram', 'bbc_helpline' => 'real', 'bbc_img' => 'real', 'bbc_divider' => '0', 'bbc_order' => '190'),
	'20' => array('bbc_id' => '20', 'bbc_name' => 'quick', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'quick width=480 height=224', 'bbc_after' => 'quick', 'bbc_helpline' => 'quick', 'bbc_img' => 'quick', 'bbc_divider' => '1', 'bbc_order' => '200'),
	'21' => array('bbc_id' => '21', 'bbc_name' => 'sup', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'sup', 'bbc_after' => 'sup', 'bbc_helpline' => 'sup', 'bbc_img' => 'sup', 'bbc_divider' => '0', 'bbc_order' => '210'),
	'22' => array('bbc_id' => '22', 'bbc_name' => 'sub', 'bbc_value' => '1', 'bbc_auth' => '0', 'bbc_before' => 'sub', 'bbc_after' => 'sub', 'bbc_helpline' => 'sub', 'bbc_img' => 'sub', 'bbc_divider' => '1', 'bbc_order' => '220'),
); 

?>