<?php defined('COT_CODE') or die('Wrong URL');

// Try to load from cache

if ($cache && $cfg['steam']['cachettl'] > 0)
{
	$steam_html = $cache->disk->get("main_$appid", 'steam', (int) $cfg['steam']['cachettl']);
}

if (is_null($steam_html))
{
	// Render heading and game selector

	$t = new XTemplate(cot_tplfile('steam', 'module'));

	$jumpbox = '';
	foreach ($games as $key => $val)
	{
		$selected = $key == $appid ? 'selected="selected"' : '';
		$url = $key == $cfg['steam']['defaultid'] ? cot_url('steam') : cot_url('steam', 'game='.$val['code']);
		$jumpbox .= '<option value="'.$url.'" '.$selected.' onclick="redirect(this)">'. htmlspecialchars($val['name']).'</option>';
	}

	$t->assign(array(
		'STEAM_GAME_NAME'     => htmlspecialchars($name),
		'STEAM_GAME_SELECTOR' => $jumpbox
	));

	$urlp = $appid == $cfg['steam']['defaultid'] ? array() : array('game' => $game);

	// Render recent achievements

	$rowset = steam_get_recent_achievements($appid, $cfg['steam']['recent']);

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
	$t->assign(array(
		'STEAM_RECENT_MORE_URL' => cot_url('steam', $urlp),
		'STEAM_RECENT_TITLE' => cot_rc('steam_title_recent', array('game' => $name))
	));

	// Render user ranks

	$rowset = steam_get_scores($appid, $cfg['steam']['ranks']);

	$i = 0;
	foreach ($rowset as $row)
	{
		$t->assign(array(
			'STEAM_RANKS_ROW_RANK'         => $i + 1,
			'STEAM_RANKS_ROW_USER'         => cot_build_user($row['user_id'], $row['user_name']),
			'STEAM_RANKS_ROW_ACHIEVEMENTS' => htmlspecialchars($row['achievements']),
			'STEAM_RANKS_ROW_ODDEVEN'      => cot_build_oddeven($i)
		));
		$t->parse('MAIN.STEAM_RANKS_ROW');
		$i++;
	}

	$urlp['m'] = 'score';
	$t->assign(array(
		'STEAM_RANKS_MORE_URL' => cot_url('steam', $urlp),
		'STEAM_RANKS_TITLE' => cot_rc('steam_title_score', array('game' => $name))
	));

	// Render weekly ranks

	$rowset = steam_get_scores($appid, $cfg['steam']['weekly'], true);

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
	$t->assign(array(
		'STEAM_WEEKLY_MORE_URL' => cot_url('steam', $urlp),
		'STEAM_WEEKLY_TITLE' => cot_rc('steam_title_weekly', array('game' => $name))
	));

	// Render totals per achievement

	$rowset = steam_get_totals($appid);

	$i = 0;
	foreach ($rowset as $row)
	{
		$t->assign(array(
			'STEAM_TOTAL_ROW_ICON'      => $row['icon'],
			'STEAM_TOTAL_ROW_NAME'      => htmlspecialchars($row['name']),
			'STEAM_TOTAL_ROW_DESC'      => htmlspecialchars($row['desc']),
			'STEAM_TOTAL_ROW_ACHIEVERS' => $row['achievers'],
			'STEAM_TOTAL_ROW_ODDEVEN'   => cot_build_oddeven($i)
		));
		$t->parse('MAIN.STEAM_TOTAL_ROW');
		$i++;
	}

	$t->assign(array(
		'STEAM_TOTAL_TITLE' => cot_rc('steam_title_achievements', array('game' => $name))
	));

	$t->parse();
	$steam_html = $t->text();

	if ($cache && $cfg['steam']['cachettl'] > 0)
	{
		$cache->disk->store("main_$appid", $steam_html, 'steam');
	}
}

echo $steam_html;