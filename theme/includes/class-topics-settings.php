<?php
/**
 * Configure the Topics settings page.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

class Topics_Settings extends Settings {

	const PAGE_TITLE  = 'Contact Form Topics';
	const FIELD_GROUP = 'topics_fields';

	public static function register_menu() {
		add_submenu_page( 'elections', 'Topics', 'Topics', 'manage_options', 'topics', [ get_called_class(), 'page' ] );
	}

	public static function register_settings() {
		add_settings_section(
			'topics_section',
			false,
			false,
			static::FIELD_GROUP
		);

		$fields = [
			[
				'uid'         => 'topic_list',
				'label'       => 'Topics',
				'section'     => 'topics_section',
				'type'        => 'multitext',
				'placeholder' => 'Lorem Ipsum',
				'label_for'   => 'topic_list',
				'args'        => [ 'sanitize_callback' => [ __CLASS__, 'sanitize_topic_list' ] ],
			],
		];

		self::configure_fields( $fields, static::FIELD_GROUP );
	}

	public static function sanitize_topic_list( $args ) {
		return array_filter( array_map( 'sanitize_text_field', $args ) );
	}
}

add_action( 'after_setup_theme', [ '\CTCL\Elections\Topics_Settings', 'hooks' ] );
