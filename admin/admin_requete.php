<?php
/***************************************************************************
 *                              admin/admin_requete.php
 *                            -------------------
 *   begin                : 6 juin 2004
 *   Fait par             : Dark_Génova
 *   email                : genovakiller@yahoo.fr
 *
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

if( !empty($setmodules) )
{
        $file = basename(__FILE__);
        $module['General']['Requète SQL'] = "$file";
        return;
}

//
// Let's set the root dir for phpBB
$phpbb_root_path = "./../";
require($phpbb_root_path . 'extension.inc');
require('./pagestart.' . $phpEx);

//
// Start session management
//
$userdata = session_pagestart($user_ip, PAGE_INDEX);
init_userprefs($userdata);

//
// On récupère et on sécurise des variables globales
//
$requete = ( isset($HTTP_POST_VARS['requete']) ) ? stripslashes(htmlSpecialChars(trim($HTTP_POST_VARS['requete']))) : stripslashes(htmlSpecialChars(trim($HTTP_GET_VARS['requete'])));
$texte_complet = ( $HTTP_GET_VARS['texte'] == 'long' ) ? TRUE : FALSE;

//
// Si le formulaire est soumis
$simple_sql = FALSE;
if ( $requete != '' )
{
	if ( $requete == '' )
	{
		message_die(GENERAL_ERROR, 'Veuillez entrer une requète avant de soumettre');
	}
	else
	{
		// On remplace le préfixe "phpbb_" par celui du forum
		$requete = preg_replace('/phpbb_/', $table_prefix, $requete, 1);

		// Si la requète contient des ; on la split en plusieurs sous requètes, autrement on l'éxécute
		if (substr_count($requete, ';') > 1)
		{
			$req_array = explode(';', $requete);
			for ( $i = 0; $i < (count($req_array)-1); $i++ )
			{
				if ( ! $result = $db->sql_query($req_array[$i]) )
				{
					message_die(GENERAL_ERROR, 'Votre requète SQL n\'a pu être éxécutée', '', __LINE__, __FILE__, $req_array[$i]);
				}
			}
		}
		else
		{
			if ( ! $result = $db->sql_query($requete) )
			{
				message_die(GENERAL_ERROR, 'Votre requète SQL n\'a pu être éxécutée', '', __LINE__, __FILE__, $requete);
			}
			$simple_sql = TRUE;
		}

		$template->assign_block_vars('exe', array());
	}
}

$template->set_filenames(array("body" => "admin/requete_body.tpl"));

$template->assign_vars( array(

	'L_REQ_TITLE' => 		$lang['req_title'],
	'L_REQ_EXPLAIN' =>	$lang['req_explain'],
	'L_REQ_PREFIX' =>		$lang['req_prefix'],
	'L_REQ_BDD' =>		$lang['req_bdd'],
	'L_REQ_ENTER' =>		$lang['req_enter'],
	'L_REQ_EXE' =>		$lang['req_executee'],
	'L_SUBMIT' =>		$lang['req_submit'],

	'REQ_ACTION' =>		append_sid('admin_requete.' . $phpEx),
	'REQ_PREFIX' =>		$table_prefix,
	'REQ_BDD' =>		$dbname,
	'REQ_ENTER' =>		$requete,
	'REQ_EXE' =>		nl2br($requete)

));

if ( $simple_sql && preg_match('/^select /si', $requete) )
{
	$template->assign_block_vars('req', array());

	$j = 0;
	$req = array();
	while ( $req[] = $db->sql_fetchrow($result) );

	$start = ( isset($HTTP_POST_VARS['start']) ) ? intval($HTTP_POST_VARS['start']) : intval($HTTP_GET_VARS['start']);
	$periode = 30;

	if ( $count_req = count($req) )
	{
		$pagination = generate_pagination('admin_requete.' . $phpEx . '?requete=' . urlencode($requete) . '&texte=' . ( $texte_complet ? 'long' : 'court' ), $count_req, $periode, $start);

		for ( $i = 0; $i < $count_req; $i++ )
		{
			if ( $i == 0 )
			{
				$template->assign_block_vars('req.l', array());
				while ( list($k, $v) = @each($req[$i]) )
				{
					if ( ! is_int($k) )
					{
						$template->assign_block_vars('req.l.c', array(
							'CLASSE' =>	'row2',
							'VALEUR' =>	'<b>' . $k . '</b>'
						));
						$j++;
					}
				}
				reset($req[$i]);
			}

			if ( $i >= $start && $i < ( $start + $periode ) )
			{
				$template->assign_block_vars('req.l', array());
				while ( list($k, $v) = @each($req[$i]) )
				{
					if ( ! is_int($k) )
					{
						$v = ( strlen($v) > 200 && ! $texte_complet) ? substr($v, 0, 80) . ' (...)' : $v;
						$template->assign_block_vars('req.l.c', array(
							'CLASSE' =>	'row1',
							'VALEUR' =>	( $v == '' ) ? '&nbsp;' : nl2br($v)
						));
					}
				}
			}
		}
	}
	else
	{
		$template->assign_block_vars('req.none', array());
	}

	$template->assign_vars( array(
		'L_OPTION' =>		$lang['req_option'],
		'L_NONE' =>			$lang['req_aucun'],
		'L_RESULT' =>		$lang['req_resultat'],

		'TYPE_TEXTE' =>		( $texte_complet ) ? '<a href="' . append_sid('admin_requete.' . $phpEx . '?texte=court&requete=' . urlencode($requete) . '&page=' . $page) . '">' . $lang['req_texte_court'] . '</a>' : '<a href="' . append_sid('admin_requete.' . $phpEx . '?texte=long&requete=' . urlencode($requete) . '&page=' . $page) . '">' . $lang['req_texte_long'] . '</a>',
		'COLSPAN' =>		$j,
		'PAGINATION' =>		$pagination
	));
}

$template->pparse('body');

include('./page_footer_admin.'.$phpEx);

?>