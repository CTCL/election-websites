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
		$svg  = '';
		$icon = $block_attributes['icon'] ?? false;

		if ( isset( $icon ) ) {
			$theme     = \CTCL\Elections\Elections_Settings::get_theme_slug();
			$cache_key = 'tile_icon_' . $icon . '_' . $theme;

			$html = wp_cache_get( $cache_key );
			if ( false !== $html ) {
				return $html;
			}

			$file = get_template_directory() . '/assets/images/tiles/' . $theme . '/' . $icon . '.svg';

			if ( file_exists( $file ) ) {
				$svg = file_get_contents( $file ); // phpcs:ignore WordPressVIPMinimum.Performance.FetchingRemoteData.FileGetContentsUnknown
			}
		}

		$url   = $block_attributes['url'] ?? '#';
		$label = $block_attributes['label'] ?? '';
		$html  = '<a href="' . esc_url( $url ) . '" class="tile">' . $svg . '<span>' . esc_html( $label ) . '</span></a>';

		if ( $svg && $url && $label ) {
			wp_cache_set( $cache_key, $html, Helpers::INLINE_IMAGE_CACHE_KEY, 600 );
		}

		return $html;
	}
}
