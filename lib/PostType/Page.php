<?php
/**
 * The class representation of the WordPress default post type 'page'.
 */

namespace Geniem\Theme\PostType;

use \Geniem\Theme\Interfaces\PostType;

/**
 * This class defines the post type.
 *
 * @package Geniem\Theme\PostType
 */
class Page implements PostType {

    /**
     * This defines the slug of this post type.
     */
    const SLUG = 'page';

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_action( 'init', \Closure::fromCallable( [ $this, 'add_tags_support' ] ), 15 );
    }

    /**
     * Add tag support to pages.
     *
     * @return void
     */
    private function add_tags_support() : void {
	    \register_taxonomy_for_object_type( 'post_tag', self::SLUG );
    }
}
