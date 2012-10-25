<!-- BEGIN: MAIN -->
<div class="block">
	<h2>{PHP.out.subtitle}</h2>

	<p>
		<div class="steam_gameselector">
			<select name="steamgame" id="steamGame" onchange="redirect(this)">
				{STEAM_GAME_SELECTOR}
			</select>
		</div>
	</p>

	<h3>{STEAM_RECENT_TITLE}</h3>
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
	<a href="{STEAM_RECENT_MORE_URL}">{PHP.L.steam_view_all_recent}</a>

	<hr />

	<h3>{STEAM_RANKS_TITLE}</h3>
	<table class="cells">
		<tr>
			<td class="coltop">#</td>
			<td class="coltop">{PHP.L.User}</td>
			<td class="coltop">{PHP.L.steam_achievements}</td>
		</tr>
		<!-- BEGIN: STEAM_RANKS_ROW -->
		<tr>
			<td>{STEAM_RANKS_ROW_RANK}</td>
			<td>{STEAM_RANKS_ROW_USER}</td>
			<td>{STEAM_RANKS_ROW_ACHIEVEMENTS}</td>
		</tr>
		<!-- END: STEAM_RANKS_ROW -->
	</table>
	<a href="{STEAM_RANKS_MORE_URL}">{PHP.L.steam_view_all_ranks}</a>

	<hr />

	<h3>{STEAM_WEEKLY_TITLE}</h3>
	<table class="cells">
		<tr>
			<td class="coltop">#</td>
			<td class="coltop">{PHP.L.User}</td>
			<td class="coltop">{PHP.L.steam_achievements}</td>
		</tr>
		<!-- BEGIN: STEAM_WEEKLY_ROW -->
		<tr>
			<td>{STEAM_WEEKLY_ROW_RANK}</td>
			<td>{STEAM_WEEKLY_ROW_USER}</td>
			<td>{STEAM_WEEKLY_ROW_ACHIEVEMENTS}</td>
		</tr>
		<!-- END: STEAM_WEEKLY_ROW -->
	</table>
	<a href="{STEAM_WEEKLY_MORE_URL}">{PHP.L.steam_view_weekly}</a>

	<hr />

	<h3>{STEAM_TOTAL_TITLE}</h3>
	<table class="cells">
		<tr>
			<td class="coltop">&nbsp;</td>
			<td class="coltop">{PHP.L.Title}</td>
			<td class="coltop">{PHP.L.Description}</td>
			<td class="coltop">{PHP.L.steam_achievers}</td>
		</tr>
		<!-- BEGIN: STEAM_TOTAL_ROW -->
		<tr>
			<td><img src="{STEAM_TOTAL_ROW_ICON}" alt="" class="steam_icon"></td>
			<td>{STEAM_TOTAL_ROW_NAME}</td>
			<td>{STEAM_TOTAL_ROW_DESC}</td>
			<td>{STEAM_TOTAL_ROW_ACHIEVERS}</td>
		</tr>
		<!-- END: STEAM_TOTAL_ROW -->
	</table>

</div>
<!-- END: MAIN -->
