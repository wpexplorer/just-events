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
		if ( \is_admin() ) {
			if ( isset( $_GET['post_type'] ) && Plugin::POST_TYPE === $_GET['post_type'] ) {
				$orderby = $query->get( 'orderby' );
				switch ( $orderby ) {
					case 'end_date':
						self::sort_events( $query, 'end' );
						break;
					case 'start_date':
						self::sort_events( $query );
						break;
				}
			}
		} elseif ( $query->is_main_query() ) {
			$is_event_archive = is_archive();

			/**
			 * Filters whether the posts should be sorted by date.
			 * 
			 * @param bool $check Whether to hide the past events.
			 * @param object $query The WordPress query object.
			 */
			$orderby_check = $is_event_archive || ( $query->is_search() && 'just_event' === \get_query_var( 'post_type' ) );
			$orderby_check = (bool) apply_filters( 'just_events/modify_queries/sort_events', $orderby_check, $query );

			if ( $orderby_check ) {
				self::sort_events( $query, 'start', ! (bool) get_option( 'hide_past_events', false ) );
			}

			/**
			 * Filters whether past events should be hidden.
			 * 
			 * @param bool $check Whether to hide the past events.
			 * @param object $query The WordPress query object.
			 */
			$hide_past_check = ( $is_event_archive || $query->is_search() ) && (bool) get_option( 'hide_past_events', false );
			$hide_past_check = (bool) apply_filters( 'just_events/modify_queries/hide_past_events', $hide_past_check, $query );

			if ( $hide_past_check ) {
				self::hide_past_events( $query );
			}
		}
	}

	/**
	 * Sort events.
	 */
	private static function sort_events( $query, string $start_end = 'start', bool $addClause = true ): void {
		$meta_key = "_just_events_{$start_end}_date";
		$orderby = $query->get( 'orderby' );

		if ( $orderby && ! \in_array( $orderby, [ 'start_date', 'end_date' ] ) ) {
			return;
		}

		if ( ! $query->get( 'orderby' ) ) {
			$query->set( 'order', 'DESC' );
		}

		$query->set( 'orderby', 'meta_value date' );
	//	$query->set( 'meta_key', $meta_key ); 

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
