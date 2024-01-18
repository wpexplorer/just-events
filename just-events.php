<?php
/*
 * Plugin Name:       Just Events
 * Plugin URI:        https://wordpress.org/plugins/just-events/
 * Description:       Adds an Event post type to your WordPress powered site.
 * Version:           1.0.3
 * Requires at least: 6.3
 * Requires PHP:      8.0
 * Author:            WPExplorer
 * Author URI:        https://www.wpexplorer.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       just-events
 * Domain Path:       /languages
 */

/*
Just Events is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Just Events is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Just Events. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/

/**
 * Prevent direct access to this file.
 */
defined( 'ABSPATH' ) || exit;

/**
 * This plugin requires PHP 8.0+
 */
if ( ! function_exists( 'str_starts_with' ) ) {
	return;
}

/**
 * Define plugin constants.
 */
define( 'JUST_EVENTS_PLUGIN_FILE', __FILE__ );

/**
 * The magic starts here.
 */
require_once plugin_dir_path( JUST_EVENTS_PLUGIN_FILE ) . 'includes/class-plugin.php';
