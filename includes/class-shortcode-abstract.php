<?php declare(strict_types=1);

namespace Just_Events;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

abstract class Shortcode_Abstract {

	/**
	 * Shortcode tag (name).
	 */
	public const TAG = '';

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Callback function for the shortcode (aka the output).
	 */
	abstract public static function callback( array $atts = [] );

	/**
	 * Callback function for the shortcode (aka the output).
	 */
	static public function register() {
		\add_shortcode( static::TAG, [ static::class, 'callback' ] );
	}

}
