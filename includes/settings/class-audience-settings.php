<?php
/**
 * Configure the Office Details settings page.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Office Details class.
 *
 * Implements the Office Details settings page.
 */
class Audience_Settings extends Settings {

	const PAGE_TITLE  = 'Audiences';
	const FIELD_GROUP = 'audience';

	/**
	 * Add Elections menu. Set first item to Office Details.
	 */
	public static function register_menu() {
		add_submenu_page( Settings::MENU_SLUG, 'Audience', 'Audience', 'edit_pages', 'audience', [ get_called_class(), 'page' ] );
	}

	/**
	 * Get Office Details settings fields. Abstracted out so we can use placeholder text in the block backend.
	 *
	 * @return array
	 */
	public static function get_fields() {

		$voter_fields = [
			[
				'uid'         => 'audience',
				'label'       => 'Audience',
				'section'     => 'audience_section',
				'type'        => 'select',
				'options'     => [
					'voters'    => 'Voters',
					'officials' => 'Election Officials',
				],
				'default'     => 'voters',
				'placeholder' => '',
				'description' => "If you're an election official making a site to inform voters, select Voters. If you're a state association leader making a site to inform election officials, select Election Officials.",
				'label_for'   => 'audience',
				'args'        => [ 'sanitize_callback' => 'sanitize_text_field' ],
			],
		];

		$fields = $voter_fields;

		return $fields;
	}

	/**
	 * Configure Office Details settings.
	 */
	public static function register_settings() {
		add_settings_section(
			'audience_section',
			'Audience',
			false,
			static::FIELD_GROUP
		);

		$fields = self::get_fields();

		self::configure_fields( $fields, static::FIELD_GROUP );
	}
}

add_action( 'after_setup_theme', [ '\CTCL\Elections\Audience_Settings', 'hooks' ] );
