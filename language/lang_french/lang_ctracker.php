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
 $lang['ctr_footer_n'] = '<a href="http://www.cback.de" target="_blank">Forum Protégé</a> par <a href="http://www.cback.de" target="_blank">CBACK CrackerTracker</a>.';
 $lang['ctr_footer_c'] = 'Protégé par <a href="http://www.cback.de" target="_blank">CBACK CrackerTracker</a><br><b>%s</b> attaques bloquées.';
 $lang['ctr_footer_i'] = 'Sécurité du Forum';
 $lang['ctr_footer_g'] = '<b>%s</b> attaques bloquées';//Notes du Traducteur: C'est ce qui apparaît sous l\'image dans votre footer, vous pouvez le modifier à votre convenance: par ex : " attaques par scripts ont été bloquées sur ce forum", ou " abrutis ont essayé de hacker mon forum" (déconseillé :D)

 // ACP
 $lang['ct_maintitle'] = 'CrackerTracker';
 $lang['ct_seccheck']  = 'Vérification Sécurité';
 $lang['ct_systest']   = 'Test Système';
 $lang['ct_config']    = 'Configuration';
 $lang['ct_logs']      = 'Gestionnaire  Logs';
 $lang['ct_footer']    = 'Pied de page';
 $lang['ct_blocker']   = 'Blocage Proxys et Agents';
 $lang['ct_adm_foot']  = 'Powered by <a href="http://www.cback.de" target="_blank">CBACK CrackerTracker</a> Security System';

 // Security-Check
 $lang['ct_s_head']    = 'CTracker Vérification de la sécurité';
 $lang['ct_s_desc']    = 'La vérification de la sécurité teste quelques fonctions de votre forum et de votre serveur à la recherche de possibles problèmes de sécurité. Ce système ne peut pas détecter les valeurs sur tous les serveurs, dans ce cas, la table est vide à cet endroit. Chez certains hébergeurs , vous ne pouvez pas configurer les options de php.ini vous-même. Dans cette situation, la version PHP elle-même ne peut-être mise à jour que par votre hébergeur.';
 $lang['ct_s_hd1']     = 'Point de vérification';
 $lang['ct_s_hd2']     = 'Votre version';
 $lang['ct_s_hd3']     = 'Version actuelle';
 $lang['ct_s_hd4']     = 'Statut';
 $lang['ct_s_hd5']     = 'Configuration';
 $lang['ct_s_t0']      = 'CrackerTracker';
 $lang['ct_s_t1']      = 'Version PHP4';
 $lang['ct_s_t2']      = 'Version PHP5';
 $lang['ct_s_t3']      = 'Version PhpBB';
 $lang['ct_s_ukn']     = '<font color="orange"><b>Inconnu(e)</b></font>';
 $lang['ct_s_ok']      = '<font color="green"><b>Sécurisé</b></font>';
 $lang['ct_s_ac']      = '<font color="red"><b>Non-sécurisé</b></font>';
 $lang['ct_sc_v0']     = 'Mode sécurisé PHP';
 $lang['ct_sc_v1']     = 'Globaux PHP';
 $lang['ct_sc_v2']     = 'Confirmation visuelle phpBB';
 $lang['ct_sc_v3']     = 'Activation du compte phpBB';
 $lang['ct_sc_on']     = 'activé';
 $lang['ct_sc_off']    = 'désactivé';
 $lang['ct_s_infohe']  = 'Informations';
 $lang['ct_s_info']    = 'Chez un hébergeur (sur votre espace en ligne), vous avez la possibilité de garder votre forum <a href="http://www.phpbb2.de" target="_blank">phpBB</a> et le <a href="http://www.cback.de" target="_blank">CBACK CrackerTracker</a> à jour. De plus, si possible, vous devez garder l\' <a href="http://www.php.net" target="_blank">Interprète PHP</a> sur votre serveur à jour : il arrive souvent qu\'un forum ou qu\'un script PHP soit sécurisé, mais qu\'une faille de sécurité puisse exister dans une ancienne version de l\'Interprète PHP. Vous pouvez contacter votre hébergeur avec les références de configuration de votre fichier php.ini et celles de votre Interprète PHP pour de plus amples informations.';

 // System-Test
 $lang['ct_sys_he']    = 'Système de test du CBack CrackerTracker';
 $lang['ct_sys_de']    = 'Le système de test du CBack CrackerTracker contrôle le système de sécurité, pour vérifier qu\'il fonctionne correctement. Il contrôle donc, si les modules de sécurité envoient une réponse (et par conséquent, fonctionnent) sur le forum, si les CHMODs pour les fichiers-logs sont corrects et si des tentatives d\'attaques par scripts peuvent être détectées. Si vous voulez faire un test de détection d\'attaque, vous pouvez le faire à l\'aide de ce petit test, %sCliquez juste sur ce lien</a>.';
 $lang['ct_sys_c1']    = 'CHMOD777 sur : Fichier Compteur';
 $lang['ct_sys_c2']    = 'CHMOD777 sur : Fichier Flood';
 $lang['ct_sys_c3']    = 'CHMOD777 sur : Fichier blocage d\'IP';
 $lang['ct_sys_c4']    = 'CHMOD777 sur : Fichier Vers/Attaques';
 $lang['ct_sys_c5']    = 'Moteur de protection contre les Vers';
 $lang['ct_sys_c6']    = 'Moteur de blocage d\'IP et d\'Agent';
 $lang['ct_sys_c7']    = 'Fichier des fonctions du CrackerTracker';
 $lang['ct_sys_c8']    = 'Entrées de la base de données';
 $lang['ct_sys_c9']    = 'Système du pied de page';
 $lang['ct_sys_c10']   = 'Definition Record';
 $lang['ct_sys_ok']    = '<font color="green"><b>OK</b></font>';
 $lang['ct_sys_er']    = '<font color="red"><b>ERREUR</b></font>';

 // Footer
 $lang['ct_submit']    = 'Sauvegarder';
 $lang['ct_foot_h']    = 'Sélectionnez un pied de page';
 $lang['ct_foot_d']    = 'D\'ici vous pouvez sélectionner le pied de page du CrackerTracker, qui est montré sur votre forum. Svp, prenez en compte que les fonctions comme le compteur d\'attaques fonctionnent seulement sur les nouvelles versions de l\'interprète PHP comme PHP 4.3.6. De plus, vous devez généralement avoir la dernière version de PHP installé sur votre serveur pour une question de sécurité.';
 $lang['ct_foot_sh']   = 'Sélectionnez votre pied de page préféré';
 $lang['ct_f_ass']     = '<font color="red">Configuration sauvegardée</font>';

 // CT IP&Agent Blocker
 $lang['ct_pf_add']    = 'Ajouter';
 $lang['ct_pf_head']   = 'Bloqueur d\'IP, de Proxys et d\'Agents';
 $lang['ct_pf_head1']  = 'Ajouter une nouvelle entrée';
 $lang['ct_pf_head2']  = 'Liste de blocage';
 $lang['ct_pf_desc']   = 'D\'ici vous pouvez bloquer des adresses IP définies (par ex : 192.168.0.40) ou des Agents (par ex. WebCrawler). Svp, prenez en compte que ce système a besoin des informations complètes pour un blocage plus rapide , les caractères jokers (*) ne sont pas autorisés. Ceux-ci sont disponibles dans les options de bannissement de phpBB. Prenez aussi en compte que les Vers utilisent souvent de fausses adresses IP, ainsi un bannissement par IP pour les vers est sans effet et inutile. Le moteur de détection d\'attaque du CrackerTracker fonctionne contre les Vers, aussi ne vous en faites pas : les Vers seront filtrés et bloqués ! Faites enfin attention à ne pas enregistrer l\'Agent de votre navigateur, car vous risquez de vous bloquer vous-même l\'accès à votre forum !';
 $lang['ct_pf_desc1']  = 'Svp, entrez ici l\'adresse <b>IP complète</b>  ou l\'<b>Agent complet</b>, que CBack CrackerTracker doit bloquer.';
 $lang['ct_pf_desc2']  = 'D\'ici vous pouvez voir la liste noire du Bloqueur de Proxys et d\'Agents et vous pouvez supprimez des entrées du système de blocage, si nécessaire.';
 $lang['ct_pf_del']    = 'SUPPRIMER';

 // Configuration
 $lang['ct_conf_h']    = 'Configuration du CBACK CrackerTracker ';
 $lang['ct_conf_d']    = 'D\'ici vous pouvez gérer des fonctions supplémentaires de sécurité et les régler à votre convenance.';
 $lang['ct_conf_tb1']  = 'Limitation dynamique des entrées des fichiers-logs';
 $lang['ct_conf_tb2']  = 'Contrôle des modules de sécurité optionnels';
 $lang['ct_conf_tb3']  = 'Limitation de la recherche';
 $lang['ct_conf_tb4']  = 'Protection contre le Flood et le Spam';
 $lang['ct_conf_p1']   = 'Fichier-log Flood & Spam';
 $lang['ct_conf_d1']   = 'D\'ici vous pouvez sélectionner le nombre maximal d\'entrées dans le fichier-log Flood & Spam. Dans ce fichier, tous les utilisateurs ayant été bloqué à cause d\'un trop grand nombre de posts dans une courte période de temps, seront sauvegardés. Si ce compte est atteint, le fichier-log sera immédiatement nettoyé.';
 $lang['ct_conf_p2']   = 'Fichier-log de bloqueur d\'ID et d\'Agents';
 $lang['ct_conf_d2']   = 'D\'ici vous pouvez fixer le nombre maximal d\'entrées pour le fichier-log de bloqueur d\'ID et d\'Agents. Là, les requêtes bloquées des Agents et des IPs du point de configuration correspondant seront enregistrées. Si ce compte est atteint, le fichier-log sera immédiatement nettoyé.';
 $lang['ct_conf_p3']   = 'Bloqueur de Proxys & d\'Agents';
 $lang['ct_conf_d3']   = 'D\'ici vous pouvez activer ou désactiver l\'option de blocage des Proxys & Agents de CBACK CrackerTracker. Si ce module est désactivé, le système ignorera la liste noire dans le point de configuration correspondant.';
 $lang['ct_conf_p4']   = 'Protection Anti-Spam';
 $lang['ct_conf_d4']   = 'D\'ici vous pouvez activer ou désactiver le moteur de protection anti-spam, qui bloque un utilisateur en fixant un nombre limité de messages dans un temps donné.';
 $lang['ct_conf_p5']   = 'Protection contre le Flood à l\'enregistrement';
 $lang['ct_conf_d5']   = 'Cette option protège l\'enregistrement sur votre forum phpBB en plus de la confirmation visuelle. CrackerTracker teste les IPs cycliques (par ex: Bots de spam) à l\'enregistrement et peut créer une boucle d\'attente entre deux inscriptions pour ralentir les scripts de Flood.';
 $lang['ct_conf_p6']   = 'Bannissement automatique des Spammeurs';
 $lang['ct_conf_d6']   = 'Si cette option et l\'option "Protection Anti-Spam" sont activées, les utilisateurs seront bannis. Sinon, leur compte-utilisateur sera désactivé. <b>Avertissement :</b> Cette option doit être activée, car le bannissement par nom d\'utilisateur est la meilleure méthode. Il y a des forums où vous pouvez demander une nouvelle fois l\'e-mail d\'activation pour le compte.';
 $lang['ct_conf_p9']   = 'Nombre de recherches autorisé avant le blocage de la recherche';
 $lang['ct_conf_d9']   = 'D\'ici vous pouvez choisir combien de recherches les utilisateurs (enregistrés) peuvent exécuter l\'une après l\'autre, avant que la limitation de durée ne soit activée.';
 $lang['ct_conf_p10']  = 'Fonction de limitation de durée de la recherche';
 $lang['ct_conf_d10']  = 'D\'ici vous pouvez choisir (en secondes) combien de temps un utilisateur (enregistré) devra attendre, s\'il a atteint la limite de recherches autorisée, pour exécuter une recherche supplémentaire. (Evite le Flood à l\'aide de scripts)';
 $lang['ct_conf_p11']  = 'Limite de durée entre deux enregistrements sur le forum';
 $lang['ct_conf_d11']  = 'D\'ici vous pouvez choisir la durée (en secondes) entre deux inscriptions sur le forum. (Evite les surcharges sur le Serveur à l\'aide de scripts)';
 $lang['ct_conf_p12']  = 'Durée du nombre autorisé de messages';
 $lang['ct_conf_d12']  = 'D\'ici vous pouvez fixer la durée (en secondes) durant laquelle un utilisateur ne doit pas dépasser un nombre défini de messages. Sinon - si le moteur au-dessus est activé - il sera bloqué.';
 $lang['ct_conf_p13']  = 'Nombre de messages autorisés pendant la duré définie.';
 $lang['ct_conf_d13']  = 'D\'ici vous pouvez fixer le nombre de messages qu\'un utilisateur peut écrire durant la durée définie, avant que le système du CrackerTracker ne le considère comme un spammeur et qu\'il - si le moteur au-dessus est activé - soit bloqué.';
 $lang['ct_conf_p14']  = 'Vérification de l\'envoi de mails';
 $lang['ct_conf_d14']  = 'Si vous activez cette fonction, un utilisateur ne pourra envoyer un mail via le formulaire de mail phpBB que toutes les 4 Minutes.';
 $lang['ct_conf_p15']  = 'Vérification de la réinitialisation du mot de passe';
 $lang['ct_conf_d15']  = 'Si vous activez cette fonction CrackerTracker autorisera juste un seul envoi de nouveau mot de passe jusqu\'à ce que le mot de passe soit changé.';
 $lang['ct_conf_p16']  = 'Système de protection de la connexion';
 $lang['ct_conf_d16']  = 'Ici vous pouvez activer la confirmation visuelle durant la connexion afin de vous protéger contre les attaques Brute Force.';
 $lang['ct_conf_act']  = 'Activer';
 $lang['ct_conf_dact'] = 'Désactiver';

 // Logfile Manager
 $lang['ct_log_head']  = 'Gestionnaire des fichiers Logs du CBack CrackerTracker';
 $lang['ct_log_desc']  = 'D\'ici vous pouvez gérer, supprimer et vérifier les fichiers Logs du CBack CrackerTracker.';
 $lang['ct_log_cell1'] = 'Fichier-log';
 $lang['ct_log_cell2'] = 'Entrées';
 $lang['ct_log_cell3'] = 'Options';
 $lang['ct_log_f1']    = 'Fichier-log des Vers et des Attaques';
 $lang['ct_log_f2']    = 'Fichier-log du Bloqueur d\'IP et d\'Agents';
 $lang['ct_log_f3']    = 'Fichier-log Protection Anti-Spam';
 $lang['ct_log_l1']    = 'VOIR';
 $lang['ct_log_l2']    = 'SUPPRIMER';
 $lang['ct_log_l3']    = 'SUPPRIMER TOUS LES FICHIERS-LOGS';
 $lang['ct_log_gl']    = 'Fonctions globales';
 $lang['ct_log_gl1']   = 'CBACK CrackerTracker a bloqué <b>%s</b> Attaques sur le forum. Avec le lien suivant, vous pouvez supprimer tous les fichiers-logs en une seule fois. Le compteur restera inchangé.<br>';
 $lang['ct_log_back']  = '&laquo; RETOUR AU MENU';
 $lang['ct_log_tc1']   = 'Date/Heure';
 $lang['ct_log_tc2']   = 'IP';
 $lang['ct_log_tc3']   = 'Type d\'attaque';
 $lang['ct_log_tc4']   = 'Referrer';
 $lang['ct_log_tc5']   = 'Agent';
 $lang['ct_log_entr']  = 'Actuellement, il y a %s entrées dans le fichier-log.';
 $lang['ct_log_entr1'] = 'Actuellement, il y a 1 entrée dans le fichier-log.';

 // Language for parts into the Board itself
 $lang['ct_forum_sfl'] = 'Notre forum est protégé contre le Flood avec l\'option de recherche, aussi vous ne pouvez faire une recherche que toutes les %s secondes. Vous devez attendre %s secondes jusqu\'à votre prochaine recherche. Merci de votre compréhension.';
 $lang['ct_forum_rfl'] = 'Désolé, il vient d\'y avoir une inscription sur ce forum. Notre forum est protégé contre le Flood à l\'inscription (Robots-Spammeurs). Vous devez attendre %s secondes jusqu\'à ce qu\'une nouvelle inscription soit possible. Merci de votre compréhension.';
 $lang['ct_forum_ifl'] = 'Il semblerait que vous ayez créé un compte il y a peu de temps. Svp, vérifiez que vous soyez connecté normallement (cliquez sur "Connexion" si ce n\'est pas le cas) ou d\'avoir confirmé votre inscription, par exemple en ayant activé votre compte par E-Mail (vérifiez votre Boite E-mail).Merci de votre compréhension.';
 $lang['ct_forum_wa']  = '<b>AVERTISSEMENT!</b><br><br>Le système de protection contre le Spam du forum a détecté que le nombre maximum de messages autorisés, pendant une durée définie par l\'administrateur de ce forum, a été atteint. Si vous n\'êtes pas un robot de Spam, et que vous postez un autre message durant les %s prochaines secondes vous serez automatiquement banni !';
 $lang['ct_forum_blo'] = '<b>PROTECTION CONTRE LE SPAM DU CRACKERTRACKER</b><br><br>Vous avez atteint le nombre maximum de messages autorisés. Votre compte sur ce forum a été bloqué !';
?>
