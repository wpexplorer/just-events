<?php declare(strict_types=1);

namespace WPExplorer\Just_Events;

use WPExplorer\Just_Events\Plugin;

\defined( 'ABSPATH' ) || exit;

final class Admin {

	/**
	 * Our options group name.
	 */
	protected const OPTION_GROUP = 'just_events_settings';

	/**
	 * Our options name.
	 */
	protected const OPTION_NAME = 'just_events';

	/**
	 * User capability that grants access to the settings page.
	 */
	protected const USER_CAPABILITY = 'manage_options';

	/**
	 * Static-only class.
	 */
	private function __construct() {}

	/**
	 * Initialize.
	 */
	public static function init(): void {
		add_action( 'admin_menu', [ self::class, 'register_menu_page' ] );

		// Note: Use late hook so devs can hook into admin_init early to add their own settings.
		add_action( 'admin_init', [ self::class, 'register_settings' ], 100 );
	}

	/**
	 * Returns admin settings array.
	 */
	protected static function get_settings(): array {
		$settings = [
			[
				'id'          => 'post_type_has_archive',
				'label'       => \__( 'Enable Archive', 'just-events' ),
				'type'        => 'checkbox',
				'default'     => true,
				'description' => \__( 'Enables the automatic archive page for the Events post type.', 'just-events' ),
				'tab'         => \__( 'General', 'just-events' ),
			],
			[
				'id'          => 'post_type_archive_slug',
				'label'       => \__( 'Archive Slug', 'just-events' ),
				'type'        => 'text',
				'placeholder' => 'events',
				'description' => \__( 'The custom slug for the archive page if enabled.', 'just-events' ),
				'tab'         => \__( 'General', 'just-events' ),
			],
			[
				'id'          => 'post_type_rewrite_slug',
				'label'       => \__( 'Single Slug', 'just-events' ),
				'type'        => 'text',
				'placeholder' => 'event',
				'description' => \__( 'The custom slug for your single events.', 'just-events' ),
				'tab'         => \__( 'General', 'just-events' ),
			],
			[
				'id'          => 'date_format',
				'label'       => \__( 'Date Format', 'just-events' ),
				'type'        => 'text',
				'description' => \sprintf( \__( 'Enter a custom date format to use for your formatted event date. Leave empty to use your WordPress defined date format. %sDocumentation on date and time formatting%s', 'just-events' ), '<br><a href="https://wordpress.org/documentation/article/customize-date-and-time-format/" target="_blank" rel="noopener noreferrer">', ' &#8599;</a>' ),
				'tab'         => \__( 'General', 'just-events' ),
			],
			[
				'id'          => 'time_format',
				'label'       => \__( 'Time Format', 'just-events' ),
				'type'        => 'text',
				'description' => \__( 'Enter a custom date format to use for your formatted event time. Leave empty to use your WordPress defined time format.', 'just-events' ),
				'tab'         => \__( 'General', 'just-events' ),
			],
			[
				'id'          => 'date_separator',
				'label'       => \__( 'Date Separator', 'just-events' ),
				'type'        => 'text',
				'placeholder' => ' - ',
				'description' => \__( 'Separator used in the formatted event date between the start and end dates (include empty spaces if needed).', 'just-events' ),
				'tab'         => \__( 'General', 'just-events' ),
			],
			[
				'id'          => 'time_separator',
				'label'       => \__( 'Time Separator', 'just-events' ),
				'type'        => 'text',
				'placeholder' => ' - ',
				'description' => \__( 'Separator used in the formatted event date between the start and end times (include empty spaces if needed).', 'just-events' ),
				'tab'         => \__( 'General', 'just-events' ),
			],
		];

		/**
		 * Filters the settings list so users can add extra settings.
		 */
		$settings = \apply_filters( 'just_events/admin/settings', $settings );

		return $settings;
	}

	/**
	 * Register the new menu page.
	 */
	public static function register_menu_page() {
		\add_submenu_page(
			'edit.php?post_type=just_event',
			'Just Events',
			\esc_html__( 'Settings', 'just-events' ),
			self::USER_CAPABILITY,
			'just-events-settings',
			[ self::class, 'render_settings_page' ]
		);
	}

	/**
	 * Register the settings.
	 */
	public static function register_settings() {
		\register_setting( self::OPTION_GROUP, self::OPTION_NAME, [
			'sanitize_callback' => [ self::class, 'sanitize_fields' ],
			'default'           => self::get_defaults(),
		] );

		add_settings_section( self::OPTION_GROUP, false, false, self::OPTION_NAME );

		foreach ( self::get_settings() as $args ) {
			$tr_class = '';
			if ( \array_key_exists( 'tab', $args ) ) {
				$tr_class .= 'just-events-admin-tab-item just-events-admin-tab-item--' . \sanitize_html_class( \sanitize_key( $args['tab'] ) );
			}
			\add_settings_field(
				'just_events-' . $args['id'],
				$args['label'],
				[ self::class, 'render_setting_field' ],
				self::OPTION_NAME,
				self::OPTION_GROUP,
				[
					'setting_id'  => $args['id'],
					'field_type'  => $args['type'] ?? 'text',
					'placeholder' => $args['placeholder'] ?? '',
					// core.
					'label_for'   => 'just-events-admin-field--' . \esc_attr( $args['id'] ),
					'class'       => $tr_class,
				]
			);
		}
	}

	/**
	 * Sanitize fields.
	 */
	public static function sanitize_fields( $value ): array {
		$value = (array) $value;
		$new_value = [];
		foreach ( self::get_settings() as $args ) {
			$field_type = $args['type'];
			$new_option_value = $value[ $args['id'] ] ?? '';
			if ( $new_option_value ) {
				$sanitize_callback = $args['sanitize_callback'] ?? self::get_sanitize_callback_by_type( $field_type );
				$new_value[ $args['id'] ] = \call_user_func( $sanitize_callback, $new_option_value, $args );
			} elseif ( 'checkbox' === $field_type ) {
				$new_value[ $args['id'] ] = 0;
			}
		}
		if ( isset( $_POST['je_admin_flush_rewrite_rules'] )
			&& 1 === (int) $_POST['je_admin_flush_rewrite_rules']
		) {
			add_option( 'just_events_flush_rewrite_rules_flag', true );
		}
		return $new_value;
	}

	/**
	 * Returns sanitize callback based on field type.
	 */
	private static function get_sanitize_callback_by_type( $field_type ): string {
		switch ( $field_type ) {
			case 'select':
				return self::class . '::sanitize_select_field';
				break;
			case 'textarea':
				return 'wp_kses_post';
				break;
			case 'checkbox':
				return self::class . '::sanitize_checkbox_field';
				break;
			case 'text':
			default:
				return 'sanitize_text_field';
				break;
		}
	}

	/**
	 * Renders the options page.
	 */
	public static function render_settings_page(): void {
		if ( ! \current_user_can( self::USER_CAPABILITY ) ) {
			return;
		}

		if ( isset( $_GET['settings-updated'] ) ) {
		   \add_settings_error(
			   self::OPTION_NAME . '_mesages',
			   self::OPTION_NAME . '_message',
			   \esc_html__( 'Settings Saved', 'just-events' ),
			   'updated'
			);
		}

		\settings_errors( self::OPTION_NAME . '_mesages' );

		\wp_enqueue_script(
			'just-events-admin',
			\untrailingslashit( \plugin_dir_url( JUST_EVENTS_PLUGIN_FILE ) ) . '/assets/js/admin/settings.js',
			[],
			Plugin::VERSION,
			true
		);

		?>
		<div class="wrap">
			<h1><?php echo \esc_html( get_admin_page_title() ); ?></h1>
			<?php self::render_tabs(); ?>
			<form action="options.php" method="post" class="just-events-admin-options-form"><?php
				\settings_fields( self::OPTION_GROUP );
				\do_settings_sections( self::OPTION_NAME );
				\submit_button( 'Save Settings' );
				echo '<input type="hidden" name="je_admin_flush_rewrite_rules" value="0">';
			?></form>
		</div>
		<?php
	}

	/**
	 * Loops through settings array to get an array of tabs.
	 */
	private static function get_tabs(): array {
		$tabs = [];

		foreach ( self::get_settings() as $setting ) {
			if ( isset( $setting['tab'] ) ) {
				$tabs[ \sanitize_key( $setting['tab'] ) ] = $setting['tab'];
			}
		}

		return $tabs;
	}

	/**
	 * Renders options page tabs.
	 */
	private static function render_tabs(): void {
		$tabs = self::get_tabs();

		if ( empty( $tabs ) || 1 === \count( $tabs ) ) {
			return;
		}

		?>

		<style>.just-events-admin-tab-item{ display: none; ?></style>

		<h2 class="nav-tab-wrapper just-events-admin-tabs"><?php
			$first_tab = true;
			foreach ( $tabs as $id => $label ) {?>
				<a href="#" data-tab="<?php echo \esc_attr( $id ); ?>" class="nav-tab<?php echo $first_tab ? ' nav-tab-active' : ''; ?>"><?php echo \ucfirst( \esc_html( $label ) ); ?></a>
				<?php
				$first_tab = false;
			}
		?></h2>

		<script>
			( function() {
				document.addEventListener( 'click', ( event ) => {
					const target = event.target;
					if ( ! target.closest( '.just-events-admin-tabs a' ) ) {
						return;
					}
					event.preventDefault();
					document.querySelectorAll( '.just-events-admin-tabs a' ).forEach( ( tablink ) => {
						tablink.classList.remove( 'nav-tab-active' );
					} );
					target.classList.add( 'nav-tab-active' );
					targetTab = target.getAttribute( 'data-tab' );
					document.querySelectorAll( '.just-events-admin-options-form .just-events-admin-tab-item' ).forEach( ( item ) => {
						if ( item.classList.contains( `just-events-admin-tab-item--${targetTab}` ) ) {
							item.style.display = 'block';
						} else {
							item.style.display = 'none';
						}
					} );
				} );
				document.addEventListener( 'DOMContentLoaded', function () {
					document.querySelector( '.just-events-admin-tabs .nav-tab' ).click();
				}, false );
			} )();
		</script>

		<?php
	}

	/**
	 * Returns default values.
	 */
	private static function get_defaults(): array {
		$defaults = [];
		foreach ( self::get_settings() as $args ) {
			$defaults[ $args['id'] ] = $args['default'] ?? '';
		}
		return $defaults;
	}

	/**
	 * Sanitizes the checkbox field.
	 */
	private static function sanitize_checkbox_field( $value = '', $field_args = [] ): int {
		return ( 'on' === $value ) ? 1 : 0;
	}

	 /**
	 * Sanitizes the select field.
	 */
	private static function sanitize_select_field( $value = '', $field_args = [] ): string {
		$choices = self::parse_choices( $field_args['choices'] ?? [] );
		return \array_key_exists( $value, $choices ) ? \sanitize_key( $value ) : '';
	}

	/**
	 * Returns an option value.
	 */
	private static function get_option_value( string $id ) {
		$option = \get_option( self::OPTION_NAME );
		if ( \array_key_exists( $id, $option ) ) {
			return $option[ $id ];
		} else {
			foreach ( self::get_settings() as $setting ) {
				if ( $id === $setting['id'] ) {
					return $setting['default'] ?? '';
				}
			}
		}
	}

	/**
	 * Get setting args.
	 */
	private static function get_setting_args( string $id ): array {
		$args = [];
		foreach ( self::get_settings() as $setting ) {
			if ( $id === $setting['id'] ) {
				return $setting;
			}
		}
		return $args;
	}

	/**
	 * Renders a setting field
	 */
	public static function render_setting_field( $args ) {
		$callback = "render_{$args['field_type']}_field";
		if ( \method_exists( self::class, $callback ) ) {
			\call_user_func( [ self::class, $callback ], $args );
		}
	}

	/**
	 * Renders a text field.
	 */
	private static function render_text_field( $args ): void {
		$value       = self::get_option_value( $args['setting_id'] );
		$field_args  = self::get_setting_args( $args['setting_id'] );
		$description = $field_args['description'] ?? '';
		$placeholder = $field_args['placeholder'] ?? '';
		?>
			<input type="text" class="regular-text" id="<?php echo \esc_attr( $args['label_for'] ); ?>" name="<?php echo \esc_attr( self::OPTION_NAME ); ?>[<?php echo \esc_attr( $args['setting_id'] ); ?>]" value="<?php echo \esc_attr( $value ); ?>" placeholder="<?php echo \esc_attr( $field_args['placeholder'] ?? '' ); ?>">
			<?php if ( $description ) { ?>
				<p class="description"><?php echo \wp_kses_post( $description ); ?></p>
			<?php } ?>
		<?php
	}

	/**
	 * Renders a textarea field.
	 */
	private static function render_textarea_field( $args ): void {
		$value       = self::get_option_value( $args['setting_id'] );
		$field_args  = self::get_setting_args( $args['setting_id'] );
		$description = $field_args['description'] ?? '';
		$rows        = $field_args['rows'] ?? '4';
		$cols        = $field_args['cols'] ?? '50';
		?>
			<textarea type="text" id="<?php echo \esc_attr( $args['label_for'] ); ?>" rows="<?php echo \esc_attr( absint( $rows ) ); ?>" cols="<?php echo \esc_attr( absint( $cols ) ); ?>" name="<?php echo \esc_attr( self::OPTION_NAME ); ?>[<?php echo \esc_attr( $args['setting_id'] ); ?>]"><?php echo \esc_attr( $value ); ?></textarea>
			<?php if ( $description ) { ?>
				<p class="description"><?php echo \esc_html( $description ); ?></p>
			<?php } ?>
		<?php
	}

	/**
	 * Renders a checkbox field.
	 */
	private static function render_checkbox_field( $args ): void {
		$value       = self::get_option_value( $args['setting_id'] );
		$field_args  = self::get_setting_args( $args['setting_id'] );
		$description = $field_args['description'] ?? '';
		?>
			<input type="checkbox" id="<?php echo \esc_attr( $args['label_for'] ); ?>" name="<?php echo \esc_attr( self::OPTION_NAME ); ?>[<?php echo \esc_attr( $args['setting_id'] ); ?>]" <?php checked( $value, 1, true ); ?>>
			<?php if ( $description ) { ?>
				<p class="description"><?php echo \esc_html( $description ); ?></p>
			<?php } ?>
		<?php
	}

	/**
	 * Renders a select field.
	 */
	private static function render_select_field( $args ): void {
		$value       = self::get_option_value( $args['setting_id'] );
		$field_args  = self::get_setting_args( $args['setting_id'] );
		$description = $field_args['description'] ?? '';
		$choices     = self::parse_choices( $field_args['choices'] ?? [] );

		if ( ! $choices || ! \is_array( $choices ) ) {
			return;
		}
		?>
			<select id="<?php echo \esc_attr( $args['label_for'] ); ?>" name="<?php echo \esc_attr( self::OPTION_NAME ); ?>[<?php echo \esc_attr( $args['setting_id'] ); ?>]">
				<?php foreach ( $choices as $choice_v => $label ) { ?>
					<option value="<?php echo \esc_attr( \sanitize_key( $choice_v ) ); ?>" <?php \selected( $choice_v, $value, true ); ?>><?php echo \esc_html( $label ); ?></option>
				<?php } ?>
			</select>
			<?php if ( $description ) { ?>
				<p class="description"><?php echo \wp_kses_post( $description ); ?></p>
			<?php } ?>
		<?php
	}

	/**
	 * Parse select choices.
	 */
	private static function parse_choices( $choices = [] ): array {
		if ( \is_array( $choices ) ) { 
			return $choices;
		}
		if ( \is_callable( $choices ) ) {
			return (array) \call_user_func( $choices );
		}
		return $choices;
	}

}

Admin::init();