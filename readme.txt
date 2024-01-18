=== Just Events ===
Contributors: WPExplorer
Donate link: https://www.wpexplorer.com/donate/
Tags: events, event
Requires at least: 6.3
Requires PHP: 8.0
Tested up to: 6.4
Stable Tag: 1.0.3
License: GPL v2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

== Description ==
The Just Events plugin registers a new event post type to your WordPress site for easily adding events. The plugin is intended to provide only the basic functionality to add event posts to your site for those looking for a very minimal solution without all the extra bloat of "traditional" event plugins. For this reason you won't find features like calendars, repeating events, tons of shortcodes, custom templates, etc.

This plugin works best with a "Full Site Editing" block theme so you can easily create your archives and single event templates using the included Event Date, Event Time, Event Status and Event Link blocks or with a compatible Classic WordPress theme such as our Total theme.

== Features ==

* Event post type.
* Event fields for setting event details (all day, start date, end date, start time, end time and link).
* Event Date, Event Time, Event Status and Event Link blocks.
* Works with Full Site Editing and Classic themes.
* Makes use of theme templates and styles rather then using it's own template files and CSS (unlike other plugins) which keeps things slim, faster and more compatible.
* Option to hide past events from event archives.
* Options to customize the event post type single and archive slugs.
* Options to set your default event date and time format.
* Options to modify the default formatted date and time separator.

== Installation ==

1. Go to your WordPress website admin panel
2. Select Plugins > Add New
3. Search for "Just Events"
4. Click Install
5. Activate the plugin
6. You will now see an "Events" tab in your WordPress admin for adding your events.
7. If using a Full Site Editing theme go to Appearance > Editor and create your single and archive templates (see FAQ).

== Frequently Asked Questions ==

= Is the Just Events Plugin Free? =
Yes. The plugin is completely free of charge under the GPL license.

= Is there a premium version? =
No.

= Will the plugin work with my theme? =
Just Events should work with any theme, however, not all themes may have a system to easily display your events. The plugin works best with FSE (Full Site Editing) block themes or classic theme's that include built-in integration (ask the theme developer).

= How do I add events? =
After activating the plugin simply go to Events > Add New and you can start adding your events.

= Where can I view my events? =
By default your Event archives will exist at yoursite.com/events/ which will automatically display your events. You can modify the URL slug (events) via the settings panel at Events > Settings > Archive Slug.

If you wish to customize this page and you are using a full site editing block theme you can do so via Appearance > Editor. From here you can click the plus icon to "Add New Template" and click the "Archive: Event" option. If you are using a classic theme you will need to contact the theme developer and ask how to modify the layout as this will be dependent on the theme you are using.

= Why do my events look like blog posts? =
By default the event posts will take on the default post template layout of your theme. If you are using a full site editing block theme simply go to Appearance > Editor > Templates and click on the plus icon to "Add New Template" then select the "Single item: Event" option. Now you can create your custom event template and use the included Event Date, Event Time, Event Status and/or Event Link blocks to display your event data.

If you are using a classic WordPress theme you will need to contact the theme developer and ask how to modify the layout as this will be dependent on the theme you are using.

== Changelog ==

= 1.0.3 =

* Fixed unescaped nonce check.

= 1.0.2 =

* Updated namespace from WPExplorer/Just_Events to Just_Events.
* Updated some code to use late escaping for variables.
* Updated class file names to be prepended with "class-".
* Updated renamed the "inc" folder to "includes".
* Updated moved inline script from class-admin.php to settings.js and removed inline style tag.
* Added extra validation to the admin settings fields.
* Added License, License URI and License comment block to main plugin file.

= 1.0.1 =

* Updated the "Tested up to" value.
* Updated code to use late escaping for variables.
* Added alignment options added to the "Event Status" and "Event Link" blocks.

= 1.0 =

* First official release