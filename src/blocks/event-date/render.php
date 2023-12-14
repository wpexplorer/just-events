<?php

namespace WPExplorer\Just_Events;

$is_gutenberg = \defined( '\REST_REQUEST' ) && \REST_REQUEST;
$event_id	  = \sanitize_text_field( $_GET['postId'] ?? $block->context['postId'] ?? $attributes['event'] ?? 0 );
$event_id	  = \absint( $event_id ?: \get_the_ID() );

$args = [
	'format'	=> $attributes['format'] ?? '',
	'prefix'	=> $attributes['prefix'] ?? '',
	'separator' => $attributes['separator'] ?? '',
	'start_end' => $attributes['startEnd'] ?? '',
	'show_time' => $attributes['showTime'] ?? true,
];

if ( ! $event_id && $is_gutenberg ) {
	$date = (string) \wp_date( $args['format'] ?: get_default_date_format( $args['show_time'] ), current_time( 'timestamp' ) );
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
	$date = get_event_formatted_date( $event_id, $args ?: [] );
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

if ( $is_gutenberg ) {
	if ( $date ) {
		echo wp_kses( $date, $allowed_html );
	} else {
		\esc_html_e( 'Event date undefined', 'just-events' );
	}
} elseif ( $date ) {
	$wrapper_attributes = [];

	if ( isset( $attributes['textAlign'] ) ) {
		$wrapper_attributes['class'] = 'has-text-align-' . \sanitize_html_class( $attributes['textAlign'] );
	}

	\printf(
		'<div %s>%s</div>',
		\get_block_wrapper_attributes( $wrapper_attributes ),
		wp_kses( $date, $allowed_html )
	);
}
