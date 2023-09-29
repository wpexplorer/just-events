<?php

namespace WPExplorer\Just_Events;

$is_gutenberg = \defined( '\REST_REQUEST' ) && \REST_REQUEST;
$event_id	  = \absint( $_GET['postId'] ?? $block->context['postId'] ?? $attributes['event'] ?? 0 );
$event_id	  = $event_id ?: \get_the_ID();

if ( ! $event_id && $is_gutenberg ) {
	$status = 'upcoming';
} else {
	$status = get_event_status( $event_id );
}

$badge = '<span class="just-events-status-badge just-events-status-badge--' . \esc_attr( \sanitize_html_class( $status ) ) . '">' . esc_html( get_event_statuses()[ $status ] ) . '</span>';

printf( '<div %s>%s</div>', \get_block_wrapper_attributes(), $badge );
