<?php declare(strict_types=1);

namespace WPExplorer\Just_Events\Shortcodes;

use WPExplorer\Just_Events\Shortcode_Abstract;
use function WPExplorer\Just_Events\get_event_formatted_date;

\defined( 'ABSPATH' ) || exit;

class Event_Date extends Shortcode_Abstract {

	/**
	 * Shortcode tag (name).
	 */
	public const TAG = 'je_event_date';

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Callback function for the shortcode (aka the output).
	 */
	public static function callback( $atts = [] ): string {
		$event_id = \absint( $atts['event'] ?? \get_the_ID() );
		if ( $event_id && $date = get_event_formatted_date( $event_id, (array) $atts ) ) {
			return '<div class="just-events-date">' . $date . '</div>';
		}
		return '';
	}

}

Event_Date::register();
