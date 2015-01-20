=== Privilege Menu ===
Contributors: fuzzguard
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=G8SPGAVH8RTBU
tags: nav menu, nav menus, menus, menu
Requires at least: 3.8
Tested up to: 4.1
Stable tag: 1.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
This plugin allows you to display menu items based on if a user is logged in, logged out or both.

This solves the problem of having to modify theme functions.php files to add in menu restrictions based on users logged in/logged out status.  The changes in functions.php is often overwritten by a theme update.  This plugin removes that worry, as you can update the theme, independant of the plugin.

== Installation ==

1. Upload the `plugin` folder to the `/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Go to Appearance > Menus
1. Edit the menu items accordingly.  First select whether you'd like to display the item to all logged in users, all logged out users or Both (Default).
1. Save the changes to the menu.  You should now see excluded menu items based on a users logged in/logged out state.

== Frequently Asked Questions ==

= Can I localize this plugin for my own language? =

Yes you can.  Included now in this plugin is a folder call "lang".  Within this folder is a file called "privilege-menu.pot".  This file can be used to create the localized translations for your own language using poedit.

If you contact me after you have done this through my website at: https://www.fuzzguard.com.au/contact/ I can include this translation file in the next pugin release.  You will be credited for your work of course.

= I cannot see the options for Privilege Menu under menu items in the Admin Panel? =

This usually occurs due to a plugin conflict.  The Admin Menu Walker can only have one custom walker so any other plugin that uses a custom Admin Menu Walker will cause conflicts with Privilege Menu plugin.

Wordpress does not yet have sufficient hooks in this area of the admin panel.  Due to this plugins are forced to load a modifed custom Admin Menu Walker.  The custom Admin Menu Walker is limited to one so only one Admin Panel menu modification plugin can be active at one time.

Although this feature has been requested since 3.6 it still hasn't been added to the WordPress Core.
There's a possibility that support for "Nav Menu UI" Hooks will be added in WordPress 4.0:
http://core.trac.wordpress.org/ticket/18584

== Screenshots ==

1. The is a view of the Admin Panel Nav Menu editing area.  The extra selectable options are added to each menu item.
2. This is the menu a logged IN user would see.  These users don't see "Login" due to the fact that it is denied to logged IN users in the Admin Panel Nav Menus section.
3. This is the menu a logged OUT user would see.  These users don't see "Logout", "My Account" or "Control Panel" due to the fact that they are denied to logged OUT users in the Admin Panel Nav Menus section.
4. This is a view of a menu item you could select only to be viewable for administrators.

== Changelog ==

= 1.4 =
* Added "lang" folder for localization files
* Added French, German, Spanish and Chinese translations
* Added .pot file for localization by others.  Located in "lang" folder

= 1.3 =
* Added Wordpress admin class protection coding to customWalker.php
* Changed "Display Mode" to "User Restriction"
* Added in bottom border to "User Restriction" area in each menu item to make the menu area easier to read

= 1.2 =
* Removed superfluous "2" from end of plugin name

= 1.1 =
* Changed "Both" to "All Users" in customWalker.php
* Added in ability to show menu only to administrators

= 1.0 =
* Gold release
