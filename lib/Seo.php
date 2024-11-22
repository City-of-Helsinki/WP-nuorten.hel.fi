<?php
/**
 * The Seo Framework modifications
 */

namespace Geniem\Theme;

use \Geniem\Theme\PostType;

/**
 * Class Seo
 *
 * @package Geniem\Theme
 */
class Seo implements Interfaces\Controller {

    /**
     * Hooks
     */
    public function hooks() : void {
        \add_filter( 'the_seo_framework_sitemap_supported_post_types', \Closure::fromCallable( [ $this, 'modify_sitemap_post_types' ] ) );
    }

    /**
     * Modify sitemap post types
     *
     * @param array $post_types
     *
     * @return array
     */
    private function modify_sitemap_post_types( array $post_types ) : array {
        return array_diff(
            $post_types,
            [
                PostType\YouthCouncilElections::SLUG,
            ]
        );
    }
}
