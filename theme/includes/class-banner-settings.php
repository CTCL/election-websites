<?php
namespace CTCL\ElectionWebsite;

class Banner_Settings {

	public static function hooks() {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
		add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
	}

	public static function register_menu() {
		add_submenu_page( 'elections', 'Banner', 'Banner', 'manage_options', 'banner', [ __CLASS__, 'options_page' ] );
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
				\CTCL\ElectionWebsite\Settings::admin_notice();
			}
				do_settings_sections( 'banner_fields' );
				submit_button();
			?>
		</form>
		<?php
	}

}
add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Banner_Settings', 'hooks' ] );
