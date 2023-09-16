<?php

namespace WPExplorer\Just_Events;

$event_id = absint( $attributes['event'] ?? $block->context['postId'] ?? '' );

if ( ! $event_id ) {
    return;
}

$date = get_event_formatted_date( $event_id, (array) $attributes );

if ( ! $date ) {
    $date = esc_html__( 'Event date undefined', 'just-events' );
}

echo '<div class="just-events-date">' . $date . '</div>';
