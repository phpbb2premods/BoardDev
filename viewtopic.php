<?php
//-- mod : merge -----------------------------------------------------------------------------------
//-- mod : separated topics ----------------------------------------------------
/***************************************************************************
 *                               viewtopic.php
 *                            -------------------
 *   begin                : Saturday, Feb 13, 2001
 *   copyright            : (C) 2001 The phpBB Group
 *   email                : support@phpbb.com
 *
 *   $Id: viewtopic.php,v 1.186.2.45 2005/10/05 17:42:04 grahamje Exp $
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
include($phpbb_root_path . 'includes/bbcode.'.$phpEx);

//
// Start initial var setup
//
$topic_id = $post_id = 0;
if ( isset($HTTP_GET_VARS[POST_TOPIC_URL]) )
{
	$topic_id = intval($HTTP_GET_VARS[POST_TOPIC_URL]);
}
else if ( isset($HTTP_GET_VARS['topic']) )
{
	$topic_id = intval($HTTP_GET_VARS['topic']);
}

if ( isset($HTTP_GET_VARS[POST_POST_URL]))
{
	$post_id = intval($HTTP_GET_VARS[POST_POST_URL]);
}


$start = ( isset($HTTP_GET_VARS['start']) ) ? intval($HTTP_GET_VARS['start']) : 0;

if (!$topic_id && !$post_id)
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}

//
// Find topic id if user requested a newer
// or older topic
//
if ( isset($HTTP_GET_VARS['view']) && empty($HTTP_GET_VARS[POST_POST_URL]) )
{
	if ( $HTTP_GET_VARS['view'] == 'newest' )
	{
		if ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_sid']) || isset($HTTP_GET_VARS['sid']) )
		{
			$session_id = isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_sid']) ? $HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_sid'] : $HTTP_GET_VARS['sid'];

			if (!preg_match('/^[A-Za-z0-9]*$/', $session_id)) 
			{
				$session_id = '';
			}

			if ( $session_id )
			{
				$sql = "SELECT p.post_id
					FROM " . POSTS_TABLE . " p, " . SESSIONS_TABLE . " s,  " . USERS_TABLE . " u
					WHERE s.session_id = '$session_id'
						AND u.user_id = s.session_user_id
						AND p.topic_id = $topic_id
						AND p.post_time >= u.user_lastvisit
					ORDER BY p.post_time ASC
					LIMIT 1";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, 'Could not obtain newer/older topic information', '', __LINE__, __FILE__, $sql);
				}

				if ( !($row = $db->sql_fetchrow($result)) )
				{
					message_die(GENERAL_MESSAGE, 'No_new_posts_last_visit');
				}

				$post_id = $row['post_id'];

				if (isset($HTTP_GET_VARS['sid']))
				{
					redirect("viewtopic.$phpEx?sid=$session_id&" . POST_POST_URL . "=$post_id#$post_id");
				}
				else
				{
					redirect("viewtopic.$phpEx?" . POST_POST_URL . "=$post_id#$post_id");
				}
			}
		}

		redirect(append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id", true));
	}
	else if ( $HTTP_GET_VARS['view'] == 'next' || $HTTP_GET_VARS['view'] == 'previous' )
	{
		$sql_condition = ( $HTTP_GET_VARS['view'] == 'next' ) ? '>' : '<';
		$sql_ordering = ( $HTTP_GET_VARS['view'] == 'next' ) ? 'ASC' : 'DESC';

		$sql = "SELECT t.topic_id
			FROM " . TOPICS_TABLE . " t, " . TOPICS_TABLE . " t2
			WHERE
				t2.topic_id = $topic_id
				AND t.forum_id = t2.forum_id
				AND t.topic_moved_id = 0
				AND t.topic_last_post_id $sql_condition t2.topic_last_post_id
			ORDER BY t.topic_last_post_id $sql_ordering
			LIMIT 1";
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not obtain newer/older topic information", '', __LINE__, __FILE__, $sql);
		}

		if ( $row = $db->sql_fetchrow($result) )
		{
			$topic_id = intval($row['topic_id']);
		}
		else
		{
			$message = ( $HTTP_GET_VARS['view'] == 'next' ) ? 'No_newer_topics' : 'No_older_topics';
			message_die(GENERAL_MESSAGE, $message);
		}
	}
}

//
// This rather complex gaggle of code handles querying for topics but
// also allows for direct linking to a post (and the calculation of which
// page the post is on and the correct display of viewtopic)
//
$join_sql_table = (!$post_id) ? '' : ", " . POSTS_TABLE . " p, " . POSTS_TABLE . " p2 ";
$join_sql = (!$post_id) ? "t.topic_id = $topic_id" : "p.post_id = $post_id AND t.topic_id = p.topic_id AND p2.topic_id = p.topic_id AND p2.post_id <= $post_id";
$count_sql = (!$post_id) ? '' : ", COUNT(p2.post_id) AS prev_posts";

$order_sql = (!$post_id) ? '' : "GROUP BY p.post_id, t.topic_id, t.topic_title, t.topic_status, t.topic_replies, t.topic_time, t.topic_type, t.topic_vote, t.topic_last_post_id, f.forum_name, f.forum_status, f.forum_id, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments, f.auth_ban, f.auth_greencard, f.auth_bluecard ORDER BY p.post_id ASC";


$sql = "SELECT t.topic_id, t.topic_title, t.topic_status, t.topic_replies, t.topic_time, t.topic_type, t.topic_vote, t.topic_last_post_id, f.forum_name, f.forum_status, f.forum_id, f.auth_view, f.auth_read, f.auth_post, f.auth_reply, f.auth_edit, f.auth_delete, f.auth_sticky, f.auth_announce, f.auth_pollcreate, f.auth_vote, f.auth_attachments, f.attached_forum_id, f.auth_ban, f.auth_greencard, f.auth_bluecard " . $count_sql . "
	FROM " . TOPICS_TABLE . " t, " . FORUMS_TABLE . " f" . $join_sql_table . "
	WHERE $join_sql
		AND f.forum_id = t.forum_id
		$order_sql";
//-- mod : quick post es -------------------------------------------------------
//-- add
$sql = str_replace(', f.forum_id', ', f.forum_id, f.forum_qpes', $sql);
//-- fin mod : quick post es ---------------------------------------------------
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain topic information", '', __LINE__, __FILE__, $sql);
}

if ( !($forum_topic_data = $db->sql_fetchrow($result)) )
{
	message_die(GENERAL_MESSAGE, 'Topic_post_not_exist');
}

$forum_id = intval($forum_topic_data['forum_id']);


//
// Start session management
//
$userdata = session_pagestart($user_ip, $forum_id);
init_userprefs($userdata);
//
// End session management
//

//
// Start auth check
//
$is_auth = array();
$is_auth = auth(AUTH_ALL, $forum_id, $userdata, $forum_topic_data);

if( !$is_auth['auth_view'] || !$is_auth['auth_read'] )
{
	if ( !$userdata['session_logged_in'] )
	{
		$redirect = ($post_id) ? POST_POST_URL . "=$post_id" : POST_TOPIC_URL . "=$topic_id";
		$redirect .= ($start) ? "&start=$start" : '';
		redirect(append_sid("login.$phpEx?redirect=viewtopic.$phpEx&$redirect", true));
	}

	$message = ( !$is_auth['auth_view'] ) ? $lang['Topic_post_not_exist'] : sprintf($lang['Sorry_auth_read'], $is_auth['auth_read_type']);

	message_die(GENERAL_MESSAGE, $message);
}
//
// End auth check
//

$forum_name = $forum_topic_data['forum_name'];
$topic_title = $forum_topic_data['topic_title'];
$topic_id = intval($forum_topic_data['topic_id']);
$topic_time = $forum_topic_data['topic_time'];

if ($post_id)
{
	$start = floor(($forum_topic_data['prev_posts'] - 1) / intval($board_config['posts_per_page'])) * intval($board_config['posts_per_page']);
}

//
// Is user watching this thread?
//
if( $userdata['session_logged_in'] )
{
	$can_watch_topic = TRUE;

	$sql = "SELECT notify_status
		FROM " . TOPICS_WATCH_TABLE . "
		WHERE topic_id = $topic_id
			AND user_id = " . $userdata['user_id'];
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain topic watch information", '', __LINE__, __FILE__, $sql);
	}

	if ( $row = $db->sql_fetchrow($result) )
	{
		if ( isset($HTTP_GET_VARS['unwatch']) )
		{
			if ( $HTTP_GET_VARS['unwatch'] == 'topic' )
			{
				$is_watching_topic = 0;

				$sql_priority = (SQL_LAYER == "mysql") ? "LOW_PRIORITY" : '';
				$sql = "DELETE $sql_priority FROM " . TOPICS_WATCH_TABLE . "
					WHERE topic_id = $topic_id
						AND user_id = " . $userdata['user_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Could not delete topic watch information", '', __LINE__, __FILE__, $sql);
				}
			}

			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;start=$start") . '">')
			);

			$message = $lang['No_longer_watching'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;start=$start") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$is_watching_topic = TRUE;

			if ( $row['notify_status'] )
			{
				$sql_priority = (SQL_LAYER == "mysql") ? "LOW_PRIORITY" : '';
				$sql = "UPDATE $sql_priority " . TOPICS_WATCH_TABLE . "
					SET notify_status = 0
					WHERE topic_id = $topic_id
						AND user_id = " . $userdata['user_id'];
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Could not update topic watch information", '', __LINE__, __FILE__, $sql);
				}
			}
		}
	}
	else
	{
		if ( isset($HTTP_GET_VARS['watch']) )
		{
			if ( $HTTP_GET_VARS['watch'] == 'topic' )
			{
				$is_watching_topic = TRUE;

				$sql_priority = (SQL_LAYER == "mysql") ? "LOW_PRIORITY" : '';
				$sql = "INSERT $sql_priority INTO " . TOPICS_WATCH_TABLE . " (user_id, topic_id, notify_status)
					VALUES (" . $userdata['user_id'] . ", $topic_id, 0)";
				if ( !($result = $db->sql_query($sql)) )
				{
					message_die(GENERAL_ERROR, "Could not insert topic watch information", '', __LINE__, __FILE__, $sql);
				}
			}

			$template->assign_vars(array(
				'META' => '<meta http-equiv="refresh" content="3;url=' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;start=$start") . '">')
			);

			$message = $lang['You_are_watching'] . '<br /><br />' . sprintf($lang['Click_return_topic'], '<a href="' . append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;start=$start") . '">', '</a>');
			message_die(GENERAL_MESSAGE, $message);
		}
		else
		{
			$is_watching_topic = 0;
		}
	}
}
else
{
	if ( isset($HTTP_GET_VARS['unwatch']) )
	{
		if ( $HTTP_GET_VARS['unwatch'] == 'topic' )
		{
			redirect(append_sid("login.$phpEx?redirect=viewtopic.$phpEx&" . POST_TOPIC_URL . "=$topic_id&unwatch=topic", true));
		}
	}
	else
	{
		$can_watch_topic = 0;
		$is_watching_topic = 0;
	}
}

//
// Generate a 'Show posts in previous x days' select box. If the postdays var is POSTed
// then get it's value, find the number of topics with dates newer than it (to properly
// handle pagination) and alter the main query
//
$previous_days = array(0, 1, 7, 14, 30, 90, 180, 364);
$previous_days_text = array($lang['All_Posts'], $lang['1_Day'], $lang['7_Days'], $lang['2_Weeks'], $lang['1_Month'], $lang['3_Months'], $lang['6_Months'], $lang['1_Year']);

if( !empty($HTTP_POST_VARS['postdays']) || !empty($HTTP_GET_VARS['postdays']) )
{
	$post_days = ( !empty($HTTP_POST_VARS['postdays']) ) ? intval($HTTP_POST_VARS['postdays']) : intval($HTTP_GET_VARS['postdays']);
	$min_post_time = time() - (intval($post_days) * 86400);

	$sql = "SELECT COUNT(p.post_id) AS num_posts
		FROM " . TOPICS_TABLE . " t, " . POSTS_TABLE . " p
		WHERE t.topic_id = $topic_id
			AND p.topic_id = t.topic_id
			AND p.post_time >= $min_post_time";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain limited topics count information", '', __LINE__, __FILE__, $sql);
	}

	$total_replies = ( $row = $db->sql_fetchrow($result) ) ? intval($row['num_posts']) : 0;

	$limit_posts_time = "AND p.post_time >= $min_post_time ";

	if ( !empty($HTTP_POST_VARS['postdays']))
	{
		$start = 0;
	}
}
else
{
	$total_replies = intval($forum_topic_data['topic_replies']) + 1;

	$limit_posts_time = '';
	$post_days = 0;
}

$select_post_days = '<select name="postdays">';
for($i = 0; $i < count($previous_days); $i++)
{
	$selected = ($post_days == $previous_days[$i]) ? ' selected="selected"' : '';
	$select_post_days .= '<option value="' . $previous_days[$i] . '"' . $selected . '>' . $previous_days_text[$i] . '</option>';
}
$select_post_days .= '</select>';

//
// Decide how to order the post display
//
if ( !empty($HTTP_POST_VARS['postorder']) || !empty($HTTP_GET_VARS['postorder']) )
{
	$post_order = (!empty($HTTP_POST_VARS['postorder'])) ? htmlspecialchars($HTTP_POST_VARS['postorder']) : htmlspecialchars($HTTP_GET_VARS['postorder']);
	$post_time_order = ($post_order == "asc") ? "ASC" : "DESC";
}
else
{
	$post_order = 'asc';
	$post_time_order = 'ASC';
}

$select_post_order = '<select name="postorder">';
if ( $post_time_order == 'ASC' )
{
	$select_post_order .= '<option value="asc" selected="selected">' . $lang['Oldest_First'] . '</option><option value="desc">' . $lang['Newest_First'] . '</option>';
}
else
{
	$select_post_order .= '<option value="asc">' . $lang['Oldest_First'] . '</option><option value="desc" selected="selected">' . $lang['Newest_First'] . '</option>';
}
$select_post_order .= '</select>';

//
// Go ahead and pull all data for this topic
//
//-- mod : birthday ------------------------------------------------------------
// here we added
//	, u.user_birthday
//-- modify
$sql = "SELECT u.username, u.user_id, u.user_posts, u.user_from, u.user_website, u.user_email, u.user_icq, u.user_aim, u.user_yim, u.user_regdate, u.user_msnm, u.user_viewemail, u.user_rank, u.user_sig, u.user_colortext, u.user_sig_bbcode_uid, u.user_avatar, u.user_avatar_type, u.user_allowavatar, u.user_allowsmile, u.user_warnings, u.user_level, u.user_birthday, p.*,  pt.post_text, pt.post_subject, pt.bbcode_uid
	FROM " . POSTS_TABLE . " p, " . USERS_TABLE . " u, " . POSTS_TEXT_TABLE . " pt
	WHERE p.topic_id = $topic_id
		$limit_posts_time
		AND pt.post_id = p.post_id
		AND u.user_id = p.poster_id
	ORDER BY p.post_time $post_time_order
	LIMIT $start, ".$board_config['posts_per_page'];
//-- fin mod : birthday --------------------------------------------------------
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain post/user information.", '', __LINE__, __FILE__, $sql);
}

$postrow = array();
if ($row = $db->sql_fetchrow($result))
{
	do
	{
		$postrow[] = $row;
	}
	while ($row = $db->sql_fetchrow($result));
	$db->sql_freeresult($result);

	$total_posts = count($postrow);
}
else 
{ 
   include($phpbb_root_path . 'includes/functions_admin.' . $phpEx); 
   sync('topic', $topic_id); 

   message_die(GENERAL_MESSAGE, $lang['No_posts_topic']); 
} 

$resync = FALSE; 
if ($forum_topic_data['topic_replies'] + 1 < $start + count($postrow)) 
{ 
   $resync = TRUE; 
} 
elseif ($start + $board_config['posts_per_page'] > $forum_topic_data['topic_replies']) 
{ 
   $row_id = intval($forum_topic_data['topic_replies']) % intval($board_config['posts_per_page']); 
   if ($postrow[$row_id]['post_id'] != $forum_topic_data['topic_last_post_id'] || $start + count($postrow) < $forum_topic_data['topic_replies']) 
   { 
      $resync = TRUE; 
   } 
} 
elseif (count($postrow) < $board_config['posts_per_page']) 
{ 
   $resync = TRUE; 
} 

if ($resync) 
{ 
   include($phpbb_root_path . 'includes/functions_admin.' . $phpEx); 
   sync('topic', $topic_id); 

   $result = $db->sql_query('SELECT COUNT(post_id) AS total FROM ' . POSTS_TABLE . ' WHERE topic_id = ' . $topic_id); 
   $row = $db->sql_fetchrow($result); 
   $total_replies = $row['total']; 
}

$sql = "SELECT *
	FROM " . RANKS_TABLE . "
	ORDER BY rank_special, rank_min";
if ( !($result = $db->sql_query($sql)) )
{
	message_die(GENERAL_ERROR, "Could not obtain ranks information.", '', __LINE__, __FILE__, $sql);
}

$ranksrow = array();
while ( $row = $db->sql_fetchrow($result) )
{
	$ranksrow[] = $row;
}
$db->sql_freeresult($result);

//
// Define censored word matches
//
$orig_word = array();
$replacement_word = array();
obtain_word_list($orig_word, $replacement_word);

//
// Censor topic title
//
if ( count($orig_word) )
{
	$topic_title = preg_replace($orig_word, $replacement_word, $topic_title);
}

//
// Was a highlight request part of the URI?
//
$highlight_match = $highlight = '';
if (isset($HTTP_GET_VARS['highlight']))
{
	// Split words and phrases
	$words = explode(' ', trim(htmlspecialchars($HTTP_GET_VARS['highlight'])));

	for($i = 0; $i < sizeof($words); $i++)
	{
		if (trim($words[$i]) != '')
		{
			$highlight_match .= (($highlight_match != '') ? '|' : '') . str_replace('*', '\w*', preg_quote($words[$i], '#'));
		}
	}
	unset($words);

	$highlight = urlencode($HTTP_GET_VARS['highlight']);
	$highlight_match = phpbb_rtrim($highlight_match, "\\");
}

//
// Post, reply and other URL generation for
// templating vars
//
$new_topic_url = append_sid("posting.$phpEx?mode=newtopic&amp;" . POST_FORUM_URL . "=$forum_id");
$reply_topic_url = append_sid("posting.$phpEx?mode=reply&amp;" . POST_TOPIC_URL . "=$topic_id");
$view_forum_url = append_sid("viewforum.$phpEx?" . POST_FORUM_URL . "=$forum_id");
$view_prev_topic_url = append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;view=previous");
$view_next_topic_url = append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;view=next");

//
// Mozilla navigation bar
//
$nav_links['prev'] = array(
	'url' => $view_prev_topic_url,
	'title' => $lang['View_previous_topic']
);
$nav_links['next'] = array(
	'url' => $view_next_topic_url,
	'title' => $lang['View_next_topic']
);
$nav_links['up'] = array(
	'url' => $view_forum_url,
	'title' => $forum_name
);

$reply_img = ( $forum_topic_data['forum_status'] == FORUM_LOCKED || $forum_topic_data['topic_status'] == TOPIC_LOCKED ) ? $images['reply_locked'] : $images['reply_new'];
$reply_alt = ( $forum_topic_data['forum_status'] == FORUM_LOCKED || $forum_topic_data['topic_status'] == TOPIC_LOCKED ) ? $lang['Topic_locked'] : $lang['Reply_to_topic'];
$post_img = ( $forum_topic_data['forum_status'] == FORUM_LOCKED ) ? $images['post_locked'] : $images['post_new'];
$post_alt = ( $forum_topic_data['forum_status'] == FORUM_LOCKED ) ? $lang['Forum_locked'] : $lang['Post_new_topic'];

//
// Set a cookie for this topic
//
if ( $userdata['session_logged_in'] )
{
	$tracking_topics = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_t']) : array();
	$tracking_forums = ( isset($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) ) ? unserialize($HTTP_COOKIE_VARS[$board_config['cookie_name'] . '_f']) : array();

	if ( !empty($tracking_topics[$topic_id]) && !empty($tracking_forums[$forum_id]) )
	{
		$topic_last_read = ( $tracking_topics[$topic_id] > $tracking_forums[$forum_id] ) ? $tracking_topics[$topic_id] : $tracking_forums[$forum_id];
	}
	else if ( !empty($tracking_topics[$topic_id]) || !empty($tracking_forums[$forum_id]) )
	{
		$topic_last_read = ( !empty($tracking_topics[$topic_id]) ) ? $tracking_topics[$topic_id] : $tracking_forums[$forum_id];
	}
	else
	{
		$topic_last_read = $userdata['user_lastvisit'];
	}

	if ( count($tracking_topics) >= 150 && empty($tracking_topics[$topic_id]) )
	{
		asort($tracking_topics);
		unset($tracking_topics[key($tracking_topics)]);
	}

	$tracking_topics[$topic_id] = time();

	setcookie($board_config['cookie_name'] . '_t', serialize($tracking_topics), 0, $board_config['cookie_path'], $board_config['cookie_domain'], $board_config['cookie_secure']);
}

//
// Load templates
//
$template->set_filenames(array(
	'body' => 'viewtopic_body.tpl')
);
if (intval($forum_topic_data['attached_forum_id'])>0)
{
	$parent_lookup=intval($forum_topic_data['attached_forum_id']);
}
make_jumpbox('viewforum.'.$phpEx, $forum_id);

//
// Output page header
//
$page_title = $lang['View_topic'] .' - ' . $topic_title;
include($phpbb_root_path . 'includes/page_header.'.$phpEx);

//-- mod : quick post es -------------------------------------------------------
//-- add
$forum_qpes = intval($forum_topic_data['forum_qpes']);
if (!empty($forum_qpes))
{
	include($phpbb_root_path . 'qpes.' . $phpEx);
}
//-- fin mod : quick post es ---------------------------------------------------

//
// User authorisation levels output
//
$s_auth_can = ( ( $is_auth['auth_post'] ) ? $lang['Rules_post_can'] : $lang['Rules_post_cannot'] ) . '<br />';
$s_auth_can .= ( ( $is_auth['auth_reply'] ) ? $lang['Rules_reply_can'] : $lang['Rules_reply_cannot'] ) . '<br />';
$s_auth_can .= ( ( $is_auth['auth_edit'] ) ? $lang['Rules_edit_can'] : $lang['Rules_edit_cannot'] ) . '<br />';
$s_auth_can .= ( ( $is_auth['auth_delete'] ) ? $lang['Rules_delete_can'] : $lang['Rules_delete_cannot'] ) . '<br />';
$s_auth_can .= ( ( $is_auth['auth_vote'] ) ? $lang['Rules_vote_can'] : $lang['Rules_vote_cannot'] ) . '<br />';
$s_auth_can .= ( $is_auth['auth_ban'] ) ? $lang['Rules_ban_can'] . "<br />" : ""; 
$s_auth_can .= ( $is_auth['auth_greencard'] ) ? $lang['Rules_greencard_can'] . "<br />" : ""; 
$s_auth_can .= ( $is_auth['auth_bluecard'] ) ? $lang['Rules_bluecard_can'] . "<br />" : "";

$topic_mod = '';

if ( $is_auth['auth_mod'] )
{
	$s_auth_can .= sprintf($lang['Rules_moderate'], "<a href=\"modcp.$phpEx?" . POST_FORUM_URL . "=$forum_id&amp;sid=" . $userdata['session_id'] . '">', '</a>');

	$topic_mod .= "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=delete&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_delete'] . '" alt="' . $lang['Delete_topic'] . '" title="' . $lang['Delete_topic'] . '" border="0" /></a>&nbsp;';

	$topic_mod .= "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=move&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_move'] . '" alt="' . $lang['Move_topic'] . '" title="' . $lang['Move_topic'] . '" border="0" /></a>&nbsp;';

	$topic_mod .= ( $forum_topic_data['topic_status'] == TOPIC_UNLOCKED ) ? "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=lock&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_lock'] . '" alt="' . $lang['Lock_topic'] . '" title="' . $lang['Lock_topic'] . '" border="0" /></a>&nbsp;' : "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=unlock&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_unlock'] . '" alt="' . $lang['Unlock_topic'] . '" title="' . $lang['Unlock_topic'] . '" border="0" /></a>&nbsp;';

	$topic_mod .= "<a href=\"modcp.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;mode=split&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_mod_split'] . '" alt="' . $lang['Split_topic'] . '" title="' . $lang['Split_topic'] . '" border="0" /></a>&nbsp;';

//-- mod : merge -----------------------------------------------------------------------------------
//-- add
	$topic_mod .= '<a href="' . append_sid("merge.$phpEx?" . POST_TOPIC_URL . '=' . $topic_id) . '"><img src="' . $images['topic_mod_merge'] . '" alt="' . $lang['Merge_topics'] . '" title="' . $lang['Merge_topics'] . '" border="0" /></a>&nbsp;';
//-- fin mod : merge -------------------------------------------------------------------------------

}

//
// Topic watch information
//
$s_watching_topic = '';
if ( $can_watch_topic )
{
	if ( $is_watching_topic )
	{
		$s_watching_topic = "<a href=\"viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;unwatch=topic&amp;start=$start&amp;sid=" . $userdata['session_id'] . '">' . $lang['Stop_watching_topic'] . '</a>';
		$s_watching_topic_img = ( isset($images['topic_un_watch']) ) ? "<a href=\"viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;unwatch=topic&amp;start=$start&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['topic_un_watch'] . '" alt="' . $lang['Stop_watching_topic'] . '" title="' . $lang['Stop_watching_topic'] . '" border="0"></a>' : '';
	}
	else
	{
		$s_watching_topic = "<a href=\"viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;watch=topic&amp;start=$start&amp;sid=" . $userdata['session_id'] . '">' . $lang['Start_watching_topic'] . '</a>';
		$s_watching_topic_img = ( isset($images['Topic_watch']) ) ? "<a href=\"viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;watch=topic&amp;start=$start&amp;sid=" . $userdata['session_id'] . '"><img src="' . $images['Topic_watch'] . '" alt="' . $lang['Start_watching_topic'] . '" title="' . $lang['Start_watching_topic'] . '" border="0"></a>' : '';
	}
}

//
// If we've got a hightlight set pass it on to pagination,
// I get annoyed when I lose my highlight after the first page.
//
$pagination = ( $highlight != '' ) ? generate_pagination("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;postdays=$post_days&amp;postorder=$post_order&amp;highlight=$highlight", $total_replies, $board_config['posts_per_page'], $start) : generate_pagination("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;postdays=$post_days&amp;postorder=$post_order", $total_replies, $board_config['posts_per_page'], $start);

//
// Send vars to template
//
$template->assign_vars(array(
	'FORUM_ID' => $forum_id,
    'FORUM_NAME' => $forum_name,
    'TOPIC_ID' => $topic_id,
    'TOPIC_TITLE' => $topic_title,
	'PAGINATION' => $pagination,
	'PAGE_NUMBER' => sprintf($lang['Page_of'], ( floor( $start / intval($board_config['posts_per_page']) ) + 1 ), ceil( $total_replies / intval($board_config['posts_per_page']) )),

	'POST_IMG' => $post_img,
	'REPLY_IMG' => $reply_img,

	'L_AUTHOR' => $lang['Author'],
	'L_MESSAGE' => $lang['Message'],
	'L_POSTED' => $lang['Posted'],
	'L_POST_SUBJECT' => $lang['Post_subject'],
	'L_VIEW_NEXT_TOPIC' => $lang['View_next_topic'],
	'L_VIEW_PREVIOUS_TOPIC' => $lang['View_previous_topic'],
	'L_POST_NEW_TOPIC' => $post_alt,
	'L_POST_REPLY_TOPIC' => $reply_alt,
	'L_BACK_TO_TOP' => $lang['Back_to_top'],
	'L_DISPLAY_POSTS' => $lang['Display_posts'],
	'L_LOCK_TOPIC' => $lang['Lock_topic'],
	'L_UNLOCK_TOPIC' => $lang['Unlock_topic'],
	'L_MOVE_TOPIC' => $lang['Move_topic'],
	'L_SPLIT_TOPIC' => $lang['Split_topic'],
	'L_DELETE_TOPIC' => $lang['Delete_topic'],
	'L_GOTO_PAGE' => $lang['Goto_page'],

	'S_TOPIC_LINK' => POST_TOPIC_URL,
	'S_SELECT_POST_DAYS' => $select_post_days,
	'S_SELECT_POST_ORDER' => $select_post_order,
	'S_POST_DAYS_ACTION' => append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . '=' . $topic_id . "&amp;start=$start"),
	'S_AUTH_LIST' => $s_auth_can,
	'S_TOPIC_ADMIN' => $topic_mod,
	'S_WATCH_TOPIC' => $s_watching_topic,
	'S_WATCH_TOPIC_IMG' => $s_watching_topic_img,

	'U_VIEW_TOPIC' => append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;start=$start&amp;postdays=$post_days&amp;postorder=$post_order&amp;highlight=$highlight"),
	'U_VIEW_FORUM' => $view_forum_url,
	'U_VIEW_OLDER_TOPIC' => $view_prev_topic_url,
	'U_VIEW_NEWER_TOPIC' => $view_next_topic_url,
	'U_POST_NEW_TOPIC' => $new_topic_url,
	'U_POST_REPLY_TOPIC' => $reply_topic_url)
);

//
// Does this topic contain a poll?
//
if ( !empty($forum_topic_data['topic_vote']) )
{
// 
// Begin Approve_Mod Block : 10
// 

}	//end of: if ( !empty($forum_topic_data['topic_vote']) )
	//ends the 'Does this topic conatin a poll?' (instead of doing a replace, we redid the if on the bottom of this 'after, add'

$approve_mod = array();
$approve_sql = "SELECT * FROM " . APPROVE_FORUMS_TABLE . " 
	WHERE forum_id = " . intval($forum_id) . " 
	LIMIT 0,1"; 
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
$approve_mod['moderators'] = array();
$approve_mod['moderators'] = explode('|', $approve_mod['approve_moderators']);

if ( $approve_mod['enabled'] )
{
	if ( in_array($userdata['user_id'], $approve_mod['moderators']) || $is_auth['auth_mod'] )
	{
		function approve_mod_pm($type, $id)
		{
			global $approve_mod, $userdata, $user_ip, $session_length, $starttime, $template, $images, $theme, $db, $board_config, $phpEx, $lang, $phpbb_root_path, $html_entities_match, $html_entities_replace, $unhtml_specialchars_match, $unhtml_specialchars_replace;

			if ( $approve_mod['approve_notify_approval'] )
			{
				$server_name = trim($board_config['server_name']);
				$server_protocol = ( $board_config['cookie_secure'] ) ? 'https://' : 'http://';
				$server_port = ( $board_config['server_port'] <> 80 ) ? ':' . trim($board_config['server_port']) . '/' : '/';
				$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($board_config['script_path']));
				$script_name = ( $script_name != '' ) ? $script_name . '/viewtopic.'.$phpEx : 'viewtopic.'.$phpEx;
				if ( !class_exists('emailer') )
				{
					@include_once($phpbb_root_path . 'includes/emailer.'.$phpEx);
				}
				@include_once($phpbb_root_path . 'includes/functions_post.'.$phpEx);
				if ( $type == 'app_p' )
				{
					//notify user of post approval
					$approve_sql = "SELECT u.*, p.poster_id FROM " . USERS_TABLE . " u, " . APPROVE_POSTS_TABLE . " p 
						WHERE p.post_id = " .  intval($id) . " 
							AND u.user_id = p.poster_id";
					if ( !$approve_result = $db->sql_query($approve_sql) )
					{
						message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'], '', __LINE__, __FILE__, $approve_sql); 
					}
					if ( $approve_row = $db->sql_fetchrow($approve_result) )
					{
						$approve_to[0] = $approve_row;
					}
					$privmsg_subject = $lang['approve_notify_post_approved'] . " " . $lang['Post'] . ": " . $id;
					$privmsg_message = $lang['approve_notify_user_link'] . "\n" . $server_protocol . $server_name . $server_port . $script_name . '?'. POST_POST_URL . '=' . $id . '#' . $id;
				}
				elseif ( $type == 'app_c' )
				{
					//notify user of post approval
					$approve_sql = "SELECT u.*, p.post_id, p.topic_id FROM " . USERS_TABLE . " u, " . APPROVE_POSTS_TABLE . " p 	WHERE p.topic_id = " . intval($id) . " 
							AND u.user_id = p.poster_id";
					if ( !$approve_result = $db->sql_query($approve_sql) )
					{
						message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'], '', __LINE__, __FILE__, $approve_sql); 
					}
					if ( $approve_row = $db->sql_fetchrow($approve_result) )
					{
						$approve_to[0] = $approve_row;
					}
					$privmsg_subject = $lang['approve_notify_post_approved'] . " " . $lang['Topic'] . ": " . $id;
					$privmsg_message = $lang['approve_notify_user_link'] . "\n" . $server_protocol . $server_name . $server_port . $script_name . '?'. POST_POST_URL . '=' . $approve_to[0]['post_id'] . '#' . $approve_to[0]['post_id'] . "\n\n" .  $lang['approve_notify_user_topic'];
				}
				else
				{
					$approve_sql = "SELECT * FROM " . USERS_TABLE . " 
						WHERE user_id = " . intval($id);
					if ( !$approve_result = $db->sql_query($approve_sql) )
					{
						message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'], '', __LINE__, __FILE__, $approve_sql); 
					}
					if ( $approve_row = $db->sql_fetchrow($approve_result) )
					{
						$approve_to[0] = $approve_row;
					}
					switch($type)
					{
						case 'app_ua':
							$privmsg_subject = $lang['approve_notify_auto_app'];
							$privmsg_message = $lang['approve_notify_auto_app_msg'];
						break;

						case 'app_ur':
							$privmsg_subject = $lang['approve_notify_auto_app_rem'];
							$privmsg_message = $lang['approve_notify_auto_app_rem_msg'];
						break;

						case 'app_ma':
							$privmsg_subject = $lang['approve_notify_moderation'];
							$privmsg_message = $lang['approve_notify_moderation_msg'];
						break;

						case 'app_mr':
							$privmsg_subject = $lang['approve_notify_moderation_rem'];
							$privmsg_message = $lang['approve_notify_moderation_rem_msg'];
						break;
					}
				}
				$approve_user_list = array();
				for($i = 0; !empty($approve_to[$i]['user_id']); $i++)
				{
					if ( $approve_to[$i]['user_id'] != ANONYMOUS && !in_array($approve_to[$i]['user_id'], $approve_user_list) )
					{
						$approve_user_list[] = $approve_to[$i]['user_id'];

						$bbcode_uid = make_bbcode_uid();
						$privmsg_message = prepare_message($privmsg_message, 1, 1, 1, $bbcode_uid);
						$msg_time = time();
						//
						// See if recipient is at their inbox limit
						//
						$sql = "SELECT COUNT(privmsgs_id) AS inbox_items, MIN(privmsgs_date) AS oldest_post_time 
							FROM " . PRIVMSGS_TABLE . " 
							WHERE ( privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
									OR privmsgs_type = " . PRIVMSGS_READ_MAIL . "  
									OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . " ) 
								AND privmsgs_to_userid = " . intval($approve_to[$i]['user_id']);
						if ( !($result = $db->sql_query($sql)) )
						{
							return false;
						}
						$sql_priority = ( SQL_LAYER == 'mysql' ) ? 'LOW_PRIORITY' : '';
						if ( $inbox_info = $db->sql_fetchrow($result) )
						{
							if ( $inbox_info['inbox_items'] >= $board_config['max_inbox_privmsgs'] && !empty($inbox_info['oldest_post_time']) )
							{
								$sql = "SELECT privmsgs_id FROM " . PRIVMSGS_TABLE . " 
									WHERE ( privmsgs_type = " . PRIVMSGS_NEW_MAIL . " 
											OR privmsgs_type = " . PRIVMSGS_READ_MAIL . " 
											OR privmsgs_type = " . PRIVMSGS_UNREAD_MAIL . "  ) 
										AND privmsgs_date = " . $inbox_info['oldest_post_time'] . " 
										AND privmsgs_to_userid = " . intval($approve_to[$i]['user_id']);
								if ( !$result = $db->sql_query($sql) )
								{
									message_die(GENERAL_ERROR, 'Could not find oldest privmsgs (inbox)', '', __LINE__, __FILE__, $sql);
								}
								$old_privmsgs_id = $db->sql_fetchrow($result);
								$old_privmsgs_id = $old_privmsgs_id['privmsgs_id'];
								$sql = "DELETE $sql_priority FROM " . PRIVMSGS_TABLE . " 
									WHERE privmsgs_id = $old_privmsgs_id";
								if ( !$db->sql_query($sql) )
								{
									message_die(GENERAL_ERROR, 'Could not delete oldest privmsgs (inbox)'.$sql, '', __LINE__, __FILE__, $sql);
								}
								$sql = "DELETE $sql_priority FROM " . PRIVMSGS_TEXT_TABLE . " 
									WHERE privmsgs_text_id = $old_privmsgs_id";
								if ( !$db->sql_query($sql) )
								{
									message_die(GENERAL_ERROR, 'Could not delete oldest privmsgs text (inbox)', '', __LINE__, __FILE__, $sql);
								}
							}
						}
						//
						// Send the pm notification
						//
						$sql_info = "INSERT INTO " . PRIVMSGS_TABLE . " (privmsgs_type, privmsgs_subject, privmsgs_from_userid, privmsgs_to_userid, privmsgs_date, privmsgs_ip, privmsgs_enable_html, privmsgs_enable_bbcode, privmsgs_enable_smilies, privmsgs_attach_sig)
							VALUES (" . PRIVMSGS_NEW_MAIL . ", '" . str_replace("'", "\'", $privmsg_subject) . "', " . intval($approve_to[$i]['user_id']) . ", " . intval($approve_to[$i]['user_id']) . ", $msg_time, '0.0.0.0', 1, 1, 1, 0)";
						if ( !($result = $db->sql_query($sql_info, BEGIN_TRANSACTION)) )
						{
							message_die(GENERAL_ERROR, "Could not insert/update private message sent info.", "", __LINE__, __FILE__, $sql_info);
						}
						$privmsg_sent_id = $db->sql_nextid();
						$sql = "INSERT INTO " . PRIVMSGS_TEXT_TABLE . " (privmsgs_text_id, privmsgs_bbcode_uid, privmsgs_text)
								VALUES ($privmsg_sent_id, '" . $bbcode_uid . "', '" . str_replace("'", "\'", $privmsg_message) . "')";
						if ( !$db->sql_query($sql, END_TRANSACTION) )
						{
							message_die(GENERAL_ERROR, "Could not insert/update private message sent text.", "", __LINE__, __FILE__, $sql_info);
						}									
						//
						// Add to the users new pm counter
						//
						$sql = "UPDATE " . USERS_TABLE . "
							SET user_new_privmsg = user_new_privmsg + 1, user_last_privmsg = " . time() . "  
							WHERE user_id = " . intval($approve_to[$i]['user_id']); 
						if ( !$status = $db->sql_query($sql) )
						{
							message_die(GENERAL_ERROR, 'Could not update private message new/read status for user', '', __LINE__, __FILE__, $sql);
						}
						//
						// E-mail notify the user of the new PM if they have email notification for PM enabled in profile
						//
						if ( $approve_to[$i]['user_notify_pm'] && !empty($approve_to[$i]['user_email']) && $approve_to[$i]['user_active'] )
						{
							$email_headers = 'From: ' . $board_config['board_email'] . "\nReturn-Path: " . $board_config['board_email'] . "\n";
							$script_name = preg_replace('/^\/?(.*?)\/?$/', "\\1", trim($board_config['script_path']));
							$script_name = ( $script_name != '' ) ? $script_name . '/privmsg.'.$phpEx : 'privmsg.'.$phpEx;
							$emailer = new emailer($board_config['smtp_delivery']);
							$emailer->use_template('privmsg_notify',$approve_to[$i]['user_lang']);
							$emailer->extra_headers($email_headers);
							$emailer->email_address($approve_to[$i]['user_email']);
							$emailer->set_subject($lang['Notification_subject']);
							$emailer->assign_vars(array(
								'USERNAME' => $approve_to[$i]['username'], 
								'SITENAME' => $board_config['sitename'],
								'EMAIL_SIG' => (!empty($board_config['board_email_sig'])) ? str_replace('<br />', "\n", "-- \n" . $board_config['board_email_sig']) : '', 
								'U_INBOX' => $server_protocol . $server_name . $server_port . $script_name . '?folder=inbox')
							);
							$emailer->send();
							$emailer->reset();
						}
					}//if not guest or we've already notified them once
				}//for loop
			}
		}//function approve_mod_pm
		
		if ( isset($HTTP_GET_VARS['app_p']) ) 
		{ 
			//notify user
			approve_mod_pm('app_p', intval($HTTP_GET_VARS['app_p']));
			$approve_sql = "DELETE FROM " . APPROVE_POSTS_TABLE . " 
				WHERE post_id = " . intval($HTTP_GET_VARS['app_p']); 
			if ( !($approve_result = $db->sql_query($approve_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_delete'], '', __LINE__, __FILE__, $approve_sql); 
			}
		}
		if ( isset($HTTP_GET_VARS['app_c']) ) 
		{ 
			//loop through & notify users
			approve_mod_pm('app_c', intval($HTTP_GET_VARS['app_c']));
			$approve_sql = "DELETE FROM " . APPROVE_POSTS_TABLE . " 
				WHERE topic_id = " . intval($HTTP_GET_VARS['app_c']); 
			if ( !($approve_result = $db->sql_query($approve_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_delete'], '', __LINE__, __FILE__, $approve_sql); 
			}
		}
		if ( isset($HTTP_GET_VARS['app_f']) ) 
		{ 
			$approve_sql = "DELETE FROM " . APPROVE_TOPICS_TABLE . " 
				WHERE topic_id = " . intval($HTTP_GET_VARS['app_f']); 
			if ( !($approve_result = $db->sql_query($approve_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_delete'], '', __LINE__, __FILE__, $approve_sql); 
			}
			$approve_sql = "INSERT INTO " . APPROVE_TOPICS_TABLE . " (topic_id, approve_moderate) 
				VALUES (" . intval($HTTP_GET_VARS['app_f']) . ", -1)"; 
			if ( !($approve_result = $db->sql_query($approve_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_insert'], '', __LINE__, __FILE__, $approve_sql); 
			}
		}
		if ( isset($HTTP_GET_VARS['app_r']) ) 
		{
			$approve_sql = "DELETE FROM " . APPROVE_TOPICS_TABLE . " 
				WHERE topic_id = " . intval($HTTP_GET_VARS['app_r']) . " 
					AND approve_moderate = -1"; 
			if ( !($approve_result = $db->sql_query($approve_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_delete'], '', __LINE__, __FILE__, $approve_sql); 
			}
		}
		if ( isset($HTTP_GET_VARS['app_ua']) ) 
		{ 
			//pm notify user they're being auto-approved now
			approve_mod_pm('app_ua', intval($HTTP_GET_VARS['app_ua']));
			$approve_sql = "DELETE FROM " . APPROVE_USERS_TABLE . " 
				WHERE user_id = " . intval($HTTP_GET_VARS['app_ua']); 
			if ( !($approve_result = $db->sql_query($approve_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_delete'], '', __LINE__, __FILE__, $approve_sql); 
			}
			$approve_sql = "INSERT INTO " . APPROVE_USERS_TABLE . " (user_id, approve_moderate) 
				VALUES (" . intval($HTTP_GET_VARS['app_ua']) . ", -1)"; 
			if ( !($approve_result = $db->sql_query($approve_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_insert'], '', __LINE__, __FILE__, $approve_sql); 
			}
			$approve_sql = "DELETE FROM " . APPROVE_POSTS_TABLE . " 
				WHERE poster_id = " . intval($HTTP_GET_VARS['app_ua']); 
			if ( !($approve_result = $db->sql_query($approve_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_delete'], '', __LINE__, __FILE__, $approve_sql); 
			}
		}
		if ( isset($HTTP_GET_VARS['app_ur']) ) 
		{ 
			//pm notify user they're no longer being auto-approved
			approve_mod_pm('app_ur', intval($HTTP_GET_VARS['app_ur']));
			$approve_sql = "DELETE FROM " . APPROVE_USERS_TABLE . " 
				WHERE user_id = " . intval($HTTP_GET_VARS['app_ur']) . " 
					AND approve_moderate = -1"; 
			if ( !($approve_result = $db->sql_query($approve_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_delete'], '', __LINE__, __FILE__, $approve_sql); 
			}
		} 
		if ( isset($HTTP_GET_VARS['app_ma']) ) 
		{ 
			//pm notify user they're being moderated now
			approve_mod_pm('app_ma', intval($HTTP_GET_VARS['app_ma']));
			$approve_sql = "DELETE FROM " . APPROVE_USERS_TABLE . " 
				WHERE user_id = " . intval($HTTP_GET_VARS['app_ma']); 
			if ( !($approve_result = $db->sql_query($approve_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_delete'], '', __LINE__, __FILE__, $approve_sql); 
			}
			$approve_sql = "INSERT INTO " . APPROVE_USERS_TABLE . " (user_id, approve_moderate) 
				VALUES (" . intval($HTTP_GET_VARS['app_ma']) . ", 1)"; 
			if ( !($approve_result = $db->sql_query($approve_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_insert'], '', __LINE__, __FILE__, $approve_sql); 
			}
		}
		if ( isset($HTTP_GET_VARS['app_mr']) ) 
		{ 
			//pm notify user they're not longer being moderated
			approve_mod_pm('app_mr', intval($HTTP_GET_VARS['app_mr']));
			$approve_sql = "DELETE FROM " . APPROVE_USERS_TABLE . " 
				WHERE user_id = " . intval($HTTP_GET_VARS['app_mr']) . " 
					AND approve_moderate = 1"; 
			if ( !($approve_result = $db->sql_query($approve_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_delete'], '', __LINE__, __FILE__, $approve_sql); 
			}
		} 
		if ( isset($HTTP_GET_VARS['app_ta']) ) 
		{ 
			//notify user
			approve_mod_pm('app_p', intval($HTTP_GET_VARS['app_ta']));
			$approve_sql = "DELETE FROM " . APPROVE_TOPICS_TABLE . " 
				WHERE topic_id = " . intval($HTTP_GET_VARS['app_ta']); 
			if ( !($approve_result = $db->sql_query($approve_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_delete'], '', __LINE__, __FILE__, $approve_sql); 
			}
			$approve_sql = "INSERT INTO " . APPROVE_TOPICS_TABLE . " (topic_id, approve_moderate) 
				VALUES (" . intval($HTTP_GET_VARS['app_ta']) . ", 1)"; 
			if ( !($approve_result = $db->sql_query($approve_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_insert'], '', __LINE__, __FILE__, $approve_sql); 
			}
		}
		if ( isset($HTTP_GET_VARS['app_tr']) ) 
		{ 
			//notify user
			approve_mod_pm('app_p', intval($HTTP_GET_VARS['app_tr']));
			$approve_sql = "DELETE FROM " . APPROVE_TOPICS_TABLE . " 
				WHERE topic_id = " . intval($HTTP_GET_VARS['app_tr']) . " 
					AND approve_moderate = 1"; 
			if ( !($approve_result = $db->sql_query($approve_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_delete'], '', __LINE__, __FILE__, $approve_sql); 
			}
		}
		//if the topic is awaiting approval, notify mod user to approve this topic
		$approve_sql = "SELECT * FROM " . APPROVE_POSTS_TABLE . " 
			WHERE topic_id = " . intval($topic_id) . " 
				AND is_topic = 1 
			LIMIT 0,1"; 
		if ( !($approve_result = $db->sql_query($approve_sql)) ) 
		{ 
			message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'], '', __LINE__, __FILE__, $approve_sql); 
		} 
		if ( $approve_row = $db->sql_fetchrow($approve_result) ) 
		{ 
			if ( intval($approve_row['is_topic']) == 1 )
			{
				$approve_mod['topics_admin_awaiting'] = true;
			}  
		}
	}
	else
	{
		//if the topic is awaiting approval, notify non-mod user
		$approve_sql = "SELECT * FROM " . APPROVE_POSTS_TABLE . " 
			WHERE topic_id = " . intval($topic_id) . " 
				AND is_topic = 1 
			LIMIT 0,1"; 
		if ( !($approve_result = $db->sql_query($approve_sql)) ) 
		{ 
			message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'], '', __LINE__, __FILE__, $approve_sql); 
		} 
		if ( $approve_row = $db->sql_fetchrow($approve_result) ) 
		{ 
			if ( intval($approve_row['is_topic']) == 1 )
			{
				$approve_mod['topics_awaiting'] = true;
			}  
		}
	}	
}

	//resetting up the if statement for polls, and modifying to hide polls when topic isn't approved yet.

//
// Does this topic contain a poll?
//
if ( !empty($forum_topic_data['topic_vote']) && !$approve_mod['topics_awaiting'] )
{

// 
// End Approve_Mod Block : 10
//

	$s_hidden_fields = '';

	$sql = "SELECT vd.vote_id, vd.vote_text, vd.vote_start, vd.vote_length, vr.vote_option_id, vr.vote_option_text, vr.vote_result
		FROM " . VOTE_DESC_TABLE . " vd, " . VOTE_RESULTS_TABLE . " vr
		WHERE vd.topic_id = $topic_id
			AND vr.vote_id = vd.vote_id
		ORDER BY vr.vote_option_id ASC";
	if ( !($result = $db->sql_query($sql)) )
	{
		message_die(GENERAL_ERROR, "Could not obtain vote data for this topic", '', __LINE__, __FILE__, $sql);
	}

	if ( $vote_info = $db->sql_fetchrowset($result) )
	{
		$db->sql_freeresult($result);
		$vote_options = count($vote_info);

		$vote_id = $vote_info[0]['vote_id'];
		$vote_title = $vote_info[0]['vote_text'];

		$sql = "SELECT vote_id
			FROM " . VOTE_USERS_TABLE . "
			WHERE vote_id = $vote_id
				AND vote_user_id = " . intval($userdata['user_id']);
		if ( !($result = $db->sql_query($sql)) )
		{
			message_die(GENERAL_ERROR, "Could not obtain user vote data for this topic", '', __LINE__, __FILE__, $sql);
		}

		$user_voted = ( $row = $db->sql_fetchrow($result) ) ? TRUE : 0;
		$db->sql_freeresult($result);

		if ( isset($HTTP_GET_VARS['vote']) || isset($HTTP_POST_VARS['vote']) )
		{
			$view_result = ( ( ( isset($HTTP_GET_VARS['vote']) ) ? $HTTP_GET_VARS['vote'] : $HTTP_POST_VARS['vote'] ) == 'viewresult' ) ? TRUE : 0;
		}
		else
		{
			$view_result = 0;
		}

		$poll_expired = ( $vote_info[0]['vote_length'] ) ? ( ( $vote_info[0]['vote_start'] + $vote_info[0]['vote_length'] < time() ) ? TRUE : 0 ) : 0;

		if ( $user_voted || $view_result || $poll_expired || !$is_auth['auth_vote'] || $forum_topic_data['topic_status'] == TOPIC_LOCKED )
		{
			$template->set_filenames(array(
				'pollbox' => 'viewtopic_poll_result.tpl')
			);

			$vote_results_sum = 0;

			for($i = 0; $i < $vote_options; $i++)
			{
				$vote_results_sum += $vote_info[$i]['vote_result'];
			}

			$vote_graphic = 0;
			$vote_graphic_max = count($images['voting_graphic']);

			for($i = 0; $i < $vote_options; $i++)
			{
				$vote_percent = ( $vote_results_sum > 0 ) ? $vote_info[$i]['vote_result'] / $vote_results_sum : 0;
				$vote_graphic_length = round($vote_percent * $board_config['vote_graphic_length']);

				$vote_graphic_img = $images['voting_graphic'][$vote_graphic];
				$vote_graphic = ($vote_graphic < $vote_graphic_max - 1) ? $vote_graphic + 1 : 0;

				if ( count($orig_word) )
				{
					$vote_info[$i]['vote_option_text'] = preg_replace($orig_word, $replacement_word, $vote_info[$i]['vote_option_text']);
				}

				$template->assign_block_vars("poll_option", array(
					'POLL_OPTION_CAPTION' => $vote_info[$i]['vote_option_text'],
					'POLL_OPTION_RESULT' => $vote_info[$i]['vote_result'],
					'POLL_OPTION_PERCENT' => sprintf("%.1d%%", ($vote_percent * 100)),

					'POLL_OPTION_IMG' => $vote_graphic_img,
					'POLL_OPTION_IMG_WIDTH' => $vote_graphic_length)
				);
			}

			$template->assign_vars(array(
				'L_TOTAL_VOTES' => $lang['Total_votes'],
				'TOTAL_VOTES' => $vote_results_sum)
			);

		}
		else
		{
			$template->set_filenames(array(
				'pollbox' => 'viewtopic_poll_ballot.tpl')
			);

			for($i = 0; $i < $vote_options; $i++)
			{
				if ( count($orig_word) )
				{
					$vote_info[$i]['vote_option_text'] = preg_replace($orig_word, $replacement_word, $vote_info[$i]['vote_option_text']);
				}

				$template->assign_block_vars("poll_option", array(
					'POLL_OPTION_ID' => $vote_info[$i]['vote_option_id'],
					'POLL_OPTION_CAPTION' => $vote_info[$i]['vote_option_text'])
				);
			}

			$template->assign_vars(array(
				'L_SUBMIT_VOTE' => $lang['Submit_vote'],
				'L_VIEW_RESULTS' => $lang['View_results'],

				'U_VIEW_RESULTS' => append_sid("viewtopic.$phpEx?" . POST_TOPIC_URL . "=$topic_id&amp;postdays=$post_days&amp;postorder=$post_order&amp;vote=viewresult"))
			);

			$s_hidden_fields = '<input type="hidden" name="topic_id" value="' . $topic_id . '" /><input type="hidden" name="mode" value="vote" />';
		}

		if ( count($orig_word) )
		{
			$vote_title = preg_replace($orig_word, $replacement_word, $vote_title);
		}

		$s_hidden_fields .= '<input type="hidden" name="sid" value="' . $userdata['session_id'] . '" />';

		$template->assign_vars(array(
			'POLL_QUESTION' => $vote_title,

			'S_HIDDEN_FIELDS' => $s_hidden_fields,
			'S_POLL_ACTION' => append_sid("posting.$phpEx?mode=vote&amp;" . POST_TOPIC_URL . "=$topic_id"))
		);

		$template->assign_var_from_handle('POLL_DISPLAY', 'pollbox');
	}
}

//
// Update the topic view counter
//
$sql = "UPDATE " . TOPICS_TABLE . "
	SET topic_views = topic_views + 1
	WHERE topic_id = $topic_id";
if ( !$db->sql_query($sql) )
{
	message_die(GENERAL_ERROR, "Could not update topic views.", '', __LINE__, __FILE__, $sql);
}

//
// Okay, let's do the loop, yeah come on baby let's do the loop
// and it goes like this ...
//
for($i = 0; $i < $total_posts; $i++)
{
	$poster_id = $postrow[$i]['user_id'];
	$poster = ( $poster_id == ANONYMOUS ) ? $lang['Guest'] : $postrow[$i]['username'];
//-- mod : birthday ------------------------------------------------------------
//-- add
	$bdays->get_user_bday($postrow[$i]['user_birthday'], $postrow[$i]['username'], true);
	$poster_age = $bdays->data_age;
	$poster_cake = $bdays->data_cake;
//-- fin mod : birthday --------------------------------------------------------

	$post_date = create_date($board_config['default_dateformat'], $postrow[$i]['post_time'], $board_config['board_timezone']);

	$poster_posts = ( $postrow[$i]['user_id'] != ANONYMOUS ) ? $lang['Posts'] . ': ' . $postrow[$i]['user_posts'] : '';

	$poster_from = ( $postrow[$i]['user_from'] && $postrow[$i]['user_id'] != ANONYMOUS ) ? $lang['Location'] . ': ' . $postrow[$i]['user_from'] : '';

	$poster_joined = ( $postrow[$i]['user_id'] != ANONYMOUS ) ? $lang['Joined'] . ': ' . create_date($lang['DATE_FORMAT'], $postrow[$i]['user_regdate'], $board_config['board_timezone']) : '';

	$poster_avatar = '';
	if ( $postrow[$i]['user_avatar_type'] && $poster_id != ANONYMOUS && $postrow[$i]['user_allowavatar'] )
	{
		switch( $postrow[$i]['user_avatar_type'] )
		{
			case USER_AVATAR_UPLOAD:
				$poster_avatar = ( $board_config['allow_avatar_upload'] ) ? '<img src="' . $board_config['avatar_path'] . '/' . $postrow[$i]['user_avatar'] . '" alt="" border="0" />' : '';
				break;
			case USER_AVATAR_REMOTE:
				$poster_avatar = ( $board_config['allow_avatar_remote'] ) ? '<img src="' . $postrow[$i]['user_avatar'] . '" alt="" border="0" />' : '';
				break;
			case USER_AVATAR_GALLERY:
				$poster_avatar = ( $board_config['allow_avatar_local'] ) ? '<img src="' . $board_config['avatar_gallery_path'] . '/' . $postrow[$i]['user_avatar'] . '" alt="" border="0" />' : '';
				break;
		}
	}

	//
	// Define the little post icon
	//
	if ( $userdata['session_logged_in'] && $postrow[$i]['post_time'] > $userdata['user_lastvisit'] && $postrow[$i]['post_time'] > $topic_last_read )
	{
		$mini_post_img = $images['icon_minipost_new'];
		$mini_post_alt = $lang['New_post'];
	}
	else
	{
		$mini_post_img = $images['icon_minipost'];
		$mini_post_alt = $lang['Post'];
	}

	$mini_post_url = append_sid("viewtopic.$phpEx?" . POST_POST_URL . '=' . $postrow[$i]['post_id']) . '#' . $postrow[$i]['post_id'];

	//
	// Generate ranks, set them to empty string initially.
	//
	$poster_rank = '';
	$rank_image = '';
	if ( $postrow[$i]['user_id'] == ANONYMOUS )
	{
	}
	else if ( $postrow[$i]['user_rank'] )
	{
		for($j = 0; $j < count($ranksrow); $j++)
		{
			if ( $postrow[$i]['user_rank'] == $ranksrow[$j]['rank_id'] && $ranksrow[$j]['rank_special'] )
			{
				$poster_rank = $ranksrow[$j]['rank_title'];
				$rank_image = ( $ranksrow[$j]['rank_image'] ) ? '<img src="' . $ranksrow[$j]['rank_image'] . '" alt="' . $poster_rank . '" title="' . $poster_rank . '" border="0" /><br />' : '';
			}
		}
	}
	else
	{
		for($j = 0; $j < count($ranksrow); $j++)
		{
			if ( $postrow[$i]['user_posts'] >= $ranksrow[$j]['rank_min'] && !$ranksrow[$j]['rank_special'] )
			{
				$poster_rank = $ranksrow[$j]['rank_title'];
				$rank_image = ( $ranksrow[$j]['rank_image'] ) ? '<img src="' . $ranksrow[$j]['rank_image'] . '" alt="' . $poster_rank . '" title="' . $poster_rank . '" border="0" /><br />' : '';
			}
		}
	}

	//
	// Handle anon users posting with usernames
	//
	if ( $poster_id == ANONYMOUS && $postrow[$i]['post_username'] != '' )
	{
		$poster = $postrow[$i]['post_username'];
		$poster_rank = $lang['Guest'];
//-- mod : birthday ------------------------------------------------------------
//-- add
		$poster_age = '';
		$poster_cake = '';
//-- fin mod : birthday --------------------------------------------------------
	}

	$temp_url = '';

	if ( $poster_id != ANONYMOUS )
	{
		$temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$poster_id");
		$profile_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_profile'] . '" alt="' . $lang['Read_profile'] . '" title="' . $lang['Read_profile'] . '" border="0" /></a>';
		$profile = '<a href="' . $temp_url . '">' . $lang['Read_profile'] . '</a>';

		$temp_url = append_sid("privmsg.$phpEx?mode=post&amp;" . POST_USERS_URL . "=$poster_id");
		$pm_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_pm'] . '" alt="' . $lang['Send_private_message'] . '" title="' . $lang['Send_private_message'] . '" border="0" /></a>';
		$pm = '<a href="' . $temp_url . '">' . $lang['Send_private_message'] . '</a>';

		if ( !empty($postrow[$i]['user_viewemail']) || $is_auth['auth_mod'] )
		{
			$email_uri = ( $board_config['board_email_form'] ) ? append_sid("profile.$phpEx?mode=email&amp;" . POST_USERS_URL .'=' . $poster_id) : 'mailto:' . $postrow[$i]['user_email'];

			$email_img = '<a href="' . $email_uri . '"><img src="' . $images['icon_email'] . '" alt="' . $lang['Send_email'] . '" title="' . $lang['Send_email'] . '" border="0" /></a>';
			$email = '<a href="' . $email_uri . '">' . $lang['Send_email'] . '</a>';
		}
		else
		{
			$email_img = '';
			$email = '';
		}

		$www_img = ( $postrow[$i]['user_website'] ) ? '<a href="' . $postrow[$i]['user_website'] . '" target="_userwww"><img src="' . $images['icon_www'] . '" alt="' . $lang['Visit_website'] . '" title="' . $lang['Visit_website'] . '" border="0" /></a>' : '';
		$www = ( $postrow[$i]['user_website'] ) ? '<a href="' . $postrow[$i]['user_website'] . '" target="_userwww">' . $lang['Visit_website'] . '</a>' : '';

		if ( !empty($postrow[$i]['user_icq']) )
		{
			$icq_status_img = '<a href="http://wwp.icq.com/' . $postrow[$i]['user_icq'] . '#pager"><img src="http://web.icq.com/whitepages/online?icq=' . $postrow[$i]['user_icq'] . '&img=5" width="18" height="18" border="0" /></a>';
			$icq_img = '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $postrow[$i]['user_icq'] . '"><img src="' . $images['icon_icq'] . '" alt="' . $lang['ICQ'] . '" title="' . $lang['ICQ'] . '" border="0" /></a>';
			$icq =  '<a href="http://wwp.icq.com/scripts/search.dll?to=' . $postrow[$i]['user_icq'] . '">' . $lang['ICQ'] . '</a>';
		}
		else
		{
			$icq_status_img = '';
			$icq_img = '';
			$icq = '';
		}

		$aim_img = ( $postrow[$i]['user_aim'] ) ? '<a href="aim:goim?screenname=' . $postrow[$i]['user_aim'] . '&amp;message=Hello+Are+you+there?"><img src="' . $images['icon_aim'] . '" alt="' . $lang['AIM'] . '" title="' . $lang['AIM'] . '" border="0" /></a>' : '';
		$aim = ( $postrow[$i]['user_aim'] ) ? '<a href="aim:goim?screenname=' . $postrow[$i]['user_aim'] . '&amp;message=Hello+Are+you+there?">' . $lang['AIM'] . '</a>' : '';

		$temp_url = append_sid("profile.$phpEx?mode=viewprofile&amp;" . POST_USERS_URL . "=$poster_id");
		$msn_img = ( $postrow[$i]['user_msnm'] ) ? '<a href="' . $temp_url . '"><img src="' . $images['icon_msnm'] . '" alt="' . $lang['MSNM'] . '" title="' . $lang['MSNM'] . '" border="0" /></a>' : '';
		$msn = ( $postrow[$i]['user_msnm'] ) ? '<a href="' . $temp_url . '">' . $lang['MSNM'] . '</a>' : '';

		$yim_img = ( $postrow[$i]['user_yim'] ) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $postrow[$i]['user_yim'] . '&amp;.src=pg"><img src="' . $images['icon_yim'] . '" alt="' . $lang['YIM'] . '" title="' . $lang['YIM'] . '" border="0" /></a>' : '';
		$yim = ( $postrow[$i]['user_yim'] ) ? '<a href="http://edit.yahoo.com/config/send_webmesg?.target=' . $postrow[$i]['user_yim'] . '&amp;.src=pg">' . $lang['YIM'] . '</a>' : '';
	}
	else
	{
		$profile_img = '';
		$profile = '';
		$pm_img = '';
		$pm = '';
		$email_img = '';
		$email = '';
		$www_img = '';
		$www = '';
		$icq_status_img = '';
		$icq_img = '';
		$icq = '';
		$aim_img = '';
		$aim = '';
		$msn_img = '';
		$msn = '';
		$yim_img = '';
		$yim = '';
	}

	$temp_url = append_sid("posting.$phpEx?mode=quote&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id']);
	$quote_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_quote'] . '" alt="' . $lang['Reply_with_quote'] . '" title="' . $lang['Reply_with_quote'] . '" border="0" /></a>';
	$quote = '<a href="' . $temp_url . '">' . $lang['Reply_with_quote'] . '</a>';

//-- mod : quick post es -------------------------------------------------------
//-- add
	$qp_quote_img = (!empty($qp_form) && empty($qp_lite)) ? '&nbsp;<img alt="' . $lang['Reply_with_quote'] . '" src="' . $images['qp_quote'] . '" title="' . $lang['Reply_with_quote'] . '" onmousedown="addquote(' . $postrow[$i]['post_id'] . ', \'' . str_replace('\'', '\\\'', (($poster_id == ANONYMOUS) ? (($postrow[$i]['post_username'] != '') ? $postrow[$i]['post_username'] : $lang['Guest']) : $postrow[$i]['username'])) . '\')" style="cursor:pointer;" border="0" />' : '';
//-- fin mod : quick post es ---------------------------------------------------

	$temp_url = append_sid("search.$phpEx?search_author=" . urlencode($postrow[$i]['username']) . "&amp;showresults=posts");
	$search_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_search'] . '" alt="' . sprintf($lang['Search_user_posts'], $postrow[$i]['username']) . '" title="' . sprintf($lang['Search_user_posts'], $postrow[$i]['username']) . '" border="0" /></a>';
	$search = '<a href="' . $temp_url . '">' . sprintf($lang['Search_user_posts'], $postrow[$i]['username']) . '</a>';

	if ( ( $userdata['user_id'] == $poster_id && $is_auth['auth_edit'] ) || $is_auth['auth_mod'] )
	{
		$temp_url = append_sid("posting.$phpEx?mode=editpost&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id']);
		$edit_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_edit'] . '" alt="' . $lang['Edit_delete_post'] . '" title="' . $lang['Edit_delete_post'] . '" border="0" /></a>';
		$edit = '<a href="' . $temp_url . '">' . $lang['Edit_delete_post'] . '</a>';
	}
	else
	{
		$edit_img = '';
		$edit = '';
	}

	if ( $is_auth['auth_mod'] )
	{
		$temp_url = "mph.$phpEx?" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;" . POST_TOPIC_URL . "=" . $topic_id . "&amp;sid=" . $userdata['session_id'];
		$ip_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_ip'] . '" alt="' . $lang['View_IP'] . '" title="' . $lang['View_IP'] . '" border="0" /></a>';
		$ip = '<a href="' . $temp_url . '">' . $lang['View_IP'] . '</a>';

		$temp_url = "posting.$phpEx?mode=delete&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;sid=" . $userdata['session_id'];
		$delpost_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" border="0" /></a>';
		$delpost = '<a href="' . $temp_url . '">' . $lang['Delete_post'] . '</a>';
	}
	else
	{
		$ip_img = '';
		$ip = '';

		if ( $userdata['user_id'] == $poster_id && $is_auth['auth_delete'] && $forum_topic_data['topic_last_post_id'] == $postrow[$i]['post_id'] )
		{
			$temp_url = "posting.$phpEx?mode=delete&amp;" . POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&amp;sid=" . $userdata['session_id'];
			$delpost_img = '<a href="' . $temp_url . '"><img src="' . $images['icon_delpost'] . '" alt="' . $lang['Delete_post'] . '" title="' . $lang['Delete_post'] . '" border="0" /></a>';
			$delpost = '<a href="' . $temp_url . '">' . $lang['Delete_post'] . '</a>';
		}
		else
		{
			$delpost_img = '';
			$delpost = '';
		}
	}
	if($poster_id != ANONYMOUS && $postrow[$i]['user_level'] != ADMIN) 
	{ 
		$current_user = str_replace("'","\'",$postrow[$i]['username']);
		if ($is_auth['auth_greencard']) 
		{ 
	      		$g_card_img = ' <input type="image" name="unban" value="unban" onClick="return confirm(\''.sprintf($lang['Green_card_warning'],$current_user).'\')" src="'. $images['icon_g_card'] . '" alt="' . $lang['Give_G_card'] . '" >'; 
		} 
		else 
		{
			$g_card_img = ''; 
		}
		$user_warnings = $postrow[$i]['user_warnings'];
		$card_img = ($user_warnings) ? (( $user_warnings < $board_config['max_user_bancard']) ? sprintf($lang['Warnings'], $user_warnings) : $lang['Banned'] ) : '';

		if ($user_warnings<$board_config['max_user_bancard'] && $is_auth['auth_ban'] )
		{ 
			$y_card_img = ' <input type="image" name="warn" value="warn" onClick="return confirm(\''.sprintf($lang['Yellow_card_warning'],$current_user).'\')" src="'. $images['icon_y_card'] . '" alt="' . sprintf($lang['Give_Y_card'],$user_warnings+1) . '" >'; 
     			$r_card_img = ' <input type="image" name="ban" value="ban"  onClick="return confirm(\''.sprintf($lang['Red_card_warning'],$current_user).'\')" src="'. $images['icon_r_card'] . '" alt="' . $lang['Give_R_card'] . '" >'; 
		}
		else
		{
			$y_card_img = '';
			$r_card_img = ''; 
		} 
	} 
	else
	{
		$card_img = '';
		$g_card_img = '';
		$y_card_img = '';
		$r_card_img = '';
	}

	if ($is_auth['auth_bluecard']) 
	{ 
		if ($is_auth['auth_mod']) 
		{ 
			$b_card_img = (($postrow[$i]['post_bluecard'])) ? ' <input type="image" name="report_reset" value="report_reset" onClick="return confirm(\''.$lang['Clear_blue_card_warning'].'\')" src="'. $images['icon_bhot_card'] . '" alt="'. sprintf($lang['Clear_b_card'],$postrow[$i]['post_bluecard']) . '">':' <input type="image" name="report" value="report" onClick="return confirm(\''.$lang['Blue_card_warning'].'\')" src="'. $images['icon_b_card'] . '" alt="'. $lang['Give_b_card'] . '" >'; 
		} 
   		else 
		{ 
			$b_card_img = ' <input type="image" name="report" value="report" onClick="return confirm(\''.$lang['Blue_card_warning'].'\')" src="'. $images['icon_b_card'] . '" alt="'. $lang['Give_b_card'] . '" >';
			
   		}
	} 
	else $b_card_img = '';

	// parse hidden filds if cards visible
	$card_hidden = ($g_card_img || $r_card_img || $y_card_img || $b_card_img) ? '<input type="hidden" name="post_id" value="'. $postrow[$i]['post_id'].'">' :'';

	$post_subject = ( $postrow[$i]['post_subject'] != '' ) ? $postrow[$i]['post_subject'] : '';

	$message = $postrow[$i]['post_text'];
	$bbcode_uid = $postrow[$i]['bbcode_uid'];

	$user_sig = ( $postrow[$i]['enable_sig'] && $postrow[$i]['user_sig'] != '' && $board_config['allow_sig'] ) ? $postrow[$i]['user_sig'] : '';
	$user_sig_bbcode_uid = $postrow[$i]['user_sig_bbcode_uid'];

	//
	// Note! The order used for parsing the message _is_ important, moving things around could break any
	// output
	//

	//
	// If the board has HTML off but the post has HTML
	// on then we process it, else leave it alone
	//
	if ( !$board_config['allow_html'] || !$userdata['user_allowhtml'])
	{
		if ( $user_sig != '' )
		{
			$user_sig = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $user_sig);
		}

		if ( $postrow[$i]['enable_html'] )
		{
			$message = preg_replace('#(<)([\/]?.*?)(>)#is', "&lt;\\2&gt;", $message);
		}
	}

	//
	// Parse message and/or sig for BBCode if reqd
	//
	if ($user_sig != '' && $user_sig_bbcode_uid != '')
	{
			// Start add - Signatures control MOD
			if ( $userdata['user_allowsignature'] != 2 && $board_config['sig_allow_font_sizes'] == 0 )
			{
				$user_sig = '[size=' . $board_config['sig_max_font_size'] . ':' . $user_sig_bbcode_uid . ']' . $user_sig . '[/size:' . $user_sig_bbcode_uid . ']';
			}
			// End add - Signatures control MOD
		$user_sig = ($board_config['allow_bbcode']) ? bbencode_second_pass($user_sig, $user_sig_bbcode_uid) : preg_replace("/\:$user_sig_bbcode_uid/si", '', $user_sig);
	}

	if ($bbcode_uid != '')
	{
		$message = ($board_config['allow_bbcode']) ? bbencode_second_pass($message, $bbcode_uid) : preg_replace("/\:$bbcode_uid/si", '', $message);
	}

	if ( $user_sig != '' && $board_config['sig_allow_url'] != 0 )
	{
		$user_sig = make_clickable($user_sig);
	}
	$message = make_clickable($message);

	//
	// Parse smilies
	//
	if ( $board_config['allow_smilies'] )
	{
		if ( $postrow[$i]['user_allowsmile'] && $user_sig != '' && $board_config['sig_allow_smilies'] != 0 )
		{
			$user_sig = smilies_pass($user_sig);
		}

		if ( $postrow[$i]['enable_smilies'] )
		{
			$message = smilies_pass($message);
		}
	}

	//
	// Highlight active words (primarily for search)
	//
	if ($highlight_match)
	{
		// This was shamelessly 'borrowed' from volker at multiartstudio dot de
		// via php.net's annotated manual
		$message = str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace('#\b(" . str_replace('\\', '\\\\', addslashes($highlight_match)) . ")\b#i', '<span style=\"color:#" . $theme['fontcolor3'] . "\"><b>\\\\1</b></span>', '\\0')", '>' . $message . '<'), 1, -1));
	}

	//
	// Replace naughty words
	//
	if (count($orig_word))
	{
		$post_subject = preg_replace($orig_word, $replacement_word, $post_subject);

		if ($user_sig != '')
		{
			$user_sig = str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $user_sig . '<'), 1, -1));
		}

		$message = str_replace('\"', '"', substr(@preg_replace('#(\>(((?>([^><]+|(?R)))*)\<))#se', "@preg_replace(\$orig_word, \$replacement_word, '\\0')", '>' . $message . '<'), 1, -1));
	}

	//
	// Replace newlines (we use this rather than nl2br because
	// till recently it wasn't XHTML compliant)
	//
	if ( $user_sig != '' && $userdata['user_allowsignature'] != 0 )
	{
		$user_sig = '<br />_________________<br />' . str_replace("\n", "\n<br />\n", $user_sig);
	} else $user_sig = '';

	$message = str_replace("\n", "\n<br />\n", $message);
	$message = ( $board_config['allow_colortext'] && $postrow[$i]['user_colortext'] != '' ) ? '<font color="' . $postrow[$i]['user_colortext'] . '">' . $message . '</font>' : $message;

	//
	// Editing information
	//
	if ( $postrow[$i]['post_edit_count'] )
	{
		$l_edit_time_total = ( $postrow[$i]['post_edit_count'] == 1 ) ? $lang['Edited_time_total'] : $lang['Edited_times_total'];

		$l_edited_by = '<br /><br />' . sprintf($l_edit_time_total, $poster, create_date($board_config['default_dateformat'], $postrow[$i]['post_edit_time'], $board_config['board_timezone']), $postrow[$i]['post_edit_count']);
	}
	else
	{
		$l_edited_by = '';
	}

	//
	// Again this will be handled by the templating
	// code at some point
	//
	$row_color = ( !($i % 2) ) ? $theme['td_color1'] : $theme['td_color2'];
	$row_class = ( !($i % 2) ) ? $theme['td_class1'] : $theme['td_class2'];

// 
// Begin Approve_Mod Block : 11
// 		
		if ( $approve_mod['enabled'] )
		{
			$approve_mod['poster_id'] = $postrow[$i]['poster_id'];
			$approve_mod['posts_awaiting'] = false;

			$approve_sql = "SELECT * FROM " . APPROVE_POSTS_TABLE . " 
				WHERE post_id = " . intval($postrow[$i]['post_id']) . " 
				LIMIT 0,1"; 
			if ( !($approve_result = $db->sql_query($approve_sql)) ) 
			{ 
				message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'], '', __LINE__, __FILE__, $approve_sql); 
			} 
			if ( $approve_row = $db->sql_fetchrow($approve_result) ) 
			{ 
				if ( intval($approve_row['post_id']) == intval($postrow[$i]['post_id']) )
				{
					$approve_mod['posts_awaiting'] = true;
				}  
			} 
			$approve_mod['moderators'] = array();
			$approve_mod['moderators'] = explode('|', $approve_mod['approve_moderators']);
			
			if ( in_array($userdata['user_id'], $approve_mod['moderators']) || $is_auth['auth_mod'] )
			{
				if ( !$approve_mod['approve_first_past'] )
				{
					$approve_mod['approve_first_past'] = true;
					$approve_sql = "SELECT * FROM " . APPROVE_POSTS_TABLE . " 
						WHERE topic_id = " . intval($topic_id) . " 
						LIMIT 0,2"; 
					if ( !($approve_result = $db->sql_query($approve_sql)) ) 
					{ 
						message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'], '', __LINE__, __FILE__, $approve_sql); 
					}
					if ( $db->sql_numrows($approve_result) > 1) 
					{ 
						$post_subject .= "<br /><a href=\"" . append_sid("viewtopic." . $phpEx . "?". POST_TOPIC_URL . "=" . $topic_id . "&app_c=" . $topic_id) . "\" class='copyright'>[ " . $lang['approve_topic_all_current'] . " ]</a>";
					}
					if ( $approve_mod['approve_users'] )
					{
						$approve_sql = "SELECT * FROM " . APPROVE_TOPICS_TABLE . " 
							WHERE topic_id = " . intval($topic_id) . " 
								AND approve_moderate = -1 
							LIMIT 0,1"; 
						if ( !($approve_result = $db->sql_query($approve_sql)) ) 
						{ 
							message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'], '', __LINE__, __FILE__, $approve_sql); 
						} 
						if ( $approve_row = $db->sql_fetchrow($approve_result) ) 
						{ 
							$post_subject .= "<br /><a href=\"" . append_sid("viewtopic." . $phpEx . "?". POST_TOPIC_URL . "=" . $topic_id . "&app_r=" . $topic_id) . "\" class='copyright'>[ " . $lang['approve_topic_all_future_rem'] . " ]</a>";
						}
						else
						{
							$post_subject .= "<br /><a href=\"" . append_sid("viewtopic." . $phpEx . "?". POST_TOPIC_URL . "=" . $topic_id . "&app_f=" . $topic_id) . "\" class='copyright'>[ " . $lang['approve_topic_all_future'] . " ]</a>";
						}
					}
					else if ( $approve_mod['approve_topics'] && $approve_mod['approve_posts'] )
					{
						$approve_sql = "SELECT * FROM " . APPROVE_TOPICS_TABLE . " 
							WHERE topic_id = " . intval($topic_id) . " 
								AND approve_moderate = 1";
						if ( !($approve_result = $db->sql_query($approve_sql)) ) 
						{ 
							message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'], '', __LINE__, __FILE__, $approve_sql); 
						} 
						if ( $approve_row = $db->sql_fetchrow($approve_result) ) 
						{
							$post_subject .= "<br /><a href=\"" . append_sid("viewtopic." . $phpEx . "?". POST_TOPIC_URL . "=" . $topic_id . "&app_tr=" . $topic_id) . "\" class='copyright'>[ " . $lang['approve_topic_moderate_rem'] . " ]</a>";
						}
						else 
						{
							$post_subject .= "<br /><a href=\"" . append_sid("viewtopic." . $phpEx . "?". POST_TOPIC_URL . "=" . $topic_id . "&app_ta=" . $topic_id) . "\" class='copyright'>[ " . $lang['approve_topic_moderate'] . " ]</a>";
						}
					}
				}
				if ( $approve_mod['approve_users'] && $approve_mod['poster_id'] != ANONYMOUS )
				{
					$approve_sql = "SELECT * FROM " . APPROVE_USERS_TABLE . " 
						WHERE user_id = " . intval($approve_mod['poster_id']) . " 
						LIMIT 0,1";
					if ( !($approve_result = $db->sql_query($approve_sql)) ) 
					{ 
						message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'], '', __LINE__, __FILE__, $approve_sql); 
					} 
					$approve_row = $db->sql_fetchrow($approve_result);
					if ( intval($approve_row['approve_moderate']) == -1 ) 
					{ 
						$poster_rank .= "<br /><a href=\"" .  append_sid("viewtopic." . $phpEx . "?". POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&app_ur=" . $poster_id) . "#" . $postrow[$i]['post_id'] . "\" class='copyright'>[ " . $lang['approve_user_auto_approve_rem'] . " ]</a>";
					}
					elseif ( intval($approve_row['approve_moderate']) == 1 ) 
					{
						$poster_rank .= "<br /><a href=\"" .  append_sid("viewtopic." . $phpEx . "?". POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&app_mr=" . $poster_id) . "#" . $postrow[$i]['post_id'] . "\" class='copyright'>[ " . $lang['approve_user_moderate_rem'] . " ]</a>";
					}
					else
					{
						
						//moderate all users, give option to auto approve this user
						$poster_rank .= "<br/ ><a href=\"" .  append_sid("viewtopic." . $phpEx . "?". POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&app_ua=" . $poster_id) . "#" . $postrow[$i]['post_id'] . "\" class='copyright'>[ " . $lang['approve_user_auto_approve'] . " ]</a>";
					}
				}
				elseif ( $approve_mod['poster_id'] != -1 )
				{
					$approve_sql = "SELECT * FROM " . APPROVE_USERS_TABLE . " 
						WHERE user_id = " . intval($approve_mod['poster_id']) . " 
						LIMIT 0,1"; 
					if ( !($approve_result = $db->sql_query($approve_sql)) ) 
					{ 
						message_die(GENERAL_ERROR, $lang['approve_posts_error_obtain'], '', __LINE__, __FILE__, $approve_sql); 
					} 
					$approve_row = $db->sql_fetchrow($approve_result);
					if ( intval($approve_row['approve_moderate']) == 1 ) 
					{ 
							$poster_rank .= "<br /><a href=\"" .  append_sid("viewtopic." . $phpEx . "?". POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&app_mr=" . $poster_id) . "#" . $postrow[$i]['post_id'] . "\" class='copyright'>[ " . $lang['approve_user_moderate_rem'] . " ]</a>";
					}
					elseif ( intval($approve_row['approve_moderate']) == -1 ) 
					{ 
							$poster_rank .= "<br /><a href=\"" .  append_sid("viewtopic." . $phpEx . "?". POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&app_ur=" . $poster_id) . "#" . $postrow[$i]['post_id'] . "\" class='copyright'>[ " . $lang['approve_user_auto_approve_rem'] . " ]</a>";
					}
					else
					{
						$poster_rank .= "<br /><a href=\"" .  append_sid("viewtopic." . $phpEx . "?". POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&app_ma=" . $poster_id) . "#" . $postrow[$i]['post_id'] . "\" class='copyright'>[ " . $lang['approve_user_moderate'] . " ]</a>";
					}
				}
				if ( $approve_mod['topics_admin_awaiting'] )
				{
					$post_subject .= "<br /><a href=\"" . append_sid("viewtopic." . $phpEx . "?". POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&app_p=" . $postrow[$i]['post_id']) . "#" . $postrow[$i]['post_id'] . "\" class='copyright'>[ " . $lang['approve_topic_approve'] . " ]</a>";
				}
				else 
				if ( $approve_mod['posts_awaiting'] )
				{
					$post_subject .= "<br /><a href=\"" . append_sid("viewtopic." . $phpEx . "?". POST_POST_URL . "=" . $postrow[$i]['post_id'] . "&app_p=" . $postrow[$i]['post_id']) . "#" . $postrow[$i]['post_id'] . "\" class='copyright'>[ " . $lang['approve_post_approve'] . " ]</a>";
				}
			}
			else
			{
				if ( $approve_mod['posts_awaiting'] )
				{
					if ( $approve_mod['forum_hide_unapproved_posts'] && !$approve_mod['topics_awaiting'] ) 
					{
						continue;
					}
					$post_subject = "[ " . $lang['approve_post_is_awaiting'] . " ]";
					$message = $post_subject;
					$quote_img = '';
					$quote = '';
					$poster = ($postrow[$i]['poster_id'] == ANONYMOUS) ? $lang['Guest'] : $poster;
				}
			}
		}
// 
// End Approve_Mod Block : 11
//

	$template->assign_block_vars('postrow', array(
		'ROW_COLOR' => '#' . $row_color,
		'ROW_CLASS' => $row_class,
		'POSTER_NAME' => $poster,
//-- mod : birthday ------------------------------------------------------------
//-- add
		'POSTER_AGE' => $poster_age,
		'POSTER_CAKE' => $poster_cake,
//-- fin mod : birthday --------------------------------------------------------
		'POSTER_RANK' => $poster_rank,
		'RANK_IMAGE' => $rank_image,
		'POSTER_JOINED' => $poster_joined,
		'POSTER_POSTS' => $poster_posts,
		'POSTER_FROM' => $poster_from,
		'POSTER_AVATAR' => $poster_avatar,
		'POST_DATE' => $post_date,
		'POST_SUBJECT' => $post_subject,
		'MESSAGE' => $message,
		'SIGNATURE' => $user_sig,
		'EDITED_MESSAGE' => $l_edited_by,

		'MINI_POST_IMG' => $mini_post_img,
		'PROFILE_IMG' => $profile_img,
		'PROFILE' => $profile,
		'SEARCH_IMG' => $search_img,
		'SEARCH' => $search,
		'PM_IMG' => $pm_img,
		'PM' => $pm,
		'EMAIL_IMG' => $email_img,
		'EMAIL' => $email,
		'WWW_IMG' => $www_img,
		'WWW' => $www,
		'ICQ_STATUS_IMG' => $icq_status_img,
		'ICQ_IMG' => $icq_img,
		'ICQ' => $icq,
		'AIM_IMG' => $aim_img,
		'AIM' => $aim,
		'MSN_IMG' => $msn_img,
		'MSN' => $msn,
		'YIM_IMG' => $yim_img,
		'YIM' => $yim,
		'EDIT_IMG' => $edit_img,
		'EDIT' => $edit,
		'QUOTE_IMG' => $quote_img,
		'QUOTE' => $quote,
		'IP_IMG' => $ip_img,
		'IP' => $ip,
		'DELETE_IMG' => $delpost_img,
		'DELETE' => $delpost,
//-- mod : quick post es -------------------------------------------------------
//-- add
		'I_QP_QUOTE' => $qp_quote_img,
//-- fin mod : quick post es ---------------------------------------------------
		'USER_WARNINGS' => $user_warnings,
		'CARD_IMG' => $card_img,
		'CARD_HIDDEN_FIELDS' => $card_hidden,
		'CARD_EXTRA_SPACE' => ($r_card_img || $y_card_img || $g_card_img || $b_card_img) ? ' ' : '',

		'L_MINI_POST_ALT' => $mini_post_alt,

		'U_MINI_POST' => $mini_post_url,
		'U_G_CARD' => $g_card_img, 
		'U_Y_CARD' => $y_card_img, 
		'U_R_CARD' => $r_card_img, 
		'U_B_CARD' => $b_card_img,
		'S_CARD' => append_sid("card.".$phpEx),
		'U_POST_ID' => $postrow[$i]['post_id'])
	);
	
//-- mod : split posts ---------------------------------------------------------
//-- add
	if ( $i != $total_posts - 1 )
	{
		$template->assign_block_vars('postrow.spacing', array());
	}
//-- fin mod : split posts -----------------------------------------------------

}


$template->pparse('body');

include($phpbb_root_path . 'includes/page_tail.'.$phpEx);

?>
