<?php
/***************************************************************************
 *                             admin_forums.php
 *                            -------------------
 *   begin                : Thursday, Jul 12, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: admin_forums.php,v 1.40.2.12 2005/05/07 22:18:10 acydburn Exp $
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
	$file = basename(__FILE__);
	$module['Forums']['Manage'] = $file;
	return;
}

//
// Load default header
//
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);
include($phpbb_root_path . 'includes/functions_admin.'.$phpEx);

$forum_auth_ary = array(
	"auth_view" => AUTH_ALL, 
	"auth_read" => AUTH_ALL, 
	"auth_post" => AUTH_REG, 
	"auth_reply" => AUTH_REG, 

	"auth_edit" => AUTH_REG, 
	"auth_delete" => AUTH_REG, 
	"auth_sticky" => AUTH_MOD, 
	"auth_announce" => AUTH_MOD, 
	"auth_vote" => AUTH_REG, 
	"auth_pollcreate" => AUTH_REG,
	"auth_ban" => AUTH_MOD, 
	"auth_greencard" => AUTH_ADMIN, 
	"auth_bluecard" => AUTH_REG
);

//
// Mode setting
//
if( isset($HTTP_POST_VARS['mode']) || isset($HTTP_GET_VARS['mode']) )
{
	$mode = ( isset($HTTP_POST_VARS['mode']) ) ? $HTTP_POST_VARS['mode'] : $HTTP_GET_VARS['mode'];
	$mode = htmlspecialchars($mode);
}
else
{
	$mode = "";
}

// ------------------
// Begin function block
//
function get_info($mode, $id)
{
	global $db;

	switch($mode)
	{
		case 'category':
			$table = CATEGORIES_TABLE;
			$idfield = 'cat_id';
			$namefield = 'cat_title';
			break;

		case 'forum':
			$table = FORUMS_TABLE;
			$idfield = 'forum_id';
			$namefield = 'forum_name';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}
	$sql = "SELECT count(*) as total
		FROM $table";
	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get Forum/Category information", "", __LINE__, __FILE__, $sql);
	}
	$count = $db->sql_fetchrow($result);
	$count = $count['total'];

	$sql = "SELECT *
		FROM $table
		WHERE $idfield = $id"; 

	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get Forum/Category information", "", __LINE__, __FILE__, $sql);
	}

	if( $db->sql_numrows($result) != 1 )
	{
		message_die(GENERAL_ERROR, "Forum/Category doesn't exist or multiple forums/categories with ID $id", "", __LINE__, __FILE__);
	}

	$return = $db->sql_fetchrow($result);
	$return['number'] = $count;
	return $return;
}

function get_list($mode, $id, $select)
{
	global $db;

	switch($mode)
	{
		case 'category':
			$table = CATEGORIES_TABLE;
			$idfield = 'cat_id';
			$namefield = 'cat_title';
			break;

		case 'forum':
			$table = FORUMS_TABLE;
			$idfield = 'forum_id';
			$namefield = 'forum_name';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}

	$sql = "SELECT *
		FROM $table";
	if( $select == 0 )
	{
		$sql .= " WHERE $idfield <> $id";
	}

	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get list of Categories/Forums", "", __LINE__, __FILE__, $sql);
	}

	$cat_list = "";

	while( $row = $db->sql_fetchrow($result) )
	{
		$s = "";
		if ($row[$idfield] == $id)
		{
			$s = " selected=\"selected\"";
		}
		$catlist .= "<option value=\"$row[$idfield]\"$s>" . $row[$namefield] . "</option>\n";
	}

	return($catlist);
}

function renumber_order($mode, $cat = 0)
{
	global $db;

	switch($mode)
	{
		case 'category':
			$table = CATEGORIES_TABLE;
			$idfield = 'cat_id';
			$orderfield = 'cat_order';
			$cat = 0;
			break;

		case 'forum':
			$table = FORUMS_TABLE;
			$idfield = 'forum_id';
			$orderfield = 'forum_order';
			$catfield = 'cat_id';
			break;

		default:
			message_die(GENERAL_ERROR, "Wrong mode for generating select list", "", __LINE__, __FILE__);
			break;
	}

	$sql = "SELECT * FROM $table";
	if( $cat != 0)
	{
		$sql .= " WHERE $catfield = $cat";
	}
	$sql .= " ORDER BY $orderfield ASC";


	if( !$result = $db->sql_query($sql) )
	{
		message_die(GENERAL_ERROR, "Couldn't get list of Categories", "", __LINE__, __FILE__, $sql);
	}

	$i = 10;
	$inc = 10;

	while( $row = $db->sql_fetchrow($result) )
	{
		$sql = "UPDATE $table
			SET $orderfield = $i
			WHERE $idfield = " . $row[$idfield];
		if( !$db->sql_query($sql) )
		{
			message_die(GENERAL_ERROR, "Couldn't update order fields", "", __LINE__, __FILE__, $sql);
		}
		$i += 10;
	}

}
//
// End function block
// ------------------

//
// Begin program proper
//
if( isset($HTTP_POST_VARS['addforum']) || isset($HTTP_POST_VARS['addcategory']) )
{
	$mode = ( isset($HTTP_POST_VARS['addforum']) ) ? "addforum" : "addcat";

	if( $mode == "addforum" )
	{
		list($cat_id) = each($HTTP_POST_VARS['addforum']);
		$cat_id = intval($cat_id);
		// 
		// stripslashes needs to be run on this because slashes are added when the forum name is posted
		//
		$forumname = stripslashes($HTTP_POST_VARS['forumname'][$cat_id]);
	}
}

if( !empty($mode) ) 
{
	switch($mode)
	{
		case 'addforum':
		case 'editforum':
			//
			// Show form to create/modify a forum
			//
			if ($mode == 'editforum')
			{
				// $newmode determines if we are going to INSERT or UPDATE after posting?

				$l_title = $lang['Edit_forum'];
				$newmode = 'modforum';
				$buttonvalue = $lang['Update'];

				$forum_id = intval($HTTP_GET_VARS[POST_FORUM_URL]);

				$row = get_info('forum', $forum_id);

				$cat_id = $row['cat_id'];
				$forumname = $row['forum_name'];
				$forumdesc = $row['forum_desc'];
				$forumstatus = $row['forum_status'];
				// Added by Attached Forums MOD

				$forum_attached_id = $row['attached_forum_id'];
				$sql = "SELECT * from ". FORUMS_TABLE. " WHERE attached_forum_id = $forum_id";
				if( !$r = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't get list of children Forums", "", __LINE__, __FILE__, $sql);
				}

				if( $db->sql_numrows($r) > 0 )
				{

					$not_attachable=1;
					$has_subforums=1;
					$template->assign_block_vars('switch_attached_no', array());
					if (intval($HTTP_POST_VARS['detach_enabled'])) $detach_enabled = "checked=\"checked\"";

				}
				else
				{

					// this forum is not a parent of any other forum
					$sql = "SELECT * FROM ". FORUMS_TABLE. " WHERE attached_forum_id=-1 and cat_id= $cat_id and forum_id<>$forum_id ORDER BY forum_order";

					if( !$result1 = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't get list of attachable Forums", "", __LINE__, __FILE__, $sql);
					}
					if( $db->sql_numrows($result1) > 0 )
					{
						$attachable_forums = '<option value = "-1"'.($forum_attached_id==-1?' selected':'').'> Choisissez votre forum de rattachement </a>';
						while( $row1 = $db->sql_fetchrow($result1) )
						{
							$s='';
							if ($forum_attached_id == $row1['forum_id'])
							{
								$s = " selected=\"selected\"";
							}
							$attachable_forums .= '<option value="'.$row1[forum_id].$s.'">' . $row1[forum_name] . '</option>\n';
						}
					}
					else
					{
						$no_attachable_forums=1;
					}

				}
				// End Added by Attached Forums MOD
//-- mod : quick post es -------------------------------------------------------
//-- add
				$forum_qpes = $row['forum_qpes'];
//-- fin mod : quick post es ---------------------------------------------------

// 
// Begin Approve_Mod Block : 14
// 
				$approve_mod = array();
				$sql = "SELECT * FROM " . APPROVE_FORUMS_TABLE . " 
					WHERE forum_id = " . intval($forum_id); 
				if ( !($result = $db->sql_query($sql)) ) 
				{ 
					message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'], '', __LINE__, __FILE__, $sql); 
				} 
				if ( $row_approve = $db->sql_fetchrow($result) ) 
				{         
					$approve_mod['moderators'] = array();
					$approve_mod['moderators'] = explode('|', $row_approve['approve_moderators']);

					$row_approve['approve_notify_user_options'] = '';					
					$row_approve['approve_notify_user_list'] = '';

					for($i = 0; !empty($approve_mod['moderators'][$i]); $i++)
					{
						$sql = "SELECT username
							FROM " . USERS_TABLE . "
							WHERE user_id = " . intval($approve_mod['moderators'][$i]);
						if ( !$result = $db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, "Couldn't get forum Prune Information","",__LINE__, __FILE__, $sql);
						}
						if ( $row_user = $db->sql_fetchrow($result) )
						{
							if ( $row_user['user_id'] != ANONYMOUS )
							{
								$row_approve['approve_notify_user_options'] = $row_approve['approve_notify_user_options'] . "\n<option>" . $row_user['username'] . "</option>";
								if ( $row_approve['approve_notify_user_list'] == '' )
								{
									$row_approve['approve_notify_user_list'] = $row_user['username'];
								}
								else
								{
									$row_approve['approve_notify_user_list'] = $row_approve['approve_notify_user_list'] . "  |  " . $row_user['username'];
									//used two spaces on either side, because phpBB truncates spaces in usernames.
								}
							}
						}
					}

					$approve_mod['notify_user_options'] = $row_approve['approve_notify_user_options'];
					$approve_mod['notify_user_list'] = $row_approve['approve_notify_user_list'];
					$approve_mod['enabled'] = ( intval($row_approve['enabled']) == 1 ) ? "checked" : ""; 
					$approve_mod['posts_enabled'] = ( intval($row_approve['approve_posts']) == 1 ) ? "checked" : ""; 
					$approve_mod['poste_enabled'] = ( intval($row_approve['approve_poste']) == 1 ) ? "checked" : ""; 
					$approve_mod['topics_enabled'] = ( intval($row_approve['approve_topics']) == 1 ) ? "checked" : ""; 
					$approve_mod['topice_enabled'] = ( intval($row_approve['approve_topice']) == 1 ) ? "checked" : ""; 
					$approve_mod['users_enabled'] = ( intval($row_approve['approve_users']) == 1 ) ? "checked" : "";
					$approve_mod['users_disabled'] = ( intval($row_approve['approve_users']) == 1 ) ? "" : "checked";
					$approve_mod['notify_user_enabled'] = ( intval($row_approve['approve_notify_approval']) == 1 ) ? "checked" : "";
					$approve_mod['notify_user_disabled'] = ( intval($row_approve['approve_notify_approval']) == 1 ) ? "" : "checked";
					$approve_mod['notify_enabled'] = ( intval($row_approve['approve_notify']) == 1 ) ? "checked" : "";
					$approve_mod['notify_disabled'] = ( intval($row_approve['approve_notify']) == 1 ) ? "" : "checked";
					$approve_mod['notify_pm_enabled'] = ( intval($row_approve['approve_notify_type']) == 1 ) ? "checked" : "";
					$approve_mod['notify_email_enabled'] = ( intval($row_approve['approve_notify_type']) == 1 ) ? "" : "checked";
					$approve_mod['notify_message_enabled'] = ( intval($row_approve['approve_notify_message']) == 1 ) ? "checked" : "";
					$approve_mod['notify_message_len'] = intval($row_approve['approve_notify_message_len']);
					$approve_mod['notify_posts_enabled'] = ( intval($row_approve['approve_notify_posts']) == 1 ) ? "checked" : "";
					$approve_mod['notify_poste_enabled'] = ( intval($row_approve['approve_notify_poste']) == 1 ) ? "checked" : "";
					$approve_mod['notify_topics_enabled'] = ( intval($row_approve['approve_notify_topics']) == 1 ) ? "checked" : "";
					$approve_mod['notify_topice_enabled'] = ( intval($row_approve['approve_notify_topice']) == 1 ) ? "checked" : "";
					$approve_mod['hide_topics_enabled'] = ( intval($row_approve['forum_hide_unapproved_topics']) == 1 ) ? "checked" : "";
					$approve_mod['hide_posts_enabled'] = ( intval($row_approve['forum_hide_unapproved_posts']) == 1 ) ? "checked" : "";
					$approve_mod['hide_topics_disabled'] = ( intval($row_approve['forum_hide_unapproved_topics']) == 1 ) ? "" : "checked";
					$approve_mod['hide_posts_disabled'] = ( intval($row_approve['forum_hide_unapproved_posts']) == 1 ) ? "" : "checked";
				}
// 
// End Approve_Mod Block : 14
//

				//
				// start forum prune stuff.
				//
				if( $row['prune_enable'] )
				{
					$prune_enabled = "checked=\"checked\"";
					$sql = "SELECT *
               			FROM " . PRUNE_TABLE . "
               			WHERE forum_id = $forum_id";
					if(!$pr_result = $db->sql_query($sql))
					{
						 message_die(GENERAL_ERROR, "Auto-Prune: Couldn't read auto_prune table.", __LINE__, __FILE__);
        			}

					$pr_row = $db->sql_fetchrow($pr_result);
				}
				else
				{
					$prune_enabled = '';
				}
			}
			else
			{
				$l_title = $lang['Create_forum'];
				$newmode = 'createforum';
				$buttonvalue = $lang['Create_forum'];

				$forumdesc = '';
				$forumstatus = FORUM_UNLOCKED;
//-- mod : quick post es -------------------------------------------------------
//-- add
				$forum_qpes = 1;
//-- fin mod : quick post es ---------------------------------------------------
				$forum_id = ''; 
				$prune_enabled = '';
   				// Added by Attached Forums MOD

				$sql = "SELECT * FROM ". FORUMS_TABLE. " WHERE attached_forum_id=-1 and cat_id= $cat_id ORDER BY forum_order";

				if( !$result1 = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't get list of Categories/Forums", "", __LINE__, __FILE__, $sql);
				}
				if( $db->sql_numrows($result1) > 0 )
				{
					$attachable_forums = '<option value = "-1"'.(($forum_attached_id==-1 || !$forum_attached_id)?' selected':'').'> NOT ATTACHED TO ANY FORUM </a>';
					while( $row1 = $db->sql_fetchrow($result1) )
					{

						if ($forum_attached_id == $row1['forum_id'])
						{
							$s = " selected=\"selected\"";
						}
						$attachable_forums .= '<option value="'.$row1[forum_id].$s.'">' . $row1[forum_name] . '</option>\n';
					}


				}
				else
				{
					$no_attachable_forums=1;
				}
   				// END Added by Attached Forums MOD
			}

			// Added by Attached Forums MOD
			$forum_attached_id = $attachable_forums;
   			// END Added by Attached Forums MOD
			$catlist = get_list('category', $cat_id, TRUE);

			$forumstatus == ( FORUM_LOCKED ) ? $forumlocked = "selected=\"selected\"" : $forumunlocked = "selected=\"selected\"";
			
			// These two options ($lang['Status_unlocked'] and $lang['Status_locked']) seem to be missing from
			// the language files.
			$lang['Status_unlocked'] = isset($lang['Status_unlocked']) ? $lang['Status_unlocked'] : 'Unlocked';
			$lang['Status_locked'] = isset($lang['Status_locked']) ? $lang['Status_locked'] : 'Locked';
			
			$statuslist = "<option value=\"" . FORUM_UNLOCKED . "\" $forumunlocked>" . $lang['Status_unlocked'] . "</option>\n";
			$statuslist .= "<option value=\"" . FORUM_LOCKED . "\" $forumlocked>" . $lang['Status_locked'] . "</option>\n"; 

			$template->set_filenames(array(
				"body" => "admin/forum_edit_body.tpl")
			);

			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode .'" /><input type="hidden" name="' . POST_FORUM_URL . '" value="' . $forum_id . '" />';
   			// Added by Attached Forums MOD

			if ($not_attachable or $no_attachable_forums)
			{
				if ($has_subforums)
				{
					$lang['Attached_Description'] = $lang['Has_attachments'].'<br>'. $lang['Attached_Description'];
					$s_hidden_fields .='<input type="hidden" name="has_subforums" value="1" />';
				}
				if ($no_attachable_forums) $lang['Attached_Description'] = $lang['No_attach_forums'].'<br>'. $lang['Attached_Description'];
				$s_hidden_fields .='<input type="hidden" name="attached_forum_id" value="-1" />';
			}
			else
			{
				$template->assign_block_vars('switch_attached_yes', array());
			}
				
			$s_hidden_fields .='<input type="hidden" name="old_cat_id" value="'.$cat_id.'" />';
   			// END Added by Attached Forums MOD

//	
// Begin Approve_Mod Block : 15
//
			if ( $mode == 'editforum' )
			{
				$template->assign_block_vars("approve_mod_switch", array() );
				$template->assign_vars(array(
					'L_APPROVE_POSTS' => $lang['approve_admin_posts'],
					'L_APPROVE_ENABLE' => $lang['approve_admin_enable'],
					'L_APPROVE_POSTS_TOPICS' => $lang['approve_admin_posts_topics'],
					'L_APPROVE_NOTIFY_POSTS_TOPICS' => $lang['approve_admin_notify_posts_topics'],
					'L_APPROVE_POSTS_ENABLE' => $lang['approve_admin_posts_enable'],
					'L_APPROVE_TOPICS_ENABLE' => $lang['approve_admin_topics_enable'], 
					'L_APPROVE_USERS_ENABLE' => $lang['approve_admin_users_enable'], 
					'L_APPROVE_USERS_ALL' => $lang['approve_admin_users_all'], 
					'L_APPROVE_USERS_MOD' => $lang['approve_admin_users_mod'], 
					'L_APPROVE_POSTE_ENABLE' => $lang['approve_admin_poste_enable'], 
					'L_APPROVE_TOPICE_ENABLE' => $lang['approve_admin_topice_enable'], 
					'L_APPROVE_NOTIFY_ENABLE' => $lang['approve_admin_notify_admin_enable'],
					'L_APPROVE_NOTIFY_USER_ENABLE' => $lang['approve_admin_notify_user_enable'],
					'L_APPROVE_NOTIFY_TYPE' => $lang['approve_admin_notify_type'],
					'L_APPROVE_NOTIFY_PM_ENABLE' => $lang['approve_admin_notify_pm_enable'],
					'L_APPROVE_NOTIFY_EMAIL_ENABLE' => $lang['approve_admin_notify_email_enable'],
					'L_APPROVE_NOTIFY_MESSAGE_ENABLE' => $lang['approve_admin_notify_message_enable'],
					'L_APPROVE_NOTIFY_MESSAGE_LEN' => $lang['approve_admin_notify_message_length'],
					'L_APPROVE_NOTIFY_ENABLED' => $lang['Enabled'],
					'L_APPROVE_NOTIFY_DISABLED' => $lang['Disabled'], 
					'L_APPROVE_NOTIFY_USER' => $lang['approve_admin_moderators'],
					'L_APPROVE_NOTIFY_POSTS_ENABLE' => $lang['approve_admin_notify_posts_enable'],
					'L_APPROVE_NOTIFY_POSTE_ENABLE' => $lang['approve_admin_notify_poste_enable'],
					'L_APPROVE_NOTIFY_TOPICS_ENABLE' => $lang['approve_admin_notify_topics_enable'],
					'L_APPROVE_NOTIFY_TOPICE_ENABLE' => $lang['approve_admin_notify_topice_enable'],
					'L_APPROVE_BUTTON_FIND' => $lang['approve_admin_button_find'],
					'L_APPROVE_BUTTON_ADD' => $lang['approve_admin_button_add'],
					'L_APPROVE_BUTTON_REM' => $lang['approve_admin_button_rem'],
					'L_APPROVE_HIDE_TOPICS_ENABLE' => $lang['approve_admin_hide_topics_enable'],
					'L_APPROVE_HIDE_POSTS_ENABLE' => $lang['approve_admin_hide_posts_enable'],
					'S_APPROVE_ENABLED' => $approve_mod['enabled'],
					'S_APPROVE_POSTS_ENABLED' => $approve_mod['posts_enabled'],
					'S_APPROVE_POSTE_ENABLED' => $approve_mod['poste_enabled'],
					'S_APPROVE_TOPICS_ENABLED' => $approve_mod['topics_enabled'],
					'S_APPROVE_TOPICE_ENABLED' => $approve_mod['topice_enabled'],
					'S_APPROVE_USERS_ENABLED' => $approve_mod['users_enabled'],
					'S_APPROVE_USERS_DISABLED' => $approve_mod['users_disabled'],
					'S_APPROVE_NOTIFY_ENABLED' => $approve_mod['notify_enabled'],
					'S_APPROVE_NOTIFY_USER_ENABLED' => $approve_mod['notify_user_enabled'],
					'S_APPROVE_NOTIFY_USER_DISABLED' => $approve_mod['notify_user_disabled'],
					'S_APPROVE_NOTIFY_PM_ENABLED' => $approve_mod['notify_pm_enabled'],
					'S_APPROVE_NOTIFY_EMAIL_ENABLED' => $approve_mod['notify_email_enabled'],
					'S_APPROVE_NOTIFY_MESSAGE_ENABLED' => $approve_mod['notify_message_enabled'],
					'S_APPROVE_NOTIFY_MESSAGE_LEN' => $approve_mod['notify_message_len'],
					'S_APPROVE_NOTIFY_DISABLED' => $approve_mod['notify_disabled'],
					'S_APPROVE_NOTIFY_USER_OPTIONS' => $approve_mod['notify_user_options'],
					'S_APPROVE_NOTIFY_USER_LIST' => $approve_mod['notify_user_list'],
					'S_APPROVE_NOTIFY_POSTS_ENABLED' => $approve_mod['notify_posts_enabled'],
					'S_APPROVE_NOTIFY_POSTE_ENABLED' => $approve_mod['notify_poste_enabled'],
					'S_APPROVE_NOTIFY_TOPICS_ENABLED' => $approve_mod['notify_topics_enabled'],
					'S_APPROVE_NOTIFY_TOPICE_ENABLED' => $approve_mod['notify_topice_enabled'],
					'S_APPROVE_HIDE_TOPICS_ENABLED' => $approve_mod['hide_topics_enabled'],
					'S_APPROVE_HIDE_POSTS_ENABLED' => $approve_mod['hide_posts_enabled'],
					'S_APPROVE_HIDE_TOPICS_DISABLED' => $approve_mod['hide_topics_disabled'],
					'S_APPROVE_HIDE_POSTS_DISABLED' => $approve_mod['hide_posts_disabled'] )
				);
			}
// 
// End Approve_Mod Block : 15
//

			$template->assign_vars(array(
				'S_FORUM_ACTION' => append_sid("admin_forums.$phpEx"),
				'S_HIDDEN_FIELDS' => $s_hidden_fields,
				'S_SUBMIT_VALUE' => $buttonvalue, 
				'S_CAT_LIST' => $catlist,
				'S_STATUS_LIST' => $statuslist,
				'S_PRUNE_ENABLED' => $prune_enabled,
				// Added by Attached Forums MOD
				'S_ATTACHED_FORUM_ID' => $forum_attached_id,
				'S_DETACH_ENABLED'=> $detach_enabled,
				// End Added by Attached Forums MOD    
//-- mod : quick post es -------------------------------------------------------
//-- add
				'L_YES' => $lang['Yes'],
				'L_NO' => $lang['No'],
				'L_QP_TITLE' => $lang['qp_quick_post'],
				'FORUM_QP_YES' => ($forum_qpes) ? 'checked="checked"' : '',
				'FORUM_QP_NO' => (!$forum_qpes) ? 'checked="checked"' : '',
//-- fin mod : quick post es ---------------------------------------------------

				'L_FORUM_TITLE' => $l_title, 
				'L_FORUM_EXPLAIN' => $lang['Forum_edit_delete_explain'], 
				'L_FORUM_SETTINGS' => $lang['Forum_settings'], 
				'L_FORUM_NAME' => $lang['Forum_name'], 
				'L_CATEGORY' => $lang['Category'], 
				// Added by Attached Forums MOD
				'L_ATTACHED_FORUM' => $lang['Attached_Field_Title'] ,
				'L_ATTACHED_DESC' => $lang['Attached_Description'],
				'L_DETACH_DESC'	=> $lang['Detach_Description'],
				// End Added by Attached Forums MOD
				'L_FORUM_DESCRIPTION' => $lang['Forum_desc'],
				'L_FORUM_STATUS' => $lang['Forum_status'],
				'L_AUTO_PRUNE' => $lang['Forum_pruning'],
				'L_ENABLED' => $lang['Enabled'],
				'L_PRUNE_DAYS' => $lang['prune_days'],
				'L_PRUNE_FREQ' => $lang['prune_freq'],
				'L_DAYS' => $lang['Days'],

				'PRUNE_DAYS' => ( isset($pr_row['prune_days']) ) ? $pr_row['prune_days'] : 7,
				'PRUNE_FREQ' => ( isset($pr_row['prune_freq']) ) ? $pr_row['prune_freq'] : 1,
				'FORUM_NAME' => $forumname,
				'DESCRIPTION' => $forumdesc)
			);
			$template->pparse("body");
			break;

		case 'createforum':
			//
			// Create a forum in the DB
			//
			if( trim($HTTP_POST_VARS['forumname']) == "" )
			{
				message_die(GENERAL_ERROR, "Can't create a forum without a name");
			}

			$sql = "SELECT MAX(forum_order) AS max_order
				FROM " . FORUMS_TABLE . "
				WHERE cat_id = " . intval($HTTP_POST_VARS[POST_CAT_URL]);
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get order number from forums table", "", __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$max_order = $row['max_order'];
			$next_order = $max_order + 10;
			
			$sql = "SELECT MAX(forum_id) AS max_id
				FROM " . FORUMS_TABLE;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get order number from forums table", "", __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$max_id = $row['max_id'];
			$next_id = $max_id + 1;

			//
			// Default permissions of public :: 
			//
			$field_sql = "";
			$value_sql = "";
			while( list($field, $value) = each($forum_auth_ary) )
			{
				$field_sql .= ", $field";
				$value_sql .= ", $value";

			}

			// There is no problem having duplicate forum names so we won't check for it.
//-- mod : quick post es -------------------------------------------------------
// here we added
//	, forum_qpes
//	, " . intval($HTTP_POST_VARS['forum_qpes']) . "
//-- modify
   			// Modified by Attached Forums MOD
			if (intval($HTTP_POST_VARS['old_cat_id']) != intval($HTTP_POST_VARS[POST_CAT_URL]))
			{
   				$HTTP_POST_VARS['attached_forum_id']=-1;
   			}

			$sql = "INSERT INTO " . FORUMS_TABLE . " (forum_id, forum_name, cat_id, attached_forum_id, forum_desc, forum_order, forum_status, forum_qpes, prune_enable" . $field_sql . ")
				VALUES ('" . $next_id . "', '" . str_replace("\'", "''", $HTTP_POST_VARS['forumname']) . "', " . intval($HTTP_POST_VARS[POST_CAT_URL]) .  ", " . intval($HTTP_POST_VARS['attached_forum_id']) . ", '" . str_replace("\'", "''", $HTTP_POST_VARS['forumdesc']) . "', $next_order, " . intval($HTTP_POST_VARS['forumstatus']) . ", " . intval($HTTP_POST_VARS['forum_qpes']) . ", " . intval($HTTP_POST_VARS['prune_enable']) . $value_sql . ")";
   			// End Added by Attached Forums MOD
//-- fin mod : quick post es ---------------------------------------------------
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't insert row in forums table", "", __LINE__, __FILE__, $sql);
			}

			if( $HTTP_POST_VARS['prune_enable'] )
			{

				if( $HTTP_POST_VARS['prune_days'] == "" || $HTTP_POST_VARS['prune_freq'] == "")
				{
					message_die(GENERAL_MESSAGE, $lang['Set_prune_data']);
				}

				$sql = "INSERT INTO " . PRUNE_TABLE . " (forum_id, prune_days, prune_freq)
					VALUES('" . $next_id . "', " . intval($HTTP_POST_VARS['prune_days']) . ", " . intval($HTTP_POST_VARS['prune_freq']) . ")";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't insert row in prune table", "", __LINE__, __FILE__, $sql);
				}
			}

			$message = $lang['Forums_updated'] . "<br /><br />" . sprintf($lang['Click_return_forumadmin'], "<a href=\"" . append_sid("admin_forums.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;

		case 'modforum':
// 
// Begin Approve_Mod Block : 16
// 
			$approve_notify_user_list = '';
			$approve_enable = (intval($HTTP_POST_VARS['approve_enable']) == 1) ? 1 : 0;

			if ( trim($HTTP_POST_VARS['usernames_list']) != '' ) 
			{ 
				$approve_mod['moderators'] = array();
				$approve_mod['moderators'] = explode('  |  ', trim($HTTP_POST_VARS['usernames_list']));

				for($i = 0; !empty($approve_mod['moderators'][$i]); $i++)
				{
					$sql = "SELECT user_id, user_level
						FROM " . USERS_TABLE . "
						WHERE username = '" . $approve_mod['moderators'][$i] . "'";
					if ( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'],"",__LINE__, __FILE__, $sql);
					}
					if ( $row_user = $db->sql_fetchrow($result) )
					{
						if ( intval($row_user['user_id']) != intval(ANONYMOUS) && intval($row_user['user_level']) != intval(DELETED) )
						{
							if ( $approve_notify_user_list == '' ) 
							{
								$approve_notify_user_list = $row_user['user_id'];
							}
							else
							{
								$approve_notify_user_list = $approve_notify_user_list . '|' . $row_user['user_id'];
							}
						}
						else
						{
							message_die(GENERAL_ERROR, $lang['approve_admin_notify_user_invalid'] . $approve_mod['moderators'][$i]);
						}
					}
					else
					{
						message_die(GENERAL_ERROR, $lang['approve_admin_notify_user_invalid'] . $approve_mod['moderators'][$i]);
					}
				}
			}
			else
			{
				if ( $HTTP_POST_VARS['approve_notify_enable'] )
				{
					message_die(GENERAL_ERROR, $lang['approve_admin_notify_user_empty']);
				}
			}
			$sql = "DELETE FROM " . APPROVE_FORUMS_TABLE . " 
				WHERE forum_id = " . intval($HTTP_POST_VARS[POST_FORUM_URL]);
			if ( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, $lang['approve_posts_error_delete'], '', __LINE__, __FILE__, $sql);
			}
			$sql = "INSERT INTO " . APPROVE_FORUMS_TABLE . " (forum_id, enabled, approve_posts, approve_topics, approve_users, approve_poste, approve_topice, approve_notify, approve_notify_type, approve_notify_message, approve_notify_message_len, approve_moderators, approve_notify_posts, approve_notify_poste, approve_notify_topics, approve_notify_topice, approve_notify_approval, forum_hide_unapproved_topics, forum_hide_unapproved_posts) 
				VALUES (" . intval($HTTP_POST_VARS[POST_FORUM_URL]) . ", " . intval($approve_enable) . ", " . intval($HTTP_POST_VARS['approve_posts_enable']) . ", " . intval($HTTP_POST_VARS['approve_topics_enable']) . ", " . intval($HTTP_POST_VARS['approve_users_enable']) . ", " . intval($HTTP_POST_VARS['approve_poste_enable']) . ", " . intval($HTTP_POST_VARS['approve_topice_enable']) . ", " . intval($HTTP_POST_VARS['approve_notify_enable']) . ", " . intval($HTTP_POST_VARS['approve_notify_type_enable']) . ", " . intval($HTTP_POST_VARS['approve_notify_message_enable']) . ", " . intval($HTTP_POST_VARS['approve_notify_message_len']) . ", '" . $approve_notify_user_list . "', " . intval($HTTP_POST_VARS['approve_notify_posts_enable']) . ", " . intval($HTTP_POST_VARS['approve_notify_poste_enable']) . ", " . intval($HTTP_POST_VARS['approve_notify_topics_enable']) . ", " . intval($HTTP_POST_VARS['approve_notify_topice_enable']) . ", " . intval($HTTP_POST_VARS['approve_notify_user_enable']) . ", " . intval($HTTP_POST_VARS['approve_hide_topics_enable']) . ", " . intval($HTTP_POST_VARS['approve_hide_posts_enable']) . ")";
			if ( !$result = $db->sql_query($sql) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_insert'], '', __LINE__, __FILE__, $sql); 
			} 
// 
// End Approve_Mod Block : 16
//

			// Modify a forum in the DB
			if( isset($HTTP_POST_VARS['prune_enable']))
			{
				if( $HTTP_POST_VARS['prune_enable'] != 1 )
				{
					$HTTP_POST_VARS['prune_enable'] = 0;
				}
			}

//-- mod : quick post es -------------------------------------------------------
// here we added
//	, forum_qpes = " . intval($HTTP_POST_VARS['forum_qpes']) . "
//-- modify
   			// Modified by Attached Forums MOD
			if (isset($HTTP_POST_VARS['detach_enabled']) && isset($HTTP_POST_VARS['has_subforums']))
			{
				$sql = "UPDATE ". FORUMS_TABLE. " SET attached_forum_id=-1 WHERE attached_forum_id=" . intval($HTTP_POST_VARS[POST_FORUM_URL]);
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't detach subforums", "", __LINE__, __FILE__, $sql);
				}

			}

 			if (intval($HTTP_POST_VARS['old_cat_id']) != intval($HTTP_POST_VARS[POST_CAT_URL]))
   			{
				$HTTP_POST_VARS['attached_forum_id']=-1;
				if (isset($HTTP_POST_VARS['has_subforums']) && !isset($HTTP_POST_VARS['detach_enabled']))
				{
					$sql = "UPDATE ". FORUMS_TABLE ." SET cat_id=". intval($HTTP_POST_VARS[POST_CAT_URL]) ." WHERE attached_forum_id=" . intval($HTTP_POST_VARS[POST_FORUM_URL]);
					if( !$result = $db->sql_query($sql) )
					{
						message_die(GENERAL_ERROR, "Couldn't update subforums to new category", "", __LINE__, __FILE__, $sql);
					}

				}
			}

			$sql = "UPDATE " . FORUMS_TABLE . "
				SET forum_name = '" . str_replace("\'", "''", $HTTP_POST_VARS['forumname']) . "', cat_id = " . intval($HTTP_POST_VARS[POST_CAT_URL]) .", attached_forum_id = " . intval($HTTP_POST_VARS['attached_forum_id']) . ", forum_desc = '" . str_replace("\'", "''", $HTTP_POST_VARS['forumdesc']) . "', forum_status = " . intval($HTTP_POST_VARS['forumstatus']) . ", forum_qpes = " . intval($HTTP_POST_VARS['forum_qpes']) . ", prune_enable = " . intval($HTTP_POST_VARS['prune_enable']) . "
				WHERE forum_id = " . intval($HTTP_POST_VARS[POST_FORUM_URL]);
   			// End Added by Attached Forums MOD
//-- fin mod : quick post es ---------------------------------------------------
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't update forum information", "", __LINE__, __FILE__, $sql);
			}

			if( $HTTP_POST_VARS['prune_enable'] == 1 )
			{
				if( $HTTP_POST_VARS['prune_days'] == "" || $HTTP_POST_VARS['prune_freq'] == "" )
				{
					message_die(GENERAL_MESSAGE, $lang['Set_prune_data']);
				}

				$sql = "SELECT *
					FROM " . PRUNE_TABLE . "
					WHERE forum_id = " . intval($HTTP_POST_VARS[POST_FORUM_URL]);
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't get forum Prune Information","",__LINE__, __FILE__, $sql);
				}

				if( $db->sql_numrows($result) > 0 )
				{
					$sql = "UPDATE " . PRUNE_TABLE . "
						SET	prune_days = " . intval($HTTP_POST_VARS['prune_days']) . ",	prune_freq = " . intval($HTTP_POST_VARS['prune_freq']) . "
				 		WHERE forum_id = " . intval($HTTP_POST_VARS[POST_FORUM_URL]);
				}
				else
				{
					$sql = "INSERT INTO " . PRUNE_TABLE . " (forum_id, prune_days, prune_freq)
						VALUES(" . intval($HTTP_POST_VARS[POST_FORUM_URL]) . ", " . intval($HTTP_POST_VARS['prune_days']) . ", " . intval($HTTP_POST_VARS['prune_freq']) . ")";
				}

				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't Update Forum Prune Information","",__LINE__, __FILE__, $sql);
				}
			}

			$message = $lang['Forums_updated'] . "<br /><br />" . sprintf($lang['Click_return_forumadmin'], "<a href=\"" . append_sid("admin_forums.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;
			
		case 'addcat':
			// Create a category in the DB
			if( trim($HTTP_POST_VARS['categoryname']) == '')
			{
				message_die(GENERAL_ERROR, "Can't create a category without a name");
			}

			$sql = "SELECT MAX(cat_order) AS max_order
				FROM " . CATEGORIES_TABLE;
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't get order number from categories table", "", __LINE__, __FILE__, $sql);
			}
			$row = $db->sql_fetchrow($result);

			$max_order = $row['max_order'];
			$next_order = $max_order + 10;

			//
			// There is no problem having duplicate forum names so we won't check for it.
			//
			$sql = "INSERT INTO " . CATEGORIES_TABLE . " (cat_title, cat_order)
				VALUES ('" . str_replace("\'", "''", $HTTP_POST_VARS['categoryname']) . "', $next_order)";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't insert row in categories table", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Forums_updated'] . "<br /><br />" . sprintf($lang['Click_return_forumadmin'], "<a href=\"" . append_sid("admin_forums.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;
			
		case 'editcat':
			//
			// Show form to edit a category
			//
			$newmode = 'modcat';
			$buttonvalue = $lang['Update'];

			$cat_id = intval($HTTP_GET_VARS[POST_CAT_URL]);

			$row = get_info('category', $cat_id);
			$cat_title = $row['cat_title'];

			$template->set_filenames(array(
				"body" => "admin/category_edit_body.tpl")
			);

			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="' . POST_CAT_URL . '" value="' . $cat_id . '" />';

			$template->assign_vars(array(
				'CAT_TITLE' => $cat_title,

				'L_EDIT_CATEGORY' => $lang['Edit_Category'], 
				'L_EDIT_CATEGORY_EXPLAIN' => $lang['Edit_Category_explain'], 
				'L_CATEGORY' => $lang['Category'], 

				'S_HIDDEN_FIELDS' => $s_hidden_fields, 
				'S_SUBMIT_VALUE' => $buttonvalue, 
				'S_FORUM_ACTION' => append_sid("admin_forums.$phpEx"))
			);

			$template->pparse("body");
			break;

		case 'modcat':
			// Modify a category in the DB
			$sql = "UPDATE " . CATEGORIES_TABLE . "
				SET cat_title = '" . str_replace("\'", "''", $HTTP_POST_VARS['cat_title']) . "'
				WHERE cat_id = " . intval($HTTP_POST_VARS[POST_CAT_URL]);
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't update forum information", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Forums_updated'] . "<br /><br />" . sprintf($lang['Click_return_forumadmin'], "<a href=\"" . append_sid("admin_forums.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;
			
		case 'deleteforum':
			// Show form to delete a forum
			$forum_id = intval($HTTP_GET_VARS[POST_FORUM_URL]);

			$select_to = '<select name="to_id">';
			$select_to .= "<option value=\"-1\"$s>" . $lang['Delete_all_posts'] . "</option>\n";
			$select_to .= get_list('forum', $forum_id, 0);
			$select_to .= '</select>';

			$buttonvalue = $lang['Move_and_Delete'];

			$newmode = 'movedelforum';

			$foruminfo = get_info('forum', $forum_id);
			$name = $foruminfo['forum_name'];

			$template->set_filenames(array(
				"body" => "admin/forum_delete_body.tpl")
			);

			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="from_id" value="' . $forum_id . '" />';

			$template->assign_vars(array(
				'NAME' => $name, 

				'L_FORUM_DELETE' => $lang['Forum_delete'], 
				'L_FORUM_DELETE_EXPLAIN' => $lang['Forum_delete_explain'], 
				'L_MOVE_CONTENTS' => $lang['Move_contents'], 
				'L_FORUM_NAME' => $lang['Forum_name'], 

				"S_HIDDEN_FIELDS" => $s_hidden_fields,
				'S_FORUM_ACTION' => append_sid("admin_forums.$phpEx"), 
				'S_SELECT_TO' => $select_to,
				'S_SUBMIT_VALUE' => $buttonvalue)
			);

			$template->pparse("body");
			break;

		case 'movedelforum':
			//
			// Move or delete a forum in the DB
			//
			$from_id = intval($HTTP_POST_VARS['from_id']);
			$to_id = intval($HTTP_POST_VARS['to_id']);
			$delete_old = intval($HTTP_POST_VARS['delete_old']);

			// Either delete or move all posts in a forum
			if($to_id == -1)
			{
				// Delete polls in this forum
				$sql = "SELECT v.vote_id 
					FROM " . VOTE_DESC_TABLE . " v, " . TOPICS_TABLE . " t 
					WHERE t.forum_id = $from_id 
						AND v.topic_id = t.topic_id";
				if (!($result = $db->sql_query($sql)))
				{
					message_die(GENERAL_ERROR, "Couldn't obtain list of vote ids", "", __LINE__, __FILE__, $sql);
				}

				if ($row = $db->sql_fetchrow($result))
				{
					$vote_ids = '';
					do
					{
						$vote_ids = (($vote_ids != '') ? ', ' : '') . $row['vote_id'];
					}
					while ($row = $db->sql_fetchrow($result));

					$sql = "DELETE FROM " . VOTE_DESC_TABLE . " 
						WHERE vote_id IN ($vote_ids)";
					$db->sql_query($sql);

					$sql = "DELETE FROM " . VOTE_RESULTS_TABLE . " 
						WHERE vote_id IN ($vote_ids)";
					$db->sql_query($sql);

					$sql = "DELETE FROM " . VOTE_USERS_TABLE . " 
						WHERE vote_id IN ($vote_ids)";
					$db->sql_query($sql);
				}
				$db->sql_freeresult($result);
				
				include($phpbb_root_path . "includes/prune.$phpEx");
				prune($from_id, 0, true); // Delete everything from forum
			}
			else
			{
				$sql = "SELECT *
					FROM " . FORUMS_TABLE . "
					WHERE forum_id IN ($from_id, $to_id)";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't verify existence of forums", "", __LINE__, __FILE__, $sql);
				}

				if($db->sql_numrows($result) != 2)
				{
					message_die(GENERAL_ERROR, "Ambiguous forum ID's", "", __LINE__, __FILE__);
				}
				$sql = "UPDATE " . TOPICS_TABLE . "
					SET forum_id = $to_id
					WHERE forum_id = $from_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't move topics to other forum", "", __LINE__, __FILE__, $sql);
				}
				$sql = "UPDATE " . POSTS_TABLE . "
					SET	forum_id = $to_id
					WHERE forum_id = $from_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't move posts to other forum", "", __LINE__, __FILE__, $sql);
				}
				sync('forum', $to_id);
			}

			// Alter Mod level if appropriate - 2.0.4
			$sql = "SELECT ug.user_id 
				FROM " . AUTH_ACCESS_TABLE . " a, " . USER_GROUP_TABLE . " ug 
				WHERE a.forum_id <> $from_id 
					AND a.auth_mod = 1
					AND ug.group_id = a.group_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't obtain moderator list", "", __LINE__, __FILE__, $sql);
			}

			if ($row = $db->sql_fetchrow($result))
			{
				$user_ids = '';
				do
				{
					$user_ids .= (($user_ids != '') ? ', ' : '' ) . $row['user_id'];
				}
				while ($row = $db->sql_fetchrow($result));

				$sql = "SELECT ug.user_id 
					FROM " . AUTH_ACCESS_TABLE . " a, " . USER_GROUP_TABLE . " ug 
					WHERE a.forum_id = $from_id 
						AND a.auth_mod = 1 
						AND ug.group_id = a.group_id
						AND ug.user_id NOT IN ($user_ids)";
				if( !$result2 = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't obtain moderator list", "", __LINE__, __FILE__, $sql);
				}
					
				if ($row = $db->sql_fetchrow($result2))
				{
					$user_ids = '';
					do
					{
						$user_ids .= (($user_ids != '') ? ', ' : '' ) . $row['user_id'];
					}
					while ($row = $db->sql_fetchrow($result2));

					$sql = "UPDATE " . USERS_TABLE . " 
						SET user_level = " . USER . " 
						WHERE user_id IN ($user_ids) 
							AND user_level <> " . ADMIN;
					$db->sql_query($sql);
				}
				$db->sql_freeresult($result);

			}
			$db->sql_freeresult($result2);

			$sql = "DELETE FROM " . FORUMS_TABLE . "
				WHERE forum_id = $from_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete forum", "", __LINE__, __FILE__, $sql);
			}
			
			$sql = "DELETE FROM " . AUTH_ACCESS_TABLE . "
				WHERE forum_id = $from_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete forum", "", __LINE__, __FILE__, $sql);
			}
			
			$sql = "DELETE FROM " . PRUNE_TABLE . "
				WHERE forum_id = $from_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete forum prune information!", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Forums_updated'] . "<br /><br />" . sprintf($lang['Click_return_forumadmin'], "<a href=\"" . append_sid("admin_forums.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;
			
		case 'deletecat':
			//
			// Show form to delete a category
			//
			$cat_id = intval($HTTP_GET_VARS[POST_CAT_URL]);

			$buttonvalue = $lang['Move_and_Delete'];
			$newmode = 'movedelcat';
			$catinfo = get_info('category', $cat_id);
			$name = $catinfo['cat_title'];

			if ($catinfo['number'] == 1)
			{
				$sql = "SELECT count(*) as total
					FROM ". FORUMS_TABLE;
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't get Forum count", "", __LINE__, __FILE__, $sql);
				}
				$count = $db->sql_fetchrow($result);
				$count = $count['total'];

				if ($count > 0)
				{
					message_die(GENERAL_ERROR, $lang['Must_delete_forums']);
				}
				else
				{
					$select_to = $lang['Nowhere_to_move'];
				}
			}
			else
			{
				$select_to = '<select name="to_id">';
				$select_to .= get_list('category', $cat_id, 0);
				$select_to .= '</select>';
			}

			$template->set_filenames(array(
				"body" => "admin/forum_delete_body.tpl")
			);

			$s_hidden_fields = '<input type="hidden" name="mode" value="' . $newmode . '" /><input type="hidden" name="from_id" value="' . $cat_id . '" />';

			$template->assign_vars(array(
				'NAME' => $name, 

				'L_FORUM_DELETE' => $lang['Forum_delete'], 
				'L_FORUM_DELETE_EXPLAIN' => $lang['Forum_delete_explain'], 
				'L_MOVE_CONTENTS' => $lang['Move_contents'], 
				'L_FORUM_NAME' => $lang['Forum_name'], 
				
				'S_HIDDEN_FIELDS' => $s_hidden_fields,
				'S_FORUM_ACTION' => append_sid("admin_forums.$phpEx"), 
				'S_SELECT_TO' => $select_to,
				'S_SUBMIT_VALUE' => $buttonvalue)
			);

			$template->pparse("body");
			break;

		case 'movedelcat':
			//
			// Move or delete a category in the DB
			//
			$from_id = intval($HTTP_POST_VARS['from_id']);
			$to_id = intval($HTTP_POST_VARS['to_id']);

			if (!empty($to_id))
			{
				$sql = "SELECT *
					FROM " . CATEGORIES_TABLE . "
					WHERE cat_id IN ($from_id, $to_id)";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't verify existence of categories", "", __LINE__, __FILE__, $sql);
				}
				if($db->sql_numrows($result) != 2)
				{
					message_die(GENERAL_ERROR, "Ambiguous category ID's", "", __LINE__, __FILE__);
				}

				$sql = "UPDATE " . FORUMS_TABLE . "
					SET cat_id = $to_id
					WHERE cat_id = $from_id";
				if( !$result = $db->sql_query($sql) )
				{
					message_die(GENERAL_ERROR, "Couldn't move forums to other category", "", __LINE__, __FILE__, $sql);
				}
			}

			$sql = "DELETE FROM " . CATEGORIES_TABLE ."
				WHERE cat_id = $from_id";
				
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't delete category", "", __LINE__, __FILE__, $sql);
			}

			$message = $lang['Forums_updated'] . "<br /><br />" . sprintf($lang['Click_return_forumadmin'], "<a href=\"" . append_sid("admin_forums.$phpEx") . "\">", "</a>") . "<br /><br />" . sprintf($lang['Click_return_admin_index'], "<a href=\"" . append_sid("index.$phpEx?pane=right") . "\">", "</a>");

			message_die(GENERAL_MESSAGE, $message);

			break;

		case 'forum_order':
			//
			// Change order of forums in the DB
			//
			$move = intval($HTTP_GET_VARS['move']);
			$forum_id = intval($HTTP_GET_VARS[POST_FORUM_URL]);

			$forum_info = get_info('forum', $forum_id);

			$cat_id = $forum_info['cat_id'];

			$sql = "UPDATE " . FORUMS_TABLE . "
				SET forum_order = forum_order + $move
				WHERE forum_id = $forum_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't change category order", "", __LINE__, __FILE__, $sql);
			}

			renumber_order('forum', $forum_info['cat_id']);
			$show_index = TRUE;

			break;
			
		case 'cat_order':
			//
			// Change order of categories in the DB
			//
			$move = intval($HTTP_GET_VARS['move']);
			$cat_id = intval($HTTP_GET_VARS[POST_CAT_URL]);

			$sql = "UPDATE " . CATEGORIES_TABLE . "
				SET cat_order = cat_order + $move
				WHERE cat_id = $cat_id";
			if( !$result = $db->sql_query($sql) )
			{
				message_die(GENERAL_ERROR, "Couldn't change category order", "", __LINE__, __FILE__, $sql);
			}

			renumber_order('category');
			$show_index = TRUE;

			break;

		case 'forum_sync':
			sync('forum', intval($HTTP_GET_VARS[POST_FORUM_URL]));
			$show_index = TRUE;

			break;

		default:
			message_die(GENERAL_MESSAGE, $lang['No_mode']);
			break;
	}

	if ($show_index != TRUE)
	{
		include('./page_footer_admin.'.$phpEx);
		exit;
	}
}

//
// Start page proper
//
$template->set_filenames(array(
	"body" => "admin/forum_admin_body.tpl")
);

$template->assign_vars(array(
	'S_FORUM_ACTION' => append_sid("admin_forums.$phpEx"),
	'L_FORUM_TITLE' => $lang['Forum_admin'], 
	'L_FORUM_EXPLAIN' => $lang['Forum_admin_explain'], 
	'L_CREATE_FORUM' => $lang['Create_forum'], 
	'L_CREATE_CATEGORY' => $lang['Create_category'], 
	'L_EDIT' => $lang['Edit'], 
	'L_DELETE' => $lang['Delete'], 
	'L_MOVE_UP' => $lang['Move_up'], 
	'L_MOVE_DOWN' => $lang['Move_down'], 
	'L_RESYNC' => $lang['Resync'])
);

$sql = "SELECT cat_id, cat_title, cat_order
	FROM " . CATEGORIES_TABLE . "
	ORDER BY cat_order";
if( !$q_categories = $db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, "Could not query categories list", "", __LINE__, __FILE__, $sql);
}

if( $total_categories = $db->sql_numrows($q_categories) )
{
	$category_rows = $db->sql_fetchrowset($q_categories);

	$sql = "SELECT *
		FROM " . FORUMS_TABLE . "
		ORDER BY cat_id, forum_order";
	if(!$q_forums = $db->sql_query($sql))
	{
		message_die(GENERAL_ERROR, "Could not query forums information", "", __LINE__, __FILE__, $sql);
	}

	if( $total_forums = $db->sql_numrows($q_forums) )
	{
		$forum_rows = $db->sql_fetchrowset($q_forums);
	}
	$subforum_rows=$forum_rows;

	//
	// Okay, let's build the index
	//
	$gen_cat = array();

	for($i = 0; $i < $total_categories; $i++)
	{
		$cat_id = $category_rows[$i]['cat_id'];

		$template->assign_block_vars("catrow", array( 
			'S_ADD_FORUM_SUBMIT' => "addforum[$cat_id]", 
			'S_ADD_FORUM_NAME' => "forumname[$cat_id]", 

			'CAT_ID' => $cat_id,
			'CAT_DESC' => $category_rows[$i]['cat_title'],

			'U_CAT_EDIT' => append_sid("admin_forums.$phpEx?mode=editcat&amp;" . POST_CAT_URL . "=$cat_id"),
			'U_CAT_DELETE' => append_sid("admin_forums.$phpEx?mode=deletecat&amp;" . POST_CAT_URL . "=$cat_id"),
			'U_CAT_MOVE_UP' => append_sid("admin_forums.$phpEx?mode=cat_order&amp;move=-15&amp;" . POST_CAT_URL . "=$cat_id"),
			'U_CAT_MOVE_DOWN' => append_sid("admin_forums.$phpEx?mode=cat_order&amp;move=15&amp;" . POST_CAT_URL . "=$cat_id"),
			'U_VIEWCAT' => append_sid($phpbb_root_path."index.$phpEx?" . POST_CAT_URL . "=$cat_id"))
		);

		for($j = 0; $j < $total_forums; $j++)
		{
			$forum_id = $forum_rows[$j]['forum_id'];
			
			if ($forum_rows[$j]['cat_id'] == $cat_id)
			{

				$sub_error=false;
				$do_template=false;
				if ($forum_rows[$j]['attached_forum_id'] !=-1)
				{
				$ok='';
					for($k = 0; $k < $total_forums; $k++)
					{
						$subforum_id = $subforum_rows[$k]['forum_id'];

						if ($subforum_id == $forum_rows[$j]['attached_forum_id'] && $forum_rows[$k]['attached_forum_id']==-1)
						{
							$ok=TRUE;//normal parent found
						}
					}
					if ($forum_rows[$j]['attached_forum_id']==$forum_id) $ok=FALSE; //attached to itself
					if ($forum_rows[$j]['attached_forum_id']==0) $ok=FALSE; //invalid parent
					if (!$ok)
					{
						$do_template=TRUE;
						$sub_error=true;
					}
				}
				else
				{
					$do_template=true;
				}//attached_forum_id'] ==-1


				if ($do_template)
				{
					$template->assign_block_vars("catrow.forumrow",	array(
						'FORUM_NAME' => $forum_rows[$j]['forum_name'],
						'FORUM_DESC' => $forum_rows[$j]['forum_desc'],
						'ROW_COLOR' => $row_color,
						'NUM_TOPICS' => $forum_rows[$j]['forum_topics'],
						'NUM_POSTS' => $forum_rows[$j]['forum_posts'],

						'U_VIEWFORUM' => append_sid($phpbb_root_path."viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id"),
						'U_FORUM_EDIT' => append_sid("admin_forums.$phpEx?mode=editforum&amp;" . POST_FORUM_URL . "=$forum_id"),
						'U_FORUM_DELETE' => append_sid("admin_forums.$phpEx?mode=deleteforum&amp;" . POST_FORUM_URL . "=$forum_id"),
						'U_FORUM_MOVE_UP' => append_sid("admin_forums.$phpEx?mode=forum_order&amp;move=-15&amp;" . POST_FORUM_URL . "=$forum_id"),
						'U_FORUM_MOVE_DOWN' => append_sid("admin_forums.$phpEx?mode=forum_order&amp;move=15&amp;" . POST_FORUM_URL . "=$forum_id"),
						'U_FORUM_RESYNC' => append_sid("admin_forums.$phpEx?mode=forum_sync&amp;" . POST_FORUM_URL . "=$forum_id"))
					);
					if ($sub_error)
					{
						$template->assign_block_vars ('catrow.forumrow.switch_error',array());
					}

					for($k = 0; $k < $total_forums; $k++)
					{
						$subforum_id = $subforum_rows[$k]['forum_id'];
						if ($subforum_rows[$k]['attached_forum_id'] == $forum_id)
						{
							$template->assign_block_vars("catrow.forumrow",	array(
								'FORUM_NAME' => $subforum_rows[$k]['forum_name'],
								'FORUM_DESC' => $subforum_rows[$k]['forum_desc'],
								'ROW_COLOR' => $row_color,
								'NUM_TOPICS' => $subforum_rows[$k]['forum_topics'],
								'NUM_POSTS' => $subforum_rows[$k]['forum_posts'],

								'U_VIEWFORUM' => append_sid($phpbb_root_path."viewforum.$phpEx?" . POST_FORUM_URL . "=$subforum_id"),
								'U_FORUM_EDIT' => append_sid("admin_forums.$phpEx?mode=editforum&amp;" . POST_FORUM_URL . "=$subforum_id"),
								'U_FORUM_DELETE' => append_sid("admin_forums.$phpEx?mode=deleteforum&amp;" . POST_FORUM_URL . "=$subforum_id"),
								'U_FORUM_MOVE_UP' => append_sid("admin_forums.$phpEx?mode=forum_order&amp;move=-15&amp;" . POST_FORUM_URL . "=$subforum_id"),
								'U_FORUM_MOVE_DOWN' => append_sid("admin_forums.$phpEx?mode=forum_order&amp;move=15&amp;" . POST_FORUM_URL . "=$subforum_id"),
								'U_FORUM_RESYNC' => append_sid("admin_forums.$phpEx?mode=forum_sync&amp;" . POST_FORUM_URL . "=$subforum_id"))
							);
							$template->assign_block_vars ('catrow.forumrow.switch_attached_forum',array());
						}
					}
				}

			}// if ... forumid == catid
			
		} // for ... forums

	} // for ... categories

}// if ... total_categories

$template->pparse("body");

include('./page_footer_admin.'.$phpEx);

?>
