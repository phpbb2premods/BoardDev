#
# Basic DB data for phpBB2 devel
#
# $Id: mysql_basic.sql,v 1.29.2.25 2006/03/09 21:55:09 grahamje Exp $

# -- Config
INSERT INTO phpbb_config (config_name, config_value) VALUES ('config_id','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('board_disable','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sitename','votredomaine.com - yourdomain.com');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('site_desc','Description de votre forum - A little text to describe your forum');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('cookie_name','phpbb2mysql');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('cookie_path','/');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('cookie_domain','');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('cookie_secure','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('session_length','3600');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_html','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_html_tags','b,i,u,pre');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_bbcode','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_smilies','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_sig','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_namechange','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_theme_create','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_avatar_local','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_avatar_remote','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_avatar_upload','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('enable_confirm', '1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_autologin','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_autologin_time','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('override_user_style','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('posts_per_page','15');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('topics_per_page','50');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('hot_threshold','25');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_poll_options','10');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_sig_chars','255');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_inbox_privmsgs','50');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_sentbox_privmsgs','25');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_savebox_privmsgs','50');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('board_email_sig','Cordialement le Staff - Thanks, The Management');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('board_email','votreadresse-youraddress@votredomaine-yourdomain.com');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('smtp_delivery','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('smtp_host','');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('smtp_username','');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('smtp_password','');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sendmail_fix','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('require_activation','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('flood_interval','15');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('search_flood_interval','15');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('max_login_attempts', '5');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('login_reset_time', '30');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('board_email_form','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('avatar_filesize','6144');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('avatar_max_width','80');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('avatar_max_height','80');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('avatar_path','images/avatars');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('avatar_gallery_path','images/avatars/gallery');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('smilies_path','images/smiles');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('default_style','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('default_dateformat','D M d, Y g:i a');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('board_timezone','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('prune_enable','1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('privmsg_disable','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('gzip_compress','0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('coppa_fax', '');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('coppa_mail', '');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('record_online_users', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('record_online_date', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('server_name', 'www.myserver.tld');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('server_port', '80');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('script_path', '/phpBB2/');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('version', '.0.20');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('rand_seed', '0');


# -- Categories
INSERT INTO phpbb_categories (cat_id, cat_title, cat_order) VALUES (1, 'Catégorie de tests - Test category 1', 10);


# -- Forums
INSERT INTO phpbb_forums (forum_id, forum_name, forum_desc, cat_id, forum_order, forum_posts, forum_topics, forum_last_post_id, auth_view, auth_read, auth_post, auth_reply, auth_edit, auth_delete, auth_announce, auth_sticky, auth_pollcreate, auth_vote, auth_attachments) VALUES (1, 'Forum de tests 1 - Test Forum 1', 'Ceci est juste un forum de tests - This is just a test forum.', 1, 10, 1, 1, 1, 0, 0, 1, 1, 1, 1, 3, 3, 1, 1, 3);


# -- Users
INSERT INTO phpbb_users (user_id, username, user_level, user_regdate, user_password, user_email, user_icq, user_website, user_occ, user_from, user_interests, user_sig, user_viewemail, user_style, user_aim, user_yim, user_msnm, user_posts, user_attachsig, user_allowsmile, user_allowhtml, user_allowbbcode, user_allow_pm, user_notify_pm, user_allow_viewonline, user_rank, user_avatar, user_lang, user_timezone, user_dateformat, user_actkey, user_newpasswd, user_notify, user_active) VALUES ( -1, 'Anonymous', 0, 0, '', '', '', '', '', '', '', '', 0, NULL, '', '', '', 0, 0, 1, 1, 1, 0, 1, 1, NULL, '', '', 0, '', '', '', 0, 0);

# -- username: admin    password: admin (change this or remove it once everything is working!)
INSERT INTO phpbb_users (user_id, username, user_level, user_regdate, user_password, user_email, user_icq, user_website, user_occ, user_from, user_interests, user_sig, user_viewemail, user_style, user_aim, user_yim, user_msnm, user_posts, user_attachsig, user_allowsmile, user_allowhtml, user_allowbbcode, user_allow_pm, user_notify_pm, user_popup_pm, user_allow_viewonline, user_rank, user_avatar, user_lang, user_timezone, user_dateformat, user_actkey, user_newpasswd, user_notify, user_active) VALUES ( 2, 'Admin', 1, 0, '21232f297a57a5a743894a0e4a801fc3', 'admin@yourdomain.com', '', '', '', '', '', '', 1, 1, '', '', '', 1, 0, 1, 0, 1, 1, 1, 1, 1, 1, '', 'english', 0, 'd M Y h:i a', '', '', 0, 1);


# -- Ranks
INSERT INTO phpbb_ranks (rank_id, rank_title, rank_min, rank_special, rank_image) VALUES ( 1, 'Administrateur - Site Admin', -1, 1, NULL);


# -- Groups
INSERT INTO phpbb_groups (group_id, group_name, group_description, group_single_user) VALUES (1, 'Anonymous', 'Personal User', 1);
INSERT INTO phpbb_groups (group_id, group_name, group_description, group_single_user) VALUES (2, 'Admin', 'Personal User', 1);


# -- User -> Group
INSERT INTO phpbb_user_group (group_id, user_id, user_pending) VALUES (1, -1, 0);
INSERT INTO phpbb_user_group (group_id, user_id, user_pending) VALUES (2, 2, 0);


# -- Demo Topic
INSERT INTO phpbb_topics (topic_id, topic_title, topic_poster, topic_time, topic_views, topic_replies, forum_id, topic_status, topic_type, topic_vote, topic_first_post_id, topic_last_post_id) VALUES (1, 'Bienvenue sur phpBB 2 - Welcome to phpBB 2', 2, '972086460', 0, 0, 1, 0, 0, 0, 1, 1);


# -- Demo Post
INSERT INTO phpbb_posts (post_id, topic_id, forum_id, poster_id, post_time, post_username, poster_ip) VALUES (1, 1, 1, 2, 972086460, NULL, '7F000001');
INSERT INTO phpbb_posts_text (post_id, post_subject, post_text) VALUES (1, NULL, 'Ceci est un exemple de message (post) dû à votre installation de phpBB 2.  Vous pouvez supprimer ce message (post), ce sujet (topic) et même ce forum si vous le souhaitez, puisque tout semble fonctionner ! - This is an example post in your phpBB 2 installation. You may delete this post, this topic and even this forum if you like since everything seems to be working!');


# -- Themes
INSERT INTO phpbb_themes (themes_id, template_name, style_name, head_stylesheet, body_background, body_bgcolor, body_text, body_link, body_vlink, body_alink, body_hlink, tr_color1, tr_color2, tr_color3, tr_class1, tr_class2, tr_class3, th_color1, th_color2, th_color3, th_class1, th_class2, th_class3, td_color1, td_color2, td_color3, td_class1, td_class2, td_class3, fontface1, fontface2, fontface3, fontsize1, fontsize2, fontsize3, fontcolor1, fontcolor2, fontcolor3, span_class1, span_class2, span_class3) VALUES (1, 'subSilver', 'subSilver', 'subSilver.css', '', 'E5E5E5', '000000', '006699', '5493B4', '', 'DD6900', 'EFEFEF', 'DEE3E7', 'D1D7DC', '', '', '', '98AAB1', '006699', 'FFFFFF', 'cellpic1.gif', 'cellpic3.gif', 'cellpic2.jpg', 'FAFAFA', 'FFFFFF', '', 'row1', 'row2', '', 'Verdana, Arial, Helvetica, sans-serif', 'Trebuchet MS', 'Courier, \'Courier New\', sans-serif', 10, 11, 12, '444444', '006600', 'FFA34F', '', '', '');

INSERT INTO phpbb_themes_name (themes_id, tr_color1_name, tr_color2_name, tr_color3_name, tr_class1_name, tr_class2_name, tr_class3_name, th_color1_name, th_color2_name, th_color3_name, th_class1_name, th_class2_name, th_class3_name, td_color1_name, td_color2_name, td_color3_name, td_class1_name, td_class2_name, td_class3_name, fontface1_name, fontface2_name, fontface3_name, fontsize1_name, fontsize2_name, fontsize3_name, fontcolor1_name, fontcolor2_name, fontcolor3_name, span_class1_name, span_class2_name, span_class3_name) VALUES (1, 'Couleur de la rangée la plus claire - The lightest row colour', 'Couleur de la rangée moyenne - The medium row color', 'Couleur de la rangée la plus foncée - The darkest row colour', '', '', '', 'Bordure entourant la page toute entière - Border round the whole page', 'Bordure externe à la table - Outer table border', 'Bordure interne à la table - Inner table border', 'Image argentée dégradée - Silver gradient picture', 'Image bleutée dégradée - Blue gradient picture', 'Image fondue dégradée - Fade-out gradient on index', 'Arrière plan du cadre de citation - Background for quote boxes', 'Toutes les zones blanches - All white areas', '', 'Arrière plan des messages des sujets - Background for topic posts', '2nd arrière plan des messages des sujets - 2nd background for topic posts', '', 'Police principale - Main fonts', 'Police additionnelle des titres des sujets - Additional topic title font', 'Polices de forme - Form fonts', 'Plus petite taille de police - Smallest font size', 'Taille de police moyenne - Medium font size', 'Taille de police normale (corps du messages etc...) - Normal font size (post body etc)', 'Texte de citation et du copyright - Quote & copyright text', 'Couleur du texte de code - Code text colour', 'Couleur principale des textes des en-têtes de table - Main table header text colour', '', '', '');


# -- Smilies
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 1, ':D', 'icon_biggrin.gif', 'Very Happy');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 2, ':-D', 'icon_biggrin.gif', 'Very Happy');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 3, ':grin:', 'icon_biggrin.gif', 'Very Happy');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 4, ':)', 'icon_smile.gif', 'Smile');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 5, ':-)', 'icon_smile.gif', 'Smile');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 6, ':smile:', 'icon_smile.gif', 'Smile');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 7, ':(', 'icon_sad.gif', 'Sad');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 8, ':-(', 'icon_sad.gif', 'Sad');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 9, ':sad:', 'icon_sad.gif', 'Sad');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 10, ':o', 'icon_surprised.gif', 'Surprised');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 11, ':-o', 'icon_surprised.gif', 'Surprised');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 12, ':eek:', 'icon_surprised.gif', 'Surprised');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 13, ':shock:', 'icon_eek.gif', 'Shocked');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 14, ':?', 'icon_confused.gif', 'Confused');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 15, ':-?', 'icon_confused.gif', 'Confused');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 16, ':???:', 'icon_confused.gif', 'Confused');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 17, '8)', 'icon_cool.gif', 'Cool');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 18, '8-)', 'icon_cool.gif', 'Cool');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 19, ':cool:', 'icon_cool.gif', 'Cool');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 20, ':lol:', 'icon_lol.gif', 'Laughing');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 21, ':x', 'icon_mad.gif', 'Mad');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 22, ':-x', 'icon_mad.gif', 'Mad');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 23, ':mad:', 'icon_mad.gif', 'Mad');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 24, ':P', 'icon_razz.gif', 'Razz');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 25, ':-P', 'icon_razz.gif', 'Razz');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 26, ':razz:', 'icon_razz.gif', 'Razz');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 27, ':oops:', 'icon_redface.gif', 'Embarassed');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 28, ':cry:', 'icon_cry.gif', 'Crying or Very sad');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 29, ':evil:', 'icon_evil.gif', 'Evil or Very Mad');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 30, ':twisted:', 'icon_twisted.gif', 'Twisted Evil');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 31, ':roll:', 'icon_rolleyes.gif', 'Rolling Eyes');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 32, ':wink:', 'icon_wink.gif', 'Wink');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 33, ';)', 'icon_wink.gif', 'Wink');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 34, ';-)', 'icon_wink.gif', 'Wink');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 35, ':!:', 'icon_exclaim.gif', 'Exclamation');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 36, ':?:', 'icon_question.gif', 'Question');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 37, ':idea:', 'icon_idea.gif', 'Idea');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 38, ':arrow:', 'icon_arrow.gif', 'Arrow');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 39, ':|', 'icon_neutral.gif', 'Neutral');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 40, ':-|', 'icon_neutral.gif', 'Neutral');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 41, ':neutral:', 'icon_neutral.gif', 'Neutral');
INSERT INTO phpbb_smilies (smilies_id, code, smile_url, emoticon) VALUES ( 42, ':mrgreen:', 'icon_mrgreen.gif', 'Mr. Green');


# -- wordlist
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 1, 'example', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 2, 'post', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 3, 'phpbb', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 4, 'installation', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 5, 'delete', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 6, 'topic', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 7, 'forum', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 8, 'since', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 9, 'everything', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 10, 'seems', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 11, 'working', 0 );
INSERT INTO phpbb_search_wordlist (word_id, word_text, word_common) VALUES ( 12, 'welcome', 0 );


# -- wordmatch
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 1, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 2, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 3, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 4, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 5, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 6, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 7, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 8, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 9, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 10, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 11, 1, 0 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 12, 1, 1 );
INSERT INTO phpbb_search_wordmatch (word_id, post_id, title_match) VALUES ( 3, 1, 1 );



# -- Modifs Board-Dev

# -- colortext
INSERT INTO phpbb_config (config_name, config_value) VALUES ('allow_colortext', '1');
ALTER TABLE phpbb_users ADD user_colortext VARCHAR(10);

# -- Global Announcement Mod
ALTER TABLE phpbb_forums ADD auth_globalannounce TINYINT (2) DEFAULT "3" NOT NULL AFTER auth_announce;
ALTER TABLE phpbb_auth_access ADD auth_globalannounce TINYINT (1) not null AFTER auth_announce;

# -- bbc box config
INSERT INTO phpbb_bbc_box VALUES (1, 'strike', '1', '0', 's', 's', 'strike', 'strike', '0', '10');
INSERT INTO phpbb_bbc_box VALUES (2, 'spoiler', '1', '0', 'spoil', 'spoil', 'spoiler', 'spoiler', '0', '20');
INSERT INTO phpbb_bbc_box VALUES (3, 'fade', '1', '0', 'fade', 'fade', 'fade', 'fade', '0', '30');
INSERT INTO phpbb_bbc_box VALUES (4, 'rainbow', '1', '0', 'rainbow', 'rainbow', 'rainbow', 'rainbow', '1', '40');
INSERT INTO phpbb_bbc_box VALUES (5, 'justify', '1', '0', 'align=justify', 'align', 'justify', 'justify', '0', '50');
INSERT INTO phpbb_bbc_box VALUES (6, 'right', '1', '0', 'align=right', 'align', 'right', 'right', '0', '60');
INSERT INTO phpbb_bbc_box VALUES (7, 'center', '1', '0', 'align=center', 'align', 'center', 'center', '0', '70');
INSERT INTO phpbb_bbc_box VALUES (8, 'left', '1', '0', 'align=left', 'align', 'left', 'left', '1', '80');
INSERT INTO phpbb_bbc_box VALUES (9, 'link', '1', '0', 'link=', 'link', 'link', 'alink', '0', '90');
INSERT INTO phpbb_bbc_box VALUES (10, 'target', '1', '0', 'target=', 'target', 'target', 'atarget', '1', '100');
INSERT INTO phpbb_bbc_box VALUES (11, 'marqd', '1', '0', 'marq=down', 'marq', 'marqd', 'marqd', '0', '110');
INSERT INTO phpbb_bbc_box VALUES (12, 'marqu', '1', '0', 'marq=up', 'marq', 'marqu', 'marqu', '0', '120');
INSERT INTO phpbb_bbc_box VALUES (13, 'marql', '1', '0', 'marq=left', 'marq', 'marql', 'marql', '0', '130');
INSERT INTO phpbb_bbc_box VALUES (14, 'marqr', '1', '0', 'marq=right', 'marq', 'marqr', 'marqr', '1', '140');
INSERT INTO phpbb_bbc_box VALUES (15, 'email', '1', '0', 'email', 'email', 'email', 'email', '0', '150');
INSERT INTO phpbb_bbc_box VALUES (16, 'flash', '1', '0', 'flash width=250 height=250', 'flash', 'flash', 'flash', '0', '160');
INSERT INTO phpbb_bbc_box VALUES (17, 'video', '1', '0', 'video width=400 height=350', 'video', 'video', 'video', '0', '170');
INSERT INTO phpbb_bbc_box VALUES (18, 'stream', '1', '0', 'stream', 'stream', 'stream', 'stream', '0', '180');
INSERT INTO phpbb_bbc_box VALUES (19, 'real', '1', '0', 'ram width=220 height=140', 'ram', 'real', 'real', '0', '190');
INSERT INTO phpbb_bbc_box VALUES (20, 'quick', '1', '0', 'quick width=480 height=224', 'quick', 'quick', 'quick', '1', '200');
INSERT INTO phpbb_bbc_box VALUES (21, 'sup', '1', '0', 'sup', 'sup', 'sup', 'sup', '0', '210');
INSERT INTO phpbb_bbc_box VALUES (22, 'sub', '1', '0', 'sub', 'sub', 'sub', 'sub', '1', '220');

# -- config with bbc box
INSERT INTO phpbb_config (config_name, config_value) VALUES ('bbc_box_on', '1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('bbc_advanced', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('bbc_per_row', '14');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('bbc_time_regen', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('bbc_style_path', 'default');

# -- birthday event
ALTER TABLE phpbb_users ADD user_birthday VARCHAR(10) DEFAULT '' NOT NULL;
INSERT INTO phpbb_config (config_name, config_value) VALUES ('birthday_settings', '0-1-5-100');

# -- quick post
INSERT INTO phpbb_config (config_name, config_value) VALUES ('users_qp_settings', '1-0-1-1-1-1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('anons_qp_settings', '1-0-1-1-1-1');
ALTER TABLE phpbb_forums ADD forum_qpes tinyint(1) DEFAULT '1' NOT NULL; 
ALTER TABLE phpbb_users ADD user_qp_settings varchar(25) DEFAULT '0' NOT NULL; 
UPDATE phpbb_users SET user_qp_settings = '1-0-1-1-1-1' WHERE user_qp_settings = '0';

# -- today userlist
INSERT INTO phpbb_config (config_name, config_value) VALUES ('today_day_selected', '0');

# -- optimize database
INSERT INTO phpbb_optimize_db VALUES ('0', 86400, 0, 0, '1', '', '0');

# -- pseudo subforum
ALTER TABLE phpbb_forums ADD attached_forum_id MEDIUMINT(8) DEFAULT '-1' NOT NULL;
ALTER TABLE phpbb_topics ADD INDEX topic_last_post_id(topic_last_post_id);

# -- mod boulet
ALTER TABLE phpbb_users ADD user_boulet VARCHAR(255) DEFAULT '0|' NOT NULL;

# -- signatures control
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_max_lines', '5');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_wordwrap', '100');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_allow_font_sizes', '1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_min_font_size', '7');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_max_font_size', '12');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_allow_bold', '1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_allow_italic', '1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_allow_underline', '1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_allow_colors', '1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_allow_quote', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_allow_code', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_allow_list', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_allow_url', '1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_allow_images', '1');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_max_images', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_max_img_height', '75');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_max_img_width', '500');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_allow_on_max_img_size_fail', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_max_img_files_size', '10');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_max_img_av_files_size', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_exotic_bbcodes_disallowed', '');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('sig_allow_smilies', '1');
ALTER TABLE phpbb_users ADD user_allowsignature TINYINT not null DEFAULT '1';

# -- approve mod
INSERT INTO phpbb_approve_users ( user_id, approve_moderate ) VALUES (-1, 1);

# -- yellow card
ALTER TABLE phpbb_forums ADD auth_ban TINYINT (2) not null DEFAULT "3";
ALTER TABLE phpbb_forums ADD auth_greencard TINYINT (2) not null DEFAULT "5";
ALTER TABLE phpbb_forums ADD auth_bluecard TINYINT (2) not null DEFAULT "1";
ALTER TABLE phpbb_auth_access ADD auth_ban TINYINT (1) not null DEFAULT "0";
ALTER TABLE phpbb_auth_access ADD auth_greencard TINYINT (1) not null DEFAULT "0";
ALTER TABLE phpbb_auth_access ADD auth_bluecard TINYINT (1) not null DEFAULT "0";
INSERT INTO phpbb_config (config_name, config_value) VALUES ("bluecard_limit", "3");
INSERT INTO phpbb_config (config_name, config_value) VALUES ("bluecard_limit_2", "1");
INSERT INTO phpbb_config (config_name, config_value) VALUES ("max_user_bancard", "10");
INSERT INTO phpbb_config (config_name, config_value) VALUES ("report_forum", "0");
ALTER TABLE phpbb_users ADD user_warnings SMALLINT (5) DEFAULT "0";
ALTER TABLE phpbb_posts ADD post_bluecard TINYINT (1);

# -- CrackerTracker
INSERT INTO phpbb_ctrack (name, value) VALUES ('lastreg', '0');
INSERT INTO phpbb_ctrack (name, value) VALUES ('version', '4.1.2');
INSERT INTO phpbb_ctrack (name, value) VALUES ('footer', '3');
INSERT INTO phpbb_ctrack (name, value) VALUES ('floodlog', '100');
INSERT INTO phpbb_ctrack (name, value) VALUES ('proxylog', '100');
INSERT INTO phpbb_ctrack (name, value) VALUES ('filter', '1');
INSERT INTO phpbb_ctrack (name, value) VALUES ('floodprot', '1');
INSERT INTO phpbb_ctrack (name, value) VALUES ('maxsearch', '4');
INSERT INTO phpbb_ctrack (name, value) VALUES ('searchtime', '16');
INSERT INTO phpbb_ctrack (name, value) VALUES ('regblock', '1');
INSERT INTO phpbb_ctrack (name, value) VALUES ('regtime', '1');
INSERT INTO phpbb_ctrack (name, value) VALUES ('autoban', '1');
INSERT INTO phpbb_ctrack (name, value) VALUES ('posttimespan', '200');
INSERT INTO phpbb_ctrack (name, value) VALUES ('postintime', '10');
INSERT INTO phpbb_ctrack (name, value) VALUES ('lastreg_ip', '000.000.000.000');
INSERT INTO phpbb_ctrack (name, value) VALUES ('mailfeature', '1');
INSERT INTO phpbb_ctrack (name, value) VALUES ('pwreset', '1');
INSERT INTO phpbb_ctrack (name, value) VALUES ('loginfeature', '1');

ALTER TABLE phpbb_users ADD ct_searchtime INT( 10 ) NOT NULL AFTER user_newpasswd;
ALTER TABLE phpbb_users ADD ct_searchcount INT( 10 ) NOT NULL AFTER user_newpasswd;
ALTER TABLE phpbb_users ADD ct_posttime INT( 10 ) NOT NULL AFTER user_newpasswd;
ALTER TABLE phpbb_users ADD ct_postcount INT( 10 ) NOT NULL AFTER user_newpasswd;
ALTER TABLE phpbb_users ADD ct_mailcount INT( 10 ) NOT NULL AFTER user_newpasswd;
ALTER TABLE phpbb_users ADD ct_pwreset INT( 2 ) NOT NULL AFTER user_newpasswd;
ALTER TABLE phpbb_users ADD ct_unsucclogin INT( 10 ) DEFAULT NULL AFTER user_newpasswd;
ALTER TABLE phpbb_users ADD ct_logintry INT( 2 ) DEFAULT 0 AFTER user_newpasswd;

INSERT INTO `phpbb_ct_filter` (`id`, `list`) VALUES (1, 'WebStripper'),
			   (2, 'NetMechanic'),
			   (3, 'CherryPicker'),
			   (4, 'EmailCollector'),
			   (5, 'EmailSiphon'),
			   (6, 'WebBandit'),
			   (7, 'EmailWolf'),
			   (8, 'ExtractorPro'),
			   (9, 'SiteSnagger'),
			   (10, 'CheeseBot'),
			   (11, 'ia_archiver/1.6'),
			   (12, 'Website Quester'),
			   (13, 'WebZip'),
			   (14, 'moget/2.1'),
			   (15, 'WebSauger'),
			   (16, 'WebCopier'),
			   (17, 'WWW-Collector-E'),
			   (18, 'InfoNaviRobot'),
			   (19, 'Harvest/1.5'),
			   (20, 'Bullseye/1.0'),
			   (21, 'lwp-trivial/1.34'),
			   (22, 'lwp-trivial'),
			   (23, 'LinkWalker'),
			   (24, 'LinkextractorPro'),
			   (25, 'Offline Explorer'),
			   (26, 'BlowFish/1.0'),
			   (27, 'WebEnhancer'),
			   (28, 'TightTwatBot'),
			   (29, 'LinkScan/8.1a Unix'),
			   (30, 'WebDownloader'),
			   (31, 'lwp-trivial/1.33'),
			   (32, 'lwp-trivial/1.38'),
			   (33, 'BruteForce'),
               (34, 'lwp');
               
# -- Approve avatar
INSERT INTO phpbb_config VALUES ('disable_avatar_approve', 0);
INSERT INTO phpbb_config VALUES ('avatar_posts', 0);

# -- Advanced Version Check
INSERT INTO phpbb_version_check VALUES(1,'phpBB','','','','www.phpbb.com','20x.txt','updatecheck','http://www.phpbb.com','','','',1,0,'');
INSERT INTO phpbb_version_config VALUES('check_time','86400');
INSERT INTO phpbb_version_config VALUES('background_check',1);
INSERT INTO phpbb_version_config VALUES('show_admin_index',1);
INSERT INTO phpbb_version_config VALUES('update_email',0);
INSERT INTO phpbb_version_config VALUES('email_address','');
INSERT INTO phpbb_version_config VALUES('update_pm',0);
INSERT INTO phpbb_version_config VALUES('pm_id','');
INSERT INTO phpbb_version_config VALUES('update_post',0);
INSERT INTO phpbb_version_config VALUES('post_forum','');
INSERT INTO phpbb_version_config VALUES('post_contents','');

# -- Desactivation des inscriptions
INSERT INTO phpbb_config (config_name, config_value) VALUES ('registration_disable', '0');
INSERT INTO phpbb_config (config_name, config_value) VALUES ('lang_registration_disable', 'Inscriptions désactivées');
			   
## --- Version board-dev ++

INSERT INTO `phpbb_config` ( `config_name` , `config_value` )
VALUES (
'premod_version', '1.1.1b'
);

# -- multiple_pseudos_hunter_1.0.1em
ALTER TABLE phpbb_users ADD user_regip char(8) NOT NULL;
UPDATE phpbb_posts SET poster_ip=LOWER(poster_ip) WHERE post_id=1;