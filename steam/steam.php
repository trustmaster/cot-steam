<?php defined('COT_CODE') or die('Wrong URL');

/* ====================
[BEGIN_COT_EXT]
Hooks=module
[END_COT_EXT]
==================== */

require_once cot_incfile('steam', 'module');

// Environment setup
define('COT_STEAM', TRUE);
$env['location'] = 'steam';

// Common params
$game = cot_import('game', 'G', 'ALP');
$user = cot_import('user', 'G', 'TXT');

// Pagination params
list($pg, $d, $durl) = cot_import_pagenav('d', $cfg['maxrowsperpage']);

// Check if game is a registered one
$games = steam_get_games();
$appid = 0;
if (!empty($game))
{
	$found = false;
	foreach ($games as $key => $val)
	{
		if ($val['code'] == $game)
		{
			$found = true;
			$appid = $key;
			$name = $val['name'];
			break;
		}
	}
	if (!$found)
	{
		// Wrong game
		cot_die_message(404);
	}
}
else
{
	// Default game
	$appid = (int) $cfg['steam']['defaultid'];
	$game = $games[$appid]['code'];
	$name = $games[$appid]['name'];
}

if (!empty($user))
{
	// Display personal achievements
	$out['subtitle'] = cot_rc('steam_title_user', array('user' => $user, 'game' => $name));
	require_once $cfg['system_dir'].'/header.php';
	require_once cot_incfile('steam', 'module', 'user');
}
elseif ($m == 'recent')
{
	// Recent achievements
	$out['subtitle'] = cot_rc('steam_title_recent', array('game' => $name));
	require_once $cfg['system_dir'].'/header.php';
	require_once cot_incfile('steam', 'module', 'recent');
}
elseif ($m == 'score')
{
	// Scoreboard
	$out['subtitle'] = cot_rc('steam_title_score', array('game' => $name));
	require_once $cfg['system_dir'].'/header.php';
	require_once cot_incfile('steam', 'module', 'score');
}
elseif ($m == 'weekly')
{
	// Weekly scoreboard
	$out['subtitle'] = cot_rc('steam_title_weekly', array('game' => $name));
	require_once $cfg['system_dir'].'/header.php';
	require_once cot_incfile('steam', 'module', 'weekly');
}
else
{
	// Display global stats
	$out['subtitle'] = cot_rc('steam_title_main', array('game' => $name));
	require_once $cfg['system_dir'].'/header.php';
	require_once cot_incfile('steam', 'module', 'main');
}

require_once $cfg['system_dir'].'/footer.php';