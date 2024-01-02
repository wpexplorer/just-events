<?php
/*
 * Plugin Name:       Just Events
 * Plugin URI:        https://wordpress.org/plugins/just-events/
 * Description:       Adds an Event post type to your WordPress powered site.
 * Version:           1.0.2
 * Requires at least: 6.3
 * Requires PHP:      8.0
 * Author:            WPExplorer
 * Author URI:        https://www.wpexplorer.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       just-events
 * Domain Path:       /languages
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
if ( ! defined( 'JUST_EVENTS_PLUGIN_FILE' ) ) {
	define( 'JUST_EVENTS_PLUGIN_FILE', __FILE__ );
}

/**
 * The magic starts here.
 */
require_once plugin_dir_path( JUST_EVENTS_PLUGIN_FILE ) . 'inc/class-plugin.php';
