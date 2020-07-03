<?php
namespace CTCL\ElectionWebsite;

class Inquiries_Settings {

	public static function hooks() {
		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ] );
		add_action( 'admin_init', [ __CLASS__, 'register_settings' ] );
	}

	public static function register_menu() {
		add_submenu_page( 'elections', 'Inquiries', 'Inquiries', 'manage_options', 'inquiries', [ __CLASS__, 'options_page' ] );
	}

	public static function register_settings() {
		add_settings_section(
			'inquiries_section',
			'Inquiries',
			false,
			'inquiries_fields'
		);

		$fields = [
			'inquiries_fields' =>
			[
				[
					'uid'         => 'question_list',
					'label'       => 'Question',
					'section'     => 'inquiries_section',
					'type'        => 'text',
					'placeholder' => 'Blah Blah',
					'label_for'   => 'question_list',
				],
			],
		];

		\CTCL\ElectionWebsite\Settings::configure_fields( $fields, 'inquiries_fields' );
	}

	public static function options_page() {
		?>
		<form method="post" action="options.php">
			<h2>Inquiries Settings</h2>
			<?php
				settings_fields( 'inquiries_fields' );
			if ( filter_input( INPUT_GET, 'settings-updated', FILTER_SANITIZE_STRING ) ) {
				\CTCL\ElectionWebsite\Settings::admin_notice();
			}
				do_settings_sections( 'inquiries_fields' );
				submit_button();
			?>
		</form>
		<?php
	}

}
add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Inquiries_Settings', 'hooks' ] );
