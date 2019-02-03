
<h1>{L_REQ_TITLE}</h1>

<p>{L_REQ_EXPLAIN}</p>

<form action="{REQ_ACTION}" method="post">
<!-- BEGIN exe -->
<table width="99%" cellpadding="4" cellspacing="1" border="0" class="forumline">
	<tr>
		<th class="thHead" align="center">{L_REQ_EXE}</th>
	</tr>
	<tr>
		<td class="code">{REQ_EXE}</td>
	</tr>
</table>
<br />
<!-- END exe -->

<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<th class="thHead" colspan="2">{L_REQ_TITLE}</th>
	</tr>
	<tr>
		<td valign="top" class="row1" width="160">&nbsp;{L_REQ_PREFIX}:</td>
		<td class="row2">&nbsp;{REQ_PREFIX}</td>
	</tr>
	<tr>
		<td valign="top" class="row1" width="160">&nbsp;{L_REQ_BDD}:</td>
		<td class="row2">&nbsp;{REQ_BDD}</td>
	</tr>
	<tr>
		<td valign="top" class="row1" width="160"><br />&nbsp;{L_REQ_ENTER}:</td>
		<td class="row2">&nbsp;<textarea name="requete" cols="70" rows="10">{REQ_ENTER}</textarea></td>
	</tr>
	<tr>
		<td align="center" class="row1" colspan="2"><input type="submit" class="mainoption" name="submit_requete" value="{L_SUBMIT}" /></td>
	</tr>
</table>

<!-- BEGIN req -->
<br />
<table width="99%" cellpadding="4" cellspacing="1" border="0" align="center" class="forumline">
	<tr>
		<th class="thHead" align="center" colspan="{COLSPAN}">{L_RESULT}</th>
	</tr>
	<tr>
		<td class="row2" colspan="{COLSPAN}"><b>{L_OPTION}:&nbsp;&nbsp;&nbsp;{TYPE_TEXTE}</b></td>
	</tr>
	<!-- BEGIN l -->
	<tr>
		<!-- BEGIN c -->
		<td align="center" class="{req.l.c.CLASSE}">{req.l.c.VALEUR}</td>
		<!-- END c -->
	</tr>
	<!-- END l -->
	<!-- BEGIN none -->
	<tr>
		<td class="row2" colspan="{COLSPAN}" align="center">{L_NONE}</td>
	</tr>
	<!-- END none -->
	<tr>
		<td class="row1" align="left" colspan="{COLSPAN}">{PAGINATION}&nbsp;</td>
	</tr>
</table>
<!-- END req -->
</form>