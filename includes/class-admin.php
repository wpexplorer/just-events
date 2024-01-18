<?php declare(strict_types=1);

namespace Just_Events;

use Just_Events\Plugin;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

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

		// Note: Use late hook so devs can hook into admin_init early to add their own settings via the hook.
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
				'id'          => 'hide_past_events',
				'label'       => \__( 'Hide Past Events', 'just-events' ),
				'type'        => 'checkbox',
				'default'     => true,
				'description' => \sprintf(
					\__( 'Check to hide all past events from archives.%sImportant%s: This option will only hide past events from the event archives and event based search results. It will not hide any events displayed by 3rd party themes or plugins. And if your site is using any form of caching you may want to exclude your event archives from caching or make sure to clear your cache manually as events expire or every so often.', 'just-events' ),
					'<br><strong>',
					'</strong>'
				),
				'tab'         => \__( 'General', 'just-events' ),
			],
			[
				'id'                => 'post_type_archive_slug',
				'label'             => \__( 'Archive Slug', 'just-events' ),
				'type'              => 'text',
				'placeholder'       => 'events',
				'sanitize_callback' => 'sanitize_title',
				'description'       => \__( 'The custom slug for the archive page if enabled.', 'just-events' ),
				'tab'               => \__( 'General', 'just-events' ),
			],
			[
				'id'                => 'post_type_rewrite_slug',
				'label'             => \__( 'Single Slug', 'just-events' ),
				'type'              => 'text',
				'placeholder'       => 'event',
				'sanitize_callback' => 'sanitize_title',
				'description'       => \__( 'The custom slug for your single events.', 'just-events' ),
				'tab'               => \__( 'General', 'just-events' ),
			],
			[
				'id'                => 'posts_per_page',
				'label'             => \__( 'Events Per Page', 'just-events' ),
				'type'              => 'text',
				'sanitize_callback' => [ self::class, 'sanitize_posts_per_page' ],
				'placeholder'       => \get_option( 'posts_per_page', 10 ),
				'description'       => \__( 'How many events to display on a per paginated page basis. The default value is defined under Settings > Reading > Blog pages show at most.', 'just-events' ),
				'tab'               => \__( 'General', 'just-events' ),
			],
			[
				'id'                => 'date_format',
				'label'             => \__( 'Date Format', 'just-events' ),
				'type'              => 'text',
				'sanitize_callback' => [ self::class, 'sanitize_date_format_field' ],
				'description'       => \__( 'Enter a custom date format to use for your formatted event date. Leave empty to use your WordPress defined date format.', 'just-events' ) . '<br>' . \sprintf(
					\__( '%sDocumentation on date and time formatting%s', 'just-events' ),
					'<a href="https://wordpress.org/documentation/article/customize-date-and-time-format/" target="_blank" rel="noopener noreferrer">',
					' &#8599;</a>'
				),
				'tab'                => \__( 'General', 'just-events' ),
			],
			[
				'id'                => 'time_format',
				'label'             => \__( 'Time Format', 'just-events' ),
				'type'              => 'text',
				'sanitize_callback' => [ self::class, 'sanitize_time_format_field' ],
				'description'       => \__( 'Enter a custom date format to use for your formatted event time. Leave empty to use your WordPress defined time format.', 'just-events' ) . '<br>' . \sprintf(
					\__( '%sDocumentation on date and time formatting%s', 'just-events' ),
					'<a href="https://wordpress.org/documentation/article/customize-date-and-time-format/" target="_blank" rel="noopener noreferrer">',
					' &#8599;</a>'
				),
				'tab'               => \__( 'General', 'just-events' ),
			],
			[
				'id'                => 'date_separator',
				'label'             => \__( 'Date Separator', 'just-events' ),
				'type'              => 'text',
				'placeholder'       => ' - ',
				'sanitize_callback' => [ self::class, 'sanitize_date_separator_field' ],
				'description'       => \__( 'Separator used in the formatted event date between the start and end dates (include empty spaces if needed). Enter &lt;br&gt; to place the start and end dates on different lines.', 'just-events' ),
				'tab'               => \__( 'General', 'just-events' ),
			],
			[
				'id'          => 'time_prefix',
				'label'       => \__( 'Time Prefix', 'just-events' ),
				'type'        => 'text',
				'placeholder' => ' @ ',
				'description' => \__( 'Prefix shown before the event date time. Value must use proper string formatting with backslashes added between letters.', 'just-events' ) . '<br>' . \sprintf(
					\__( '%sDocumentation on date and time formatting%s', 'just-events' ),
					'<a href="https://wordpress.org/documentation/article/customize-date-and-time-format/#format-string-examples" target="_blank" rel="noopener noreferrer">',
					' &#8599;</a>'
				),
				'tab'         => \__( 'General', 'just-events' ),
			],
			[
				'id'          => 'time_separator',
				'label'       => \__( 'Time Separator', 'just-events' ),
				'type'        => 'text',
				'placeholder' => ' - ',
				'description' => \__( 'Separator used when displaying only the event time between the start and end times (include empty spaces if needed).', 'just-events' ),
				'tab'         => \__( 'General', 'just-events' ),
			],
			[
				'id'          => 'link_text',
				'label'       => \__( 'Default Link Text', 'just-events' ),
				'type'        => 'text',
				'placeholder' => \__( 'View Event', 'just-events' ),
				'description' => \__( 'Enter the default text for the event link block.', 'just-events' ),
				'tab'         => \__( 'General', 'just-events' ),
			],
		];

		/**
		 * Filters the settings list so users can add extra settings.
		 */
		$settings = \apply_filters( 'just_events/admin/settings', $settings );

		return (array) $settings;
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

		if ( $new_value ) {
			self::add_settings_error( \esc_html__( 'Settings Saved', 'just-events' ), 'updated' );
		}

		if ( \array_key_exists( '_wpnonce', $_POST )
			&& \wp_verify_nonce( \sanitize_text_field( \wp_unslash( $_POST['_wpnonce'] ) ), self::OPTION_GROUP . '-options' )
			&& \array_key_exists( 'just_events_admin_flush_rewrite_rules', $_POST )
			&& 1 === (int) \sanitize_text_field( \wp_unslash( $_POST['just_events_admin_flush_rewrite_rules'] ) )
		) {
			\add_option( 'just_events_flush_rewrite_rules_flag', true );
		}

		return $new_value;
	}

	/**
	 * Returns sanitize callback based on field type.
	 */
	private static function get_sanitize_callback_by_type( $field_type ) {
		$method_name = "sanitize_{$field_type}_field";
		if ( \method_exists( self::class, $method_name ) ) {
			return self::class . "::{$method_name}";
		} else {
			return 'sanitize_text_field';
		}
	}

	/**
	 * Renders the options page.
	 */
	public static function render_settings_page(): void {
		if ( ! \current_user_can( self::USER_CAPABILITY ) ) {
			return;
		}

		\settings_errors( self::OPTION_NAME . '_mesages' );

		\wp_enqueue_script(
			'just-events-admin-settings',
			\plugins_url( 'assets/js/admin/settings.js', JUST_EVENTS_PLUGIN_FILE ),
			[],
			\filemtime( \plugin_dir_path( JUST_EVENTS_PLUGIN_FILE ) . '/assets/js/admin/settings.js' ),
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
				echo '<input type="hidden" name="just_events_admin_flush_rewrite_rules" value="0">';
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

		<h2 class="nav-tab-wrapper just-events-admin-tabs"><?php
			$first_tab = true;
			foreach ( $tabs as $id => $label ) {
				$class = 'nav-tab';
				if ( $first_tab ) {
					$class .= ' nav-tab-active';
					$first_tab = false;
				}
				?>
				<a href="#" data-tab="<?php echo \esc_attr( $id ); ?>" class="<?php echo \esc_attr( $class ); ?>"><?php echo \ucfirst( \esc_html( $label ) ); ?></a>
				<?php
			}
		?></h2>

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
	 * Sanitizes the text field.
	 * 
	 * Note: We don't use the core sanitize_text_field() function
	 * 		 because it strips out whitespace via trim().
	 */
	private static function sanitize_text_field( string $value ): string {
		$filtered = wp_check_invalid_utf8( $value );

		if ( ! $filtered ) {
			return '';
		}

		if ( str_contains( $filtered, '<' ) ) {
			$filtered = wp_pre_kses_less_than( $filtered );
			// This will strip extra whitespace for us.
			$filtered = wp_strip_all_tags( $filtered, false );
	
			/*
			 * Use HTML entities in a special case to make sure that
			 * later newline stripping stages cannot lead to a functional tag.
			 */
			$filtered = str_replace( "<\n", "&lt;\n", $filtered );
		}

		// Remove newlines.
		$filtered = preg_replace( '/[\r\n\t ]+/', ' ', $filtered );

		// Remove percent-encoded characters.
		$found = false;
		while ( preg_match( '/%[a-f0-9]{2}/i', $filtered, $match ) ) {
			$filtered = str_replace( $match[0], '', $filtered );
			$found    = true;
		}

		if ( $found ) {
			// Strip out the whitespace that may now exist after removing percent-encoded characters.
			$filtered = preg_replace( '/ +/', ' ', $filtered );
		}

		return $filtered;
	}

	/**
	 * Sanitizes the posts per page field.
	 */
	private static function sanitize_posts_per_page( string $value = '' ): ?int {
		if ( ! \is_numeric( $value ) ) {
			self::add_settings_error( \esc_html__( 'Incorrect value added for the "Events Per Page" field. Please enter a numeric value.', 'just-events' ) );
			return null;
		}
		return (int) $value;
	}

	/**
	 * Sanitizes the date format field.
	 */
	private static function sanitize_date_format_field( string $value, array $args ): string {
		if ( false === \strtotime( \wp_date( $value ) ) ) {
			self::add_settings_error( \esc_html__( 'Incorrect date format.', 'just-events' ) );
			return '';
		}
		return \sanitize_text_field( $value );
	}

	/**
	 * Sanitizes the time format field.
	 */
	private static function sanitize_time_format_field( string $value, array $args ): string {
		if ( false === \strtotime( \wp_date( $value ) ) ) {
			self::add_settings_error( \esc_html__( 'Incorrect time format.', 'just-events' ) );
			return '';
		}
		return \sanitize_text_field( $value );
	}

	/**
	 * Sanitizes the "separator" fields.
	 */
	private static function sanitize_date_separator_field( string $value ): string {
		if ( '<br>' === $value ) {
			return '<br>';
		} else {
			return self::sanitize_text_field( $value );
		}
	}

	/**
	 * Sanitizes the checkbox field.
	 */
	private static function sanitize_checkbox_field( $value ): int {
		return ( 'on' === $value ) ? 1 : 0;
	}

	 /**
	 * Sanitizes the select field.
	 */
	private static function sanitize_select_field( string $value, $field_args = [] ): string {
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
			<?php if ( $description ) {
				?>
				<p class="description"><?php echo \wp_kses_post( $description ); ?></p>
				<?php
			} ?>
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
			<textarea type="text" id="<?php echo \esc_attr( $args['label_for'] ); ?>" rows="<?php echo \esc_attr( absint( $rows ) ); ?>" cols="<?php echo \esc_attr( absint( $cols ) ); ?>" name="<?php echo \esc_attr( self::OPTION_NAME ); ?>[<?php echo \esc_attr( $args['setting_id'] ); ?>]"><?php echo \esc_textarea( $value ); ?></textarea>
			<?php if ( $description ) {
				?>
				<p class="description"><?php echo \wp_kses_post( $description ); ?></p>
				<?php
			} ?>
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
			<?php if ( $description ) {
				?>
				<p class="description"><?php echo \wp_kses_post( $description ); ?></p>
				<?php
			} ?>
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
				<?php foreach ( $choices as $choice_v => $label ) {
					?>
					<option value="<?php echo \esc_attr( \sanitize_key( $choice_v ) ); ?>" <?php \selected( $choice_v, $value, true ); ?>><?php echo \esc_html( $label ); ?></option>
					<?php
				} ?>
			</select>
			<?php if ( $description ) {
				?>
				<p class="description"><?php echo \wp_kses_post( $description ); ?></p>
				<?php
			} ?>
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

	/**
	 * Adds a new setting error.
	 */
	public static function add_settings_error( string $message, string $type = 'error' ): void {
		\add_settings_error(
			self::OPTION_NAME . '_mesages',
			self::OPTION_NAME . '_message',
			$message,
			$type
		);
	}

}

Admin::init();
