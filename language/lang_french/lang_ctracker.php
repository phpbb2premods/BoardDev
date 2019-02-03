<?php
/***************************************************************************
 *                            lang_ctracker.php [English]
 *                            -------------------
 *   copyright            : (C) 2005 by Christian Knerr (CBACK)
 *   homepage             : http://www.cback.de
 *
 *                            english translation
 *                           ---------------------
 *   copyright            : (C) 2005 by Michael Auchtor (herr-der-winde)
 *   homepage             : http://www.herrderwinde.de.vu
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

 // Footer Text
 $lang['ctr_footer_n'] = '<a href="http://www.cback.de" target="_blank">Forum Prot�g�</a> par <a href="http://www.cback.de" target="_blank">CBACK CrackerTracker</a>.';
 $lang['ctr_footer_c'] = 'Prot�g� par <a href="http://www.cback.de" target="_blank">CBACK CrackerTracker</a><br><b>%s</b> attaques bloqu�es.';
 $lang['ctr_footer_i'] = 'S�curit� du Forum';
 $lang['ctr_footer_g'] = '<b>%s</b> attaques bloqu�es';//Notes du Traducteur: C'est ce qui appara�t sous l\'image dans votre footer, vous pouvez le modifier � votre convenance: par ex : " attaques par scripts ont �t� bloqu�es sur ce forum", ou " abrutis ont essay� de hacker mon forum" (d�conseill� :D)

 // ACP
 $lang['ct_maintitle'] = 'CrackerTracker';
 $lang['ct_seccheck']  = 'V�rification S�curit�';
 $lang['ct_systest']   = 'Test Syst�me';
 $lang['ct_config']    = 'Configuration';
 $lang['ct_logs']      = 'Gestionnaire  Logs';
 $lang['ct_footer']    = 'Pied de page';
 $lang['ct_blocker']   = 'Blocage Proxys et Agents';
 $lang['ct_adm_foot']  = 'Powered by <a href="http://www.cback.de" target="_blank">CBACK CrackerTracker</a> Security System';

 // Security-Check
 $lang['ct_s_head']    = 'CTracker V�rification de la s�curit�';
 $lang['ct_s_desc']    = 'La v�rification de la s�curit� teste quelques fonctions de votre forum et de votre serveur � la recherche de possibles probl�mes de s�curit�. Ce syst�me ne peut pas d�tecter les valeurs sur tous les serveurs, dans ce cas, la table est vide � cet endroit. Chez certains h�bergeurs , vous ne pouvez pas configurer les options de php.ini vous-m�me. Dans cette situation, la version PHP elle-m�me ne peut-�tre mise � jour que par votre h�bergeur.';
 $lang['ct_s_hd1']     = 'Point de v�rification';
 $lang['ct_s_hd2']     = 'Votre version';
 $lang['ct_s_hd3']     = 'Version actuelle';
 $lang['ct_s_hd4']     = 'Statut';
 $lang['ct_s_hd5']     = 'Configuration';
 $lang['ct_s_t0']      = 'CrackerTracker';
 $lang['ct_s_t1']      = 'Version PHP4';
 $lang['ct_s_t2']      = 'Version PHP5';
 $lang['ct_s_t3']      = 'Version PhpBB';
 $lang['ct_s_ukn']     = '<font color="orange"><b>Inconnu(e)</b></font>';
 $lang['ct_s_ok']      = '<font color="green"><b>S�curis�</b></font>';
 $lang['ct_s_ac']      = '<font color="red"><b>Non-s�curis�</b></font>';
 $lang['ct_sc_v0']     = 'Mode s�curis� PHP';
 $lang['ct_sc_v1']     = 'Globaux PHP';
 $lang['ct_sc_v2']     = 'Confirmation visuelle phpBB';
 $lang['ct_sc_v3']     = 'Activation du compte phpBB';
 $lang['ct_sc_on']     = 'activ�';
 $lang['ct_sc_off']    = 'd�sactiv�';
 $lang['ct_s_infohe']  = 'Informations';
 $lang['ct_s_info']    = 'Chez un h�bergeur (sur votre espace en ligne), vous avez la possibilit� de garder votre forum <a href="http://www.phpbb2.de" target="_blank">phpBB</a> et le <a href="http://www.cback.de" target="_blank">CBACK CrackerTracker</a> � jour. De plus, si possible, vous devez garder l\' <a href="http://www.php.net" target="_blank">Interpr�te PHP</a> sur votre serveur � jour : il arrive souvent qu\'un forum ou qu\'un script PHP soit s�curis�, mais qu\'une faille de s�curit� puisse exister dans une ancienne version de l\'Interpr�te PHP. Vous pouvez contacter votre h�bergeur avec les r�f�rences de configuration de votre fichier php.ini et celles de votre Interpr�te PHP pour de plus amples informations.';

 // System-Test
 $lang['ct_sys_he']    = 'Syst�me de test du CBack CrackerTracker';
 $lang['ct_sys_de']    = 'Le syst�me de test du CBack CrackerTracker contr�le le syst�me de s�curit�, pour v�rifier qu\'il fonctionne correctement. Il contr�le donc, si les modules de s�curit� envoient une r�ponse (et par cons�quent, fonctionnent) sur le forum, si les CHMODs pour les fichiers-logs sont corrects et si des tentatives d\'attaques par scripts peuvent �tre d�tect�es. Si vous voulez faire un test de d�tection d\'attaque, vous pouvez le faire � l\'aide de ce petit test, %sCliquez juste sur ce lien</a>.';
 $lang['ct_sys_c1']    = 'CHMOD777 sur : Fichier Compteur';
 $lang['ct_sys_c2']    = 'CHMOD777 sur : Fichier Flood';
 $lang['ct_sys_c3']    = 'CHMOD777 sur : Fichier blocage d\'IP';
 $lang['ct_sys_c4']    = 'CHMOD777 sur : Fichier Vers/Attaques';
 $lang['ct_sys_c5']    = 'Moteur de protection contre les Vers';
 $lang['ct_sys_c6']    = 'Moteur de blocage d\'IP et d\'Agent';
 $lang['ct_sys_c7']    = 'Fichier des fonctions du CrackerTracker';
 $lang['ct_sys_c8']    = 'Entr�es de la base de donn�es';
 $lang['ct_sys_c9']    = 'Syst�me du pied de page';
 $lang['ct_sys_c10']   = 'Definition Record';
 $lang['ct_sys_ok']    = '<font color="green"><b>OK</b></font>';
 $lang['ct_sys_er']    = '<font color="red"><b>ERREUR</b></font>';

 // Footer
 $lang['ct_submit']    = 'Sauvegarder';
 $lang['ct_foot_h']    = 'S�lectionnez un pied de page';
 $lang['ct_foot_d']    = 'D\'ici vous pouvez s�lectionner le pied de page du CrackerTracker, qui est montr� sur votre forum. Svp, prenez en compte que les fonctions comme le compteur d\'attaques fonctionnent seulement sur les nouvelles versions de l\'interpr�te PHP comme PHP 4.3.6. De plus, vous devez g�n�ralement avoir la derni�re version de PHP install� sur votre serveur pour une question de s�curit�.';
 $lang['ct_foot_sh']   = 'S�lectionnez votre pied de page pr�f�r�';
 $lang['ct_f_ass']     = '<font color="red">Configuration sauvegard�e</font>';

 // CT IP&Agent Blocker
 $lang['ct_pf_add']    = 'Ajouter';
 $lang['ct_pf_head']   = 'Bloqueur d\'IP, de Proxys et d\'Agents';
 $lang['ct_pf_head1']  = 'Ajouter une nouvelle entr�e';
 $lang['ct_pf_head2']  = 'Liste de blocage';
 $lang['ct_pf_desc']   = 'D\'ici vous pouvez bloquer des adresses IP d�finies (par ex : 192.168.0.40) ou des Agents (par ex. WebCrawler). Svp, prenez en compte que ce syst�me a besoin des informations compl�tes pour un blocage plus rapide , les caract�res jokers (*) ne sont pas autoris�s. Ceux-ci sont disponibles dans les options de bannissement de phpBB. Prenez aussi en compte que les Vers utilisent souvent de fausses adresses IP, ainsi un bannissement par IP pour les vers est sans effet et inutile. Le moteur de d�tection d\'attaque du CrackerTracker fonctionne contre les Vers, aussi ne vous en faites pas : les Vers seront filtr�s et bloqu�s ! Faites enfin attention � ne pas enregistrer l\'Agent de votre navigateur, car vous risquez de vous bloquer vous-m�me l\'acc�s � votre forum !';
 $lang['ct_pf_desc1']  = 'Svp, entrez ici l\'adresse <b>IP compl�te</b>  ou l\'<b>Agent complet</b>, que CBack CrackerTracker doit bloquer.';
 $lang['ct_pf_desc2']  = 'D\'ici vous pouvez voir la liste noire du Bloqueur de Proxys et d\'Agents et vous pouvez supprimez des entr�es du syst�me de blocage, si n�cessaire.';
 $lang['ct_pf_del']    = 'SUPPRIMER';

 // Configuration
 $lang['ct_conf_h']    = 'Configuration du CBACK CrackerTracker ';
 $lang['ct_conf_d']    = 'D\'ici vous pouvez g�rer des fonctions suppl�mentaires de s�curit� et les r�gler � votre convenance.';
 $lang['ct_conf_tb1']  = 'Limitation dynamique des entr�es des fichiers-logs';
 $lang['ct_conf_tb2']  = 'Contr�le des modules de s�curit� optionnels';
 $lang['ct_conf_tb3']  = 'Limitation de la recherche';
 $lang['ct_conf_tb4']  = 'Protection contre le Flood et le Spam';
 $lang['ct_conf_p1']   = 'Fichier-log Flood & Spam';
 $lang['ct_conf_d1']   = 'D\'ici vous pouvez s�lectionner le nombre maximal d\'entr�es dans le fichier-log Flood & Spam. Dans ce fichier, tous les utilisateurs ayant �t� bloqu� � cause d\'un trop grand nombre de posts dans une courte p�riode de temps, seront sauvegard�s. Si ce compte est atteint, le fichier-log sera imm�diatement nettoy�.';
 $lang['ct_conf_p2']   = 'Fichier-log de bloqueur d\'ID et d\'Agents';
 $lang['ct_conf_d2']   = 'D\'ici vous pouvez fixer le nombre maximal d\'entr�es pour le fichier-log de bloqueur d\'ID et d\'Agents. L�, les requ�tes bloqu�es des Agents et des IPs du point de configuration correspondant seront enregistr�es. Si ce compte est atteint, le fichier-log sera imm�diatement nettoy�.';
 $lang['ct_conf_p3']   = 'Bloqueur de Proxys & d\'Agents';
 $lang['ct_conf_d3']   = 'D\'ici vous pouvez activer ou d�sactiver l\'option de blocage des Proxys & Agents de CBACK CrackerTracker. Si ce module est d�sactiv�, le syst�me ignorera la liste noire dans le point de configuration correspondant.';
 $lang['ct_conf_p4']   = 'Protection Anti-Spam';
 $lang['ct_conf_d4']   = 'D\'ici vous pouvez activer ou d�sactiver le moteur de protection anti-spam, qui bloque un utilisateur en fixant un nombre limit� de messages dans un temps donn�.';
 $lang['ct_conf_p5']   = 'Protection contre le Flood � l\'enregistrement';
 $lang['ct_conf_d5']   = 'Cette option prot�ge l\'enregistrement sur votre forum phpBB en plus de la confirmation visuelle. CrackerTracker teste les IPs cycliques (par ex: Bots de spam) � l\'enregistrement et peut cr�er une boucle d\'attente entre deux inscriptions pour ralentir les scripts de Flood.';
 $lang['ct_conf_p6']   = 'Bannissement automatique des Spammeurs';
 $lang['ct_conf_d6']   = 'Si cette option et l\'option "Protection Anti-Spam" sont activ�es, les utilisateurs seront bannis. Sinon, leur compte-utilisateur sera d�sactiv�. <b>Avertissement :</b> Cette option doit �tre activ�e, car le bannissement par nom d\'utilisateur est la meilleure m�thode. Il y a des forums o� vous pouvez demander une nouvelle fois l\'e-mail d\'activation pour le compte.';
 $lang['ct_conf_p9']   = 'Nombre de recherches autoris� avant le blocage de la recherche';
 $lang['ct_conf_d9']   = 'D\'ici vous pouvez choisir combien de recherches les utilisateurs (enregistr�s) peuvent ex�cuter l\'une apr�s l\'autre, avant que la limitation de dur�e ne soit activ�e.';
 $lang['ct_conf_p10']  = 'Fonction de limitation de dur�e de la recherche';
 $lang['ct_conf_d10']  = 'D\'ici vous pouvez choisir (en secondes) combien de temps un utilisateur (enregistr�) devra attendre, s\'il a atteint la limite de recherches autoris�e, pour ex�cuter une recherche suppl�mentaire. (Evite le Flood � l\'aide de scripts)';
 $lang['ct_conf_p11']  = 'Limite de dur�e entre deux enregistrements sur le forum';
 $lang['ct_conf_d11']  = 'D\'ici vous pouvez choisir la dur�e (en secondes) entre deux inscriptions sur le forum. (Evite les surcharges sur le Serveur � l\'aide de scripts)';
 $lang['ct_conf_p12']  = 'Dur�e du nombre autoris� de messages';
 $lang['ct_conf_d12']  = 'D\'ici vous pouvez fixer la dur�e (en secondes) durant laquelle un utilisateur ne doit pas d�passer un nombre d�fini de messages. Sinon - si le moteur au-dessus est activ� - il sera bloqu�.';
 $lang['ct_conf_p13']  = 'Nombre de messages autoris�s pendant la dur� d�finie.';
 $lang['ct_conf_d13']  = 'D\'ici vous pouvez fixer le nombre de messages qu\'un utilisateur peut �crire durant la dur�e d�finie, avant que le syst�me du CrackerTracker ne le consid�re comme un spammeur et qu\'il - si le moteur au-dessus est activ� - soit bloqu�.';
 $lang['ct_conf_p14']  = 'V�rification de l\'envoi de mails';
 $lang['ct_conf_d14']  = 'Si vous activez cette fonction, un utilisateur ne pourra envoyer un mail via le formulaire de mail phpBB que toutes les 4 Minutes.';
 $lang['ct_conf_p15']  = 'V�rification de la r�initialisation du mot de passe';
 $lang['ct_conf_d15']  = 'Si vous activez cette fonction CrackerTracker autorisera juste un seul envoi de nouveau mot de passe jusqu\'� ce que le mot de passe soit chang�.';
 $lang['ct_conf_p16']  = 'Syst�me de protection de la connexion';
 $lang['ct_conf_d16']  = 'Ici vous pouvez activer la confirmation visuelle durant la connexion afin de vous prot�ger contre les attaques Brute Force.';
 $lang['ct_conf_act']  = 'Activer';
 $lang['ct_conf_dact'] = 'D�sactiver';

 // Logfile Manager
 $lang['ct_log_head']  = 'Gestionnaire des fichiers Logs du CBack CrackerTracker';
 $lang['ct_log_desc']  = 'D\'ici vous pouvez g�rer, supprimer et v�rifier les fichiers Logs du CBack CrackerTracker.';
 $lang['ct_log_cell1'] = 'Fichier-log';
 $lang['ct_log_cell2'] = 'Entr�es';
 $lang['ct_log_cell3'] = 'Options';
 $lang['ct_log_f1']    = 'Fichier-log des Vers et des Attaques';
 $lang['ct_log_f2']    = 'Fichier-log du Bloqueur d\'IP et d\'Agents';
 $lang['ct_log_f3']    = 'Fichier-log Protection Anti-Spam';
 $lang['ct_log_l1']    = 'VOIR';
 $lang['ct_log_l2']    = 'SUPPRIMER';
 $lang['ct_log_l3']    = 'SUPPRIMER TOUS LES FICHIERS-LOGS';
 $lang['ct_log_gl']    = 'Fonctions globales';
 $lang['ct_log_gl1']   = 'CBACK CrackerTracker a bloqu� <b>%s</b> Attaques sur le forum. Avec le lien suivant, vous pouvez supprimer tous les fichiers-logs en une seule fois. Le compteur restera inchang�.<br>';
 $lang['ct_log_back']  = '&laquo; RETOUR AU MENU';
 $lang['ct_log_tc1']   = 'Date/Heure';
 $lang['ct_log_tc2']   = 'IP';
 $lang['ct_log_tc3']   = 'Type d\'attaque';
 $lang['ct_log_tc4']   = 'Referrer';
 $lang['ct_log_tc5']   = 'Agent';
 $lang['ct_log_entr']  = 'Actuellement, il y a %s entr�es dans le fichier-log.';
 $lang['ct_log_entr1'] = 'Actuellement, il y a 1 entr�e dans le fichier-log.';

 // Language for parts into the Board itself
 $lang['ct_forum_sfl'] = 'Notre forum est prot�g� contre le Flood avec l\'option de recherche, aussi vous ne pouvez faire une recherche que toutes les %s secondes. Vous devez attendre %s secondes jusqu\'� votre prochaine recherche. Merci de votre compr�hension.';
 $lang['ct_forum_rfl'] = 'D�sol�, il vient d\'y avoir une inscription sur ce forum. Notre forum est prot�g� contre le Flood � l\'inscription (Robots-Spammeurs). Vous devez attendre %s secondes jusqu\'� ce qu\'une nouvelle inscription soit possible. Merci de votre compr�hension.';
 $lang['ct_forum_ifl'] = 'Il semblerait que vous ayez cr�� un compte il y a peu de temps. Svp, v�rifiez que vous soyez connect� normallement (cliquez sur "Connexion" si ce n\'est pas le cas) ou d\'avoir confirm� votre inscription, par exemple en ayant activ� votre compte par E-Mail (v�rifiez votre Boite E-mail).Merci de votre compr�hension.';
 $lang['ct_forum_wa']  = '<b>AVERTISSEMENT!</b><br><br>Le syst�me de protection contre le Spam du forum a d�tect� que le nombre maximum de messages autoris�s, pendant une dur�e d�finie par l\'administrateur de ce forum, a �t� atteint. Si vous n\'�tes pas un robot de Spam, et que vous postez un autre message durant les %s prochaines secondes vous serez automatiquement banni !';
 $lang['ct_forum_blo'] = '<b>PROTECTION CONTRE LE SPAM DU CRACKERTRACKER</b><br><br>Vous avez atteint le nombre maximum de messages autoris�s. Votre compte sur ce forum a �t� bloqu� !';
?>
