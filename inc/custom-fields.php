<?php declare(strict_types=1);

namespace WPExplorer\Just_Events;

use WPExplorer\Just_Events\Plugin;

\defined( 'ABSPATH' ) || exit;

class Custom_Fields {

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Initialize.
	 */
	public static function init(): void {
		\add_action( 'admin_init', [ self::class, 'on_admin_init' ] );
	}

	/**
	 * Runs on the admin_init hook.
	 */
	public static function on_admin_init(): void {
		if ( ! self::get_fields() ) {
			return;
		}

		\add_action( 'load-post.php', [ self::class, 'register_hooks' ] );
		\add_action( 'load-post-new.php', [ self::class, 'register_hooks' ] );
		\add_action( 'admin_enqueue_scripts', [ self::class, 'meta_box_scripts' ] );
	}

	/**
	 * Register hooks.
	 */
	public static function register_hooks(): void {
		\add_action( 'add_meta_boxes_' . Plugin::POST_TYPE, [ self::class, 'add_meta_box' ] );
		\add_action( 'save_post_' . Plugin::POST_TYPE, [ self::class, 'save_meta_box' ] );
	}

	/**
	 * Add meta box.
	 */
	public static function add_meta_box(): void {
		\add_meta_box(
			'just_events',
			esc_html__( 'Event Settings', 'just-events' ),
			[ self::class, 'render_meta_box' ],
			Plugin::POST_TYPE,
			'advanced',
			'high'
		);
	}

	/**
	 * Enqueue scripts for the meta box.
	 */
	public static function meta_box_scripts( $hook_suffix ): void {
		if ( \in_array( $hook_suffix, [ 'post.php', 'post-new.php' ] ) ) {
			$screen = \get_current_screen();
			if ( \is_object( $screen ) && ! empty( $screen->post_type ) && $screen->post_type === Plugin::POST_TYPE ) {
				self::enqueue_meta_box_scripts();
			}
		}
	}

	/**
	 * Enqueues the meta box scripts.
	 */
	private static function enqueue_meta_box_scripts(): void {
		$asset_dir = \untrailingslashit( \plugin_dir_url( JUST_EVENTS_PLUGIN_FILE ) );

		\wp_enqueue_script(
			'just-events-meta-box',
			"{$asset_dir}/assets/js/admin/meta-box.js",
			[],
			Plugin::VERSION,
			true
		);

		\wp_enqueue_style(
			'just-events-meta-box',
			"{$asset_dir}/assets/css/admin/meta-box.css",
			[],
			Plugin::VERSION,
			'all'
		);
	}

	/**
	 * Renders the meta box.
	 */
	public static function render_meta_box( $post ): void {
		\wp_nonce_field(
			'just_events_meta_box_action',
			'just_events_meta_box_nonce',
		);

		// @todo Show notice if end date is before start date.

		// Get default time zone and set UTC timezone to prevent issues.
		$default_timezone = \date_default_timezone_get();
		\date_default_timezone_set( 'UTC' );

		?>

		<table class="form-table just-events-meta-box-table">
			<?php foreach ( self::get_fields() as $field ) {
				$hidden = false;
				if ( \in_array( self::get_field_id( $field ), [ 'start_time', 'end_time' ] ) && self::get_field_value( $post->ID, 'all_day' ) ) {
					$hidden = true;
				}
				?>
				<tr<?php echo ( $hidden ? ' class="hidden"' : '' ); ?>>
					<th><label for="just-events-<?php echo \esc_attr( \sanitize_key( $field['id'] ) ); ?>"><?php echo \esc_html( $field['label'] ); ?></label></th>
					<td><?php self::render_meta_box_field( $field, $post->ID ); ?></td>
				</tr>
			<?php } ?>
		</table>

		<?php

		// Restore the default timezone.
		\date_default_timezone_set( $default_timezone );
	}

	/**
	 * Renders a metabox text field.
	 */
	private static function render_meta_box_field( array $field, int $post_id ): void {
		$field_id   = self::get_field_id( $field );
		$input_type = self::get_field_type( $field_id );
		$value      = self::get_field_value( $post_id, $field_id );

		if ( 'all_day' === $field_id ) {
			$checked = checked( $value, true, false );
			echo '<input id="just-events-' . \esc_attr( $field_id ) . '" name="just_events_' . \esc_attr( $field_id ) . '" type="' . \esc_attr( $input_type ) . '" ' . $checked . '>';
		} else {
			echo '<input id="just-events-' . \esc_attr( $field_id ) . '" name="just_events_' . \esc_attr( $field_id ) . '" type="' . \esc_attr( $input_type ) . '" value="'. \esc_attr( $value ) . '">';
		}
	}

	/**
	 * Returns array of custom fields.
	 */
	private static function get_fields(): array {
		$fields = [
			[
				'label' => \__( 'All Day Event?', 'just-events' ),
				'id'    => 'all_day',
				'type'  => 'checkbox',
			],
			[
				'label' => \__( 'Start Date', 'just-events' ),
				'id'    => 'start_date',
				'type'  => 'date',
			],
			[
				'label' => \__( 'End Date', 'just-events' ),
				'id'    => 'end_date',
				'type'  => 'date',
			],
			[
				'label' => \__( 'Start Time', 'just-events' ),
				'id'    => 'start_time',
				'type'  => 'time',
			],
			[
				'label' => \__( 'End Time', 'just-events' ),
				'id'    => 'end_time',
				'type'  => 'time',
			],
			[
				'label' => \__( 'External Link', 'just-events' ),
				'id'    => 'link',
				'type'  => 'url',
			],
		];

		/**
		 * Filters the meta box fields.
		 *
		 * This filter is intended for removing fields only. If you use the filter to add new fields
		 * they won't be saved.
		 *
		 * @param array $fields
		 */
		$fields = \apply_filters( 'just_events/custom_fields/fields', $fields );

		return (array) $fields;
	}

	/**
	 * Returns the field id.
	 */
	private static function get_field_id( array $field ): string {
		return \sanitize_key( $field['id'] );
	}

	/**
	 * Returns a field type.
	 */
	private static function get_field_type( string $field_id ): string {
		$type = 'text';
		foreach ( self::get_fields() as $field ) {
			if ( $field['id'] === $field_id ) {
				$type = $field['type'] ?? 'text';
				break;
			}
		}
		return $type;
	}

	/**
	 * Returns a field value.
	 */
	public static function get_field_value( int $post_id, string $key, bool $format_value = true, string $format = '' ): string {

		switch ( $key ) {
			case 'start_date':
			case 'start_time':
				$meta_key = '_just_events_start_date';
				break;
			case 'end_date':
			case 'end_time':
				$meta_key = '_just_events_end_date';
				break;
			default:
				$meta_key = "_just_events_{$key}";
				break;
		}

		$value = \get_post_meta( $post_id, $meta_key, true );

		if ( $format_value && $value ) {
			switch ( $key ) {
				case 'start_date':
				case 'end_date':
					if ( $value = \strtotime( $value ) ) {
						$value = \date( $format ?: 'Y-m-d', $value );
					}
					break;
				case 'start_time':
				case 'end_time':
					if ( $value = \strtotime( $value ) ) {
						$value = \date( $format ?: 'H:i:s', $value );
					}
					break;
				case 'all_day':
					$value = \wp_validate_boolean( $value );
					break;
			}
		}

		return (string) $value;
	}

	/**
	 * Save the meta box.
	 */
	public static function save_meta_box( $post_id ): void {

		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( \defined( '\DOING_AUTOSAVE' ) && \DOING_AUTOSAVE ) {
			return;
		}

		// Check post type.
		if ( Plugin::POST_TYPE !== \get_post_type( $post_id ) ) {
			return;
		}

		// Check if our nonce is set.
		if ( ! \array_key_exists( 'just_events_meta_box_nonce', $_POST ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! \wp_verify_nonce( $_POST['just_events_meta_box_nonce' ], 'just_events_meta_box_action' ) ) {
			return;
		}

		// Check the user's permissions.
		if ( ! \current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		/* OK, it's safe for us to save the data now. Now we can loop through fields */

		// Check if it's an all day event (aka no times).
		$all_day = \array_key_exists( 'just_events_all_day', $_POST );

		// Change the default time zone to UTC for saving the start/end dates.
		$default_timezone = \date_default_timezone_get();
		\date_default_timezone_set( 'UTC' );

		// Get start date.
		$start_date = isset( $_POST['just_events_start_date'] ) ? \sanitize_text_field( $_POST['just_events_start_date'] ) : '';

		// Save dates.
		if ( $start_date ) {

			// Save start date.
			$start_time = ( ! $all_day && isset( $_POST['just_events_start_time'] ) ) ? \sanitize_text_field( $_POST['just_events_start_time'] ) : '';
			\update_post_meta( $post_id, '_just_events_start_date', self::sanitize_date_for_db( $start_date . $start_time ) );

			// Save end date.
			$end_date = isset( $_POST['just_events_end_date'] ) ? \sanitize_text_field( $_POST['just_events_end_date'] ) : '';

			if ( ! $end_date ) {
				$end_date = $start_date; // end date should fallback to the start date if empty.
			}

			$end_time = ( ! $all_day && isset( $_POST['just_events_end_time'] ) ) ? \sanitize_text_field( $_POST['just_events_end_time'] ) : '';
			\update_post_meta( $post_id, '_just_events_end_date', self::sanitize_date_for_db( $end_date . $end_time ) );

		}

		// Restore the default timezone.
		\date_default_timezone_set( $default_timezone );

		// Save link field.
		if ( \array_key_exists( 'just_events_link', $_POST ) ) {
			if ( $_POST['just_events_link'] ) {
				\update_post_meta( $post_id, '_just_events_link', \esc_url( \sanitize_text_field( $_POST['just_events_link'] ) ) );
			} else {
				\delete_post_meta( $post_id, '_just_events_link' );
			}
		}

		// Save all day field.
		if ( $all_day ) {
			\update_post_meta( $post_id, '_just_events_all_day', 1 );
		} else {
			\delete_post_meta( $post_id, '_just_events_all_day' );
		}

		\do_action( 'just_events/custom_fields/save_meta_box', $post_id );
	}

	/**
	 * Sanitize date for db.
	 */
	public static function sanitize_date_for_db( string $date ) {
		return \sanitize_text_field( \date( 'Y-m-d H:i:s', \strtotime( $date ) ) );
	}
}

Custom_Fields::init();