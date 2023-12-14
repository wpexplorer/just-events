<?php

namespace WPExplorer\Just_Events;

$is_gutenberg = \defined( '\REST_REQUEST' ) && \REST_REQUEST;

if ( $is_gutenberg ) {
	$link = '#';
} else {
	$event_id = \sanitize_text_field( $_GET['postId'] ?? $block->context['postId'] ?? $attributes['event'] ?? 0 );
	$link     = get_event_link( \absint( $event_id ?: \get_the_ID() ) );
}

if ( ! $link ) {
	return;
}

$wrapper_attributes = [];
$extra_class        = '';
$link_class         = 'wp-block-just-events-link__a';
$link_text          = ! empty( $attributes['text'] ) ? \sanitize_text_field( $attributes['text'] ) : get_event_link_text();
$target             = ( isset( $attributes['targetBlank'] ) && \wp_validate_boolean( $attributes['targetBlank'] ) ) ? ' target="_blank"' : '';
$is_button          = isset( $attributes['design'] ) && 'button' === $attributes['design'];

if ( $is_button ) {
	$link_class .= ' wp-element-button';
}

if ( $is_gutenberg ) {
	echo '<a class="' . \esc_attr( $link_class ) . '" href="' . \esc_url( $link ) . '"' . $target . '>' . \esc_html( $link_text ) . '</a>';
} else {

	$wrapper_attributes = [];

	if ( isset( $attributes['textAlign'] ) ) {
		$wrapper_attributes['class'] = 'has-text-align-' . \sanitize_html_class( $attributes['textAlign'] );
	}

	printf(
		'<div %s>%s</div>',
		\get_block_wrapper_attributes( $wrapper_attributes ),
		'<a class="' . \esc_attr( $link_class ) . '" href="' . \esc_url( $link ) . '"' . $target . '>' . \esc_html( $link_text ) . '</a>'
	);
}
