<!-- BEGIN: MAIN -->
<div class="block">
	<h2>{STEAM_GAME_NAME} Weekly Scoreboard</h2>

	<p>
		<div class="steam_gameselector">
			<select name="steamgame" id="steamGame" onchange="redirect(this)">
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
			<td class="coltop">#</td>
			<td class="coltop">User</td>
			<td class="coltop">Achievements</td>
		</tr>
		<!-- BEGIN: STEAM_WEEKLY_ROW -->
		<tr>
			<td>{STEAM_WEEKLY_ROW_RANK}</td>
			<td>{STEAM_WEEKLY_ROW_USER}</td>
			<td>{STEAM_WEEKLY_ROW_ACHIEVEMENTS}</td>
		</tr>
		<!-- END: STEAM_WEEKLY_ROW -->
	</table>

	<p class="paging">{STEAM_WEEKLY_PAGENAV}</p>
</div>
<!-- END: MAIN -->