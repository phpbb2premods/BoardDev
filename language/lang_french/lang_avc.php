<?php
/***************************************************************************
 *                              lang_avc.php
 *                            -------------------
 *   begin                : May 17, 2005
 *   author               : Fountain of Apples < webmacster87@gmail.com >
 *   copyright            : (C) 2005-2006 Douglas Bell
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

/* All lang tags for Advanced Version Check in here. */

//
// Format is same as lang_main
//

//
// Version Check Summary & Admin Index Summary
//
$lang['Version_check_explain'] = 'En Utilisant cette fonctionnalit�, vous pouvez voir si les divers MODs que vous avez install� sont � jour ou non. S\'il ne sont pas � jour alors le num�ro de version dans la derni�re colonne sera rouge, et une mise � jour conseill�e. Vous pouvez activer/d�sactiver les v�rifications individuelles par le panneau Activer/D�sactiver les v�rifications, et d�finir les options de configuration pour le syst�me de v�rification de version dans le panneau de configuration de v�rification de version. Notez que les informations peuvent ne pas �tre � jour, cliquez sur "V�rifier maintenant" pour mettre � jour les informations.';
$lang['Version_check'] = 'V�rification de version';
$lang['Version_up_to_date_short'] = 'Version � jour';
$lang['Version_not_up_to_date_short'] = 'VERSION NON A JOUR !';
$lang['Error_connect_socket'] = '<b>Erreur:</b><br />%s'; // %s displays the error information, this is set by default in any page that uses the Version Check
$lang['No_socket_functions'] = '<b>Erreur:</b><br />Les fonctions sockets ne peuvent pas �tre utilis�es.';
$lang['MOD_Name'] = 'Nom du mod';
$lang['Download'] = 'T�l�charger';
$lang['Re-check'] = 'Rev�rifier';
$lang['Latest_version'] = 'Derni�re version';
$lang['Current_version'] = 'Version actuelle';
$lang['MOD_Status'] = 'Statut du mod';
$lang['Index_summary_explain'] = 'Voici une liste des MODs qui ne sont pas � jour. Vous pouveez voir les d�tails complets sur la page %sV�rification de version%s. Cette liste peut �tre d�sactiv�e par la page de configuration de la v�rification de versione.'; // <a href>, </a> tags
$lang['MODs_uptodate'] = 'Tous les MODs sont � jour.';
$lang['Last_Updated'] = 'Derni�re mise � jour : '; // This is used for the Last Updated: timestamp in the Version Check Summary. The timestamp will immediately follow this.
$lang['Result_new_vers_available'] = 'Une nouvelle version de ce MOD est disponible !';
$lang['Result_have_latest'] = 'Vous avez la derni�re version de ce MOD disponible.';
$lang['Error_occured'] = 'Une erreur est arriv�e !';
$lang['Secondary_version_msg'] = '<i><b>Note:</b> une %s version de ce MOD, version %s, est disponible. V�rifiez la page web du MOD pour plus d\'infos.</i>'; // MOD development status, version number
$lang['stable'] = 'stable';
$lang['development'] = 'd�veloppement';
$lang['Not_specified'] = 'Non sp�cifi�';
$lang['More_info'] = 'plus d\'infos';

//
// Checker Management page
//
$lang['Version_Manager'] = 'Activer/D�sactiver les v�rifications';
$lang['Version_Manager_explain'] = 'Ici vous pouvez activer/d�sactiver les v�rifications individuelles des v�rifications de version. Si elle est d�sactiv�e, la v�rification ne sera pas activ�e sur la page "V�rification de version" ou sur la page d\'index de l\'administration.';
$lang['Check_disable'] = 'V�rification d�sactiv�e';
$lang['Check_enable'] = 'V�rification activ�e';
$lang['Run_check'] = 'Lancer la v�rification';
$lang['Run_all_checks'] = 'Lancer toutes les v�rifications';

//
// Information Boxes
//
$lang['Click_return_versionmanage'] = 'Cliquez %sIci%s pour retourner � la page Activation/D�sactivation';
$lang['Click_return_version'] = 'Cliquez %sIci%s pour retourner � la page de v�rification de version';
$lang['Version_updated'] = 'Informations de v�rification de version mises � jour avec succ�s !';
$lang['No_Config_Info'] = 'Les informations de configuration ne peuvent pas �tre obtenues';
$lang['Update_failed'] = 'Echec lors de la tentative de mise � jour de la configuration g�n�rale pour %s'; // Name of configuration option
$lang['Status_no_Update'] = 'Le statut ne peut pas �tre mis � jour';
$lang['No_Version'] = 'Les informations de v�rification de version dans la table de donn�es ne peuvent pas �tre obtenues';
$lang['Connection_error'] = 'Erreur de connexion';
$lang['No_socket'] = 'Fonctions sockets d�sactiv�es';
/*****/
$lang['No_Version_Config'] = 'Les informations de configuration de la v�rification de version dans la base de donn�es ne peuvent pas �tre s�lectionn�es.';
$lang['Cant_update_config'] = 'La configuration de v�rification de version pour %s ne peut pas �tre mise � jour';// $config_name
$lang['Version_config_updated'] = 'La configuration de v�rification de version a �t� mise � jour avec succ�s.';
$lang['No_Version_Log'] = 'Le fichier-log d\'informations ne peut pas �tre s�lectionn� dans la base de donn�es.';
$lang['No_Update_Log'] = 'Le fichier-log de v�rification de version ne peut pas �tre mis � jour.';
$lang['No_Delete_Log'] = 'Le fichier-log %s ne peut pas �tre supprim� de la base de donn�es.'; // $log_id
$lang['No_MOD_Status'] = 'Le statut du MOD ne peut pas �tre s�lectionn� dans la base de donn�es.';
$lang['No_MOD_Status_update'] = 'Le statut du MOD %s ne peut pas �tre mis � jour.';
$lang['No_Version_Data'] = 'Les informations de v�rification de version ne peuvent pas �tre s�lectionn�es dans la base de donn�es.';
$lang['No_add_phpBB_version'] = 'la version phpBB ne peut pas �tre ajout�e � la table de v�rification de version.';
$lang['No_new_MOD'] = 'Un/le nouveau mod ne peut pas �tre ajout� � la table de v�rification de version.';
$lang['No_update_MOD'] = 'les statistiques du MOD ne peuvent pas �tre mises � jours dans la table de v�rification de version.';
$lang['No_delete_MOD'] = 'les informations non-utilis�es du MOD ne peuvent pas �tre supprim�es de la table de v�rification de version.';
$lang['No_error_msg'] = 'Le message d\'erreur ne paut pas �tre envoy� � la base de donn�es. Le message d\'erreur original �tait: %s'; // $error_msg
$lang['No_error_msg_MODID'] = 'ID du MOD: %s'; // $mod_id
$lang['No_admin_addresses'] = 'Les adresses e-mail des administateurs ne peuvent �tre r�cup�rer dans la base de donn�es pour envoyer le mail AVC.';
$lang['No_PM_info'] = 'Les informations de MP ne peuvent pas �tre obtenues pour envoyer le mail AVC.';
$lang['No_post_info'] = 'les informations ne peuvent pas �tre obtenues pour l\'insertion du message AVC.';
$lang['No_update_version_table'] = 'la table de v�rification avec les infos sur %s ne peut pas �tre mise � jour'; // MOD Name
$lang['No_checker_ID'] = 'Aucune ID de v�rification n\'a �t� sp�cifi�e.';
$lang['No_find_oldest_privmsgs'] = 'Les plus anciens MP ne peuvent pas �tre trouv�s (Messages re�us)';
$lang['No_delete_oldest_privmsgs'] = 'Les plus anciens MP ne peuvent pas �tre supprim�s (Messages re�us)';
$lang['No_delete_oldest_privmsgs_text'] = 'Les plus anciens textes de MP ne peuvent pas �tre supprim�s (Messages re�us)';
$lang['No_PM_sent_info'] = 'Les infos de MP envoy� ne peuvent pas �tre ins�r�es/mises � jour.';
$lang['No_PM_sent_text'] = 'Le texte de MP envoy� ne peut pas �tre ins�r�/mis � jour.';
$lang['No_PM_read_status'] = 'Le statut de l\'utilisateur pour les nouveaux messages ou lus ne peut pas �tre mis � jour';
$lang['No_AVC_post'] = 'Le message AVC ne peut pas �tre ins�r�.';
$lang['No_retrievable'] = 'Le fichier accessible ne peut pas �tre ouvert.';
$lang['No_retrievable_socket'] = 'Les fonctions sockets sont d�sactiv�es. Le fichier accessible ne peut pas �tre ouvert.';
$lang['Invalid_retrievable'] = 'Le fichier accessible n\'utilise pas une extension valide.';

//
// Version Check Configuration page
//
$lang['Version_check_config'] = 'Configuration de la v�rification de version';
$lang['Version_Config_explain'] = 'Ici vous pouvez configurer diff�rentes options pour le syst�me de v�rification de version. Une explication pour chaque option disponible vous est fournie.';
$lang['One_Address'] = 'Une addresse';
$lang['One_User'] = 'Un utilisateur';
$lang['All_Admins'] = 'Tous les administrateurs';
$lang['Version_check_interval'] = 'Intervalle de v�rification de version :';
$lang['Version_Check_Time_explain'] = 'D�finit tout les combien de temps la v�rification de version se d�clenche. Notez que vous pouvez outrepasser cette option en cliquant surNote that you can override this by clicking on the \'Lancer la v�rification\' or \'Lancer toutes les v�rifications\' sur la page de v�rification de version.';
$lang['Background_check'] = 'Lancer la v�rification de version en arri�re-plan :';
$lang['Background_check_explain'] = 'Si \'oui\', la v�rification de version sera automatiquement lanc�e en arri�re-plan en fonction de l\'intervalle de temps d�fini plus haut.';
$lang['Admin_index_summary'] = 'Montrer le r�capitulatif sur la page d\'index de l\'administration :';
$lang['Allow_Index_explain'] = 'Si \'oui\', un r�capitulatif de tous les MODs qui ne sont pas � jour appara�tra sur l\'index d\'administration. Notez que les infos de v�rification de version phpBB seront affich�s quelque soit la configuration.';
$lang['Email_on_update'] = 'Envoyer un e-mail � la mise � jour :';
$lang['Email_on_update_explain'] = 'Envoye un e-mail si un MOD est mis � jour. Si \'Une addresse\' est s�lectionn�, alors l\'e-mail sera seulement envoy�e � l\'adresse indiqu�e ci-dessous. Si \'Tous les administrateurs\' est s�lectionn�, alors le mail sera envoy� � tous les administrateurs du forum.';
$lang['Send_email_address'] = 'Adresse � qui envoyer le mail :';
$lang['Send_email_address_explain'] = 'Si \'Une addresse\' est indiqu� au-dessus,alors l\'e-mail sera seulement envoy�e � cette adresse. Sinon, cette option sera ignor�e.';
$lang['PM_on_update'] = 'Envoyer un MP � la mise � jour :';
$lang['PM_on_update_explain'] = 'Envoye un message priv� si un MOD est mis � jour. Si \'Un utilisateur\' est s�lectionn� alors le MP sera seulement envoy� � cet utilisateur en particulier. Si \'Tous les administrateurs\' est s�lectionn�, alors le MP sera envoy� � tous les administrateurs du forum.';
$lang['Send_PM_user'] = 'Utilisateur � qui envoyer le MP :';
$lang['Send_PM_user_explain'] = 'Si \'Un utilisateur\' est indiqu� au-dessus, le MP sera seulement envoy� � cet utilisateur.  Sinon, cette option sera ignor�e.';
$lang['Post_on_update'] = 'Poster un message � la mise � jour :';
$lang['Post_on_update_explain'] = 'Post un message dans le forum indiqu� en dessous si un MOD est mis � jour.';
$lang['Update_post_forum'] = 'Forum o� poster le message :';
$lang['Update_post_forum_explain'] = 'Si un message est post� (voir au-dessus), il sera post� dans ce forum.';
$lang['CH_bad_post_forum'] = 'Le forum que vous avez s�lectionn� n\'est pas un \'Forum\' type. Le forum <b>ne peut pas</b> �tre une cat�gorie ou un lien.';
$lang['Update_post_contents'] = 'Contenu du message:';
$lang['Update_post_contents_explain'] = 'Si un message est post� (voir au-dessus), ce sera le contenu de ce message. <b>&%n</b> repr�sente le nom du MOD mis � jour. <b>&%v</b> repr�sente la nouvelle version de ce mod (celui qui a besoin d\'�tre mis � jour). <b>&%c</b> repr�sente la version du Mod qui est install�e. <b>&%u</b> repr�sent l\'URL du site du MOD. <b>&%d</b> repr�sente l\'URL du lien de t�l�chargement du MOD. <b>&%a</b> affichera toutes les notes relatives � ce MOD.';
$lang['Update_post_contents_default'] = 'Bonjour,

Vous recevez cette notification car le MOD &%n a �t� mis � jour vers la version &%v. La version du MOD install�e sur ce forum est la &%c. Vous devriez vous mettre � jour le plus t�t possible.

&%a

Vous pouvez t�l�charger la derni�re version de &%n ici : &%d
Pour plus d\'information visitez ce site : &%u';
$lang['Notes_from_author'] = 'Notes de l\'auteur:';
$lang['avc_check_int']['3600'] = '1 Heure';
$lang['avc_check_int']['7200'] = '2 Heures';
$lang['avc_check_int']['10800'] = '3 Heures';
$lang['avc_check_int']['21600'] = '6 Heures';
$lang['avc_check_int']['32400'] = '9 Heures';
$lang['avc_check_int']['43200'] = '12 Heures';
$lang['avc_check_int']['64800'] = '18 Heures';
$lang['avc_check_int']['86400'] = '24 Heures';
$lang['avc_check_int']['129600'] = '36 Heures';
$lang['avc_check_int']['172800'] = '48 Heures';
$lang['avc_check_int']['259200'] = '72 Heures';

//
// Version Check Log
//
$lang['Log_MOD_updated'] = 'La nouvelle version est devenue la version %s'; // New version
$lang['Log_MOD_current'] = 'MOD install� mis � jour vers la version %s'; // Current version
$lang['Log_MOD_inserted'] = 'V�rification du MOD ajout�e';
$lang['Log_MOD_deleted'] = 'V�rification du MOD supprim�e';
$lang['Log_MOD_disabled'] = 'V�rification activ�e';
$lang['Log_MOD_enabled'] = 'V�rification d�sactiv�e';
$lang['Update_log'] = 'Fichier-log de mise � jour AVC';
$lang['Update_log_explain'] = 'le fichier-log "Mise � jour AVC" enregistre l\'historique des v�rifications de version, y compris quand une v�rification est ajout�e, supprim�e, activ�e ou d�sactiv�, quand une nouvele version d\'un MOD est post�e ou mise � jour sur ce forum. Vous pouvez supprimer une ou plusieurs entr�es en les s�lectionnant et en cliquant sur \'Supprimer les entr�es\'.';
$lang['Time'] = 'Heure';
$lang['Log_message'] = 'Message du fichier-log';
$lang['Delete_entries'] = 'Supprimer les entr�es';

//
// Download phpBB Page
//
$lang['Download_phpBB'] = 'T�l�charger phpBB';
$lang['phpBB_version'] = 'Version phpBB';
$lang['Download_phpBB_page_explain'] = 'Ce qui suit est une liste de liens que vous pouvez utilisez pour t�l�charger phpBB, ainsi que des informations vous expliquant quel lien vous devez utiliser. Des liens vous sont �galement fournis vers un tutorial vous expliquant comment mettre � jour phpBB et un lien vers la mailing list de phpBB.com, o� vous pouvez recevoir des informations sur les mises � jour de phpBB par mail.';
$lang['Downloads_page'] = 'T�l�chargez la derni�re version de phpBB de <a href="http://www.phpbb.com/downloads.php" target="_blank">page des t�l�chargements de phpBB</a>.';
$lang['Code_changes'] = 'Si vous avez de nombreux MODs ou des styles complexes install�s, vous d�sirez peut-�tre utiliser un des <br />the phpBB <a href="http://www.phpbb.com/phpBB/catdb.php?cat=48" target="_blank">Mods de changement de code</a> � la place.';
$lang['Upgrade_tutorial'] = 'Vous ne savez pas comment mettre � jour jetez un coup d\'oeil � ce <a href="http://www.phpbb.com/kb/article.php?article_id=271" target="_blank">tutorial de mise � jour</a>.';
$lang['Mailing_list'] = 'Pour rester informer sur les mises � jour et les autres nouvelles en rapport avec phpBB,<br /><a href="http://www.phpbb.com/support/" target="_blank">souscrivez � la mailing list de phpBB.com</a>.';

//
// Version Check Notification System
//
$lang['VCNS_post_subject'] = '[AVC] %s a �t� mis � jour !'; // Name of MOD
$lang['VCNS_PM_msg'] = 'Bonjour,

Vous recevez cette notification car le MOD %s a �t� mis � jour vers la version %s. La version du mod install� sur %s est la %s.  Vous devriez vous mettre � jour le plus t�t possible.

Vous pouvez t�l�charger la derni�re version de %s ici: %s
Pour plus d\'informations, visitez ce site web : %s

Vous recevez cet e-mail car un administrateur de %s a demand� � ce que cet e-mail vous soit envoy�. Si vous n\'avez pas demand� � le recevoir ou si vous avez une question quelconque, veuillez contacter l\'administrateur du forum.'; // MOD Name, Latest Version, Sitename, Current Version, MOD Name, Download Link, MOD Link, Sitename

//
// That's all, folks!
// -------------------------------------------------

?>