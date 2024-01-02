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
