<?php
namespace CTCL\ElectionWebsite;

class Banner_Settings extends Settings {

	public static function register_menu() {
		add_submenu_page( 'elections', 'Banner', 'Banner', 'manage_options', 'banner', [ get_called_class(), 'options_page' ] );
	}

	public static function register_settings() {
		add_settings_section(
			'banner_section',
			'Banner',
			false,
			'banner_fields'
		);

		add_settings_section(
			'alert_banner_section',
			'Alert Banner',
			false,
			'banner_fields'
		);

		$fields = [
			'banner_fields' =>
			[
				[
					'uid'         => 'banner_title',
					'label'       => 'Title',
					'section'     => 'banner_section',
					'type'        => 'text',
					'placeholder' => 'Important News',
					'label_for'   => 'banner_title',
				],
				[
					'uid'         => 'banner_description',
					'label'       => 'Title',
					'section'     => 'banner_section',
					'type'        => 'textarea',
					'placeholder' => 'Lorem ipsum…',
					'label_for'   => 'banner_description',
				],
			],
			'alert_banner_fields' =>
			[
				[
					'uid'         => 'alert_banner_title',
					'label'       => 'Title',
					'section'     => 'alert_banner_section',
					'type'        => 'text',
					'placeholder' => 'Don’t Forget',
					'label_for'   => 'alert_banner_title',
				],
			],
		];

		\CTCL\ElectionWebsite\Settings::configure_fields( $fields, 'banner_fields' );
	}

	public static function options_page() {
		?>
		<form method="post" action="options.php">
			<h2>Banner Settings</h2>
			<?php
				settings_fields( 'banner_fields' );
			if ( filter_input( INPUT_GET, 'settings-updated', FILTER_SANITIZE_STRING ) ) {
				self::admin_notice();
			}
				do_settings_sections( 'banner_fields' );
				submit_button();
			?>
		</form>
		<?php
	}
}

add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Banner_Settings', 'hooks' ] );
