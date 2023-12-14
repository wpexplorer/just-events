<?php

namespace WPExplorer\Just_Events;

$is_gutenberg = \defined( '\REST_REQUEST' ) && \REST_REQUEST;
$event_id	  = \sanitize_text_field( $_GET['postId'] ?? $block->context['postId'] ?? $attributes['event'] ?? 0 );
$event_id	  = \absint( $event_id ?: \get_the_ID() );

if ( ! $event_id && $is_gutenberg ) {
	$status = 'upcoming';
} else {
	$status = get_event_status( $event_id );
}

$wrapper_attributes = [];

if ( isset( $attributes['textAlign'] ) ) {
	$wrapper_attributes['class'] = 'has-text-align-' . \sanitize_html_class( $attributes['textAlign'] );
}

printf(
	'<div %s>%s</div>',
	\get_block_wrapper_attributes( $wrapper_attributes ),
	'<span class="just-events-status-badge just-events-status-badge--' . \esc_attr( \sanitize_html_class( $status ) ) . '">' . esc_html( get_event_statuses()[ $status ] ) . '</span>'
);
