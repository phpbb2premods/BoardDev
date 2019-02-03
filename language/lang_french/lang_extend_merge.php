<?php
/***************************************************************************
 *						lang_extend_merge.php [French]
 *						------------------------------
 *	begin				: 28/09/2003
 *	copyright			: Ptirhiik
 *	email				: ptirhiik@clanmckeen.com
 *
 *	version				: 1.0.1 - 21/10/2003
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

if ( !defined('IN_PHPBB') )
{
	die("Hacking attempt");
}

// admin part
if ( $lang_extend_admin )
{
	$lang['Lang_extend_merge'] = 'Simply Merge Threads';
}

$lang['Refresh'] = 'Actualiser';
$lang['Merge_topics'] = 'Fusionner les sujets';
$lang['Merge_title'] = 'Nouveau titre du sujet';
$lang['Merge_title_explain'] = 'Saisissez ici le nouveau titre du sujet, ou laissez cette zone vide si vous voulez que le syst�me utilise le titre du topic de destination.';
$lang['Merge_topic_from'] = 'Sujet � fusionner';
$lang['Merge_topic_from_explain'] = 'Ce sujet sera fusionn�. Vous pouvez saisir le n� de sujet, le lien vers le sujet ou le lien vers un post de ce sujet.';
$lang['Merge_topic_to'] = 'Sujet d\'accueil';
$lang['Merge_topic_to_explain'] = 'Ce sujet recevra tous les messages du sujet fusionn�. Vous pouvez saisir le n� de sujet, le lien vers le sujet ou le lien vers un post de ce sujet.';
$lang['Merge_from_not_found'] = 'Le sujet � fusionner n\'a pas �t� trouv�';
$lang['Merge_to_not_found'] = 'Le sujet d\'accueil n\'a pas �t� trouv�';
$lang['Merge_topics_equals'] = 'Vous ne pouvez pas fusionner un sujet avec lui-m�me';
$lang['Merge_from_not_authorized'] = 'Vous n\'�tes pas autoris� � mod�rer le forum du sujet � fusionner';
$lang['Merge_to_not_authorized'] =  'Vous n\'�tes pas autoris� � mod�rer le forum du sujet d\'accueil';
$lang['Merge_poll_from'] = 'Il y a un sondage sur le sujet � fusionner. Il sera copier sur le sujet d\'accueil';
$lang['Merge_poll_from_and_to'] = 'Le sujet d\'accueil a d�j� un sondage. Le sondage du sujet � fusionner sera donc supprim�';
$lang['Merge_confirm_process'] = 'Etes-vous s�r de vouloir fusionner<br />"<b>%s</b>"<br />avec<br />"<b>%s</b>"';
$lang['Merge_topic_done'] = 'Les sujets ont �t� fusionn�s avec succ�s.';

?>