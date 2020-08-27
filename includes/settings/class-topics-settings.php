<?php
/**
 * Configure the Topics settings page.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Topic Settings class.
 *
 * Implements the Topic settings page.
 */
class Topics_Settings extends Settings {

	const PAGE_TITLE  = 'Contact Form Topics';
	const FIELD_GROUP = 'topics_fields';

	/**
	 * Set up actions and filters.
	 */
	public static function hooks() {
		add_action( 'before_settings_fields', [ get_called_class(), 'before_settings_fields' ] );
		parent::hooks();
	}

	/**
	 * Add Topics submenu page.
	 */
	public static function register_menu() {
		add_submenu_page( Settings::MENU_SLUG, 'Topics', 'Topics', 'edit_pages', 'topics', [ get_called_class(), 'page' ] );
	}

	/**
	 * Configure Topics settings.
	 */
	public static function register_settings() {
		add_settings_section(
			'topics_section',
			false,
			false,
			static::FIELD_GROUP
		);

		$fields = [
			[
				'uid'         => Topics::TOPIC_LIST_SLUG,
				'label'       => 'Topics',
				'section'     => 'topics_section',
				'type'        => 'multitext',
				'placeholder' => 'Lorem Ipsum',
				'label_for'   => Topics::TOPIC_LIST_SLUG,
				'args'        => [ 'sanitize_callback' => [ __CLASS__, 'sanitize_topic_list' ] ],
			],
		];

		self::configure_fields( $fields, static::FIELD_GROUP );
	}

	/**
	 * Format topic list: sanitize text and remove empty items.
	 *
	 * @param array $topics Topic list.
	 *
	 * @return array
	 */
	public static function sanitize_topic_list( $topics ) {
		$topics = array_map( 'sanitize_text_field', $topics );

		// Remove the default topic so it doesn't appear twice (if the user accidentally adds it).
		$key = array_search( Contact_Form::DEFAULT_TOPIC, $topics, true );
		if ( false !== $key ) {
			unset( $topics[ $key ] );
		}

		return array_unique( array_filter( $topics ) );
	}

	/**
	 * Print text at the top of the settings fields page.
	 *
	 * @param string $field_group Field group name.
	 */
	public static function before_settings_fields( $field_group ) {
		if ( self::FIELD_GROUP === $field_group ) {
			print 'An “Other” option will be included at the end of the list.';
		}
	}
}

add_action( 'after_setup_theme', [ '\CTCL\Elections\Topics_Settings', 'hooks' ] );
