<?php declare(strict_types=1);

namespace WPExplorer\Just_Events;

use WPExplorer\Just_Events\Plugin;

\defined( 'ABSPATH' ) || exit;

final class Posts_Columns {

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Initialize.
	 */
	public static function init(): void {
		\add_action( 'admin_enqueue_scripts', [ self::class, 'enqueue_admin_columns_css' ] );
		\add_filter( 'manage_' . Plugin::POST_TYPE . '_posts_columns', [ self::class, 'register_admin_columns' ] );
		\add_filter( 'manage_' . Plugin::POST_TYPE . '_posts_custom_column', [ self::class, 'display_admin_columns' ], 10, 2 );
	}

	/**
	 * Enqueues css for the admin columns.
	 */
	public static function enqueue_admin_columns_css( $hook ): void {
		if ( 'edit.php' !== $hook || ! isset( $_GET['post_type'] ) || Plugin::POST_TYPE !== $_GET['post_type'] ) {
			return;
		}

		wp_enqueue_style(
			'just-events-post-status', 
			\untrailingslashit( \plugin_dir_url( JUST_EVENTS_PLUGIN_FILE ) ) . '/assets/css/admin/posts-columns.css',
			[],
			\filemtime( plugin_dir_path( JUST_EVENTS_PLUGIN_FILE ) . '/assets/css/admin/posts-columns.css')
		);
	}

	/**
	 * Register admin columns.
	 */
	public static function register_admin_columns( $columns ): array {
		return \array_merge( $columns, [
			'status'     => esc_html__( 'Status', 'just-events' ),
			'all_day'    => esc_html__( 'All Day Event?', 'just-events' ),
			'start_date' => esc_html__( 'Start Date', 'just-events' ),
			'end_date'   => esc_html__( 'End Date', 'just-events' ),
			'start_time' => esc_html__( 'Start Time', 'just-events' ),
			'end_time'   => esc_html__( 'End Time', 'just-events' ),
		] );
	}

	/**
	 * Display admin columns.
	 */
	public static function display_admin_columns( $column_name, $post_id ): void {
		switch ( $column_name ) {
			case 'status':
				$status = get_event_status_label( $post_id );
				if ( \WP_Block_Type_Registry::get_instance()->is_registered( 'just-events/event-status' ) ) {
					$parsed_block = [
						'blockName' => 'just-events/event-status',
						'attrs'     => [
							'event' => $post_id,
						],
					];
					echo \render_block( $parsed_block );
				}
				break;
			case 'all_day':
				$dashicon = is_all_day_event( $post_id ) ? 'yes' : 'no-alt';
				echo "<span class='dashicons dashicons-{$dashicon}'></span>";
				break;
			case 'start_date':
				echo ( $start_date = get_event_start_date( $post_id ) ) ? \esc_html( $start_date ) : '&dash;';
				break;
			case 'end_date':
				echo ( $start_date = get_event_end_date( $post_id ) ) ? \esc_html( $start_date ) : '&dash;';
				break;
			case 'start_time':
				echo ( $start_time = get_event_start_time( $post_id ) ) ? \esc_html( $start_time ) : '&dash;';
				break;
			case 'end_time':
				echo ( $end_time = get_event_end_time( $post_id ) ) ? \esc_html( $end_time ) : '&dash;';
				break;
		}
	}

}

Posts_Columns::init();
