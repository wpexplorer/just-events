<?php

namespace WPExplorer\Just_Events;

$is_gutenberg =  defined( 'REST_REQUEST' ) && REST_REQUEST;
$event_id	  = \absint( $_GET['postId'] ?? $block->context['postId'] ?? $attributes['event'] ?? 0 );
$event_id	  = $event_id ?: \get_the_ID();

if ( ! $event_id && $is_gutenberg ) {
	$link = '#';
} else {
	$link = get_event_link();
}

if ( ! $link ) {
	return;
}

$link_text = ! empty( $attributes['text'] ) ? \sanitize_text_field( $attributes['text'] ) : get_event_link_text();
$target    = ( isset( $attributes['target_blank'] ) && \wp_validate_boolean( $attributes['target_blank'] ) ) ? ' target="_blank"' : '';

$link_html = '<a href="' . \esc_url( $link ) . '"' . $target . '>' . \esc_html( $link_text ) . '</a>';

if ( $is_gutenberg ) {
   echo $link_html;
} else {
	$wrapper_attributes = [];

	if ( isset( $attributes['textAlign'] ) ) {
		$wrapper_attributes[ 'class' ] = 'has-text-align-' . $attributes['textAlign'];
	}

	printf( '<div %s>%s</div>', get_block_wrapper_attributes( $wrapper_attributes ), $link_html );
}
