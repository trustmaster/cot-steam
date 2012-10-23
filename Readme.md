# Steam Achievements module for Cotonti

This module displays in-game achievements of your Cotonti website users utilizing [Steam API](http://api.steampowered.com) and [SteamCommunity](http://steamcommunity.com).

## Installation

1. Copy steam module into your Cotonti modules folder.
2. Install the module in Administration / Extensions.
3. Proceed to configuration.

## Configuration

1. Obtain your own [Steam API Key](http://steamcommunity.com/dev/apikey) and specify it in the module configuration.
2. Collect Steam appIDs for your games from [SteamCommunity](http://steamcommunity.com/apps), e.g. if the game URL looks like `http://steamcommunity.com/app/71270` then appID for the game is '71270'.
3. Collect SteamCommunity shortcuts for your games by visiting Global Achievements for those games, e.g. if the achievements URL looks like `http://steamcommunity.com/stats/FM2012/achievements/` then SteamCommunityName for the game is 'FM2012'.
4. Go to module configuration in Administration / Extensions / Steam and fill collected information in game configuration. Don't forget to specify default appID.

Every time you change configuration and add/remove games, Steam module synchronizes its database with SteamCommunity and obtains properties and descriptions for those games.

## Usage

This module installs a new extrafield for user profiles: SteamID. Add {USERS_PROFILE_STEAMID} it to your 'users.profile.tpl' file.

A user needs to specify his SteamID in profile for the module to start tracking their achievements. SteamID can be either a 64-bit SteamID (a big integer number) or a name part of Steam vanityURL. For example if your SteamCommmunity Profile URL looks like `http://steamcommunity.com/profiles/12345678901234567/` then your SteamID is '12345678901234567'. If your SteamCommmunity Profile URL looks like `http://steamcommunity.com/id/coolguy` then your SteamID (vanityURL) is 'coolguy'.

Once a user has specified his SteamID, the module starts synchronizing their achievements every time he comes back to the site from absence.

Add {USERS_DETAILS_STEAMBOX} tag to 'users.details.tpl' file to display user achievements on their public profile page.

The global stats URL is something like http://example.com/steam/ or http://example.com/index.php?e=steam depending on your site's URL settings.

## Customization

You can easily customize Steam module templates. In order to do so, copy files from 'modules/steam/tpl' folder to 'themes/your_theme/modules' folder and edit them there.

## Credits

* This module uses [PHP SteamAPI](https://github.com/MattRyder/SteamAPI) by Matt Ryder.
* This module was sponsored and tested by [FM Scout](http://www.fmscout.com/) community.
