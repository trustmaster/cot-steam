<?php defined('COT_CODE') or die('Wrong URL');
/* ====================
[BEGIN_COT_EXT]
Code=steam
Name=Steam
Description=Steam Achievments Tracker
Version=1.0.3
Date=2012-10-25
Author=Trustmaster
Copyright=(c) Vladimir Sibirov and FMScout.com 2012
Notes=BSD License
SQL=
Auth_guests=R
Lock_guests=12345A
Auth_members=RW
Lock_members=12345
[END_COT_EXT]

[BEGIN_COT_EXT_CONFIG]
apikey=01:string:::Steam API key
games=02:text::71270|FM2012|Football Manager 2012:Games to grab data for (one per line, format appid|SteamCommunityName|Full Name), one per line
defaultid=03:string::71270:Default game appid
recent=04:string::5:Recent achievements on main page
ranks=05:string::10:Top ranked users on main page
weekly=05:string::10:Weekly top ranked users on main page
cachettl=10:string::60:Cache lifetime in seconds
[END_COT_EXT_CONFIG]
==================== */
