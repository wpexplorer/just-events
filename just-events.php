<?php
/**
 * Plugin Name: Just Events
 * Plugin URI: https://wordpress.org/plugins/just-events/
 * Description: Adds an Event post type to your WordPress powered site.
 * Author: WPExplorer
 * Author URI: https://www.wpexplorer.com/
 * Version: 1.0
 *
 * Text Domain: just-events
 * Domain Path: /languages/
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
 * Run on plugin activation.
 */
function just_events_plugin_activation_hook() {
	if ( ! get_option( 'just_events_flush_rewrite_rules_flag' ) ) {
		add_option( 'just_events_flush_rewrite_rules_flag', true );
	}
	// Fixes issues with #https://core.trac.wordpress.org/ticket/21989
	if ( ! get_option( 'just_events' ) ) {
		add_option( 'just_events', [], false );
	}
}
register_activation_hook( JUST_EVENTS_PLUGIN_FILE, 'just_events_plugin_activation_hook' );

/**
 * Flush Rewrite rules on deactivation.
 */
register_deactivation_hook( JUST_EVENTS_PLUGIN_FILE, 'flush_rewrite_rules' );

/**
 * The magic starts here.
 */
require_once plugin_dir_path( JUST_EVENTS_PLUGIN_FILE ) . 'inc/plugin.php';
