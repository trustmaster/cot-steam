<?php defined('COT_CODE') or die('Wrong URL');

/* ====================
[BEGIN_COT_EXT]
Hooks=global
[END_COT_EXT]
==================== */

if (isset($usr['profile']['user_steamid']) && !empty($usr['profile']['user_steamid']) && $usr['profile']['user_steamsynced'] < $sys['now'] - 12 * 3600)
{
	// User is coming back after idleness, let's sync him
	require_once cot_incfile('steam', 'module');
	if (steam_sync_user($usr) !== false)
	{
		$db->update($db_users, array('user_steamsynced' => $sys['now']), 'user_id = ' . $usr['id']);
	}
}
