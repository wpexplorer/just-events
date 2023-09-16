<?php declare(strict_types=1);

namespace WPExplorer\Just_Events;

\defined( 'ABSPATH' ) || exit;

class Plugin {

	/**
	 * Holds the plugin version.
	 */
	public const VERSION = '1.0.0';

	/**
	 * The events post type name.
	 */
	public const POST_TYPE = 'just_event';

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Initialize.
	 */
	public static function init(): void {
		require_once self::dir_path() . 'inc/functions.php';
		require_once self::dir_path() . 'inc/custom-fields.php';
		require_once self::dir_path() . 'inc/register-post-type.php';

		if ( \is_admin() ) {
			require_once self::dir_path() . 'inc/admin.php';
		}

		// Hooks.
		\register_activation_hook( JUST_EVENTS_PLUGIN_FILE, [ self::class, 'on_activation' ] );

		// Shortcodes.
		self::register_shortcodes();
	}

	/**
	 * Runs when the plugin is activated.
	 */
	public static function on_activation(): void {

		// Make sure our post type is registered before flushing rewrite rules.
		Register_Post_Type::init();

		// Flush rewrite rules to prevent 404 errors.
		\flush_rewrite_rules();
	}

	/**
	 * Register shortcodes.
	 */
	public static function register_shortcodes(): void {
		require_once self::dir_path() . 'inc/shortcode-abstract.php';
		require_once self::dir_path() . 'inc/shortcodes/formatted-event-date.php';
		require_once self::dir_path() . 'inc/shortcodes/formatted-event-time.php';
	}

	/**
	 * Returns the plugin directory path.
	 */
	protected static function dir_path(): string {
		return (string) trailingslashit( plugin_dir_path( JUST_EVENTS_PLUGIN_FILE ) );
	}

}

Plugin::init();
