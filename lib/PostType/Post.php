<?php
/**
 * The class representation of the WordPress default post type 'post'
 */

namespace Geniem\Theme\PostType;

use \Geniem\Theme\Interfaces\PostType;

/**
 * This class defines the post type.
 *
 * @package Geniem\Theme\PostType
 */
class Post implements PostType {

    /**
     * This defines the slug of this post type.
     */
    const SLUG = 'post';

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {}
}
