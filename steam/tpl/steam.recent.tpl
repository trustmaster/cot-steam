<!-- BEGIN: MAIN -->
<div class="block">
	<h2>{PHP.out.subtitle}</h2>

	<p>
		<div class="steam_gameselector">
			<select name="steamgame" id="steamGame">
				{STEAM_GAME_SELECTOR}
			</select>
		</div>
		<div class="steam_statrow">
			<a href="{STEAM_GLOBAL_STATS_URL}">{PHP.L.steam_view_global_stats}</a>
			<img src="" alt="" class="steam_stats_icon">
		</div>
	</p>

	<table class="cells">
		<tr>
			<td class="coltop">&nbsp;</td>
			<td class="coltop">{PHP.L.User}</td>
			<td class="coltop">{PHP.L.Description}</td>
			<td class="coltop">{PHP.L.Date}</td>
		</tr>
		<!-- BEGIN: STEAM_RECENT_ROW -->
		<tr>
			<td><img src="{STEAM_RECENT_ROW_ICON}" alt="" class="steam_icon"></td>
			<td>{STEAM_RECENT_ROW_USER}</td>
			<td>
				<h4>{STEAM_RECENT_ROW_NAME}</h4>
				{STEAM_RECENT_ROW_DESC}
			</td>
			<td>{STEAM_RECENT_ROW_DATE}</td>
		</tr>
		<!-- END: STEAM_RECENT_ROW -->
	</table>

	<p class="paging">{STEAM_RECENT_PAGENAV}</p>
</div>
<!-- END: MAIN -->