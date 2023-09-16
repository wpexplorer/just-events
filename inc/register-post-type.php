<?php declare(strict_types=1);

namespace WPExplorer\Just_Events;

use WPExplorer\Just_Events\Plugin;

\defined( 'ABSPATH' ) || exit;

class Register_Post_Type {

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Initialize.
	 */
	public static function init(): void {
		\add_action( 'init', [ self::class, 'on_init' ] );
	}

	/**
	 * This function runs on the init hook.
	 */
	public static function on_init(): void {
		\register_post_type( Plugin::POST_TYPE, self::get_args() );

		if ( \get_option( 'just_events_flush_rewrite_rules_flag' ) ) {
			\flush_rewrite_rules();
			\delete_option( 'just_events_flush_rewrite_rules_flag' );
		}
	}

	/**
	 * Returns the post type args.
	 */
	protected static function get_args(): array {
		$labels = [
			'name'                  => \_x( 'Events', 'Post type general name', 'just-events' ),
			'singular_name'         => \_x( 'Event', 'Post type singular name', 'just-events' ),
			'menu_name'             => \_x( 'Events', 'Admin Menu text', 'just-events' ),
			'name_admin_bar'        => \_x( 'Event', 'Add New on Toolbar', 'just-events' ),
			'add_new'               => \__( 'Add New', 'just-events' ),
			'add_new_item'          => \__( 'Add New Event', 'just-events' ),
			'new_item'              => \__( 'New Event', 'just-events' ),
			'edit_item'             => \__( 'Edit Event', 'just-events' ),
			'view_item'             => \__( 'View Event', 'just-events' ),
			'all_items'             => \__( 'All Events', 'just-events' ),
			'search_items'          => \__( 'Search Events', 'just-events' ),
			'parent_item_colon'     => \__( 'Parent Events:', 'just-events' ),
			'not_found'             => \__( 'No events found.', 'just-events' ),
			'not_found_in_trash'    => \__( 'No events found in Trash.', 'just-events' ),
			'archives'              => \_x( 'Event archives', 'The post type archive label used in nav menus. Default "Post Archives". Added in 4.4', 'just-events' ),
			'insert_into_item'      => \_x( 'Insert into event', 'Overrides the "Insert into post"/"Insert into page" phrase (used when inserting media into a post). Added in 4.4', 'just-events' ),
			'uploaded_to_this_item' => \_x( 'Uploaded to this event', 'Overrides the "Uploaded to this post"/"Uploaded to this page" phrase (used when viewing media attached to a post). Added in 4.4', 'just-events' ),
			'filter_items_list'     => \_x( 'Filter events list', 'Screen reader text for the filter links heading on the post type listing screen. Default "Filter posts list"/"Filter pages list". Added in 4.4', 'just-events' ),
			'items_list_navigation' => \_x( 'Events list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default "Posts list navigation"/"Pages list navigation". Added in 4.4', 'just-events' ),
			'items_list'            => \_x( 'Events list', 'Screen reader text for the items list heading on the post type listing screen. Default "Posts list"/"Pages list". Added in 4.4', 'just-events' ),
		];

		$has_archive = \wp_validate_boolean( get_option( 'post_type_has_archive', true ) );

		if ( $has_archive ) {
			$archive_slug = get_option( 'post_type_archive_slug' ) ?: 'events';
		}

		$args = [
			'labels'          => $labels,
			'public'          => true,
			'show_ui'         => true,
			'show_in_menu'    => true,
			'show_in_rest'    => true,
			'rewrite'         => [
				'slug' => get_option( 'post_type_rewrite_slug' ) ?: 'event',
			],
			'capability_type' => 'post',
			'has_archive'     => $archive_slug ?? false,
			'menu_position'   => null,
			'menu_icon'       => 'dashicons-calendar-alt',
			'supports'        => [ 'title', 'editor', 'author', 'thumbnail', 'excerpt' ],
		];

		return $args;
	}

}

Register_Post_Type::init();
