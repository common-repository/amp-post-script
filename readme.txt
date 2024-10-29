=== AMP Post Script ===
Contributors: Peter Stevenson (https://www.p-stevenson.com)
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=55UDRSZMRD4KA
Tags: AMP, Post Script, Custom
Requires at least: 5.0
Tested up to: 5.9.3
Stable tag: 1.7.5
License: GPL-2.0+
License URI: http://www.gnu.org/licenses/gpl-2.0.txt

Modify the AMP plugin for WordPress


== Description ==

In short, this plugin make custom modifications to the AMP plugin.

* The AMP plugin settings can be customized here: /wp-admin/customize.php?customize_amp=1
* Add a custom menu to AMP... update it through (/wp-admin/nav-menus.php)
* Add related articles based on similar categories. Taxonomy can be changed using.... define('PS_AMP_RELATED_TAXONOMY', 'category');
* Add Google Analytics to AMP view using.... define('PS_AMP_GA_ANALYTICS', 'UA-xxxxxxxx-x');
* Override post type support using.... define('PS_AMP_ADDITIONAL_POST_TYPES', 'events,news,locations,wpseo_locations,tribe_events');
* Disable Related Posts Using.... define('PS_AMP_DISABLE_RELATED_POSTS', true);
* Use Site Icon for AMP metadata image if no image present
* Added required CSS for these additions


== Installation ==

1. Upload `post-script-amp` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress


== Changelog ==

= 1.0.0 =
* Initial release

= 1.1.0 =
* Bug Fixes
* Fix to Genesis Framework incompatibilities
* Added amp support for pages (and a few customizations)
* Added amp support for multiple other post types, including other plugins such as ( wpseo_locations | tribe_events )

= 1.2.0 =
* Bug Fixes
* Fix general incompatibilities
* Added amp support for editable post-types by defining them in your theme
* Ability to disabled the related post feature
* Removed all wp_footer actions, which can cause validation errors

= 1.3.0 =
* Bug Fixes
* Update CSS

= 1.4.0 =
* Removed "pages" as a default post type, can be added back manually, see plugin "description"

= 1.4.1 =
* Cleaned up class and methods construction

= 1.5.0 =
* Restructured the plugin code to give it a better namespace to avoid any potential conflicts
* Better documented plugin code

= 1.5.1 =
* Plugin compatibility fixes

= 1.5.2 =
* Plugin/css compatibility fixes

= 1.5.3 =
* Improvements to "related posts" functionality

= 1.5.6 =
* Bug Fixes

= 1.6.0 =
* Add Alt Tags to Related Posts

= 1.7.3 =
* Fix AMP html structure
* Misc Bug Fixes

= 1.7.4 =
* Misc Bug Fixes

= 1.7.5 =
* Fixed issue where title tag does not appear
