<?php
/**
 * Trait FeaturedImage
 * Featured image related functionality.
 */

namespace Geniem\Theme\Traits;

use Geniem\Theme\Settings;
use Geniem\Theme\PostType;

/**
 * Trait FeaturedImage
 *
 * @package Geniem\Theme\Traits
 */
trait FeaturedImage {

    /**
     * Get featured image.
     *
     * @param string   $size    Image size.
     * @param null|int $post_id WP_Post ID.
     *
     * @return array|bool
     */
    protected function get_featured_image( $size = 'large', $post_id = null ) {
        if ( null === $post_id ) {
            $post_id = \get_the_ID();
        }

        if ( \has_post_thumbnail( $post_id ) ) {
            return [
                'id'  => get_post_thumbnail_id( $post_id ),
                'url' => get_the_post_thumbnail_url( $post_id, $size ),
            ];
        }

        $default_image_name = 'default_page_image';

        if ( \get_post_type( $post_id ) === PostType\Post::SLUG ) {
            $default_image_name = 'default_post_image';
        }
        elseif ( \get_post_type( $post_id ) === PostType\Initiative::SLUG ) {
            $default_image_name = 'default_initiative_image';
        }

        $default_image = Settings::get_setting( $default_image_name ) ?: false;

        if ( empty( $default_image ) ) {
            return false;
        }

        return [
            'id'  => $default_image['ID'],
            'url' => wp_get_attachment_image_url( $default_image['ID'], $size ),
        ];
    }
}
