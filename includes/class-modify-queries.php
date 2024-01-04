<?php declare(strict_types=1);

namespace Just_Events;

use Just_Events\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

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

		if ( \is_admin() ) {
			\add_action( 'parse_query', [ self::class, 'on_parse_query' ] );
		}
	}

	/**
	 * This function runs on the "pre_get_posts" hook.
	 */
	public static function on_pre_get_posts( $query ): void {
		if ( \is_admin() ) {
			if ( Plugin::POST_TYPE === $query->get( 'post_type' ) ) {
				switch ( $query->get( 'orderby' ) ) {
					case 'just_events_end_date':
						self::sort_events( $query, 'end' );
						break;
					case 'just_events_start_date':
						self::sort_events( $query );
						break;
				}
			}
		} elseif ( $query->is_main_query() ) {
			$is_event_archive = is_archive();

			// Modifies the number of posts_per_page for event archives.
			if ( $is_event_archive && $posts_per_page = get_option( 'posts_per_page' ) ) {
				if ( \is_numeric( $posts_per_page ) ) {
					$query->set( 'posts_per_page', (int) $posts_per_page );
				}
			}

			/**
			 * Filters whether the posts should be sorted by date.
			 * 
			 * @param bool $check Whether to hide the past events.
			 * @param object $query The WordPress query object.
			 */
			$orderby_check = (bool) apply_filters( 'just_events/modify_queries/sort_events', $is_event_archive, $query );

			if ( $orderby_check ) {
				self::sort_events( $query, 'start', ! (bool) get_option( 'hide_past_events', true ) );
			}

			/**
			 * Filters whether past events should be hidden.
			 * 
			 * @param bool $check Whether to hide the past events.
			 * @param object $query The WordPress query object.
			 */
			$hide_past_check = ( $is_event_archive || $query->is_search() ) && (bool) get_option( 'hide_past_events', true );
			$hide_past_check = (bool) apply_filters( 'just_events/modify_queries/hide_past_events', $hide_past_check, $query );

			if ( $hide_past_check ) {
				self::hide_past_events( $query );
			}
		}
	}

	/**
	 * This function runs on the "parse_query" hook to filter events in the admin Posts Table.
	 */
	public static function on_parse_query( $query ) {
		if ( ! is_admin()
			|| ! $query->is_main_query()
			|| Plugin::POST_TYPE !== $query->get( 'post_type' )
			|| empty( $_REQUEST['filter_action'] )
			|| empty( $_REQUEST['just_events_status'] )
		) {
			return $query;
		}

		switch ( \sanitize_text_field( \wp_unslash( $_REQUEST['just_events_status'] ) ) ) {
			case 'ongoing':
				$clause = [
					[
						'key'     => '_just_events_start_date',
						'value'   => get_current_date_time(),
						'compare' => '<=',
						'type'    => 'CHAR'
					],
					[
						'key'     => '_just_events_end_date',
						'value'   => get_current_date_time(),
						'compare' => '>=',
						'type'    => 'CHAR'
					],
				];
				break;
			case 'upcoming':
				$clause = [
					[
						'key'     => '_just_events_start_date',
						'value'   => get_current_date_time(),
						'compare' => '>',
						'type'    => 'CHAR'
					],
				];
				break;
			case 'past':
				$clause = [
					[
						'key'     => '_just_events_end_date',
						'value'   => get_current_date_time(),
						'compare' => '<',
						'type'    => 'CHAR'
					],
				];
				break;
		}

		if ( isset( $clause ) ) {
			self::add_meta_query_clause( $query, 'just_events_status_clause', $clause );
		}

		return $query;
	}

	/**
	 * Sort events.
	 */
	private static function sort_events( $query, string $start_end = 'start', bool $addClause = true ): void {
		$meta_key = "_just_events_{$start_end}_date";
		$orderby  = $query->get( 'orderby' );

		if ( $orderby && ! \in_array( $orderby, [ 'just_events_start_date', 'just_events_end_date' ], true ) ) {
			return;
		}

		if ( ! $query->get( 'order' ) ) {
			$order = wp_validate_boolean( get_option( 'hide_past_events', true ) ) ? 'ASC' : 'DESC';
			$query->set( 'order', $order );
		}

		$query->set( 'orderby', 'meta_value date' );

		$clause = [
			'relation' => 'OR',
			[
				'key'     => $meta_key,
				'compare' => 'EXISTS',
			],
			[
				'key'     => $meta_key,
				'compare' => 'NOT EXISTS',
			],
		];

		self::add_meta_query_clause( $query, 'just_events_orderby_clause', $clause );
	}

	/**
	 * Hide past events.
	 */
	private static function hide_past_events( $query ): void {
		$clause = [
			'relation' => 'OR',
			[
				'key'     => '_just_events_end_date',
				'value'   => get_current_date_time(),
				'compare' => '>=',
				'type'    => 'CHAR'
			],
			[
				'key'     => '_just_events_start_date',
				'compare' => 'NOT EXISTS',
			]
		];

		self::add_meta_query_clause( $query, 'just_events_hide_past_clause', $clause );
	}

	/**
	 * Adds new meta query clause.
	 */
	private static function add_meta_query_clause( $query, string $clause_name, array $clause_args ): void {
		$meta_query = $query->get( 'meta_query' ) ?: [];
		$meta_query[][ $clause_name ] = $clause_args;
		$query->set( 'meta_query', $meta_query );
	}

}

Modify_Queries::init();
