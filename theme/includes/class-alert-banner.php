<?php
namespace CTCL\Elections;

class Alert_Banner {

	public static function is_enabled() {
		return 1 === absint( get_option( 'alert_banner_enabled' ) );
	}

	public static function title() {
		return get_option( 'alert_banner_title' );
	}

	public static function description() {
		return get_option( 'alert_banner_description' );
	}

	public static function link() {
		return get_option( 'alert_banner_link' );
	}

}
