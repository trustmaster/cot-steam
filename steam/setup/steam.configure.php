<?php defined('COT_CODE') or die('Wrong URL');

// This script syncs game schema on plugin configuration update

require_once cot_incfile('steam', 'module');

steam_sync_games();