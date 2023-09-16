<?php declare(strict_types=1);

namespace WPExplorer\Just_Events\Shortcodes;

use WPExplorer\Just_Events\Shortcode_Abstract;
use function WPExplorer\Just_Events\get_event_formatted_date;

\defined( 'ABSPATH' ) || exit;

class Formatted_Event_Date extends Shortcode_Abstract {

	/**
	 * Shortcode tag (name).
	 */
	public const TAG = 'just_events_formatted_event_date';

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Callback function for the shortcode (aka the output).
	 */
	public static function callback( $atts = [] ): string {
		if ( $date = get_event_formatted_date( $atts['event'] ?? get_the_ID(), (array) $atts ) ) {
			return '<div class="just-events-date">' . $date . '</div>';
		}
		return '';
	}

}

Formatted_Event_Date::register();
