<?php

namespace Just_Events;

$is_gutenberg = is_gutenberg_edit_mode();

if ( ! isset( $block ) && ! empty( $attributes['event'] ) ) {
	$event_id = \absint( $attributes['event']);
} elseif ( ! empty( $block->context['postId'] ) ) {
	$event_id = \absint( \sanitize_text_field( $block->context['postId'] ) );
} elseif ( $is_gutenberg && isset( $_GET['postId'] ) ) {
	// Fix for Gutenberg issue: https://github.com/WordPress/gutenberg/issues/34882
	$event_id = \absint( \sanitize_text_field( \wp_unslash( $_GET['postId'] ) ) );
} else {
	$event_id = get_the_ID();;
}

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
	foreach ( $args as $k => $v ) {
		if ( '' === $v ) {
			unset( $args[ $k ] );
		}
	}
	$date = get_event_formatted_time( $event_id, $args ?: [] );
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
		echo \wp_kses( $date, $allowed_html );
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
		\wp_kses( $date, $allowed_html )
	);
}
