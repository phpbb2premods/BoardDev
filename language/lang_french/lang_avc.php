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
$lang['Version_check_explain'] = 'En Utilisant cette fonctionnalité, vous pouvez voir si les divers MODs que vous avez installé sont à jour ou non. S\'il ne sont pas à jour alors le numéro de version dans la dernière colonne sera rouge, et une mise à jour conseillée. Vous pouvez activer/désactiver les vérifications individuelles par le panneau Activer/Désactiver les vérifications, et définir les options de configuration pour le système de vérification de version dans le panneau de configuration de vérification de version. Notez que les informations peuvent ne pas être à jour, cliquez sur "Vérifier maintenant" pour mettre à jour les informations.';
$lang['Version_check'] = 'Vérification de version';
$lang['Version_up_to_date_short'] = 'Version à jour';
$lang['Version_not_up_to_date_short'] = 'VERSION NON A JOUR !';
$lang['Error_connect_socket'] = '<b>Erreur:</b><br />%s'; // %s displays the error information, this is set by default in any page that uses the Version Check
$lang['No_socket_functions'] = '<b>Erreur:</b><br />Les fonctions sockets ne peuvent pas être utilisées.';
$lang['MOD_Name'] = 'Nom du mod';
$lang['Download'] = 'Télécharger';
$lang['Re-check'] = 'Revérifier';
$lang['Latest_version'] = 'Dernière version';
$lang['Current_version'] = 'Version actuelle';
$lang['MOD_Status'] = 'Statut du mod';
$lang['Index_summary_explain'] = 'Voici une liste des MODs qui ne sont pas à jour. Vous pouveez voir les détails complets sur la page %sVérification de version%s. Cette liste peut être désactivée par la page de configuration de la vérification de versione.'; // <a href>, </a> tags
$lang['MODs_uptodate'] = 'Tous les MODs sont à jour.';
$lang['Last_Updated'] = 'Dernière mise à jour : '; // This is used for the Last Updated: timestamp in the Version Check Summary. The timestamp will immediately follow this.
$lang['Result_new_vers_available'] = 'Une nouvelle version de ce MOD est disponible !';
$lang['Result_have_latest'] = 'Vous avez la dernière version de ce MOD disponible.';
$lang['Error_occured'] = 'Une erreur est arrivée !';
$lang['Secondary_version_msg'] = '<i><b>Note:</b> une %s version de ce MOD, version %s, est disponible. Vérifiez la page web du MOD pour plus d\'infos.</i>'; // MOD development status, version number
$lang['stable'] = 'stable';
$lang['development'] = 'développement';
$lang['Not_specified'] = 'Non spécifié';
$lang['More_info'] = 'plus d\'infos';

//
// Checker Management page
//
$lang['Version_Manager'] = 'Activer/Désactiver les vérifications';
$lang['Version_Manager_explain'] = 'Ici vous pouvez activer/désactiver les vérifications individuelles des vérifications de version. Si elle est désactivée, la vérification ne sera pas activée sur la page "Vérification de version" ou sur la page d\'index de l\'administration.';
$lang['Check_disable'] = 'Vérification désactivée';
$lang['Check_enable'] = 'Vérification activée';
$lang['Run_check'] = 'Lancer la vérification';
$lang['Run_all_checks'] = 'Lancer toutes les vérifications';

//
// Information Boxes
//
$lang['Click_return_versionmanage'] = 'Cliquez %sIci%s pour retourner à la page Activation/Désactivation';
$lang['Click_return_version'] = 'Cliquez %sIci%s pour retourner à la page de vérification de version';
$lang['Version_updated'] = 'Informations de vérification de version mises à jour avec succès !';
$lang['No_Config_Info'] = 'Les informations de configuration ne peuvent pas être obtenues';
$lang['Update_failed'] = 'Echec lors de la tentative de mise à jour de la configuration générale pour %s'; // Name of configuration option
$lang['Status_no_Update'] = 'Le statut ne peut pas être mis à jour';
$lang['No_Version'] = 'Les informations de vérification de version dans la table de données ne peuvent pas être obtenues';
$lang['Connection_error'] = 'Erreur de connexion';
$lang['No_socket'] = 'Fonctions sockets désactivées';
/*****/
$lang['No_Version_Config'] = 'Les informations de configuration de la vérification de version dans la base de données ne peuvent pas être sélectionnées.';
$lang['Cant_update_config'] = 'La configuration de vérification de version pour %s ne peut pas être mise à jour';// $config_name
$lang['Version_config_updated'] = 'La configuration de vérification de version a été mise à jour avec succès.';
$lang['No_Version_Log'] = 'Le fichier-log d\'informations ne peut pas être sélectionné dans la base de données.';
$lang['No_Update_Log'] = 'Le fichier-log de vérification de version ne peut pas être mis à jour.';
$lang['No_Delete_Log'] = 'Le fichier-log %s ne peut pas être supprimé de la base de données.'; // $log_id
$lang['No_MOD_Status'] = 'Le statut du MOD ne peut pas être sélectionné dans la base de données.';
$lang['No_MOD_Status_update'] = 'Le statut du MOD %s ne peut pas être mis à jour.';
$lang['No_Version_Data'] = 'Les informations de vérification de version ne peuvent pas être sélectionnées dans la base de données.';
$lang['No_add_phpBB_version'] = 'la version phpBB ne peut pas être ajoutée à la table de vérification de version.';
$lang['No_new_MOD'] = 'Un/le nouveau mod ne peut pas être ajouté à la table de vérification de version.';
$lang['No_update_MOD'] = 'les statistiques du MOD ne peuvent pas être mises à jours dans la table de vérification de version.';
$lang['No_delete_MOD'] = 'les informations non-utilisées du MOD ne peuvent pas être supprimées de la table de vérification de version.';
$lang['No_error_msg'] = 'Le message d\'erreur ne paut pas être envoyé à la base de données. Le message d\'erreur original était: %s'; // $error_msg
$lang['No_error_msg_MODID'] = 'ID du MOD: %s'; // $mod_id
$lang['No_admin_addresses'] = 'Les adresses e-mail des administateurs ne peuvent être récupérer dans la base de données pour envoyer le mail AVC.';
$lang['No_PM_info'] = 'Les informations de MP ne peuvent pas être obtenues pour envoyer le mail AVC.';
$lang['No_post_info'] = 'les informations ne peuvent pas être obtenues pour l\'insertion du message AVC.';
$lang['No_update_version_table'] = 'la table de vérification avec les infos sur %s ne peut pas être mise à jour'; // MOD Name
$lang['No_checker_ID'] = 'Aucune ID de vérification n\'a été spécifiée.';
$lang['No_find_oldest_privmsgs'] = 'Les plus anciens MP ne peuvent pas être trouvés (Messages reçus)';
$lang['No_delete_oldest_privmsgs'] = 'Les plus anciens MP ne peuvent pas être supprimés (Messages reçus)';
$lang['No_delete_oldest_privmsgs_text'] = 'Les plus anciens textes de MP ne peuvent pas être supprimés (Messages reçus)';
$lang['No_PM_sent_info'] = 'Les infos de MP envoyé ne peuvent pas être insérées/mises à jour.';
$lang['No_PM_sent_text'] = 'Le texte de MP envoyé ne peut pas être inséré/mis à jour.';
$lang['No_PM_read_status'] = 'Le statut de l\'utilisateur pour les nouveaux messages ou lus ne peut pas être mis à jour';
$lang['No_AVC_post'] = 'Le message AVC ne peut pas être inséré.';
$lang['No_retrievable'] = 'Le fichier accessible ne peut pas être ouvert.';
$lang['No_retrievable_socket'] = 'Les fonctions sockets sont désactivées. Le fichier accessible ne peut pas être ouvert.';
$lang['Invalid_retrievable'] = 'Le fichier accessible n\'utilise pas une extension valide.';

//
// Version Check Configuration page
//
$lang['Version_check_config'] = 'Configuration de la vérification de version';
$lang['Version_Config_explain'] = 'Ici vous pouvez configurer différentes options pour le système de vérification de version. Une explication pour chaque option disponible vous est fournie.';
$lang['One_Address'] = 'Une addresse';
$lang['One_User'] = 'Un utilisateur';
$lang['All_Admins'] = 'Tous les administrateurs';
$lang['Version_check_interval'] = 'Intervalle de vérification de version :';
$lang['Version_Check_Time_explain'] = 'Définit tout les combien de temps la vérification de version se déclenche. Notez que vous pouvez outrepasser cette option en cliquant surNote that you can override this by clicking on the \'Lancer la vérification\' or \'Lancer toutes les vérifications\' sur la page de vérification de version.';
$lang['Background_check'] = 'Lancer la vérification de version en arrière-plan :';
$lang['Background_check_explain'] = 'Si \'oui\', la vérification de version sera automatiquement lancée en arrière-plan en fonction de l\'intervalle de temps défini plus haut.';
$lang['Admin_index_summary'] = 'Montrer le récapitulatif sur la page d\'index de l\'administration :';
$lang['Allow_Index_explain'] = 'Si \'oui\', un récapitulatif de tous les MODs qui ne sont pas à jour apparaîtra sur l\'index d\'administration. Notez que les infos de vérification de version phpBB seront affichés quelque soit la configuration.';
$lang['Email_on_update'] = 'Envoyer un e-mail à la mise à jour :';
$lang['Email_on_update_explain'] = 'Envoye un e-mail si un MOD est mis à jour. Si \'Une addresse\' est sélectionné, alors l\'e-mail sera seulement envoyée à l\'adresse indiquée ci-dessous. Si \'Tous les administrateurs\' est sélectionné, alors le mail sera envoyé à tous les administrateurs du forum.';
$lang['Send_email_address'] = 'Adresse à qui envoyer le mail :';
$lang['Send_email_address_explain'] = 'Si \'Une addresse\' est indiqué au-dessus,alors l\'e-mail sera seulement envoyée à cette adresse. Sinon, cette option sera ignorée.';
$lang['PM_on_update'] = 'Envoyer un MP à la mise à jour :';
$lang['PM_on_update_explain'] = 'Envoye un message privé si un MOD est mis à jour. Si \'Un utilisateur\' est sélectionné alors le MP sera seulement envoyé à cet utilisateur en particulier. Si \'Tous les administrateurs\' est sélectionné, alors le MP sera envoyé à tous les administrateurs du forum.';
$lang['Send_PM_user'] = 'Utilisateur à qui envoyer le MP :';
$lang['Send_PM_user_explain'] = 'Si \'Un utilisateur\' est indiqué au-dessus, le MP sera seulement envoyé à cet utilisateur.  Sinon, cette option sera ignorée.';
$lang['Post_on_update'] = 'Poster un message à la mise à jour :';
$lang['Post_on_update_explain'] = 'Post un message dans le forum indiqué en dessous si un MOD est mis à jour.';
$lang['Update_post_forum'] = 'Forum où poster le message :';
$lang['Update_post_forum_explain'] = 'Si un message est posté (voir au-dessus), il sera posté dans ce forum.';
$lang['CH_bad_post_forum'] = 'Le forum que vous avez sélectionné n\'est pas un \'Forum\' type. Le forum <b>ne peut pas</b> être une catégorie ou un lien.';
$lang['Update_post_contents'] = 'Contenu du message:';
$lang['Update_post_contents_explain'] = 'Si un message est posté (voir au-dessus), ce sera le contenu de ce message. <b>&%n</b> représente le nom du MOD mis à jour. <b>&%v</b> représente la nouvelle version de ce mod (celui qui a besoin d\'être mis à jour). <b>&%c</b> représente la version du Mod qui est installée. <b>&%u</b> représent l\'URL du site du MOD. <b>&%d</b> représente l\'URL du lien de téléchargement du MOD. <b>&%a</b> affichera toutes les notes relatives à ce MOD.';
$lang['Update_post_contents_default'] = 'Bonjour,

Vous recevez cette notification car le MOD &%n a été mis à jour vers la version &%v. La version du MOD installée sur ce forum est la &%c. Vous devriez vous mettre à jour le plus tôt possible.

&%a

Vous pouvez télécharger la dernière version de &%n ici : &%d
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
$lang['Log_MOD_current'] = 'MOD installé mis à jour vers la version %s'; // Current version
$lang['Log_MOD_inserted'] = 'Vérification du MOD ajoutée';
$lang['Log_MOD_deleted'] = 'Vérification du MOD supprimée';
$lang['Log_MOD_disabled'] = 'Vérification activée';
$lang['Log_MOD_enabled'] = 'Vérification désactivée';
$lang['Update_log'] = 'Fichier-log de mise à jour AVC';
$lang['Update_log_explain'] = 'le fichier-log "Mise à jour AVC" enregistre l\'historique des vérifications de version, y compris quand une vérification est ajoutée, supprimée, activée ou désactivé, quand une nouvele version d\'un MOD est postée ou mise à jour sur ce forum. Vous pouvez supprimer une ou plusieurs entrées en les sélectionnant et en cliquant sur \'Supprimer les entrées\'.';
$lang['Time'] = 'Heure';
$lang['Log_message'] = 'Message du fichier-log';
$lang['Delete_entries'] = 'Supprimer les entrées';

//
// Download phpBB Page
//
$lang['Download_phpBB'] = 'Télécharger phpBB';
$lang['phpBB_version'] = 'Version phpBB';
$lang['Download_phpBB_page_explain'] = 'Ce qui suit est une liste de liens que vous pouvez utilisez pour télécharger phpBB, ainsi que des informations vous expliquant quel lien vous devez utiliser. Des liens vous sont également fournis vers un tutorial vous expliquant comment mettre à jour phpBB et un lien vers la mailing list de phpBB.com, où vous pouvez recevoir des informations sur les mises à jour de phpBB par mail.';
$lang['Downloads_page'] = 'Téléchargez la dernière version de phpBB de <a href="http://www.phpbb.com/downloads.php" target="_blank">page des téléchargements de phpBB</a>.';
$lang['Code_changes'] = 'Si vous avez de nombreux MODs ou des styles complexes installés, vous désirez peut-être utiliser un des <br />the phpBB <a href="http://www.phpbb.com/phpBB/catdb.php?cat=48" target="_blank">Mods de changement de code</a> à la place.';
$lang['Upgrade_tutorial'] = 'Vous ne savez pas comment mettre à jour jetez un coup d\'oeil à ce <a href="http://www.phpbb.com/kb/article.php?article_id=271" target="_blank">tutorial de mise à jour</a>.';
$lang['Mailing_list'] = 'Pour rester informer sur les mises à jour et les autres nouvelles en rapport avec phpBB,<br /><a href="http://www.phpbb.com/support/" target="_blank">souscrivez à la mailing list de phpBB.com</a>.';

//
// Version Check Notification System
//
$lang['VCNS_post_subject'] = '[AVC] %s a été mis à jour !'; // Name of MOD
$lang['VCNS_PM_msg'] = 'Bonjour,

Vous recevez cette notification car le MOD %s a été mis à jour vers la version %s. La version du mod installé sur %s est la %s.  Vous devriez vous mettre à jour le plus tôt possible.

Vous pouvez télécharger la dernière version de %s ici: %s
Pour plus d\'informations, visitez ce site web : %s

Vous recevez cet e-mail car un administrateur de %s a demandé à ce que cet e-mail vous soit envoyé. Si vous n\'avez pas demandé à le recevoir ou si vous avez une question quelconque, veuillez contacter l\'administrateur du forum.'; // MOD Name, Latest Version, Sitename, Current Version, MOD Name, Download Link, MOD Link, Sitename

//
// That's all, folks!
// -------------------------------------------------

?>