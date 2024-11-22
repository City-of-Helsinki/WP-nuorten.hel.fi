<?php
/**
 * Define the PageSitemap class.
 */

namespace Geniem\Theme\Model;

use Geniem\Theme\Interfaces\Model;
use Geniem\Theme\Traits;
use \Geniem\Theme\Localization;
use \Geniem\Theme\PostType\Page;

/**
 * The PageSitemap class.
 *
 * @package Geniem\Theme\Model
 */
class PageSitemap implements Model {

    use Traits\Breadcrumbs;

    /**
     * This defines the name of this model.
     */
    const NAME = 'PageSitemap';

    /**
     * This defines the template of this model.
     */
    const TEMPLATE = 'page-templates/page-sitemap.php';

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {
        \add_action(
            'admin_head',
            \Closure::fromCallable( [ $this, 'clean_page_edit_view' ] )
        );
        \add_filter(
            'use_block_editor_for_post_type',
            \Closure::fromCallable( [ $this, 'disable_gutenberg' ] ),
            10,
            1
        );
    }

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
     * Return the sitemap.
     */
    public function Content() {
        $main_theme_pages = $this->get_main_theme_pages();
        $title            = \get_the_title();

        if ( empty( $main_theme_pages ) ) {
            return [
                'title' => $title,
            ];
        }

        $query_args = [
            'post_type'      => Page::SLUG,
            'posts_per_page' => 20, // phpcs:ignore
            'post_status'    => 'publish',
            'fields'         => 'ids',
            'meta_query'     => [
                'relation' => 'OR',
                [
                    'key'     => 'exclude_from_sitemap',
                    'value'   => '1',
                    'compare' => '!=',
                ],
                [
                    'key'     => 'exclude_from_sitemap',
                    'compare' => 'NOT EXISTS',
                ],
            ],

        ];

        $the_query      = new \WP_Query( $query_args );
        $included_pages = $the_query->have_posts() ? $the_query->posts : [];

        if ( empty( $included_pages ) ) {
            return [
                'title' => $title,
            ];
        }

        if ( $the_query->max_num_pages > 1 ) {
            for ( $page = 2; $page <= $the_query->max_num_pages; $page ++ ) {
                $query_args['paged'] = $page;
                $paged_query         = new \WP_Query( $query_args );

                if ( $paged_query->have_posts() ) {
                    $included_pages = array_merge( $included_pages, $paged_query->posts );
                }
            }
        }

        $ret_pages = [];
        foreach ( $included_pages as $page ) {
            if ( in_array( $page, $main_theme_pages ) ) {
                $ret_pages[] = $page;
                continue;
            }

            $ancestors = \get_post_ancestors( $page );
            $ancestors = array_reverse( $ancestors );
            if ( ! empty( $ancestors ) && in_array( $ancestors[0], $main_theme_pages ) ) {
                $ret_pages[] = $page;
            }
        }

        $sitemap = \wp_list_pages( [
            'title_li'    => '',
            'sort_column' => 'menu_order',
            'echo'        => 0,
            'include'     => $ret_pages,
        ] );

        return [
            'title'   => $title,
            'sitemap' => $sitemap,
        ];
    }

    /**
     * Disable Gutenberg.
     *
     * @param bool $can_edit 
     *
     * @return bool
     */
    private function disable_gutenberg( bool $can_edit ) : bool {
        if ( ! ( is_admin() && ! empty( $_GET['post'] ) ) ) {
            return $can_edit;
        }

        return \get_page_template_slug( \get_the_ID() ) === self::TEMPLATE ? false : true;
    }

    /**
     * Clean page edit view.
     *
     * @return void
     */
    private function clean_page_edit_view() : void {
        $screen = get_current_screen();
        if ( 'page' !== $screen->id || ! isset( $_GET['post'] ) ) {
            return;
        }

        if ( \get_page_template_slug( \get_the_ID() ) === self::TEMPLATE ) {
            \remove_post_type_support( 'page', 'editor' );
            \remove_meta_box( 'postimagediv', 'page', 'side' );
        }
    }

    /**
     * Get main theme pages
     *
     * @return array 
     */
    private function get_main_theme_pages() : array {
        $options  = \get_option( 'polylang' );
        $theme    = \get_option( 'stylesheet' );
        $cur_lang = Localization::get_current_language();
        $menu_id  = $options['nav_menus'][ $theme ]['primary_navigation'][ $cur_lang ];

        if ( empty( $menu_id ) ) {
            return [];
        }

        $menu       = \wp_get_nav_menu_object( $menu_id );
        $menu_items = \wp_get_nav_menu_items( $menu->term_id );

        if ( empty( $menu_items ) ) {
            return [];
        }

        $ret_items = [];

        foreach ( $menu_items as $item ) {
            $menu_item_parent_id = (int) $item->menu_item_parent;
            if ( $menu_item_parent_id === 0 ) {
                $ret_items[] = $item->object_id;
            }
        }

        return $ret_items;
    }
}
