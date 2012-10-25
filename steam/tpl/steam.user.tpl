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

	<!-- BEGIN: STEAM -->

	<p>{STEAM_PLAYED_HOURS} hours on record, {STEAM_HOURS_LAST2WEEKS} hours during last 2 weeks.</p>

	<p>
		{STEAM_ACHIEVEMENTS_UNLOCKED} of {STEAM_ACHIEVEMENTS_TOTAL} Achievements Earned

		<div class="steam_bar_outer">
			<div class="steam_bar_inner" style="width:{STEAM_ACHIEVEMENTS_PERCENT}%">&nbsp;</div>
		</div>
	</p>

	<div class="steam_achievements">
		<div class="steam_achievements_unlocked">
			<!-- BEGIN: STEAM_ITEM_UNLOCKED -->
			<div class="steam_achievement">
				<img src="{STEAM_ITEM_UNLOCKED_ICON}" alt="" class="steam_icon">
				<h4>{STEAM_ITEM_UNLOCKED_NAME}</h4>
				<span class="steam_achievement_date">Unlocked {STEAM_ITEM_UNLOCKED_DATE}</span>
				<span class="steam_achievement_desc">{STEAM_ITEM_UNLOCKED_DESC}</span>
			</div>
			<!-- END: STEAM_ITEM_UNLOCKED -->
		</div>
		<div class="steam_achievements_locked">
			<!-- BEGIN: STEAM_ITEM_LOCKED -->
			<div class="steam_achievement">
				<img src="{STEAM_ITEM_LOCKED_ICON}" alt="" class="steam_icon">
				<h4>{STEAM_ITEM_LOCKED_NAME}</h4>
				<span class="steam_achievement_desc">{STEAM_ITEM_LOCKED_DESC}</span>
			</div>
			<!-- END: STEAM_ITEM_LOCKED -->
		</div>
	</div>

	<!-- END: STEAM -->

	<!-- BEGIN: STEAM_UNAVAILABLE -->
	<p>
		This user does not have Steam statistics available for this game.
	</p>
	<!-- END: STEAM_UNAVAILABLE -->

</div>
<!-- END: MAIN -->
