<?php declare(strict_types=1);

namespace Just_Events\Shortcodes;

use Just_Events\Shortcode_Abstract;
use function Just_Events\get_event_formatted_time;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Event_Time extends Shortcode_Abstract {

	/**
	 * Shortcode tag (name).
	 */
	public const TAG = 'just_events_event_time';

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Callback function for the shortcode (aka the output).
	 */
	public static function callback( $atts = [] ): string {
		$event_id = \absint( $atts['event'] ?? \get_the_ID() );
		if ( $event_id && $time = get_event_formatted_time( $event_id, (array) $atts ) ) {
			return '<div class="just-events-time">' . $time . '</div>';
		}
		return '';
	}

}

Event_Time::register();
