<?php declare(strict_types=1);

namespace WPExplorer\Just_Events\Integration;

use WPExplorer\Just_Events\Plugin;

\defined( 'ABSPATH' ) || exit;

final class Post_Types_Unlimited {

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Initialize.
	 */
	public static function init(): void {
		\add_filter( 'ptu/taxonomies/meta_box_tabs', [ self::class, 'filter_taxonomies_meta_box_tabs' ] );
		\add_filter( 'just_events/is_archive', [ self::class, 'filter_is_event_archive' ] );
	}

	/**
	 * Register new metabox tabs for taxonomies.
	 */
	public static function filter_taxonomies_meta_box_tabs( array $tabs ): array {
		$tabs[] = [
			'id'     => 'just_events',
			'title'  => 'Just Events',
			'fields' => [
				[
					'name' => \esc_html__( 'Is an Event Archive?', 'just-events' ),
					'desc' => \esc_html__( 'Enable to apply the same sorting and hiding of past events to your taxonomy archives.', 'just-events' ),
					'id'   => 'just_events_is_archive',
					'type' => 'checkbox',
				]
			],
		];
		return $tabs;
	}

	/**
	 * Hooks into 'just_events/is_archive' to set defined taxonomies as event archives.
	 */
	public static function filter_is_event_archive( $check ): bool {
		if ( \is_tax()
			&& ! empty( get_queried_object()->taxonomy )
			&& true === (bool) self::get_tax_setting_value( (string) get_queried_object()->taxonomy, 'is_archive' )
		) {
			$check = true;
		}
		return $check;
	}

	/**
	 * Returns an array of registered PTU taxonomies.
	 */
	private static function get_ptu_taxonomies(): array {
		if ( \is_callable( '\PTU\Taxonomies::get_registered_items' ) ) {
			$taxonomies = \PTU\Taxonomies::get_registered_items();
		}
		return ( isset( $taxonomies ) && \is_array( $taxonomies ) ) ? $taxonomies : [];
	}

	/**
	 * Return the value of a PTU taxonomy setting.
	 */
	private static function get_tax_setting_value( string $tax, string $setting_id ) {
		$taxes = self::get_ptu_taxonomies();
		if ( ! empty( $taxes[ $tax ] ) ) {
			return \get_post_meta( $taxes[ $tax ], "_ptu_just_events_{$setting_id}", true );
		}
	}

}

Post_Types_Unlimited::init();
