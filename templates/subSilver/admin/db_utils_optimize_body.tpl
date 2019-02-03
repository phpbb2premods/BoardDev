{SELECT_SCRIPT}

<h1>{L_DATABASE_OPTIMIZE}</h1>

<P>{L_OPTIMIZE_EXPLAIN}</p>

<form method="post" action="{S_DBUTILS_ACTION}" name="tablesForm">

<table cellspacing="1" cellpadding="4" border="0" align="center" class="forumline">

<tr>
	<th class="thHead" colspan="6">{L_OPTIMIZE_DB}</th>
</tr>	

<tr>
	<td class="catHead" align="center" valign="middle" colspan="6"><span class="cattitle">{L_CONFIGURATION}</span></td>
</tr>

<tr>
	<td class="row1" colspan="2">{L_ENABLE_CRON}:</td>
	<td class="row2" colspan="4"><input type="radio" name="enable_optimize_cron" value="1" {S_ENABLE_CRON_YES} /> {L_YES}&nbsp;&nbsp;<input type="radio" name="enable_optimize_cron" value="0" {S_ENABLE_CRON_NO} /> {L_NO}</td>
</tr>

<tr>
	<td class="row1" colspan="2">{L_CRON_EVERY}:</td>
	<td class="row2" colspan="4">
<!-- BEGIN sel_cron_every -->
		<select name="cron_every">
			<option value="2592000" {sel_cron_every.MONTH}>{sel_cron_every.L_MONTH}</option>
			<option value="1296000" {sel_cron_every.2WEEKS}>{sel_cron_every.L_2WEEKS}</option>
			<option value="604800" {sel_cron_every.WEEK}>{sel_cron_every.L_WEEK}</option>
			<option value="259200" {sel_cron_every.3DAYS}>{sel_cron_every.L_3DAYS}</option>
			<option value="86400" {sel_cron_every.DAY}>{sel_cron_every.L_DAY}</option>
			<option value="21600" {sel_cron_every.6HOURS}>{sel_cron_every.L_6HOURS}</option>
			<option value="3600" {sel_cron_every.HOUR}>{sel_cron_every.L_HOUR}</option>
			<option value="1800" {sel_cron_every.30MINUTES}>{sel_cron_every.L_30MINUTES}</option>
			<option value="20" {sel_cron_every.20SECONDS}>{sel_cron_every.L_20SECONDS}</option>
		</select>
<!-- END sel_cron_every -->
	</td>
</tr>

<tr>
	<td class="row1" colspan="2" valign="top">
		{L_CURRENT_TIME}:<br />
		{L_NEXT_CRON_ACTION}:<br />
		{L_PERFORMED_CRON}:
	</td>
	<td class="row2" colspan="4" valign="top">
		{CURRENT_TIME}<br />
		{NEXT_CRON}<br />
		{PERFORMED_CRON}
	</td>
</tr>

<tr>
	<td class="row1" colspan="2">{L_SHOW_NOT_OPTIMIZED}:</td>
	<td class="row2" colspan="4"><input type="radio" name="show_not_optimized" value="1" {S_ENABLE_NOT_OPTIMIZED_YES}/> {L_YES}&nbsp;&nbsp;<input type="radio" name="show_not_optimized" value="0" {S_ENABLE_NOT_OPTIMIZED_NO} /> {L_NO}</td>
</tr>

<tr>
	<td class="row1" colspan="2">{L_SHOW_BEGIN_FOR}:</td>
	<td class="row2" colspan="4"><input class="post" type="text" maxlength="255" name="show_begin_for" value="{S_SHOW_BEGIN_FOR}" /></td>
</tr>

<tr>
	<td class="row2" align="center" colspan="6"><input type="submit" name="configure" value="{L_CONFIGURE}" class="liteoption" />&nbsp;&nbsp;<input type="submit" name="reset" value="{L_RESET}" class="liteoption" onClick="document.tablesForm.show_begin_for.value=''" /></td>
</tr>	

<tr>
	<td colspan="6" height="1" class="spaceRow"><img src="../templates/subSilver/images/spacer.gif" alt="" width="1" height="1" /></td>
</tr>
	
<tr>
	<td class="catLeft" align="center" valign="middle"><span class="cattitle">{L_TABLE}</span></td>
	<td class="cat" align="center" valign="middle"><span class="cattitle">{L_RECORD}</span></td>
	<td class="cat" align="center" valign="middle"><span class="cattitle">{L_TYPE}</span></td>
	<td class="cat" align="center" valign="middle"><span class="cattitle">{L_SIZE}</span></td>
	<td class="cat" align="center" valign="middle"><span class="cattitle">{L_STATUS}</span></td>
	<td class="catRight" align="center" valign="middle"> &nbsp; &nbsp; </span></td>
</tr>	

<!-- BEGIN optimize -->
<tr>
	<td class="{optimize.ROW_CLASS}">{optimize.TABLE}</td>
	<td class="{optimize.ROW_CLASS}" align="right">{optimize.RECORD}</td>
	<td class="{optimize.ROW_CLASS}" align="center">{optimize.TYPE}</td>
	<td class="{optimize.ROW_CLASS}" align="right">{optimize.SIZE}</td>
	<td class="{optimize.ROW_CLASS}" align="center">{optimize.STATUS}</td>
	<td class="{optimize.ROW_CLASS}">{optimize.S_SELECT_TABLE}</td>
</tr>
<!-- END optimize -->

<tr>
	<td class="row3"><b>{TOT_TABLE}</b></td>
	<td class="row3" align="right"><b>{TOT_RECORD}</b></td>
	<td class="row3" align="center"><b>- -</b></td>
	<td class="row3" align="right"><b>{TOT_SIZE}</b></td>
	<td class="row3" align="center"><b>{TOT_STATUS}</b></td>
	<td class="row3">&nbsp;</td>
</tr>

<tr>
	<td class="row3" colspan="6" align="center">	
		<a href="#" onclick="setCheckboxes('tablesForm', true); return false;">{L_CHECKALL}</a>&nbsp;/&nbsp;<a href="#" onclick="setCheckboxes('tablesForm', false); return false;">{L_UNCHECKALL}</a>&nbsp;/&nbsp;<a href="#" onclick="setCheckboxes('tablesForm', 'invert'); return false;">{L_INVERTCHECKED}</a>	
	</td>
</tr>

<tr>
	<td class="catBottom" colspan="6" align="center">
	{S_HIDDEN_FIELDS}
	<input type="submit" name="optimize" value="{L_START_OPTIMIZE}" class="mainoption" />
	</td>
</tr>
</table>

</form>
