<!-- BEGIN xs_file_version -->
/***************************************************************************
 *                             export_data2.tpl
 *                             ----------------
 *   copyright            : (C) 2003 - 2005 CyberAlien
 *   support              : http://www.phpbbstyles.com
 *
 *   version              : 2.3.1
 *
 *   file revision        : 55
 *   project revision     : 78
 *   last modified        : 05 Dec 2005  13:54:54
 *
 ***************************************************************************/
<!-- END xs_file_version -->

<h1>{L_XS_EXPORT_STYLES_DATA}</h1>

<p>{L_XS_EXPORT_STYLES_DATA_EXPLAIN2}<br /><br />{L_XS_EXPORT_STYLES_DATA_EXPLAIN3}</p>


<form action="{U_ACTION}" method="post">{S_HIDDEN_FIELDS}<input type="hidden" name="export" value="{EXPORT}" /><input type="hidden" name="export_total" value="{TOTAL}" />
<table cellpadding="4" cellspacing="1" border="0" class="forumline" align="center">
<tr>
	<th class="thHead" colspan="2">{L_XS_EXPORT_STYLES_DATA}</th>
</tr>
<tr>
	<td class="catLeft" align="center"><span class="gen">{L_XS_STYLES}</span></td>
	<td class="catRight" align="center"><span class="gen">{L_XS_SELECT}</span></td>
</tr>
<!-- BEGIN styles -->
<tr> 
	<td class="{styles.ROW_CLASS}" align="left" valign="middle"><span class="gen">{styles.STYLE}</span></td>
	<td class="{styles.ROW_CLASS}" align="left" valign="middle"><span class="gen"><input type="checkbox" name="export_check_{styles.NUM}" checked="checked" /><input type="hidden" name="export_id_{styles.NUM}" value="{styles.ID}" /></span></td>
</tr>
<!-- END styles -->
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></td>
</tr>
</table>
</form>
<br />