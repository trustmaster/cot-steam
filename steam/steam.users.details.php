<?php defined('COT_CODE') or die('Wrong URL');

/* ====================
[BEGIN_COT_EXT]
Hooks=users.details.tags
Tags=users.details.tpl:{USERS_DETAILS_STEAMBOX}
[END_COT_EXT]
==================== */

require_once cot_incfile('steam', 'module');

$tt = new XTemplate(cot_tplfile('steam.box', 'module'));

if (empty($urr['user_steamid']))
{
	$tt->parse('MAIN.STEAMBOX_UNAVAILABLE');
}
else
{
	$steambox_appid = $cfg['steam']['defaultid'];

	$steambox_hours = steam_get_hours($urr['user_id'], $steambox_appid);
	$steambox_played_hours = $steambox_hours['onrecord'];

	$steambox_counters = steam_get_achievement_counts($urr['user_id'], $steambox_appid);

	if ($steambox_counters['unlocked'] > 0)
	{
		// Show 6 random achievement icons
		$steambox_achievements = steam_get_user_achievements($urr['user_id'], $steambox_appid, 'RAND()', '6', true);

		foreach ($steambox_achievements as $row)
		{
			$tt->assign(array(
				'STEAMBOX_ITEM_ICON' => $row['icon'],
				'STEAMBOX_ITEM_NAME' => htmlspecialchars($row['name']),
				'STEAMBOX_ITEM_DESC' => htmlspecialchars($row['desc'])
			));
			$tt->parse('MAIN.STEAMBOX.STEAMBOX_ITEM');
		}
		// Show +N icon if necessary
		if ($steambox_counters['unlocked'] > 6)
		{
			$tt->assign('STEAMBOX_MORE_ITEMS_COUNT', $steambox_counters['unlocked'] - 6);
			$tt->parse('MAIN.STEAMBOX.STEAMBOX_MORE_ITEMS');
		}
	}

	$tt->assign(array(
		'STEAMBOX_PLAYED_HOURS'          => $steambox_played_hours,
		'STEAMBOX_ACHIEVEMENTS_UNLOCKED' => $steambox_counters['unlocked'],
		'STEAMBOX_ACHIEVEMENTS_LOCKED'   => $steambox_counters['locked'],
		'STEAMBOX_ACHIEVEMENTS_TOTAL'    => $steambox_counters['total'],
		'STEAMBOX_ACHIEVEMENTS_PERCENT'  => $steambox_counters['percent'],
		'STEAMBOX_PERSONAL_STATS_URL'    => cot_url('steam', 'user='.$urr['user_name']),
		'STEAMBOX_GLOBAL_STATS_URL'      => cot_url('steam')
	));
	$tt->parse('MAIN.STEAMBOX');
}

$tt->parse();
$t->assign('USERS_DETAILS_STEAMBOX', $tt->text());
