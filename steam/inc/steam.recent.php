<?php defined('COT_CODE') or die('Wrong URL');

// Render heading and game selector

$t = new XTemplate(cot_tplfile('steam.recent', 'module'));

$stats_url = $appid == $cfg['steam']['defaultid'] ? cot_url('steam') : cot_url('steam', 'game='.$game);

$jumpbox = '';
foreach ($games as $key => $val)
{
	$selected = $key == $appid ? 'selected="selected"' : '';
	$url = $key == $cfg['steam']['defaultid'] ? cot_url('steam', 'm=recent') : cot_url('steam', 'm=recent&game='.$val['code']);
	$jumpbox .= '<option value="'.$url.'" '.$selected.' onclick="redirect(this)">'. htmlspecialchars($val['name']).'</option>';
}

$t->assign(array(
	'STEAM_GAME_NAME'        => htmlspecialchars($name),
	'STEAM_GAME_SELECTOR'    => $jumpbox,
	'STEAM_GLOBAL_STATS_URL' => $stats_url
));

$urlp = $appid == $cfg['steam']['defaultid'] ? array() : array('game' => $game);

// Render recent achievements

$totalitems = (int) $db->query("SELECT COUNT(*)
	FROM $db_steam_user_achievements
	WHERE sua_appid = ? AND sua_unlocked = 1", array((int)$appid))->fetchColumn();

$rowset = steam_get_recent_achievements($appid, "$d, {$cfg['maxrowsperpage']}");

$i = 0;
foreach ($rowset as $row)
{
	$t->assign(array(
		'STEAM_RECENT_ROW_ICON'    => $row['icon'],
		'STEAM_RECENT_ROW_USER'    => cot_build_user($row['user_id'], $row['user_name']),
		'STEAM_RECENT_ROW_NAME'    => htmlspecialchars($row['name']),
		'STEAM_RECENT_ROW_DESC'    => htmlspecialchars($row['desc']),
		'STEAM_RECENT_ROW_DATE'    => cot_date('datetime_full', $row['date']),
		'STEAM_RECENT_ROW_ODDEVEN' => cot_build_oddeven($i)
	));
	$t->parse('MAIN.STEAM_RECENT_ROW');
	$i++;
}

$urlp['m'] = 'recent';

// Render pagination

$pagenav = cot_pagenav('steam', $urlp, $d, $totalitems, $cfg['maxrowsperpage']);

$t->assign(array(
	'STEAM_RECENT_PAGENAV'      => $pagenav['main'],
	'STEAM_RECENT_PAGENAV_PREV' => $pagenav['prev'],
	'STEAM_RECENT_PAGENAV_NEXT' => $pagenav['next']
));

$t->parse();
$t->out();
