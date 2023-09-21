<?php declare(strict_types=1);

namespace WPExplorer\Just_Events;

use WPExplorer\Just_Events\Plugin;

\defined( 'ABSPATH' ) || exit;

class Modify_Queries {

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Initialize.
	 */
	public static function init(): void {
		\add_action( 'pre_get_posts', [ self::class, 'on_pre_get_posts' ] );
	}

	/**
	 * This function runs on the "pre_get_posts" hook.
	 */
	public static function on_pre_get_posts( $query ): void {
		if ( \is_admin() || ! $query->is_main_query() ) {
			return;
		}

		$is_event_archive = is_archive();

		// Hide past events from archives.
		if ( $is_event_archive || $query->is_search() ) {
			// Sort posts.
			if ( $is_event_archive || ( $query->is_search() && 'just_event' === \get_query_var( 'post_type' ) ) ) {
				$query->set( 'order', 'DESC' );
				$query->set( 'orderby', 'meta_value' );
				$query->set( 'meta_key', '_just_events_start_date' );
			}
			
			// Hide past events from archives.
			if ( true === (bool) get_option( 'hide_past_events', true ) ) {
				$today = get_today();
				$meta_query = $query->get( 'meta_query' ) ?: [];
				$clause = [
					[
						'key'     => '_just_events_end_date',
						'value'   => $today,
						'compare' => '>=',
						'type'    => 'CHAR'
					],
				];
				if ( $query->is_search() ) {
					$clause['relation'] = 'OR';
					$clause[] = [
						'key'     => '_just_events_start_date',
						'compare' => 'NOT EXISTS',
					];
				}
				$meta_query[]['just_events_clause'] = $clause;
				$query->set( 'meta_query', $meta_query );
			}
		}
	}

}

Modify_Queries::init();
