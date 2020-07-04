<?php
namespace CTCL\ElectionWebsite;

class Banner {

	public static function is_enabled() {
		return 1 === absint( get_option( 'banner_enabled' ) );
	}

	public static function title() {
		return get_option( 'banner_title' );
	}

	public static function description() {
		return get_option( 'banner_description' );
	}

	public static function link() {
		return get_option( 'banner_link' );
	}

	public static function image_id() {
		return get_option( 'banner_image' );
	}
}
