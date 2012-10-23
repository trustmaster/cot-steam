<?php defined('COT_CODE') or die('Wrong URL');

/* ====================
[BEGIN_COT_EXT]
Hooks=global
[END_COT_EXT]
==================== */

if ($sys['comingback'] === true && isset($usr['profile']['user_steamid']) && !empty($usr['profile']['user_steamid']))
{
	// User is coming back after idleness, let's sync him
	require_once cot_incfile('steam', 'module');
	steam_sync_user($usr);
}
