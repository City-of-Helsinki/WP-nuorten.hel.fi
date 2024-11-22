<?php
/**
 * Trait for breadcrumbs.
 */

namespace Geniem\Theme\Traits;

use \Geniem\Theme\PostType;
use \Geniem\Theme\Model\PageEvent;
use Geniem\Theme\Model\PageInitiatives;
use \Geniem\Theme\Model\PageYouthCouncilElections;

/**
 * Trait Breadcrumbs
 */
trait Breadcrumbs {

    /**
     * Get breadcrumb items for single post or page.
     *
     * @param int|null $post_id      Current post ID.
     * @param string   $custom_title Custom title.
     * @return array
     */
    public function get_breadcrumbs( $post_id = null, $custom_title = '' ) {

        $post_id = ! empty( $post_id ) ? $post_id : get_queried_object_id();

        $breadcrumbs = [];

        if ( empty( $post_id ) ) {
            return $breadcrumbs;
        }

        // Add front page item
        $frontpage                          = static::get_frontpage_breadcrumb();
        $breadcrumbs[ $frontpage['title'] ] = $frontpage;

        $post_type     = \get_post_type( $post_id );
        $page_template = get_page_template_slug( $post_id );

        if ( $page_template === PageEvent::TEMPLATE ) {
            $title                 = __( 'Events', 'nuhe' );
            $breadcrumbs[ $title ] = [
                'title'     => $title,
                'permalink' => \get_post_type_archive_link( 'event' ),
            ];

            // Set current post item
            $breadcrumbs[] = [
                'title'     => $custom_title,
                'permalink' => false,
                'current'   => true,
            ];

            return $breadcrumbs;
        }

        if ( get_queried_object() instanceof \WP_Term ) {
            $breadcrumbs[] = [
                'title'     => get_queried_object()->name,
                'permalink' => false,
                'current'   => true,
            ];
            
            return $breadcrumbs;
        }

        // Add ancestors or archive pages
        switch ( $post_type ) {
            case PostType\Post::SLUG:
                $page_for_posts = \get_option( 'page_for_posts' );
                if ( $page_for_posts ) {
                    $title                 = \get_the_title( $page_for_posts );
                    $breadcrumbs[ $title ] = [
                        'title'     => $title,
                        'permalink' => \get_permalink( $page_for_posts ),
                    ];
                }
                break;
            case PostType\YouthCouncilElections::SLUG:
                $elections_page = new \WP_Query( [
                    'post_type'  => 'page',
                    'meta_key'   => '_wp_page_template',
                    'meta_value' => PageYouthCouncilElections::TEMPLATE,
                ] );

                if ( $elections_page->have_posts() ) {
                    $title                 = $elections_page->posts[0]->post_title;
                    $breadcrumbs[ $title ] = [
                        'title'     => $title,
                        'permalink' => get_permalink( $elections_page->posts[0]->ID ),
                    ];
                }
                break;
            case PostType\Initiative::SLUG:
                $initiative_page = new \WP_Query( [
                    'post_type'  => 'page',
                    'meta_key'   => '_wp_page_template',
                    'meta_value' => PageInitiatives::TEMPLATE,
                ] );

                if ( $initiative_page->have_posts() ) {
                    $title                 = $initiative_page->posts[0]->post_title;
                    $breadcrumbs[ $title ] = [
                        'title'     => $title,
                        'permalink' => get_permalink( $initiative_page->posts[0]->ID ),
                    ];
                }
                break;
            case PostType\YouthCenter::SLUG:
            case PostType\HighSchool::SLUG:
            case PostType\VocationalSchool::SLUG:
                $post_type_obj = \get_post_type_object( $post_type );
                if ( ! is_null( $post_type_obj ) ) {
                    $units_page = new \WP_Query( [
                        'post_type'  => PostType\Page::SLUG,
                        'fields'     => 'ids',
                        'meta_key'   => 'unit_post_type',
                        'meta_value' => $post_type,
                    ] );

                    $permalink = ! empty( $units_page->have_posts() ) ? \get_permalink( $units_page->posts[0] ) : '';

                    // Set the title by post type
                    if ( $post_type === PostType\YouthCenter::SLUG ) {
                        $title = __( 'Youth centers', 'nuhe' );
                    }
                    elseif ( $post_type === PostType\HighSchool::SLUG ) {
                        $title = __( 'High schools', 'nuhe' );
                    }
                    elseif ( $post_type === PostType\VocationalSchool::SLUG ) {
                        $title = __( 'Vocational schools', 'nuhe' );
                    }

                    $breadcrumbs[ $title ] = [
                        'title'     => $title,
                        'permalink' => $permalink,
                    ];
                }
                break;
            case PostType\Page::SLUG:
            default:
                $ancestors = array_reverse( \get_post_ancestors( $post_id ) );
                foreach ( $ancestors as $ancestor ) {
                    $title                 = \get_the_title( $ancestor );
                    $breadcrumbs[ $title ] = [
                        'title'     => $title,
                        'permalink' => \get_permalink( $ancestor ),
                    ];
                }
                unset( $ancestor );
        }

        // Set current post item
        $breadcrumbs[] = [
            'title'     => \get_the_title( $post_id ),
            'permalink' => false,
            'current'   => true,
        ];

        return $breadcrumbs;
    }

    /**
     * Get breadcrumb items for archives.
     *
     * @param WP_Post_Type $post_type The post type for this archive.
     * @return array
     */
    protected function get_archive_breadcrumbs( $post_type ) {
        $breadcrumbs = [];

        if ( empty( $post_type ) ) {
            return $breadcrumbs;
        }

        // Add front page item
        $breadcrumbs[] = static::get_frontpage_breadcrumb();

        $breadcrumbs[] = [
            'title'     => $post_type->labels->name,
            'permalink' => false,
            'current'   => true,
        ];

        return $breadcrumbs;
    }

    /**
     * Return the breadcrumb data for front page.
     *
     * @return array
     */
    private static function get_frontpage_breadcrumb() {
        return [
            'title'     => \get_the_title( \get_option( 'page_on_front' ) ),
            'permalink' => \get_home_url(),
        ];
    }
}
