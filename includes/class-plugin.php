<?php declare(strict_types=1);

namespace Just_Events;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Plugin {

	/**
	 * The plugin version.
	 */
	public const VERSION = '1.0.5';

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
		require_once self::dir_path() . 'includes/functions.php';
		require_once self::dir_path() . 'includes/class-custom-fields.php';
		require_once self::dir_path() . 'includes/class-modify-queries.php';
		require_once self::dir_path() . 'includes/class-register-post-type.php';

		if ( \is_admin() ) {
			require_once self::dir_path() . 'includes/class-admin.php';
			require_once self::dir_path() . 'includes/class-posts-columns.php';
		}

		\register_activation_hook( JUST_EVENTS_PLUGIN_FILE, [ self::class, 'on_activation' ] );
		\register_deactivation_hook( JUST_EVENTS_PLUGIN_FILE, 'flush_rewrite_rules' );

		\add_filter( 'block_categories_all', [ self::class, 'filter_block_categories_all' ], 10, 2 );
		\add_action( 'init', [ self::class, 'on_init' ] );
		\add_action( 'plugins_loaded', [ self::class, 'integrations' ] );
	}

	/**
	 * Runs when the plugin is activated.
	 */
	public static function on_activation(): void {
		if ( ! \get_option( 'just_events_flush_rewrite_rules_flag' ) ) {
			\add_option( 'just_events_flush_rewrite_rules_flag', true );
		}

		// Fixes issues with #https://core.trac.wordpress.org/ticket/21989
		if ( ! \get_option( 'just_events' ) ) {
			\add_option( 'just_events', [], '', false );
		}

		// Make sure our post type is registered before flushing rewrite rules.
		Register_Post_Type::init();
	}

	/**
	 * Runs on the "init" hook.
	 */
	public static function on_init(): void {
		self::register_shortcodes();
		self::register_blocks();
	}

	/**
	 * Provices support for 3rd party scripts.
	 */
	public static function integrations(): void {
		if ( \class_exists( 'Post_Types_Unlimited', false ) ) {
			require_once self::dir_path() . 'includes/integration/class-post-types-unlimited.php';
		}
	}

	/**
	 * Register shortcodes.
	 */
	public static function register_shortcodes(): void {
		require_once self::dir_path() . 'includes/class-shortcode-abstract.php';
		require_once self::dir_path() . 'includes/shortcodes/class-event-date.php';
		require_once self::dir_path() . 'includes/shortcodes/class-event-time.php';
	}

	/**
	 * Register blocks.
	 */
	public static function register_blocks(): void {
		$blocks = [
			'event-status',
			'event-date',
			'event-time',
			'event-link',
		];
		foreach ( $blocks as $block ) {
			$path = self::dir_path();
			$file = "{$path}build/blocks/{$block}/block.json";
			if ( \file_exists( $file ) ) {
				\register_block_type( $file );
			}
		}
	}

	/**
	 * Register blocks.
	 */
	public static function filter_block_categories_all( $block_categories, $editor_context ) {
		if ( \is_array( $block_categories ) ) {
			\array_push(
				$block_categories,
				[
					'slug'  => 'just-events',
					'title' => 'Just Events',
					'icon'  => '',
				]
			);
		}
		return $block_categories;
	}

	/**
	 * Returns the plugin directory path.
	 */
	protected static function dir_path(): string {
		return (string) trailingslashit( plugin_dir_path( JUST_EVENTS_PLUGIN_FILE ) );
	}

}

Plugin::init();
