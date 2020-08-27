<?php
/**
 * Helpers to get the list of topics for the contact form.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Banner class.
 *
 * Helpers to get the topic list.
 */
class Topics {

	const TOPIC_LIST_SLUG = 'ctcl_topic_list';

	/**
	 * The topic list.
	 *
	 * @return string
	 */
	public static function get_list() {
		return array_unique( get_option( self::TOPIC_LIST_SLUG ) );
	}
}
