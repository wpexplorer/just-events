<?php declare(strict_types=1);

namespace WPExplorer\Just_Events;

use WPExplorer\Just_Events\Custom_Fields;

\defined( 'ABSPATH' ) || exit;

/**
 * Returns plugin option value.
 */
function get_option( string $key, $default_value = '' ) {
	return \get_option( 'just_events' )[ $key ] ?? $default_value;
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
 * Returns the event date.
 */
function get_event_date( string $start_end, int $event = 0, bool $display_time = true, string $format = '' ): string {
	$date = Custom_Fields::get_field_value( $event, "{$start_end}_date", false );

	if ( ! $date ) {
		return '';
	}

	if ( is_all_day_event( $event ) ) {
		$display_time = false; // never display time for all day events.
	}

	$date      = new \DateTime( $date, wp_timezone() );
	$timestamp = $date->format( 'U' );
	$format    = $format ?: get_default_date_format( $display_time );

	return (string) \wp_date( $format, $timestamp );
}

/**
 * Returns the event start date.
 */
function get_event_start_date( int $event = 0, bool $display_time = true, string $format = '' ): string {
	return get_event_date( 'start', $event, $display_time, $format );
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
	return get_event_date( 'end', $event, $display_time, $format );
}

/**
 * Returns the event time.
 */
function get_event_time( string $start_end, int $event = 0, string $format = '' ): string {
	if ( ! $format ) {
		$format = get_default_time_format();
	}

	return get_event_date( $start_end, $event, true, $format );
}

/**
 * Returns the event start time.
 */
function get_event_start_time( int $event = 0, string $format = '' ): string {
	return get_event_time( 'start', $event, $format );
}

/**
 * Returns the event end time.
 */
function get_event_end_time( int $event = 0, string $format = '' ): string {
	return get_event_time( 'end', $event, $format );
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
	return get_event_start_date( $event, false ) === get_event_end_date( $event, false );
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

	// Get event data.
	$is_all_day  = is_all_day_event( $event );
	$is_same_day = is_same_day_event( $event );

	if ( $is_same_day ) {
		$start_date = get_event_start_date( $event, false, $format );
		if ( $is_all_day ) {
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
