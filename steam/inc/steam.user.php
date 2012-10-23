<?php defined('COT_CODE') or die('Wrong URL');

$urr = $db->query("SELECT * FROM $db_users WHERE user_name = ?", array($user))->fetch();
if (!$urr)
{
	// Wrong user
	cot_die_message(404);
}

$t = new XTemplate(cot_tplfile('steam.user', 'module'));

$stats_url = $appid == $cfg['steam']['defaultid'] ? cot_url('steam') : cot_url('steam', 'game='.$game);

$jumpbox = '';
foreach ($games as $key => $val)
{
	$selected = $key == $appid ? 'selected="selected"' : '';
	$url = $key == $cfg['steam']['defaultid'] ? cot_url('steam', 'user='.$user) : cot_url('steam', 'user='.$user.'&game='.$val['code']);
	$jumpbox .= '<option value="'.$url.'" '.$selected.' onclick="redirect(this)">'. htmlspecialchars($val['name']).'</option>';
}

$t->assign(array(
	'STEAM_USERNAME'         => htmlspecialchars($urr['user_name']),
	'STEAM_GAME_NAME'        => htmlspecialchars($name),
	'STEAM_GAME_SELECTOR'    => $jumpbox,
	'STEAM_GLOBAL_STATS_URL' => $stats_url
));

if (empty($urr['user_steamid']))
{
	$t->parse('MAIN.STEAM_UNAVAILABLE');
}
else
{
	// Render stats
	$hours = steam_get_hours($urr['user_id'], $appid);
	$counters = steam_get_achievement_counts($urr['user_id'], $appid);
	$t->assign(array(
		'STEAM_PLAYED_HOURS'          => $hours['onrecord'],
		'STEAM_HOURS_LAST2WEEKS'      => $hours['last2weeks'],
		'STEAM_ACHIEVEMENTS_UNLOCKED' => $counters['unlocked'],
		'STEAM_ACHIEVEMENTS_LOCKED'   => $counters['locked'],
		'STEAM_ACHIEVEMENTS_TOTAL'    => $counters['total'],
		'STEAM_ACHIEVEMENTS_PERCENT'  => $counters['percent']
	));

	// Render items
	$achievements = steam_get_user_achievements($urr['user_id'], $appid, 'sua_unlocked DESC');

	$current_state = $counters['unlocked'] > 0 ? 1 : 0;
	$current_type = $current_state ? 'UNLOCKED' : 'LOCKED';

	$i = 0;
	foreach ($achievements as $row)
	{
		if ($current_state && $row['state'] == 0)
		{
			// There go locked ones
			$current_state = 0;
			$current_type = 'LOCKED';
		}
		$t->assign(array(
			"STEAM_ITEM_{$current_type}_ICON"    => $current_state ? $row['icon'] : $row['icongray'],
			"STEAM_ITEM_{$current_type}_NAME"    => htmlspecialchars($row['name']),
			"STEAM_ITEM_{$current_type}_DESC"    => htmlspecialchars($row['desc']),
			"STEAM_ITEM_{$current_type}_ODDEVEN" => cot_build_oddeven($i)
		));
		if ($current_state)
		{
			$t->assign('STEAM_ITEM_UNLOCKED_DATE', cot_date('datetime_full', $row['date']));
		}
		$t->parse("MAIN.STEAM.STEAM_ITEM_{$current_type}");
		$i++;
	}

	$t->parse('MAIN.STEAM');
}

$t->parse();
$t->out();
