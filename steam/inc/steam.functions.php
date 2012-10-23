<?php defined('COT_CODE') or die('Wrong URL');

/**
 * Steam API for Cotonti
 *
 * @package Steam
 * @author Vladimir Sibirov <trustmaster@kodigy.com>
 * @license http://opensource.org/licenses/bsd-license.php
 */

// Disable XML warnings
libxml_use_internal_errors(true);

// Requirements
require_once $cfg['modules_dir'] . '/steam/lib/steam/SteamGame.php';
require_once $cfg['modules_dir'] . '/steam/lib/steam/SteamUser.php';

require_once cot_langfile('steam', 'module');

// Globals
global $db_steam_achievement_types, $db_steam_user_hours, $db_steam_user_achievements, $db_x;
$db_steam_achievement_types = (isset($db_steam_achievement_types)) ? $db_steam_achievement_types : $db_x . 'steam_achievement_types';
$db_steam_user_achievements = (isset($db_steam_user_achievements)) ? $db_steam_user_achievements : $db_x . 'steam_user_achievements';
$db_steam_user_hours = (isset($db_steam_user_hours)) ? $db_steam_user_hours : $db_x . 'steam_user_hours';

/**
 * Returns achievement counters
 * @param  integer $userid User ID
 * @param  integer $appid  AppID
 * @return array           Achievement counters: ['total'], ['locked'], ['unlocked'], ['percent']
 */
function steam_get_achievement_counts($userid, $appid)
{
	global $db, $db_steam_user_achievements;

	$total = (int) $db->query("SELECT COUNT(*) FROM $db_steam_user_achievements
			WHERE sua_userid = ? AND sua_appid = ?",
			array((int)$userid, (int)$appid))
		->fetchColumn();

	if ($total == 0)
	{
		return false;
	}

	$unlocked = (int) $db->query("SELECT COUNT(*) FROM $db_steam_user_achievements
			WHERE sua_userid = ? AND sua_appid = ? AND sua_unlocked = 1",
			array((int)$userid, (int)$appid))
		->fetchColumn();

	$locked = $total - $unlocked;
	$percent = round($unlocked * 100 / $total);

	return array(
		'total'    => $total,
		'unlocked' => $unlocked,
		'locked'   => $locked,
		'percent'  => $percent
	);
}

/**
 * Loads target games from module configuration
 * @return array Tracked game IDs and names as an array
 */
function steam_get_games()
{
	global $cfg;
	$res = array();
	$games = preg_split('#\r?\n#', $cfg['steam']['games']);
	foreach ($games as $game)
	{
		$conf = array_map('trim', explode('|', $game));
		$appid = (int) $conf[0];
		$res[$appid] = array(
			'appid' => $appid,
			'code'  => $conf[1],
			'name'  => $conf[2]
		);
	}
	return $res;
}

/**
 * Returns the number of hours a user has played a game
 * @param  integer $userid User ID
 * @param  integer $appid  AppID
 * @return array           An array of float with 2 keys: ['onrecord'] and ['last2weeks']
 */
function steam_get_hours($userid, $appid)
{
	global $db, $db_steam_user_hours;
	$row = $db->query("SELECT *
			FROM $db_steam_user_hours
			WHERE suh_userid = ? AND suh_appid = ?",
			array((int)$userid, (int)$appid))
		->fetch();
	return array(
		'onrecord'   => round($row['suh_onrecord'], 1),
		'last2weeks' => round($row['suh_last2weeks'], 1)
	);
}

/**
 * Returns recent achievements rowset among all users
 * @param  integer $appid    AppID
 * @param  string  $limit    Optional limit criteria
 * @return array             Rowset with keys per item: apiname, name, desc, icon, icongray, state, date, user_id, user_name
 */
function steam_get_recent_achievements($appid, $limit = '')
{
	global $db, $db_steam_user_achievements, $db_steam_achievement_types, $db_users;

	if (!empty($limit))
	{
		$limit = "LIMIT $limit";
	}

	$res = $db->query("SELECT t.sat_apiname AS `apiname`, t.sat_name AS `name`,
			t.sat_desc AS `desc`, t.sat_icon AS `icon`, t.sat_icongray AS `icongray`, a.sua_unlocked AS `state`, a.sua_unlock_date AS `date`,
			u.user_id AS `user_id`, u.user_name AS `user_name`
		FROM $db_steam_user_achievements AS a
			LEFT JOIN $db_steam_achievement_types AS t
				ON a.sua_appid = t.sat_appid AND a.sua_apiname = t.sat_apiname
			LEFT JOIN $db_users AS u
				ON a.sua_userid = u.user_id
		WHERE sua_appid = ? AND sua_unlocked = 1
		ORDER BY sua_unlock_date DESC
		$limit", array((int)$appid));

	return $res->fetchAll();
}

/**
 * Returns array of unlocked user achievements
 * @param  integer $userid   User ID
 * @param  integer $appid    AppID
 * @param  string  $order_by Optional order criteria
 * @param  string  $limit    Optional limit criteria
 * @return array             Rowset with keys per item: apiname, name, desc, icon, icongray, state, date
 */
function steam_get_user_achievements($userid, $appid, $order_by = '', $limit = '', $unlocked_only = false)
{
	global $db, $db_steam_user_achievements, $db_steam_achievement_types;

	if (!empty($order_by))
	{
		$order_by = "ORDER BY $order_by";
	}

	if (!empty($limit))
	{
		$limit = "LIMIT $limit";
	}

	$unlocked = $unlocked_only ? "AND sua_unlocked = 1" : '';

	$res = $db->query("SELECT t.sat_apiname AS `apiname`, t.sat_name AS `name`,
			t.sat_desc AS `desc`, t.sat_icon AS `icon`, t.sat_icongray AS `icongray`, a.sua_unlocked AS `state`, a.sua_unlock_date AS `date`
		FROM $db_steam_user_achievements AS a
			LEFT JOIN $db_steam_achievement_types AS t
				ON a.sua_appid = t.sat_appid AND a.sua_apiname = t.sat_apiname
		WHERE sua_userid = ? AND sua_appid = ? $unlocked
		$order_by
		$limit", array((int)$userid, (int)$appid));

	return $res->fetchAll();
}

/**
 * Loads users and their achievements ranks in descending order
 * @param  integer $appid AppID
 * @param  string  $limit Optional limit criteria
 * @return array          Rowset containing keys: user_id, user_name, achievements
 */
function steam_get_scores($appid, $limit = '', $weekly = false)
{
	global $db, $db_steam_user_achievements, $db_users;

	if (!empty($limit))
	{
		$limit = "LIMIT $limit";
	}

	if ($weekly)
	{
		$where_weekly = "AND YEARWEEK(NOW()) = YEARWEEK(FROM_UNIXTIME(sua_unlock_date))";
	}

	$res = $db->query("SELECT u.user_id, u.user_name, SUM(a.sua_unlocked) AS `achievements`
		FROM $db_users AS u
			LEFT JOIN $db_steam_user_achievements AS a
				ON a.sua_userid = u.user_id
		WHERE sua_appid = ? AND sua_unlocked = 1 $where_weekly
		GROUP BY sua_userid, sua_appid
		ORDER BY `achievements` DESC
		$limit", array((int) $appid));

	return $res->fetchAll();
}

/**
 * Returns total numbers of achievers per game achievement
 * @param  integer $appid AppID
 * @return array          Rowset containing keys: name, desc, icon, achievers
 */
function steam_get_totals($appid)
{
	global $db, $db_steam_user_achievements, $db_steam_achievement_types;

	$res = $db->query("SELECT t.sat_name AS `name`, t.sat_desc AS `desc`, t.sat_icon AS `icon`, SUM(a.sua_unlocked) AS `achievers`
		FROM $db_steam_achievement_types AS t
			LEFT JOIN $db_steam_user_achievements AS a
				ON a.sua_apiname = t.sat_apiname AND a.sua_appid = t.sat_appid
		WHERE sat_appid = ? AND sua_unlocked = 1
		GROUP BY sua_apiname, sua_appid
		ORDER BY `achievers` DESC", array((int) $appid));

	return $res->fetchAll();
}

/**
 * Syncs game schema for currently selected games.
 * @return integer Number of achievement types imported
 */
function steam_sync_games()
{
	global $cfg, $db, $db_steam_achievement_types;

	$count = 0;

	$games = steam_get_games();

	foreach ($games as $appid => $game)
	{
		// Check if the game schema is already in db
		$count = $db->query("SELECT COUNT(*) FROM $db_steam_achievement_types WHERE sat_appid = ?", array($appid))->fetchColumn();

		if ($count > 0)
		{
			// Skip games already in db
			continue;
		}

		// Get schema from Steam
		$steamGame = new SteamGame($appid, $cfg['steam']['apikey']);

		$schema = $steamGame->getSchema();

		// Save achievements in our db
		$rowset = array();
		foreach ($schema['achievements'] as $row)
		{
			$rowset[] = array(
				'sat_appid'    => $game['appid'],
				'sat_apiname'  => $row['name'],
				'sat_name'     => $row['displayName'],
				'sat_desc'     => $row['description'],
				'sat_icon'     => $row['icon'],
				'sat_icongray' => $row['icongray']
			);
		}
		$count += $db->insert($db_steam_achievement_types, $rowset);
	}

	return $count;
}

/**
 * Updates user games, hours and achievements querying Steam sites.
 * @param  array   $usr User array, normally just global $usr
 * @return integer      Number of database entries affected by this sync or false on error
 */
function steam_sync_user($usr)
{
	global $cfg, $db, $db_steam_user_hours, $db_steam_user_achievements;

	if (!empty($usr['profile']['user_steamid']))
	{
		$steamID = $usr['profile']['user_steamid'];
	}
	elseif (!empty($usr['user_steamid']))
	{
		$steamID = $usr['user_steamid'];
	}
	else
	{
		// SteamID or vanityURL is required
		return false;
	}

	$count = 0;

	$games = steam_get_games();

	$steamUser = new SteamUser($steamID, $cfg['steam']['apikey']);

	// Get the list of user games and get hours for tracked games
	$gameList = $steamUser->getGamesList();
	if (!is_array($gameList))
	{
		return false;
	}

	$gameIDs = array_keys($games);
	foreach ($gameList as $game)
	{
		if (in_array($game->appID, $gameIDs))
		{
			// Found one of our selected games, save hours
			$count += $db->query("INSERT INTO $db_steam_user_hours
					(suh_userid, suh_appid, suh_last2weeks, suh_onrecord)
				VALUES ({$usr['id']}, {$game->appID}, {$game->hoursLast2Weeks}, {$game->hoursOnRecord})
				ON DUPLICATE KEY UPDATE suh_last2weeks = {$game->hoursLast2Weeks}, suh_onrecord = {$game->hoursOnRecord}")->rowCount();

			// Update achievements
			$steamAchievements = $steamUser->getAchievements($games[$game->appID]['code']);
			$savedAchievements = $db->query("SELECT sua_apiname, sua_unlocked
				FROM $db_steam_user_achievements
				WHERE sua_userid = ? AND sua_appid = ?", array($usr['id'], $game->appID))
				->fetchAll();

			if (!is_null($steamAchievements) && count($steamAchievements) > 0)
			{
				$new_rows = array();
				foreach ($steamAchievements as $ach)
				{
					// Find it in the saved achievements
					$found = false;
					$changed = false;
					foreach ($savedAchievements as $row)
					{
						if ($row['sua_apiname'] == $ach->apiname)
						{
							$found = true;
							if ($row['sua_unlocked'] != $ach->unlocked && $ach->unlocked)
							{
								$changed = true;
							}
							break;
						}
					}
					if (!$found)
					{
						// Insert a new row
						$new_rows[] = array(
							'sua_userid'      => (int) $usr['id'],
							'sua_appid'       => (int) $game->appID,
							'sua_apiname'     => $ach->apiname,
							'sua_unlocked'    => $ach->unlocked,
							'sua_unlock_date' => $ach->unlockTimestamp
						);
					}
					elseif ($changed)
					{
						// Update achievement state
						$db->update($db_steam_user_achievements, array(
								'sua_unlocked'    => 1,
								'sua_unlock_date' => $ach->unlockTimestamp
							),
							"sua_userid = ? AND sua_appid = ? AND sua_apiname = ?",
							array((int) $usr['id'], (int) $game->appID, $ach->apiname)
						);
					}
				}
				// Apply changes
				if (count($new_rows) > 0)
				{
					$count += $db->insert($db_steam_user_achievements, $new_rows);
				}
			}
		}
	}

	return $count;
}
