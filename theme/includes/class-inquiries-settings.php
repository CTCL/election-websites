<?php
namespace CTCL\ElectionWebsite;

class Inquiries_Settings extends Settings {

	const PAGE_TITLE = 'Inquiries';
	const FIELD_GROUP = 'inquiries_fields';

	public static function register_menu() {
		add_submenu_page( 'elections', 'Inquiries', 'Inquiries', 'manage_options', 'inquiries', [ get_called_class(), 'page' ] );
	}

	public static function register_settings() {
		add_settings_section(
			'inquiries_section',
			'Inquiries',
			false,
			static::FIELD_GROUP
		);

		$fields = [
			[
				'uid'         => 'question_list',
				'label'       => 'Question',
				'section'     => 'inquiries_section',
				'type'        => 'text',
				'placeholder' => 'Blah Blah',
				'label_for'   => 'question_list',
			],
		];

		self::configure_fields( $fields, static::FIELD_GROUP );
	}
}

add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Inquiries_Settings', 'hooks' ] );
