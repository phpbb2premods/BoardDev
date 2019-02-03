<?php
/** 
*
* @package quick_post_es_mod
* @version $Id: admin_qpes.php,v 1.0 09/02/2006 20:31 reddog Exp $
* @copyright (c) 2006 reddog - http://www.reddevboard.com/
* @license http://opensource.org/licenses/gpl-license.php GNU Public License 
*
*/

/**
* begin process
*/
define('IN_PHPBB', 1);

if( !empty($setmodules) )
{
	$file = basename(__FILE__);
	$module['Mods']['Quick_Post_ES'] = $file;
	return;
}

// load default header
$phpbb_root_path = './../';
$phpEx = substr(strrchr(__FILE__, '.'), 1);
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

/**
* main process
*/
$sql = 'SELECT config_name, config_value
	FROM ' . CONFIG_TABLE . '
	WHERE config_name IN (\'users_qp_settings\', \'anons_qp_settings\')';
if(!$result = $db->sql_query($sql))
{
	message_die(CRITICAL_ERROR, 'Could not query qpes config information in admin_qpes', '', __LINE__, __FILE__, $sql);
}
else
{
	while( $row = $db->sql_fetchrow($result) )
	{
		$config_name = $row['config_name'];
		$config_value = $row['config_value'];
		$default_config[$config_name] = isset($HTTP_POST_VARS['submit']) ? str_replace('\'', '\\\'', $config_value) : $config_value;
		
		$new[$config_name] = ( isset($HTTP_POST_VARS[$config_name]) ) ? $HTTP_POST_VARS[$config_name] : $default_config[$config_name];

		$user_qp = $user_qp_show = $user_qp_subject = $user_qp_bbcode = $user_qp_smilies = $user_qp_more = 0;
		$anon_qp = $anon_qp_show = $anon_qp_subject = $anon_qp_bbcode = $anon_qp_smilies = $anon_qp_more = 0;

		if (!empty($board_config['users_qp_settings']))
		{
			list($user_qp, $user_qp_show, $user_qp_subject, $user_qp_bbcode, $user_qp_smilies, $user_qp_more) = explode('-', $board_config['users_qp_settings']);
		}

		if (!empty($board_config['anons_qp_settings']))
		{
			list($anon_qp, $anon_qp_show, $anon_qp_subject, $anon_qp_bbcode, $anon_qp_smilies, $anon_qp_more) = explode('-', $board_config['anons_qp_settings']);
		}

		$params = array(
			'user_qp', 'user_qp_show', 'user_qp_subject', 'user_qp_bbcode', 'user_qp_smilies', 'user_qp_more',
			'anon_qp', 'anon_qp_show', 'anon_qp_subject', 'anon_qp_bbcode', 'anon_qp_smilies', 'anon_qp_more',
		);
		for($i = 0; $i < count($params); $i++)
		{
			$$params[$i] = ( isset($HTTP_POST_VARS[$params[$i]]) ) ? intval($HTTP_POST_VARS[$params[$i]]) : $$params[$i];
		}

		$users_qp_settings = array($user_qp, $user_qp_show, $user_qp_subject, $user_qp_bbcode, $user_qp_smilies, $user_qp_more);
		$anons_qp_settings = array($anon_qp, $anon_qp_show, $anon_qp_subject, $anon_qp_bbcode, $anon_qp_smilies, $anon_qp_more);
		$new['users_qp_settings'] = implode('-', $users_qp_settings);
		$new['anons_qp_settings'] = implode('-', $anons_qp_settings);

		if( isset($HTTP_POST_VARS['submit']) && $default_config[$config_name] != $new[$config_name] )
		{
			$sql = 'UPDATE ' . CONFIG_TABLE . ' SET
				config_value = \'' . str_replace('\\\'', '\'\'', $new[$config_name]) . '\'
				WHERE config_name = \'' . $config_name . '\'';
			if( !$db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Failed to update qpes configuration for $config_name", "", __LINE__, __FILE__, $sql);
			}
		}
	}

	if( isset($HTTP_POST_VARS['submit']) )
	{
		$message = $lang['qp_config_updated'] . '<br /><br />' . sprintf($lang['qp_click_return_config'], '<a href="' . append_sid('admin_qpes.' . $phpEx) . '">', '</a>') . '<br /><br />' . sprintf($lang['Click_return_admin_index'], '<a href="' . append_sid('index.' . $phpEx . '?pane=right') . '">', '</a>');

		message_die(GENERAL_MESSAGE, $message);
	}
}

// instantiate vars
$qp_displays = array(
	array('qp_title' => $lang['qp_enable'], 'qp_desc' => $lang['qp_enable_explain'], 'qp_user_var' => 'user_qp', 'qp_anon_var' => 'anon_qp'),
	array('qp_title' => $lang['qp_show'], 'qp_desc' => $lang['qp_show_explain'], 'qp_user_var' => 'user_qp_show', 'qp_anon_var' => 'anon_qp_show'),
	array('qp_title' => $lang['qp_subject'], 'qp_desc' => $lang['qp_subject_explain'], 'qp_user_var' => 'user_qp_subject', 'qp_anon_var' => 'anon_qp_subject'),
	array('qp_title' => $lang['qp_bbcode'], 'qp_desc' => $lang['qp_bbcode_explain'], 'qp_user_var' => 'user_qp_bbcode', 'qp_anon_var' => 'anon_qp_bbcode'),
	array('qp_title' => $lang['qp_smilies'], 'qp_desc' => $lang['qp_smilies_explain'], 'qp_user_var' => 'user_qp_smilies', 'qp_anon_var' => 'anon_qp_smilies'),
	array('qp_title' => $lang['qp_more'], 'qp_desc' => $lang['qp_more_explain'], 'qp_user_var' => 'user_qp_more', 'qp_anon_var' => 'anon_qp_more'),
);

foreach( $qp_displays as $qp_display => $qp_generate )
{
	$qp_user_var = $qp_generate['qp_user_var'];
	$qp_anon_var = $qp_generate['qp_anon_var'];

	// options constants
	$template->assign_block_vars('qpes', array(
		'L_QP_TITLE' => $qp_generate['qp_title'],
		'L_QP_DESC' => $qp_generate['qp_desc'],
		'USER_QP_VAR' => $qp_user_var,
		'ANON_QP_VAR' => $qp_anon_var,
		'USER_QP_YES' => ($$qp_user_var) ? 'checked="checked"' : '',
		'USER_QP_NO' => (!$$qp_user_var) ? 'checked="checked"' : '',
		'ANON_QP_YES' => ($$qp_anon_var) ? 'checked="checked"' : '',
		'ANON_QP_NO' => (!$$qp_anon_var) ? 'checked="checked"' : '',
	));
}

// main constants
$template->assign_vars(array(
	'L_QP_CONFIGURATION_TITLE' => $lang['qp_config_title'],
	'L_QP_CONFIGURATION_DESC' => $lang['qp_config_title_desc'],

	'L_QP_SETTINGS' => $lang['qp_settings'],
	'L_QP_USER' => $lang['qp_user'],
	'L_QP_ANON' => $lang['qp_anon'],

	'L_YES' => $lang['Yes'],
	'L_NO' => $lang['No'],
	'L_SUBMIT' => $lang['Submit'], 
	'L_RESET' => $lang['Reset'],

	'S_QPES_ACTION' => append_sid('admin_qpes.' . $phpEx),
));

// send the display
$template->set_filenames(array('body' => 'admin/qpes_body.tpl'));
$template->pparse('body');
include('./page_footer_admin.' . $phpEx);

?>