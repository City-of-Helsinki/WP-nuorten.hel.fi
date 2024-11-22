<?php
/**
 * Define the single post class.
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use Geniem\Theme\Traits;
use Geniem\Theme\Taxonomy\PostTag;

/**
 * Single class
 */
class Single implements Model {

    use Traits\FlexibleContent;
    use Traits\FlexibleSidebar;
    use Traits\Breadcrumbs;

    /**
     * This defines the name of this model.
     */
    const NAME = 'Single';

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {}

    /**
     * Get class name constant
     *
     * @return string Class name constant
     */
    public function get_name() : string {
        return self::NAME;
    }

    /**
     * Add classes to html.
     *
     * @param array $classes Array of classes.
     * @return array Array of classes.
     */
    public function add_classes_to_html( $classes ) : array {
        return $classes;
    }

    /**
     * Get related youth centers.
     *
     * @return array
     */
    public function get_related_youthcenters() : array {
        $related_youthcenters = get_field( 'youthcenter' ) ?: [];

        if ( empty( $related_youthcenters ) ) {
            return [];
        }

        return array_map( function( $item ) {
            return [
                'title' => $item->post_title,
                'url'   => get_permalink( $item->ID ),
            ];
        }, $related_youthcenters );
    }

    /**
     * Get post tags.
     *
     * @return array
     */
    public function get_post_tags() : array {
        global $post;

        $post_tags = get_the_terms( $post, PostTag::SLUG  );

        if ( empty( $post_tags ) || $post_tags instanceof \WP_Error ) {
            return [];
        }

        return array_map( function( $term ) {
            return [
                'title' => $term->name,
                'url'   => get_term_link( $term->term_id ),
            ];
        }, $post_tags );

    }
}
