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
		\add_filter( 'manage_edit-' . Plugin::POST_TYPE . '_sortable_columns', [ self::class, 'sortable_columns' ], 10, 2 );
		\add_filter( 'manage_' . Plugin::POST_TYPE . '_posts_custom_column', [ self::class, 'display_admin_columns' ], 10, 2 );
		\add_filter( 'restrict_manage_posts', [ self::class, 'admin_filters' ] );
	}

	/**
	 * Enqueues css for the admin columns.
	 */
	public static function enqueue_admin_columns_css( $hook ): void {
		if ( 'edit.php' !== $hook
			|| ! isset( $_GET['post_type'] )
			|| Plugin::POST_TYPE !== \sanitize_text_field( $_GET['post_type'] )
		) {
			return;
		}

		wp_enqueue_style(
			'just-events-post-status', 
			\untrailingslashit( \plugin_dir_url( JUST_EVENTS_PLUGIN_FILE ) ) . '/assets/css/admin/posts-columns.css',
			[],
			\filemtime( \plugin_dir_path( JUST_EVENTS_PLUGIN_FILE ) . '/assets/css/admin/posts-columns.css')
		);
	}

	/**
	 * Register admin columns.
	 */
	public static function register_admin_columns( $columns ): array {
		return \array_merge( $columns, [
			'just_events_thumbnail'  => esc_html__( 'Thumbnail', 'just-events' ),
			'just_events_status'     => esc_html__( 'Status', 'just-events' ),
			'just_events_all_day'    => esc_html__( 'All Day Event?', 'just-events' ),
			'just_events_start_date' => esc_html__( 'Start Date', 'just-events' ),
			'just_events_end_date'   => esc_html__( 'End Date', 'just-events' ),
			'just_events_start_time' => esc_html__( 'Start Time', 'just-events' ),
			'just_events_end_time'   => esc_html__( 'End Time', 'just-events' ),
		] );
	}
	
	/**
	 * Register sortable admin columns.
	 */
	public static function sortable_columns( $columns ): array {
		return \array_merge( $columns, [
			'just_events_start_date' => 'just_events_start_date',
			'just_events_end_date'   => 'just_events_end_date',
		] );
	}

	/**
	 * Display admin columns.
	 */
	public static function display_admin_columns( $column_name, $post_id ): void {
		switch ( $column_name ) {
			case 'just_events_status':
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
			case 'just_events_thumbnail':
				if ( \has_post_thumbnail( $post_id ) ) {
					\the_post_thumbnail(
						[ 60, 60 ],
					);
				} else {
					echo '&#8212;';
				}
				break;
			case 'just_events_all_day':
				$dashicon = is_all_day_event( $post_id ) ? 'yes' : 'no-alt';
				$screen_text = 'yes' === $dashicon ? \esc_html__( 'yes', 'just-events' ) : \esc_html__( 'no', 'just-events' );
				echo "<span class='dashicons dashicons-{$dashicon}' aria-hidden'true'></span><span class='screen-reader-text'>{$screen_text}</span>";
				break;
			case 'just_events_start_date':
				echo ( $start_date = get_event_start_date( $post_id, false ) ) ? \esc_html( $start_date ) : '&dash;';
				break;
			case 'just_events_end_date':
				echo ( $start_date = get_event_end_date( $post_id, false ) ) ? \esc_html( $start_date ) : '&dash;';
				break;
			case 'just_events_start_time':
				echo ( $start_time = get_event_start_time( $post_id ) ) ? \esc_html( $start_time ) : '&dash;';
				break;
			case 'just_events_end_time':
				echo ( $end_time = get_event_end_time( $post_id ) ) ? \esc_html( $end_time ) : '&dash;';
				break;
		}
	}

	/**
	 * Custom admin filters.
	 */
	public static function admin_filters( $post_type ): void {
		if ( Plugin::POST_TYPE !== $post_type ){
			return;
		}

		$selected = isset( $_REQUEST['event_status'] ) ? \sanitize_text_field( $_REQUEST['event_status'] ) : '';

		echo '<select name="event_status">';
			echo '<option value="">' . \esc_html__( 'All Event Statuses', 'just-events' ) . ' </option>';
			foreach( get_event_statuses() as $k => $v ) {
				echo '<option value="' . \esc_attr( $k ) . '"' . \selected( $k, $selected, false ) . '>' . \esc_html( $v ). ' </option>';
			}
		echo '</select>';
	}

}

Posts_Columns::init();
