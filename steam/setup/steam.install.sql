-- Types of steam achievements saved by game
CREATE TABLE IF NOT EXISTS `cot_steam_achievement_types` (
	`sat_appid` INT NOT NULL,
	`sat_apiname` VARCHAR(128) NOT NULL,
	`sat_name` VARCHAR(128) NOT NULL,
	`sat_desc` VARCHAR(255) NOT NULL,
	`sat_icon` VARCHAR(255) NOT NULL,
	`sat_icongray` VARCHAR(255) NOT NULL,
	PRIMARY KEY (`sat_appid`, `sat_apiname`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Steam achivements by users
CREATE TABLE IF NOT EXISTS `cot_steam_user_achievements` (
	`sua_userid` INT NOT NULL REFERENCES `cot_users` (`user_id`),
	`sua_appid` INT NOT NULL,
	`sua_apiname` VARCHAR(128) NOT NULL,
	`sua_unlocked` TINYINT NOT NULL DEFAULT 0,
	`sua_unlock_date` INT NOT NULL DEFAULT 0,
	`sua_up2date` TINYINT NOT NULL DEFAULT 1,
	PRIMARY KEY (`sua_userid`, `sua_appid`, `sua_apiname`),
	KEY (`sua_unlock_date`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Steam played hours by users
CREATE TABLE IF NOT EXISTS `cot_steam_user_hours` (
	`suh_userid` INT NOT NULL REFERENCES `cot_users` (`user_id`),
	`suh_appid` INT NOT NULL,
	`suh_last2weeks` REAL NOT NULL DEFAULT 0,
	`suh_onrecord` REAL NOT NULL DEFAULT 0,
	PRIMARY KEY (`suh_userid`, `suh_appid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
