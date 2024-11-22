<?php
/**
 * Trait ThemeColor
 */

namespace Geniem\Theme\Traits;

use \Geniem\Theme\PostType;
use \Geniem\Theme\Settings;

/**
 * Trait ThemeColor
 *
 * @package Geniem\Theme\Traits
 */
trait ThemeColor {

    /**
     * Get theme color.
     *
     * @param null|int $post_id WP_Post ID.
     *
     * @return string
     */
    protected function get_theme_color( $post_id = null ) {
        if ( null === $post_id ) {
            $post_id = \get_the_ID();
        }
        if ( \get_post_type( $post_id ) === PostType\Post::SLUG ) {
            return $this->get_theme_color_by_category( $post_id );
        }

        return $this->get_theme_color_by_page( $post_id );
    }

    /**
     * Get theme color by category.
     *
     * @param int $post_id WP_Post ID.
     *
     * @return string
     */
    private function get_theme_color_by_category( $post_id ) : string {
        $categories_colors = Settings::get_setting( 'categories_colors' );
        $post_categories   = \wp_get_post_categories( $post_id );

        if ( empty( $categories_colors ) || empty( $post_categories ) ) {
            return COLOR_THEME_DEFAULT;
        }

        $categories_colors_assoc = [];

        foreach ( $categories_colors as $color ) {
            $categories_colors_assoc[ $color['taxonomy_field'] ] = $color['color_theme'];
        }

        foreach ( $post_categories as $category ) {
            if ( array_key_exists( $category, $categories_colors_assoc ) ) {
                return $categories_colors_assoc[ $category ];
            }
        }

        return COLOR_THEME_DEFAULT;
    }

    /**
     * Get theme color by page id
     *
     * @param int $post_id WP_Post ID.
     *
     * @return string
     */
    private function get_theme_color_by_page( $post_id ) : string {
        $pages_colors = Settings::get_setting( 'pages_colors' );

        if ( empty( $pages_colors ) || is_404() || is_search() || is_archive() ) {
            return COLOR_THEME_DEFAULT;
        }

        $pages_colors_assoc = [];

        foreach ( $pages_colors as $color ) {
            $pages_colors_assoc[ $color['page_field'] ] = $color['color_theme'];
        }

        $page_ancestors = \get_ancestors( $post_id, PostType\Page::SLUG, 'post_type' );

        if ( empty( $page_ancestors ) ) {
            return array_key_exists( $post_id, $pages_colors_assoc )
                    ? $pages_colors_assoc[ $post_id ]
                    : COLOR_THEME_DEFAULT;
        }

        foreach ( $page_ancestors as $ancestor ) {
            if ( array_key_exists( $ancestor, $pages_colors_assoc ) ) {
                return $pages_colors_assoc[ $ancestor ];
            }
        }

        return COLOR_THEME_DEFAULT;
    }
}
