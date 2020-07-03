<?php
namespace CTCL\ElectionWebsite;

class Google_Analytics {
	public static function hooks() {
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'wp_enqueue_scripts' ] );
		add_filter( 'script_loader_tag', [ __CLASS__, 'async_js' ], 10, 2 );
	}

	/**
	 * Enqueue Google Analytics JavaScript
	 */
	public static function wp_enqueue_scripts() {
		$tracking_id = get_option( 'tracking_id ');
		if ( ! $tracking_id ) {
			return;
		}

		$google_url = add_query_arg( [ 'id' => $tracking_id ], 'https://www.googletagmanager.com/gtag/js' );

		wp_enqueue_script( 'gtm', $google_url, [], null, false );
		wp_add_inline_script( 'gtm', "window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', '" . esc_js( $tracking_id ) . "');" );
	}

	/**
	 * Defer Google Analytics JavaScript
	 *
	 * @param $tag
	 * @param $handle
	 *
	 * @return $string
	 */
	public static function async_js( $tag, $handle ) {
		if ( in_array( $handle, [ 'gtm' ], true ) ) {
			return str_replace( ' src', ' async="async" src', $tag );
		}

		return $tag;
	}
}

add_action( 'after_setup_theme', [ '\CTCL\ElectionWebsite\Google_Analytics', 'hooks' ] );
