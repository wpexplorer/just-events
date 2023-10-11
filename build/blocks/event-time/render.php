<?php

namespace WPExplorer\Just_Events;

$is_gutenberg = \defined( '\REST_REQUEST' ) && \REST_REQUEST;
$event_id	  = \absint( $_GET['postId'] ?? $block->context['postId'] ?? $attributes['event'] ?? 0 );
$event_id	  = $event_id ?: \get_the_ID();

$args = [
	'format'    => $attributes['format'] ?? '',
	'prefix'    => $attributes['prefix'] ?? '',
	'separator' => $attributes['separator'] ?? '',
	'start_end' => $attributes['startEnd'] ?? '',
];

if ( ! $event_id && $is_gutenberg ) {
	$date = (string) \wp_date( $args['format'] ?: get_default_time_format(), \current_time( 'timestamp' ) );
	if ( $args['prefix'] ) {
		$date = $args['prefix'] . ' ' . $date;
	}
	$date = \wp_kses_post( $date );
} else {
	foreach( $args as $k => $v ) {
		if ( '' === $v ) {
			unset( $args[ $k ] );
		}
	}
	$date = get_event_formatted_time( $event_id, $args ?: [] );
}

if ( ! $date ) {
	$date = \esc_html__( 'Event date undefined', 'just-events' );
}

if ( $is_gutenberg ) {
   echo $date;
} else {
	$wrapper_attributes = [];

	if ( isset( $attributes['textAlign'] ) ) {
		$wrapper_attributes['class'] = 'has-text-align-' . \sanitize_html_class( $attributes['textAlign'] );
	}

	\printf( '<div %s>%s</div>', \get_block_wrapper_attributes( $wrapper_attributes ), $date );
}
