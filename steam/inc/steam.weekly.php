<?php defined('COT_CODE') or die('Wrong URL');

// Render heading and game selector

$t = new XTemplate(cot_tplfile('steam.weekly', 'module'));

$stats_url = $appid == $cfg['steam']['defaultid'] ? cot_url('steam') : cot_url('steam', 'game='.$game);

$jumpbox = '';
foreach ($games as $key => $val)
{
	$selected = $key == $appid ? 'selected="selected"' : '';
	$url = $key == $cfg['steam']['defaultid'] ? cot_url('steam', 'm=weekly') : cot_url('steam', 'm=weekly&game='.$val['code']);
	$jumpbox .= '<option value="'.$url.'" '.$selected.' onclick="redirect(this)">'. htmlspecialchars($val['name']).'</option>';
}

$t->assign(array(
	'STEAM_GAME_NAME'        => htmlspecialchars($name),
	'STEAM_GAME_SELECTOR'    => $jumpbox,
	'STEAM_GLOBAL_STATS_URL' => $stats_url
));

$urlp = $appid == $cfg['steam']['defaultid'] ? array() : array('game' => $game);

// Render recent achievements

$totalitems = (int) $db->query("SELECT COUNT(DISTINCT sua_userid)
	FROM $db_steam_user_achievements
	WHERE sua_appid = ? AND sua_unlocked = 1 AND YEARWEEK(NOW()) = YEARWEEK(FROM_UNIXTIME(sua_unlock_date))", array((int)$appid))->fetchColumn();

$rowset = steam_get_scores($appid, "$d, {$cfg['maxrowsperpage']}", true);

$i = 0;
foreach ($rowset as $row)
{
	$t->assign(array(
		'STEAM_WEEKLY_ROW_RANK'         => $i + 1,
		'STEAM_WEEKLY_ROW_USER'         => cot_build_user($row['user_id'], $row['user_name']),
		'STEAM_WEEKLY_ROW_ACHIEVEMENTS' => htmlspecialchars($row['achievements']),
		'STEAM_WEEKLY_ROW_ODDEVEN'      => cot_build_oddeven($i)
	));
	$t->parse('MAIN.STEAM_WEEKLY_ROW');
	$i++;
}

$urlp['m'] = 'weekly';

// Render pagination

$pagenav = cot_pagenav('steam', $urlp, $d, $totalitems, $cfg['maxrowsperpage']);

$t->assign(array(
	'STEAM_WEEKLY_PAGENAV'      => $pagenav['main'],
	'STEAM_WEEKLY_PAGENAV_PREV' => $pagenav['prev'],
	'STEAM_WEEKLY_PAGENAV_NEXT' => $pagenav['next']
));

$t->parse();
$t->out();
