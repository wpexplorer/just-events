<?php declare(strict_types=1);

namespace WPExplorer\Just_Events\Shortcodes;

use WPExplorer\Just_Events\Shortcode_Abstract;
use function WPExplorer\Just_Events\get_event_formatted_time;

\defined( 'ABSPATH' ) || exit;

class Formatted_Event_Time extends Shortcode_Abstract {

	/**
	 * Shortcode tag (name).
	 */
	public const TAG = 'just_events_formatted_event_time';

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Callback function for the shortcode (aka the output).
	 */
	public static function callback( $atts = [] ): string {
		if ( $time = get_event_formatted_time( $atts['event'] ?? get_the_ID(), $atts['separator'] ?? '' ) ) {
			return '<div class="just-events-time">' . $time . '</div>';
		}
		return '';
	}

}

Formatted_Event_Time::register();
