<?php defined('COT_CODE') or die('Wrong URL');

// A new field containing last sync timestamp

global $db_users;

if ($db->query("SHOW COLUMNS FROM `$db_users` WHERE `Field` = 'user_steamsynced'")->rowCount() == 0)
{
	cot_extrafield_add($db_users, 'steamsynced', 'inputint', '<input class="text" type="hidden" />', '', '0',
		false, 'Text', 'Steam last sync timestamp', '', false);
}
