<?php
/**
 * Load Google Analytics tracking.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Google Analytics class.
 *
 * Implements Google Analytics loading.
 */
class Google_Analytics {

	const TRACKING_ID = 'ctcl_tracking_id';

	/**
	 * Set up actions and filters.
	 */
	public static function hooks() {
		add_action( 'wp_enqueue_scripts', [ __CLASS__, 'wp_enqueue_scripts' ] );
		add_filter( 'script_loader_tag', [ __CLASS__, 'async_js' ], 10, 3 );
	}

	/**
	 * Enqueue Google Analytics JavaScript.
	 */
	public static function wp_enqueue_scripts() {
		$tracking_id = get_option( self::TRACKING_ID );
		if ( ! $tracking_id ) {
			return;
		}

		$google_url = add_query_arg( [ 'id' => $tracking_id ], 'https://www.googletagmanager.com/gtag/js' );

		// Don't set a resource version here. We don't want query parameters passed to Google.
		wp_enqueue_script( 'gtm', $google_url, [], null, false ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
		wp_add_inline_script( 'gtm', "window.dataLayer=window.dataLayer||[];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', '" . esc_js( $tracking_id ) . "');" );
	}

	/**
	 * Defer Google Analytics JavaScript.
	 *
	 * @param string $tag    The `<script>` tag for the enqueued script.
	 * @param string $handle The script's registered handle.
	 * @param string $src    The script's source URL.
	 *
	 * @return string
	 */
	public static function async_js( $tag, $handle, $src ) { // phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis.UnusedVariable
		if ( in_array( $handle, [ 'gtm' ], true ) ) {
			return str_replace( ' src', ' async="async" src', $tag );
		}

		return $tag;
	}
}

add_action( 'after_setup_theme', [ '\CTCL\Elections\Google_Analytics', 'hooks' ] );
