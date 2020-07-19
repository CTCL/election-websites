<?php
/**
 * Functions called on theme installation/activation.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Functions called on theme installation/activation.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */
class Activation {

	/**
	 * Check if an image already exists in the media library.
	 *
	 * @param string $file  File name.
	 *
	 * @return boolean
	 */
	public static function image_exists( $file ) {
		if ( ! $file ) {
			return false;
		}

		$query = new \WP_Query(
			[
				'meta_query'  => [ // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_meta_query
					[
						'key'     => '_wp_attached_file',
						'compare' => 'LIKE',
						'value'   => $file,
					],
				],
				'post_type'   => 'attachment',
				'post_status' => 'inherit',
			]
		);

		return count( $query->posts ) > 0;
	}

	/**
	 * Upload banner images to the media library.
	 */
	public static function upload_banner_images() {
		$image_list = [
			'ballot-box-1.svg' => 'Ballot box (blue)',
			'ballot-box-2.svg' => 'Ballot box (green)',
			'mailbox-1.svg'    => 'Mailbox (blue)',
			'mailbox-2.svg'    => 'Mailbox (green)',
		];

		foreach ( $image_list as $filename => $description ) {

			if ( self::image_exists( $filename ) ) {
				continue;
			}

			$file = get_theme_file_uri( 'assets/images/banner/' . $filename );
			Helpers::upload_image( $file, $description );
		}
	}
}
