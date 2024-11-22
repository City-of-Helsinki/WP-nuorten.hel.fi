<?php
/**
 * Trait for ListQuery
 */

namespace Geniem\Theme\Traits;

use Geniem\Theme\Taxonomy;
use Geniem\Theme\PostType;

/**
 * Trait ListQuery
 *
 * @package Geniem\Theme\Traits
 */
trait ListQuery {

    /**
     * List query args
     *
     * @param  array $layout_data Data from layout.
     * @return array Return args-array.
     */
    protected function list_query_args( $layout_data ) : array {
        global $post;

        $categories        = $layout_data['category'];
        $tags              = $layout_data['tag'];
        $community_centers = $layout_data['community_center'];
        $amount            = $layout_data['amount'] ?? 3;

        // Construct the query
        $query_args = [
            'posts_per_page' => $amount,
        ];

        // Maybe add category ids to the query.
        if ( $categories ) {
            $query_args['category__in'] = $categories;
        }

        // Maybe add tag ids to the query.
        if ( $tags ) {
            $query_args['tag__in'] = $tags;
        }

        // Maybe add community_center ids to the tax query.
        if ( $community_centers ) {
            $query_args['tax_query'][] = [
                'taxonomy' => Taxonomy\CommunityCenter::SLUG,
                'terms'    => $community_centers,
            ];
        }

        // make sure not to retrieve the current post
        if ( $post->post_type === PostType\Post::SLUG ) {
            $query_args['post__not_in'] = [ $post->ID ];
        }

        return $query_args;
    }
}
