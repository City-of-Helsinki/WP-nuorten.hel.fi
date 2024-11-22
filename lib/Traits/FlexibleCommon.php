<?php
/**
 * Trait for traits used in both FlexibleContent
 * and FlexibleSidebar
 */

namespace Geniem\Theme\Traits;

use Geniem\Theme\PostType\Initiative as InitiativeCpt;

/**
 * Trait FlexibleCommon
 *
 * @package Geniem\Theme\Traits
 */
trait FlexibleCommon {

    use FeaturedImage;
    use ThemeColor;
    use ListQuery;
    use Initiative;

    /**
     * Page list data
     *
     * @param array $layout_data ACF layout data.
     * @return array
     */
    private function page_list( array $layout_data ) : array {
        if ( empty( $layout_data['pages'] ) ) {
            return [];
        }

        $layout_data['columns']   = $this->get_columns_count( $layout_data['pages'] );
        $columns_less_than_four   = $layout_data['columns'] < 4;
        $image_size               = $layout_data['columns'] === 1 ? 'fullhd' : 'large';
        $layout_data['anchor_id'] = $this->generate_anchor_id( $layout_data['anchor_link_text'] ?? '', $layout_data['title'] ?? '' );

        $layout_data['pages'] = array_filter( $layout_data['pages'], function( $item ) {
            return ! empty( $item['page'] );
        } );

        if ( empty( $layout_data['pages'] ) ) {
            return [];
        }

        foreach ( $layout_data['pages'] as $item ) {
            $page                   = $item['page'];
            $page_id                = $page->ID;
            $page->color_theme      = $this->get_theme_color( $page_id );
            $page->image            = $this->get_featured_image( $image_size, $page_id );
            $page->post_excerpt     = $item['short_desc'] ?? '';
            $page->is_grid_column   = $layout_data['use_grid'];
            $page->show_excerpt     = $layout_data['context'] === 'main-content' && $layout_data['columns'] < 3;
            $page->background_class = $columns_less_than_four && ! \has_post_thumbnail( $page->ID )
                                    ? " has-bg-color {$page->color_theme}"
                                    : '';
        }

        return $layout_data;
    }

    /**
     * Article list data
     *
     * @param array $layout_data ACF layout data.
     * @param bool  $articles_queried
     *
     * @return array
     */
    private function article_list( array $layout_data, bool $articles_queried = false ) : array {
        // articles from repeater field
        $articles = $layout_data['articles'];

        if ( ! empty( $articles ) && ! $articles_queried ) {
            $articles = array_filter( $articles, function( $article ) {
                return ! empty( $article['article'] );
            } );
            $articles = array_map( function( $article ) {
                return $article['article'];
            }, $articles);
        }

        if ( empty( $articles ) ) {
            $query_args = $this->list_query_args( $layout_data );
            $query      = new \WP_Query( $query_args );
            $articles   = $query->posts ?? [];
        }

        if ( empty( $articles ) ) {
            return [];
        }

        $layout_data['columns']   = $this->get_columns_count( $articles );
        $columns_less_than_four   = $layout_data['columns'] < 4;
        $image_size               = $layout_data['columns'] === 1 ? 'fullhd' : 'large';
        $layout_data['anchor_id'] = $this->generate_anchor_id( $layout_data['anchor_link_text'] ?? '', $layout_data['title'] ?? '' );

        foreach ( $articles as $article ) {
            $article->color_theme      = $this->get_theme_color( $article->ID );
            $article->image            = $this->get_featured_image( $image_size, $article->ID );
            $article->is_grid_column   = $layout_data['use_grid'];
            $article->show_excerpt     = $layout_data['context'] === 'main-content' && $layout_data['columns'] < 3;
            $article->background_class = $columns_less_than_four && ! \has_post_thumbnail( $article->ID )
                                        ? " has-bg-color {$article->color_theme}"
                                        : '';
        }

        $layout_data['articles'] = $articles;

        return $layout_data;
    }

    /**
     * Initiative list data
     *
     * @param array $layout_data Layout data.
     *
     * @return array
     */
    private function initiative_list( array $layout_data ) : array {

        if ( empty( $layout_data['initiatives'] ) ) {
            $args = [
                'post_type'      => InitiativeCpt::SLUG,
                'posts_per_page' => 9,
            ];

            if ( ! empty( $layout_data['show_only_open'] ) ) {
                $args['meta_query'] = [
                    [
                        'key'   => 'ruuti_initiative_status',
                        'value' => 'open',
                    ],
                    [
                        'key'   => 'ruuti_initiative_detailed_status',
                        'value' => 'IN_PROCESS',
                    ],
                ];
            }

            $the_query = new \WP_Query( $args );

            if ( ! $the_query->have_posts() ) {
                return [];
            }

            // if show_only_open is not empty, get all open initiatives
            $initiatives = ! empty( $layout_data['show_only_open'] )
                        ? $this->get_initiatives( $the_query, $args )
                        : $the_query->posts;
        }
        else {
            $initiatives = $layout_data['initiatives'];
        }

        $layout_data['columns']   = $this->get_columns_count( $initiatives );
        $columns_less_than_four   = $layout_data['columns'] < 4;
        $image_size               = $layout_data['columns'] === 1 ? 'fullhd' : 'large';
        $layout_data['anchor_id'] = $this->generate_anchor_id( $layout_data['anchor_link_text'] ?? '', $layout_data['title'] ?? '' );

        foreach ( $initiatives as $initiative ) {
            $initiative->status           = $this->get_status( $initiative->ID );
            $initiative->color_theme      = $this->get_theme_color( $initiative->ID );
            $initiative->image            = $this->get_featured_image( $image_size, $initiative->ID );
            $initiative->background_class = $columns_less_than_four && ! \has_post_thumbnail( $initiative->ID )
                                        ? " has-bg-color {$initiative->color_theme}"
                                        : '';
            $initiative->publish_date     = \get_the_date( '', $initiative->ID );
        }

        if ( ! empty( $layout_data['link_title'] ) ) {
            $layout_data['link'] = [
                'title' => $layout_data['link_title'],
                'url'   => \get_post_type_archive_link( InitiativeCpt::SLUG ),

            ];
        }

        $layout_data['initiatives'] = $initiatives;

        return $layout_data;
    }

    /**
     * Get columns count.
     *
     * @param array $items Item to count.
     * @return int
     */
    private function get_columns_count( $items ) : int {
        $item_count = count( $items );
        return $item_count > 3 ? 4 : $item_count;
    }

    /**
     * Generate anchor id.
     *
     * @param string $anchor_link_text
     * @param string $title
     * @return string
     */
    private function generate_anchor_id( $anchor_link_text, $title ) : string {
        if ( empty( $anchor_link_text ) && empty( $title ) ) {
            return '';
        }

        $anchor_text = $title;
        $anchor_text = $anchor_link_text ?: $anchor_text;

        return \sanitize_title( $anchor_text );
    }

    /**
     * Get initiatives.
     *
     * @param \WP_Query $the_query
     * @param array     $args
     *
     * @return array
     */
    private function get_initiatives( \WP_Query $the_query, array $args ) : array {
        $initiatives = $the_query->posts;

        if ( $the_query->max_num_pages === 1 ) {
            return $initiatives;
        }

        for ( $page = 2; $page <= $the_query->max_num_pages; $page ++ ) {
            $args['paged'] = $page;
            $paged_query   = new \WP_Query( $args );

            if ( $paged_query->have_posts() ) {
                $initiatives = array_merge( $initiatives, $paged_query->posts );
            }
        }

        return $initiatives;
    }
}
