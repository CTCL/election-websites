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
		$svg   = '';
		$icon  = $block_attributes['icon'] ?? false;
		$url   = $block_attributes['url'] ?? '#';
		$label = $block_attributes['label'] ?? '';

		if ( isset( $icon ) ) {
			$theme     = \CTCL\Elections\Elections_Settings::get_theme_slug();
			$cache_key = 'tile_icon_' . $icon . '_' . $theme;

			$html = wp_cache_get( $cache_key );
			if ( false !== $html ) {
				return $html;
			}

			$file = get_template_directory() . '/assets/images/icons/' . $theme . '/' . $icon . '.svg';

			// Leave the alt tag empty, as it is redundance with the label.
			$svg = Helpers::inline_svg_tag( $file, 50, 50 );
		}

		$html = '<a href="' . esc_url( $url ) . '" class="tile"';
		if ( 0 !== strpos( $url, home_url() ) ) {
			$html .= ' target="_blank"';
		}
		$html .= '>' . $svg . '<label>' . esc_html( $label ) . '</label></a>';

		if ( $svg && $url && $label ) {
			wp_cache_set( $cache_key, $html, Helpers::INLINE_IMAGE_CACHE_KEY, 600 );
		}

		return $html;
	}
}
