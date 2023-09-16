<?php

namespace WPExplorer\Just_Events;

$event_id = \absint( $_GET['postId'] ?? $block->context['postId'] ?? $attributes['event'] ?? 0 );

if ( ! $event_id ) {
    return;
}

$args = [
    'format'    => $attributes['format'] ?? '',
    'prefix'    => $attributes['prefix'] ?? '',
    'separator' => $attributes['separator'] ?? '',
    'start_end' => $attributes['startEnd'] ?? '',
    'show_time' => $attributes['showTime'] ?? true,
];

$date = get_event_formatted_date( $event_id, (array) \array_filter( $args ) );

if ( ! $date ) {
    $date = \esc_html__( 'Event date undefined', 'just-events' );
}

if ( defined( 'REST_REQUEST' ) && REST_REQUEST ) {
   echo $date;
} else {
    $wrapper_attributes = [];

    if ( isset( $attributes['textAlign'] ) ) {
		$wrapper_attributes[ 'class' ] = 'has-text-align-' . $attributes['textAlign'];
	}

    printf( '<div %s>%s</div>', get_block_wrapper_attributes( $wrapper_attributes ), $date );
}
