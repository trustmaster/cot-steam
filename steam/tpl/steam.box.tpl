<!-- BEGIN: MAIN -->
<div class="block">
	<h2>{PHP.L.steam_my_activity}</h2>

	<!-- BEGIN: STEAMBOX -->

	<p>{STEAMBOX_PLAYED_HOURS} {PHP.L.steam_hours_on_record}</p>

	<p>
		{STEAMBOX_ACHIEVEMENTS_UNLOCKED} {PHP.L.Of} {STEAMBOX_ACHIEVEMENTS_TOTAL} {PHP.L.steam_achievements_earned}

		<div class="steambox_bar_outer">
			<div class="steambox_bar_inner" style="width:{STEAMBOX_ACHIEVEMENTS_PERCENT}%">&nbsp;</div>
		</div>

		<div class="steambox_icons">
			<!-- BEGIN: STEAMBOX_ITEM -->
			<img src="{STEAMBOX_ITEM_ICON}" alt="{STEAMBOX_ITEM_NAME}" class="steambox_icon">
			<!-- END: STEAMBOX_ITEM -->
			<!-- BEGIN: STEAMBOX_MORE_ITEMS -->
			<div class="steambox_more_count">+{STEAMBOX_MORE_ITEMS_COUNT}</div>
			<!-- END: STEAMBOX_MORE_ITEMS -->
		</div>
	</p>

	<p>
		<div class="steambox_statrow">
			<a href="{STEAMBOX_PERSONAL_STATS_URL}">{PHP.L.steam_view_your_stats}</a>
			<img src="" alt="" class="steambox_stats_icon">
		</div>
		<div class="steambox_statrow">
			<a href="{STEAMBOX_GLOBAL_STATS_URL}">{PHP.L.steam_view_global_stats}</a>
			<img src="" alt="" class="steambox_stats_icon">
		</div>
	</p>

	<!-- END: STEAMBOX -->

	<!-- BEGIN: STEAMBOX_UNAVAILABLE -->
	<p>
		Run, run, run! Link your Steam account <a href="{PHP|cot_url('users','m=profile')}">your profile</a> and start participating in achievement charts!
	</p>
	<!-- END: STEAMBOX_UNAVAILABLE -->

</div>
<!-- END: MAIN -->
