<?php
/***************************************************************************
 *                       lang_sig_control.php [French]
 *                            -------------------
 *   begin                : Sat May 28 2005
 *   copyright            : (C) 2005 -=ET=- http://www.golfexpert.net/phpbb
 *   email                : n/a
 *
 *   $Id: lang_sig_control.php, 1.0.0, 2005/05/28 00:00:00, -=ET=- Exp $
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

$lang['sig_settings'] = 'Options des Signatures';
$lang['sig_settings_explain'] = 'Attention : pour tous les champs num�riques (sauf la taille de police impos�e), saisir un "0" ou rien signifie "illimit�" !';

$lang['sig_max_lines'] = 'Nb de lignes maximum';
$lang['sig_wordwrap'] = 'Nb de caract�res maximum sans espace';
$lang['sig_allow_font_sizes'] = 'Taille des caract�res [size]';
$lang['sig_allow_font_sizes_yes'] = 'Libre';
$lang['sig_allow_font_sizes_max'] = 'Limit�e';
$lang['sig_allow_font_sizes_imposed'] = 'Impos�e';
$lang['sig_font_size_limit'] = 'Limites de la taille ou taille impos�e';
$lang['sig_font_size_limit_explain'] = 'phpBB ne g�re pas les tailles sup�rieures � 29. Par ailleurs si vous voulez imposer une taille, vous ne pouvez pas param�trer une taille inf�rieur � 7';
$lang['sig_min_font_size'] = 'min /';
$lang['sig_max_font_size'] = 'max ou taille impos�e';
$lang['sig_text_enhancement'] = 'Autoriser les enrichissements';
$lang['sig_allow_bold'] = 'Gras [b]';
$lang['sig_allow_italic'] = 'Italique [i]';
$lang['sig_allow_underline'] = 'Soulign� [u]';
$lang['sig_allow_colors'] = 'Couleurs de texte [color]';
$lang['sig_text_presentation'] = 'Autoriser les mises en forme';
$lang['sig_allow_quote'] = 'Citations [quote]';
$lang['sig_allow_code'] = 'Citations de code [code]';
$lang['sig_allow_list'] = 'Listes [list]';
$lang['sig_allow_url'] = 'Autoriser les urls [url]';
$lang['sig_allow_images'] = 'Autoriser les images [img]';
$lang['sig_max_images'] = 'Nombre maximum d\'images autoris�';
$lang['sig_max_img_size'] = 'Dimensions maximum des images';
$lang['sig_max_img_size_explain1'] = 'Le contr�le de la taille des images ne doit � priori pas poser de pb. N�anmoins, indiquez si une image n\'�tait pas contr�lable elle devrait �tre accept�e par d�faut ou refus�e';
$lang['sig_max_img_size_explain2'] = 'Le contr�le de la taille de certaines images est impossible sur ce forum (%s). Indiquez si les images qui ne peuvent pas �tre contr�l�es doivent �tre accept�es par d�faut ou refus�es';
$lang['sig_max_img_size_explain3'] = 'Le contr�le de la taille des images est � priori impossible sur ce forum (%s). Indiquez si les images qui ne peuvent pas �tre control�es doivent �tre accept�es par d�faut ou refus�es';
$lang['sig_img_size_legend'] = '(h x l)';
$lang['sig_allow_on_max_img_size_fail'] = 'Autoriser si contr�le impossible';
$lang['sig_max_img_files_size'] = 'Taille max. du total des fichiers image';
$lang['sig_max_img_av_files_size'] = 'Taille max. du total des fichiers image+avatar';
$lang['sig_max_img_av_files_size_explain'] = 'Si une valeur est saisie dans ce champ un contr�le global de la taille des fichiers image de la signature et de l\'avatar sera activ�, et les 2 contr�les s�par�s seront d�sactiv�s. Si aucune valeur n\'est saisie ou un 0, le contr�le global sera d�sactiv�.';
$lang['sig_Kbytes'] = 'Ko';
$lang['sig_exotic_bbcodes_disallowed'] = 'Interdire d\'autres BBCodes';
$lang['sig_exotic_bbcodes_disallowed_explain'] = 'Indiquer les autres BBCodes qui doivent �tre interdit (ex. : fade,php,shadow)';
$lang['sig_allow_smilies'] = 'Autoriser les smilies';
$lang['sig_reset'] = 'R�initialiser les signatures des membres';
$lang['sig_reset_explain'] = 'Efface les signatures des profils de <span style="color: #800000">tous les membres !</span> Cela permet de les obliger � les resaisir et donc � les faire valider';
$lang['sig_reset_confirm'] = 'Etes vous s�r de vouloir effacer les signatures de tous les membres ?';

$lang['sig_reset_successful'] = 'Les signatures de tous les membres ont �t� effac�es des profils avec succ�s !';
$lang['sig_reset_failed'] = 'Erreur : les signatures des membres n\'ont pas pu �tre effac�es.';

$lang['sig_config_error'] = 'Vos param�trages des signatures ne sont pas corrects.';
$lang['sig_config_error_int'] = 'Les donn�es saisies pour les champs suivant ne sont pas des nombres entiers positifs (ou les polices de caract�res demand�es sont sup�rieures � 29) :';
$lang['sig_config_error_min_max'] = 'Vous avez saisi des valeurs incoh�rentes pour les tailles de police minimum et maximum (min : %s / max : %s). La taille de police de caract�re maximum doit �tre plus grande que la taille minimum.';
$lang['sig_config_error_imposed'] = 'Vous avez s�lectionn� le fait que la taille de caract�re soit impos�e mais avec une taille de caract�re incorrecte (%). Le minimum est de 7 et le maximum de 29.';

$lang['sig_allow_signature'] = 'Peut afficher une signature';
$lang['sig_yes_not_controled'] = 'Oui sans contr�le';
$lang['sig_yes_controled'] = 'Oui contr�l�e';

$lang['sig_explain'] = 'Une signature est un petit texte qui peut �tre ajout� au bas des messages que vous postez.';
$lang['sig_explain_limits'] =  'Il est limit� � %s caract�res%s%s%s.';
$lang['sig_explain_max_lines'] = ' sur %s ligne(s)'; // Be careful to the space at the begining!
$lang['sig_explain_font_size_limit'] = ' (taille %s � %s)'; // Be careful to the space at the begining!
$lang['sig_explain_font_size_max'] = ' (taille %s maximum)'; // Be careful to the space at the begining!
$lang['sig_explain_no_image'] = ' et aucune image'; // Be careful to the space at the begining!
$lang['sig_explain_images_limit'] = ' et %s image(s) dont aucune ne peut d�passer %sx%s pixels, pour un total de %sKo maximum'; // Be careful to the space at the begining!
$lang['sig_explain_unlimited_images'] = ' et autant d\'images que vous voulez mais aucune ne peut d�passer %sx%s pixels et pour un total de %sKo maximum'; // Be careful to the space at the begining!
$lang['sig_explain_avatar_included'] = ', avatar inclus';
$lang['sig_explain_wordwrap'] = 'Dans le texte, pas plus de %s caract�res sans espace non plus.';

$lang['sig_BBCodes_are_OFF'] = 'Les BBCodes sont <u>D�sactiv�s</u>';
$lang['sig_bbcodes_on'] = '%sBBCodes%s activ�s : ';
$lang['sig_bbcodes_off'] = '%sBBCodes%s d�sactiv�s : ';
$lang['sig_none'] = 'aucun';
$lang['sig_all'] = 'tous';

$lang['sig_error'] = 'Votre signature n\'est pas conforme.';
$lang['sig_error_max_lines'] = 'Votre texte comprend %s lignes alors que %s seulement sont autoris�es.';
$lang['sig_error_wordwrap'] = 'Votre texte comprend %s suite(s) de plus de %s caract�res sans espace alors que c\'est interdit.';
$lang['sig_error_bbcode'] = 'Vous avez utilisez ce(s) BBCode(s) interdit(s) : %s';
$lang['sig_error_font_min'] = 'Vous avez utilis� la taille de caract�res %s alors que le minimum autoris� est de %s.';
$lang['sig_error_font_max'] = 'Vous avez utilis� la taille de caract�res %s alors que le maximum autoris� est de %s.';
$lang['sig_error_num_images'] = 'Vous avez utilis� %s images alors que le maximum autoris� est de %s.';
$lang['sig_error_images_size'] = 'L\'image %s est trop grande.<br />Sa taille est de %s pixels de haut et %s de large alors que le maximum autoris� par image est de %s en hauteur et %s en largeur.';
$lang['sig_unlimited'] = 'illimit�';
$lang['sig_error_images_size_control'] = 'Il est impossible de contr�ler la taille de cette image : %s<br />Soit il n\'y a pas d\'image � cette adresse, soit le forum n\'est pas en mesure de la contr�ler et vous ne pouvez donc pas l\'utiliser.';
$lang['sig_error_avatar_local'] = 'Ce fichier a un probl�me : %s<br />Il est impossible d\'en v�rifier sa taille.';
$lang['sig_error_avatar_url'] = 'Cette url doit �tre erron�e : %s<br />Il n\'y a pas d\'avatar � cette adresse.';
$lang['sig_error_img_files_size'] = 'Le taille totale des images utilis�es est de %sKo alors que le maximum autoris� est de %sKo.';
$lang['sig_error_img_av_files_size'] = 'Le taille totale des images utilis�es pour votre signature (%sKo) et votre avatar (%sKo) est sup�rieure aux %sKo autoris�s.';

?>