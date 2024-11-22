<?php
/**
 * The class representation of the WordPress default post type 'attachment'.
 */

namespace Geniem\Theme\PostType;

use \Geniem\Theme\Interfaces\PostType;

/**
 * This class represents WordPress default post type 'attachment'.
 *
 * @package Geniem\Theme\PostType
 */
class Attachment implements PostType {

    /**
     * This defines the slug of this post type.
     */
    const SLUG = 'attachment';

    /**
     * This is called in setup automatically.
     *
     * @return void
     */
    public function hooks() : void {}
}
