<?php
//-- mod : today userlist ------------------------------------------------------
/***************************************************************************
 *                                index.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: index.php,v 1.99.2.6 2005/10/30 15:17:13 acydburn Exp $
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

define('IN_PHPBB', true);
$phpbb_root_path = './';
include($phpbb_root_path . 'extension.inc');
include($phpbb_root_path . 'common.'.$phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);
//
// End session management
//

$viewcat = ( !empty($HTTP_GET_VARS[POST_CAT_URL]) ) ? $HTTP_GET_VARS[POST_CAT_URL] : -1;

if( isset($HTTP_GET_VARS['mark']) || isset($HTTP_POST_VARS['mark']) )
{
	$mark_read = ( isset($HTTP_POST_VARS['mark']) ) ? $HTTP_POST_VARS['mark'] : $HTTP_GET_VARS['mark'];
}
else
{
	$mark_read = '';
}

//
// Handle marking posts
//
if( $mark_read == 'forums' )
{
	if( $userdata['session_logged_in'] )
	{
		setcookie($board_config['cookie_name'] . '_f_all', time(), 0, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
	}

	$template->assign_vars(array(
		"META" => '<meta http-equiv="refresh" content="3;url='  .append_sid("index.$phpEx") . '">')
	);

	$message = $lang['Forums_marked_read'] . '<br /><br />' . sprintf($lang['Click_return_index'], '<a href="' . append_sid("index.$phpEx") . '">', '</a> ');

	message_die(GENERAL_MESSAGE, $message);
}
//
// End handle marking posts
//

$tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . "_t"]) : array();
$tracking_forums = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . "_f"]) : array();

//
// If you don't use these stats on your index you may want to consider
// removing them
//
$total_posts = get_db_stat('postcount');
$total_users = get_db_stat('usercount');
$newest_userdata = get_db_stat('newestuser');
$newest_user = $newest_userdata['username'];
$newest_uid = $newest_userdata['user_id'];

if( $total_posts == 0 )
{
	$l_total_post_s = $lang['Posted_articles_zero_total'];
}
else if( $total_posts == 1 )
{
	$l_total_post_s = $lang['Posted_article_total'];
}
else
{
	$l_total_post_s = $lang['Posted_articles_total'];
}

if( $total_users == 0 )
{
	$l_total_user_s = $lang['Registered_users_zero_total'];
}
else if( $total_users == 1 )
{
	$l_total_user_s = $lang['Registered_user_total'];
}
else
{
	$l_total_user_s = $lang['Registered_users_total'];
}


//
// Start page proper
//
$sql = "SELECT c.cat_id, c.cat_title, c.cat_order
	FROM " . CATEGORIES_TABLE . " c 
	".(($userdata['user_level']!=ADMIN)? "WHERE c.cat_id<>'".HIDDEN_CAT."'" :"" )."
	ORDER BY c.cat_order";
if( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, 'Could not query categories list', '', __LINE__, __FILE__, $sql);
}

$category_rows = array();
while ($row = $db->sql_fetchrow($result))
{
	$category_rows[] = $row;
}

$db->sql_freeresult($result);

if( ( $total_categories = count($category_rows) ) )
{
	//
	// Define appropriate SQL
	//
	switch(SQL_LAYER)
	{
		case 'postgresql':
			$sql = "SELECT f.*, p.post_time, p.post_username, u.username, u.user_id 
				FROM " . FORUMS_TABLE . " f, " . POSTS_TABLE . " p, " . USERS_TABLE . " u
				WHERE p.post_id = f.forum_last_post_id 
					AND u.user_id = p.poster_id  
					UNION (
						SELECT f.*, NULL, NULL, NULL, NULL
						FROM " . FORUMS_TABLE . " f
						WHERE NOT EXISTS (
							SELECT p.post_time
							FROM " . POSTS_TABLE . " p
							WHERE p.post_id = f.forum_last_post_id  
						)
					)
					ORDER BY cat_id, forum_order";
			break;

		case 'oracle':
			$sql = "SELECT f.*, p.post_time, p.post_username, u.username, u.user_id 
				FROM " . FORUMS_TABLE . " f, " . POSTS_TABLE . " p, " . USERS_TABLE . " u
				WHERE p.post_id = f.forum_last_post_id(+)
					AND u.user_id = p.poster_id(+)
				ORDER BY f.cat_id, f.forum_order";
			break;

		default:
   			// Modified by Attached Forums MOD
			$sql = "SELECT f.*, p.post_time, p.post_username,  u.username, u.user_id, t.topic_id, t.topic_title
				FROM ((( " . FORUMS_TABLE . " f
				LEFT JOIN " . POSTS_TABLE . " p ON p.post_id = f.forum_last_post_id )
				LEFT JOIN " . USERS_TABLE . " u ON u.user_id = p.poster_id )
				LEFT JOIN " . TOPICS_TABLE . " t ON t.topic_last_post_id = f.forum_last_post_id)
				GROUP BY f.forum_id ORDER BY f.cat_id, f.forum_order";
   			// END Modified by Attached Forums MOD
			break;
	}
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query forums information', '', __LINE__, __FILE__, $sql);
	}

	$forum_data = array();
	while( $row = $db->sql_fetchrow($result) )
	{
		$forum_data[] = $row;
	}
   	// Added by Attached Forums MOD
	$attach=$forum_data;
   	// END Added by Attached Forums MOD
	$db->sql_freeresult($result);

	if ( !($total_forums = count($forum_data)) )
	{
		message_die(GENERAL_MESSAGE, $lang['No_forums']);
	}

	//
	// Obtain a list of topic ids which contain
	// posts made since user last visited
	//
	if ($userdata['session_logged_in'])
	{
		// 60 days limit
		if ($userdata['user_lastvisit'] < (time() - 5184000))
		{
			$userdata['user_lastvisit'] = time() - 5184000;
		}

		$sql = "SELECT t.forum_id, t.topic_id, p.post_time 
			FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p 
			WHERE p.post_id = t.topic_last_post_id 
				AND p.post_time > " . $userdata['user_lastvisit'] . " 
				AND t.topic_moved_id = 0"; 
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, 'Could not query new topic information', '', __LINE__, __FILE__, $sql);
		}

		$new_topic_data = array();
		while( $topic_data = $db->sql_fetchrow($result) )
		{
			$new_topic_data[$topic_data['forum_id']][$topic_data['topic_id']] = $topic_data['post_time'];
		}
		$db->sql_freeresult($result);
	}

	//
	// Obtain list of moderators of each forum
	// First users, then groups ... broken into two queries
	//
	$sql = "SELECT aa.forum_id, u.user_id, u.username 
		FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g, " . USERS_TABLE . " u
		WHERE aa.auth_mod = " . TRUE . " 
			AND g.group_single_user = 1 
			AND ug.group_id = aa.group_id 
			AND g.group_id = aa.group_id 
			AND u.user_id = ug.user_id 
		GROUP BY u.user_id, u.username, aa.forum_id 
		ORDER BY aa.forum_id, u.user_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query forum moderator information', '', __LINE__, __FILE__, $sql);
	}

	$forum_moderators = array();
	while( $row = $db->sql_fetchrow($result) )
	{
		$forum_moderators[$row['forum_id']][] = '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=" . $row['user_id']) . '">' . $row['username'] . '</a>';
	}
	$db->sql_freeresult($result);

	$sql = "SELECT aa.forum_id, g.group_id, g.group_name 
		FROM " . AUTH_ACCESS_TABLE . " aa, " . USER_GROUP_TABLE . " ug, " . GROUPS_TABLE . " g 
		WHERE aa.auth_mod = " . TRUE . " 
			AND g.group_single_user = 0 
			AND g.group_type <> " . GROUP_HIDDEN . "
			AND ug.group_id = aa.group_id 
			AND g.group_id = aa.group_id 
		GROUP BY g.group_id, g.group_name, aa.forum_id 
		ORDER BY aa.forum_id, g.group_id";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, 'Could not query forum moderator information', '', __LINE__, __FILE__, $sql);
	}

	while( $row = $db->sql_fetchrow($result) )
	{
		$forum_moderators[$row['forum_id']][] = '<a href="' . append_sid("groupcp.$phpEx?" . POST_GROUPS_URL . "=" . $row['group_id']) . '">' . $row['group_name'] . '</a>';
	}
	$db->sql_freeresult($result);

	//
	// Find which forums are visible for this user
	//
	$is_auth_ary = array();
	$is_auth_ary = auth(AUTH_VIEW, AUTH_LIST_ALL, $userdata, $forum_data);

	//
	// Start output of page
	//
	define('SHOW_ONLINE', true);
	$page_title = $lang['Index'];
	include($phpbb_root_path . 'includes/page_header.'.$phpEx);
//-- mod : today userlist ------------------------------------------------------
//-- add
	include_once($phpbb_root_path . 'includes/today_userlist.'.$phpEx);
//-- fin mod : today userlist --------------------------------------------------

//-- mod : birthday ------------------------------------------------------------
//-- add
	$bdays->display_bdays();
//-- fin mod : birthday --------------------------------------------------------

	$template->set_filenames(array(
		'body' => 'index_body.tpl')
	);

	$template->assign_vars(array(
		'TOTAL_POSTS' => sprintf($l_total_post_s, $total_posts),
		'TOTAL_USERS' => sprintf($l_total_user_s, $total_users),
		'NEWEST_USER' => sprintf($lang['Newest_user'], '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$newest_uid") . '">', $newest_user, '</a>'), 

		'FORUM_IMG' => $images['forum'],
		'FORUM_NEW_IMG' => $images['forum_new'],
		'FORUM_LOCKED_IMG' => $images['forum_locked'],

		'L_FORUM' => $lang['Forum'],
		'L_TOPICS' => $lang['Topics'],
		'L_REPLIES' => $lang['Replies'],
		'L_VIEWS' => $lang['Views'],
		'L_POSTS' => $lang['Posts'],
		'L_LASTPOST' => $lang['Last_Post'], 
		'L_NO_NEW_POSTS' => $lang['No_new_posts'],
		'L_NEW_POSTS' => $lang['New_posts'],
		'L_NO_NEW_POSTS_LOCKED' => $lang['No_new_posts_locked'], 
		'L_NEW_POSTS_LOCKED' => $lang['New_posts_locked'], 
		'L_ONLINE_EXPLAIN' => $lang['Online_explain'], 

		'L_MODERATOR' => $lang['Moderators'], 
		'L_FORUM_LOCKED' => $lang['Forum_is_locked'],
		'L_MARK_FORUMS_READ' => $lang['Mark_all_forums'], 

		'U_MARK_READ' => append_sid("index.$phpEx?mark=forums"))
	);

	//
	// Let's decide which categories we should display
	//
	$display_categories = array();

	for ($i = 0; $i < $total_forums; $i++ )
	{
		if ($is_auth_ary[$forum_data[$i]['forum_id']]['auth_view'])
		{
			$display_categories[$forum_data[$i]['cat_id']] = true;
		}
	}

	//
	// Okay, let's build the index
	//
	for($i = 0; $i < $total_categories; $i++)
	{
		$cat_id = $category_rows[$i]['cat_id'];

		//
		// Yes, we should, so first dump out the category
		// title, then, if appropriate the forum list
		//
		if (isset($display_categories[$cat_id]) && $display_categories[$cat_id])

		{
			$template->assign_block_vars('catrow', array(
				'CAT_ID' => $cat_id,
				'CAT_DESC' => $category_rows[$i]['cat_title'],
				'U_VIEWCAT' => append_sid("index.$phpEx?" . POST_CAT_URL . "=$cat_id"))
			);

			if ( $viewcat == $cat_id || $viewcat == -1 )
			{
				for($j = 0; $j < $total_forums; $j++)
				{
					if ( $forum_data[$j]['cat_id'] == $cat_id )
					{
						$forum_id = $forum_data[$j]['forum_id'];
						// Added by Attached Forums MOD
						$attached_id = $forum_data[$j]['attached_forum_id'];
						$attached_sub_forum = 0;
						if ( $is_auth_ary[$forum_id]['auth_view'] && $attached_id == -1 )
						{
							$attached_forums = array();
							foreach ($attach as $key => $value)
							{
								$sub_forum_id = $value['forum_id'];
								if ($value['attached_forum_id']==$forum_id && $is_auth_ary[$sub_forum_id]['auth_view'])
								{
									//combining topic and post count for forum and subforums
									$forum_data[$j]['forum_posts']=$forum_data[$j]['forum_posts']+$value['forum_posts'];
									$forum_data[$j]['forum_topics']=$forum_data[$j]['forum_topics']+$value['forum_topics'];
									//END combining topic and post count

									//Last post link - check if any of subforums have newest posts and link to them instead
									if ($value['post_time']>$forum_data[$j]['post_time'])
									{
										$forum_data[$j]['user_id'] = $value['user_id'];
										$forum_data[$j]['post_username'] = $value['post_username'];
										$forum_data[$j]['forum_last_post_id'] = $value['forum_last_post_id'];
										$forum_data[$j]['post_time'] = $value['post_time'];
										$forum_data[$j]['username'] = $value['username'];
										$forum_data[$j]['topic_title'] = $value['topic_title'];
									}
									// END last post check

									$unread_topics = false;
									if ( $userdata['session_logged_in'] )
									{
										if (check_unread($value['forum_id']))
										{
											$attach_img = $images['icon_minipost_new'];
											$l_attach_img = $lang['New_posts'];
											$attached_sub_forum_crew = $attached_sub_forum_crew + 1;
										}
										else
										{
											$attach_img = $images['icon_minipost'];
											$l_attach_img = $lang['No_new_posts'];
										}
									}
									else
									{
										$attach_img = $images['icon_minipost'];
										$l_attach_img = $lang['No_new_posts'];
									}

									$attached_forums[] = array(
										'sub_img'=>$attach_img,
										'sub_alt'=>$l_attach_img,
										'sub_name'=>$value['forum_name'],
										'sub_url'=>append_sid ('viewforum.php?f=' . $value['forum_id'] )
										);
								}
							}	
						// END Added by Attached Forums MOD

						if ( $is_auth_ary[$forum_id]['auth_view'] )
						{
							if ( $forum_data[$j]['forum_status'] == FORUM_LOCKED )
							{
								$folder_image = $images['forum_locked']; 
								$folder_alt = $lang['Forum_locked'];
							}
							else
							{
								$unread_topics = false;
								if ( $userdata['session_logged_in'] )
								{
   									// Added by Attached Forums MOD
									$unread_topics=check_unread($forum_id);
									// END Added by Attached Forums MOD
								}

								$folder_image = ( $unread_topics || ($attached_sub_forum_crew != 0) ) ? $images['forum_new'] : $images['forum'];
                        					$folder_alt = ( $unread_topics || ($attached_sub_forum_crew != 0) ) ? $lang['New_posts'] : $lang['No_new_posts'];
							}

							$posts = $forum_data[$j]['forum_posts'];
							$topics = $forum_data[$j]['forum_topics'];

							if ( $forum_data[$j]['forum_last_post_id'] )
							{
								// Modified by Attached Forums MOD
								if (strlen($forum_data[$j]['topic_title'])>=25)
								{
									$forum_data[$j]['topic_title']=substr($forum_data[$j]['topic_title'],0,25). "...";
								}

								$last_post_time = create_date($board_config['default_dateformat'], $forum_data[$j]['post_time'], $board_config['board_timezone']);
								$last_post = '<a href="' . append_sid("viewtopic.$phpEx?"  . POST_POST_URL . '=' . $forum_data[$j]['forum_last_post_id']) . '#' . $forum_data[$j]['forum_last_post_id'] . '">'.$forum_data[$j]['topic_title'].' <img src="' . $images['icon_latest_reply'] . '" border="0" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';

								$last_post .= '<br /> '; 
								$last_post .= ' '.$last_post_time;								
								$last_post .= '<br /> '; 
								$last_post .= ( $forum_data[$j]['user_id'] == ANONYMOUS ) ? ( ($forum_data[$j]['post_username'] != '' ) ? $forum_data[$j]['post_username'] . ' ' : $lang['Guest'] . ' ' ) : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $forum_data[$j]['user_id']) . '">' . $forum_data[$j]['username'] . '</a> ';
								// END Modified by Attached Forums MOD
							}
							else
							{
								$last_post = $lang['No_Posts'];
							}

							if ( count($forum_moderators[$forum_id]) > 0 )
							{
								$l_moderators = ( count($forum_moderators[$forum_id]) == 1 ) ? $lang['Moderator'] : $lang['Moderators'];
								$moderator_list = implode(', ', $forum_moderators[$forum_id]);
							}
							else
							{
								// Modified by Attached Forums MOD
								$l_moderators = '';
								$moderator_list = '';
								// END Modified by Attached Forums MOD
							}

							$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
							$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

//
// Begin Approve_Mod Block : 1
//
							$approve_mod = array();
							$approve_sql = "SELECT enabled, approve_moderators, forum_hide_unapproved_posts, forum_hide_unapproved_topics
								FROM " . APPROVE_FORUMS_TABLE . " 
								WHERE forum_id = " . intval($forum_data[$j]['forum_id']) . " LIMIT 0,1"; 
							if ( !($approve_result = $db->sql_query($approve_sql)) ) 
							{ 
								message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'], '', __LINE__, __FILE__, $approve_sql); 
							} 
							if ( $approve_row = $db->sql_fetchrow($approve_result) ) 
							{    
								if ( intval($approve_row['enabled']) == 1 )
								{
									$approve_mod = $approve_row;
									$approve_mod['enabled'] = true;
								}
							}
							if ( $approve_mod['enabled'] )
							{
								
								if ( $forum_data[$j]['user_id'] == ANONYMOUS || $approve_mod['forum_hide_unapproved_posts'] || $approve_mod['forum_hide_unapproved_topics'] )
								{
									$approve_mod['moderators'] = array();
									$approve_mod['moderators'] = explode('|', $approve_mod['approve_moderators']);
									
									if ( !in_array($userdata['user_id'], $approve_mod['moderators']) && !$is_auth_ary[$forum_id]['auth_mod'] && $last_post != $lang['No_Posts'] )
									{
										$approve_sql = "SELECT * 
											FROM " . APPROVE_POSTS_TABLE . " 
											WHERE post_id = " . intval($forum_data[$j]['forum_last_post_id']) . " 
											LIMIT 0,1"; 
										if ( !$approve_result = $db->sql_query($approve_sql) ) 
										{ 
											message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'], '', __LINE__, __FILE__, $approve_sql); 
										}	
										$approve_row = $db->sql_fetchrow($approve_result);
										if ( intval($approve_row['post_id']) == intval($forum_data[$j]['forum_last_post_id']) )
										{
											if ( $approve_mod['forum_hide_unapproved_posts'] || $approve_mod['forum_hide_unapproved_topics'] )
											{
												$approve_sql = "SELECT p.post_id, p.poster_id, p.post_time, p.post_username, u.username, u.user_id 
													FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u 
													WHERE p.forum_id = " . intval($forum_data[$j]['forum_id']) . "
													AND u.user_id = p.poster_id 
													ORDER BY p.post_time DESC";
												if ( !($approve_result = $db->sql_query($approve_sql)) ) 
												{ 
													message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'], '', __LINE__, __FILE__, $approve_sql); 
												} 
												while( $approve_row = $db->sql_fetchrow($approve_result) )
												{
													$approve_sql = "SELECT * FROM " . APPROVE_POSTS_TABLE . " 
														WHERE post_id = " . intval($approve_row['post_id']) . " 
														LIMIT 0,1";
													if ( !($approve_result2 = $db->sql_query($approve_sql)) ) 
													{ 
														message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'], '', __LINE__, __FILE__, $approve_sql); 
													} 
													$approve_row2 = $db->sql_fetchrow($approve_result2);
													if ( !$approve_row2['post_id'] )
													{
														$last_post_time = create_date($board_config['default_dateformat'], $approve_row['post_time'], $board_config['board_timezone']);
														$last_post = $last_post_time . '<br />';

														$last_post .= ( $approve_row['user_id'] == ANONYMOUS ) ? ( ($approve_row['post_username'] != '' ) ? $approve_row['post_username'] . ' ' : $lang['Guest'] . ' ' ) : '<a href="' . append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . '='  . $approve_row['poster_id']) . '">' . $approve_row['username'] . '</a> ';
														
														$last_post .= '<a href="' . append_sid("viewtopic.$phpEx?"  . POST_POST_URL . '=' . $approve_row['post_id']) . '#' . $approve_row['post_id'] . '"><img src="' . $images['icon_latest_reply'] . '" border="0" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';
														break;
													}
												}		
											}
											else
											{
												$last_post = $last_post_time . '<br />';
												$last_post .= $lang['Guest'] . '  ' . '<a href="' . append_sid("viewforum.$phpEx?"  . POST_FORUM_URL . '=' . $forum_data[$j]['forum_id']) . '"><img src="' . $images['icon_latest_reply'] . '" border="0" alt="' . $lang['View_latest_post'] . '" title="' . $lang['View_latest_post'] . '" /></a>';
											}
										}
									}
								}
							}
//
// End Approve_Mod Block : 1
//

							$template->assign_block_vars('catrow.forumrow',	array(
								'ROW_COLOR' => '#' . $row_color,
								'ROW_CLASS' => $row_class,
								'FORUM_FOLDER_IMG' => $folder_image, 
								'FORUM_NAME' => $forum_data[$j]['forum_name'],
								'FORUM_DESC' => $forum_data[$j]['forum_desc'],
								'POSTS' => $forum_data[$j]['forum_posts'],
								'TOPICS' => $forum_data[$j]['forum_topics'],
								'LAST_POST' => $last_post,
								'MODERATORS' => $moderator_list,

								'L_MODERATOR' => $l_moderators, 
								'L_FORUM_FOLDER_ALT' => $folder_alt, 

								'U_VIEWFORUM' => append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id"))
							);
							// Added by Attached Forums MOD
							$attached_forum_count = count($attached_forums);
							if($attached_forum_count)
							{
								$template->assign_block_vars('catrow.forumrow.switch_attached_forums', array(
								'L_ATTACHED_FORUMS' => ($attached_forum_count ==1)? $lang['Attached_forum']: $lang['Attached_forums']
								));
								if (count($forum_moderators[$forum_id]) > 0 )
								{
						   			$template->assign_block_vars('catrow.forumrow.switch_attached_forums.br', array());
								}
                        					for($k = 0; $k < $attached_forum_count; $k++)
                        					{
                           						$template->assign_block_vars('catrow.forumrow.switch_attached_forums.attached_forums', array(
                              						'FORUM_IMAGE' => $attached_forums[$k]['sub_img'],
                              						'FORUM_NAME' => $attached_forums[$k]['sub_name'],
                              						'L_FORUM_IMAGE' => $attached_forums[$k]['sub_alt'],
                              						'U_VIEWFORUM' => $attached_forums[$k]['sub_url']
                           						));
                        					}
                     					}
							// END added by Attached Forums MOD
						}
					}
				}
			}
		}
	} // for ... categories
   }

}// if ... total_categories
else
{
	message_die(GENERAL_MESSAGE, $lang['No_forums']);
}

//
// Generate the page
//
$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
