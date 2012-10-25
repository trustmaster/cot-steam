<!-- BEGIN: MAIN -->
<div class="block">
	<h2>{PHP.out.subtitle}</h2>

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

	<p class="paging">{STEAM_RANKS_PAGENAV}</p>
</div>
<!-- END: MAIN -->