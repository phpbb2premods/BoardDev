<!-- BEGIN xs_file_version -->
/***************************************************************************
 *                              downloads.tpl
 *                              -------------
 *   copyright            : (C) 2003 - 2005 CyberAlien
 *   support              : http://www.phpbbstyles.com
 *
 *   version              : 2.3.1
 *
 *   file revision        : 72
 *   project revision     : 78
 *   last modified        : 05 Dec 2005  13:54:54
 *
 ***************************************************************************/
<!-- END xs_file_version -->

<h1>{L_XS_DOWNLOAD_STYLES}</h1>

<p>{L_XS_DOWNLOAD_EXPLAIN2}</p>

<table cellpadding="4" cellspacing="1" border="0" class="forumline" align="center" width="100%">
<tr>
	<th class="thHead" colspan="2">{L_XS_DOWNLOAD_LOCATIONS}</th>
</tr>
<!-- BEGIN url -->
<tr> 
	<td class="{url.ROW_CLASS}" align="left" nowrap="nowrap"><span class="gen">{url.NUM1}. {url.TITLE} [<a href="{url.U_DOWNLOAD}">{L_XS_CLICK_HERE_LC}</a>]</span></td>
	<td class="{url.ROW_CLASS}" align="left" nowrap="nowrap"><span class="gensmall">{url.URL} [<a href="{url.U_EDIT}">{L_XS_EDIT_LC}</a>]</span></td>
</tr>
<!-- END url -->
<!-- BEGIN edit -->
<tr>	
	<th colspan="2" class="thHead">{L_XS_EDIT_LINK}</th>
</tr>
<form action="{U_POST}" method="post">{S_HIDDEN_FIELDS}<input type="hidden" name="edit" value="{edit.ID}" />
<tr>
	<td class="row1" align="left">{L_XS_LINK_TITLE}:</td><td class="row2"><input type="text" class="post" name="edit_title" value="{edit.TITLE}" /></td>
</tr>
<tr>
	<td class="row1" align="left">{L_XS_LINK_URL}:</td><td class="row2"><input type="text" class="post" name="edit_url" value="{edit.URL}" /></td>
</tr>
<tr>
	<td class="row1" align="left">{L_XS_DELETE}:</td><td class="row2"><input type="checkbox" name="edit_delete" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></td>
</tr>
</form>
<!-- END edit -->
<tr>	
	<th colspan="2" class="thHead">{L_XS_ADD_LINK}</th>
</tr>
<form action="{U_POST}" method="post">{S_HIDDEN_FIELDS}
<tr>
	<td class="row1" align="left">{L_XS_LINK_TITLE}:</td><td class="row2"><input type="text" class="post" name="add_title" /></td>
</tr>
<tr>
	<td class="row1" align="left">{L_XS_LINK_URL}:</td><td class="row2"><input type="text" class="post" name="add_url" /></td>
</tr>
<tr>
	<td class="catBottom" colspan="2" align="center"><input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /></td>
</tr>
</form>
</table>
<br />

