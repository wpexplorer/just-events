<?php

namespace WPExplorer\Just_Events;

$is_gutenberg =  defined( 'REST_REQUEST' ) && REST_REQUEST;
$event_id     = \absint( $_GET['postId'] ?? $block->context['postId'] ?? $attributes['event'] ?? 0 );
$event_id     = $event_id ?: \get_the_ID();

$args = [
    'format'    => $attributes['format'] ?? '',
    'prefix'    => $attributes['prefix'] ?? '',
    'separator' => $attributes['separator'] ?? '',
    'start_end' => $attributes['startEnd'] ?? '',
    'show_time' => $attributes['showTime'] ?? true,
];

if ( ! $event_id && $is_gutenberg ) {
    $format    = $args['format'] ?: get_default_date_format( $args['show_time'] );
    $date      = (string) \wp_date( $format, current_time( 'timestamp' ) );
} else {
    $date = get_event_formatted_date( $event_id, (array) \array_filter( $args ) );
}

if ( ! $date ) {
    $date = \esc_html__( 'Event date undefined', 'just-events' );
}

if ( $is_gutenberg ) {
   echo $date;
} else {
    $wrapper_attributes = [];

    if ( isset( $attributes['textAlign'] ) ) {
		$wrapper_attributes[ 'class' ] = 'has-text-align-' . $attributes['textAlign'];
	}

    printf( '<div %s>%s</div>', get_block_wrapper_attributes( $wrapper_attributes ), $date );
}