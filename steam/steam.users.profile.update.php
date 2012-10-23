<?php defined('COT_CODE') or die('Wrong URL');

/* ====================
[BEGIN_COT_EXT]
Hooks=users.profile.update.done
[END_COT_EXT]
==================== */

if (isset($ruser['user_steamid']) && !empty($ruser['user_steamid']))
{
	// User has updated his profile, let's sync him
	$usr['profile']['user_steamid'] = $ruser['user_steamid'];
	require_once cot_incfile('steam', 'module');
	steam_sync_user($usr);
}
