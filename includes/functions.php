<?php declare(strict_types=1);

namespace Just_Events;

use Just_Events\Custom_Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Returns a plugin option value.
 */
function get_option( string $key, $default_value = '' ) {
	$value = \get_option( 'just_events' )[ $key ] ?? '';
	return ( '' !== $value ) ? $value : $default_value;
}

/**
 * Returns true if currently viewing an event archive.
 * 
 * This is useful if you are registering custom taxonomies and want to apply
 * the same sorting and hiding of past events to your archive.
 *
 * Note: is_post_type_archive() may true on is_search() but FSE themes don't actually use the
 * post type archive template for search results hence the added ! is_search() check.
 */
function is_archive(): bool {
	$check = \is_post_type_archive( 'just_event' ) && ! is_search();
	
	/**
	 * Filters whether the current page is a Just Events archive.
	 * 
	 * @param bool $check Is the current page a Just Events archive.
	 */
	$check = apply_filters( 'just_events/is_archive', $check );

	return (bool) $check;
}

/**
 * Returns the formatted current date & time.
 */
function get_current_date_time( string $timezone = '' ): string {
	$datetime = new \DateTimeImmutable( 'now', $timezone ?: \wp_timezone() );
	return $datetime->format( 'Y-m-d H:i:s' );
}

/**
 * Returns the default date format.
 */
function get_default_date_format( $display_time = true ): string {
	if ( $display_time ) {
		$time_prefix = get_option( 'time_prefix', ' @ ' );
		$format = \sprintf(
			'%s \\' . $time_prefix . '\\ %s',
			get_option( 'date_format', \get_option( 'date_format', 'F j, Y' ) ),
			get_default_time_format()
		);
	} else {
		$format = get_option( 'date_format', \get_option( 'date_format', 'g:i a' ) );
	}

	return $format;
}

/**
 * Returns the default time format.
 */
function get_default_time_format(): string {
	return get_option( 'time_format', \get_option( 'time_format', 'g:i a' ) );
}

/**
 * Returns the event link.
 */
function get_event_link( int $event = 0 ): string {
	if ( ! $event ) {
		$event = \get_the_ID();
	}
	return (string) Custom_Fields::get_field_value( $event, 'link', false );
}

/**
 * Returns the event link text.
 */
function get_event_link_text(): string {
	return get_option( 'link_text' ) ?: \__( 'View Event', 'just-events' );
}

/**
 * Returns the timezone of an event as a string.
 *
 * The plugin currently doesn't support multi-timezone events so this function
 * returns the user defined timezone in WP.
 */
function get_event_timezone_string(): string {
	return wp_timezone_string();
}

/**
 * Returns the timezone of an event as a DateTimeZone object.
 */
function get_event_timezone(): \DateTimeZone {
	return new \DateTimeZone( get_event_timezone_string() );
}

/**
 * Returns the event date.
 */
function get_event_date( int $event = 0, string $start_end = 'start', bool $display_time = true, string $format = '' ): string {
	if ( ! $event ) {
		$event = \get_the_ID();
	}

	$date = Custom_Fields::get_field_value( $event, "{$start_end}_date", false );

	if ( ! $date || false === strtotime( $date ) ) {
		return '';
	}

	if ( 'raw' === $format ) {
		return $display_time ? $date : strtok( $date, ' ' );
	}

	if ( is_all_day_event( $event ) ) {
		$display_time = false; // never display time for all day events.
	}

	$date      = new \DateTime( $date, get_event_timezone( $event ) );
	$timestamp = $date->format( 'U' );
	$format    = $format ?: get_default_date_format( $display_time );

	return (string) \wp_date( $format, $timestamp );
}

/**
 * Returns the event start date.
 */
function get_event_start_date( int $event = 0, bool $display_time = true, string $format = '' ): string {
	return get_event_date( $event, 'start', $display_time, $format );
}

/**
 * Returns the raw event start date.
 */
function get_event_start_date_raw( int $event = 0, bool $include_time = true ) {
	return get_event_date( $event, 'start', $include_time, 'raw' );
}

/**
 * Returns the event end date.
 *
 * Note: Will return the start date if an end date is not defined.
 */
function get_event_end_date( int $event = 0, bool $display_time = true, string $format = '' ): string {
	if ( ! Custom_Fields::get_field_value( $event, 'end_date', false ) ) {
		return get_event_start_date( $event, $display_time, $format );
	}
	return get_event_date( $event, 'end', $display_time, $format );
}

/**
 * Returns the raw event end date.
 */
function get_event_end_date_raw( int $event = 0, bool $include_time = true ) {
	return get_event_date( $event, 'end', $include_time, 'raw' );
}

/**
 * Returns the event time.
 */
function get_event_time( int $event = 0, string $start_end = 'start', string $format = '' ): string {
	if ( ! $format ) {
		$format = get_default_time_format();
	}

	return get_event_date( $event, $start_end, true, $format );
}

/**
 * Returns the raw event start time.
 */
function get_event_time_raw( int $event = 0, string $start_end = 'start' ): string {
	if ( ! $event ) {
		$event = \get_the_ID();
	}
	
	$date = Custom_Fields::get_field_value( $event, "{$start_end}_date", false );

	if ( ! $date || false === strtotime( $date ) ) {
		return '';
	}

	$array = explode( ' ', $date, 2 );

	return $array[1] ?? '00:00:00';
}

/**
 * Returns the event start time.
 */
function get_event_start_time( int $event = 0, string $format = '' ): string {
	return get_event_time( $event, 'start', $format );
}

/**
 * Returns the raw event start time.
 */
function get_event_start_time_raw( int $event = 0 ): string {
	return get_event_time_raw( $event, 'start' );
}

/**
 * Returns the event end time.
 */
function get_event_end_time( int $event = 0, string $format = '' ): string {
	return get_event_time( $event, 'end', $format );
}

/**
 * Returns the raw event end time.
 */
function get_event_end_time_raw( int $event = 0 ): string {
	return get_event_time_raw( $event, 'end' );
}

/**
 * Checks if an event is all day event.
 */
function is_all_day_event( int $event = 0 ): bool {
	return (bool) Custom_Fields::get_field_value( $event, 'all_day', true );
}

/**
 * Checks if an event is a single day event.
 */
function is_same_day_event( int $event = 0 ): bool {
	return get_event_start_date_raw( $event, false ) === get_event_end_date_raw( $event, false );
}

/**
 * Checks if an event is all day event.
 */
function is_past_event( int $event = 0 ): bool {
	return 'past' === get_event_status( $event );
}

/**
 * Returns an array of supported event statuses.
 */
function get_event_statuses(): array {
	return [
		'past'     => \_x( 'Past', 'Adverb: Event Status', 'just-events' ),
		'ongoing'  => \_x( 'Ongoing', 'Adverb: Event Status', 'just-events' ),
		'upcoming' => \_x( 'Upcoming', 'Adverb: Event Status', 'just-events' ),
	];
}

/**
 * Returns the status.
 */
function get_event_status( int $event = 0 ): string {
	if ( ! $event ) {
		$event = \get_the_ID();
	}

	$end_date = get_event_end_date_raw( $event );

	if ( ! $end_date ) {
		return 'upcoming';
	}

	$current_date_time = get_current_date_time();

	if ( $end_date < $current_date_time ) {
		return 'past';
	} elseif ( $end_date >= $current_date_time && get_event_start_date_raw( $event ) <= $current_date_time ) {
		return 'ongoing';
	} else {
		return 'upcoming';
	}
}

/**
 * Returns the status label
 */
function get_event_status_label( int $event = 0 ): string {
	if ( ! $event ) {
		$event = \get_the_ID();
	}

	return get_event_statuses()[ get_event_status( $event ) ];
}

/**
 * Returns the formatted event date for frontend use.
 */
function get_event_formatted_date( int $event = 0, array $args = [] ): string {
	$default_args = [
		'prefix'    => '',
		'start_end' => '',
		'format'    => '',
		'show_time' => true,
		'separator' => (string) get_option( 'date_separator', ' - ' ),
	];

	\extract( \wp_parse_args( $args, $default_args ) );

	if ( ! $event ) {
		$event = \get_the_ID();
	}

	if ( ! $event ) {
		return '';
	}

	$show_time  = \wp_validate_boolean( $show_time );
	$start_date = '';
	$end_date   = '';

	if ( is_same_day_event( $event ) ) {
		$start_date = get_event_start_date( $event, false, $format );
		if ( is_all_day_event( $event ) ) {
			$separator = '';
		} else {
			if ( $show_time ) {
				$start_date = get_event_start_date( $event, $show_time, $format );
				if ( '<br>' === $separator ) {
					$end_date = get_event_end_date( $event, $show_time, $format );
				} elseif ( $show_time ) {
					$end_date = get_event_end_time( $event );
				}
			} else {
				$separator = '';
			}
		}
	} else {
		if ( 'end' !== $start_end ) {
			$start_date = get_event_start_date( $event, $show_time, $format );
		}
		if ( 'start' !== $start_end ) {
			$end_date = get_event_end_date( $event, $show_time, $format );
		}
	}

	if ( $start_date && $end_date ) {
		$end_date = $separator . $end_date;
	}

	if ( $prefix ) {
		$prefix = "{$prefix} ";
	}
	

	$allowed_html = [
		'br'     => [],
		'strong' => [],
		'div' => [
			'class' => [],
			'title' => [],
			'style' => [],
		],
		'span' => [
			'class' => [],
			'title' => [],
			'style' => [],
		],
	];

	return \wp_kses( $prefix . $start_date . $end_date, $allowed_html );
}

/**
 * Returns the formatted event time for frontend use.
 */
function get_event_formatted_time( int $event = 0, array $args = [] ): string {
	$default_args = [
		'prefix'    => '',
		'format'    => '',
		'start_end' => '',
		'separator' => (string) get_option( 'time_separator', ' - ' ),
	];

	\extract( \wp_parse_args( $args, $default_args ) );

	if ( ! $event ) {
		$event = \get_the_ID();
	}

	if ( ! $event ) {
		return '';
	}

	$start_time = '';
	$end_time = '';

	if ( 'end' !== $start_end ) {
		$start_time = get_event_start_time( $event, $format );
	}
	
	if ( 'start' !== $start_end ) {
		$end_time = get_event_end_time( $event, $format );
		if ( $end_time && 'end' !== $start_end ) {
			$end_time = $separator . $end_time;
		}
	}

	if ( $prefix ) {
		$prefix = "{$prefix} ";
	}

	$allowed_html = [
		'br'     => [],
		'strong' => [],
		'div' => [
			'class' => [],
			'title' => [],
			'style' => [],
		],
		'span' => [
			'class' => [],
			'title' => [],
			'style' => [],
		],
	];

	return \wp_kses( $prefix . $start_time . $end_time, $allowed_html );
}

/**
 * Check if currently in gutenberg edit mode.
 * 
 * Internal use only for the custom blocks render.php files.
 */
function is_gutenberg_edit_mode(): bool {
	return \defined( '\REST_REQUEST' ) && true === \REST_REQUEST;
}