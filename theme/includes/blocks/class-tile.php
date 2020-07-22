<?php
/**
 * Render the Tile block.
 *
 * @package CTCL\Elections
 * @since 1.0.0
 */

namespace CTCL\Elections;

/**
 * Tile class.
 *
 * Implements the tile block.
 */
class Tile {
	/**
	 * Render the Tile block.
	 *
	 * @param array  $block_attributes  Array of block attributes.
	 * @param string $content           Post content.
	 *
	 * @return string
	 */
	public static function block_render( $block_attributes, $content ) {
		$theme     = \CTCL\Elections\Elections_Settings::get_theme_slug();
		$cache_key = 'tile_icon_' . $block_attributes['icon'] . '_' . $theme;

		$html = wp_cache_get( $cache_key );
		if ( false !== $html ) {
			return $html;
		}

		$file = get_template_directory() . '/assets/images/tiles/' . $theme . '/' . $block_attributes['icon'] . '.svg';
		$svg  = file_get_contents( $file ); // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown
		$html = '<a href="' . esc_url( $block_attributes['url'] ) . '" class="tile">' . $svg . '<span>' . esc_html( $block_attributes['label'] ) . '</span></a>';

		wp_cache_set( $cache_key, $html, false, 600 );

		return $html;
	}
}
