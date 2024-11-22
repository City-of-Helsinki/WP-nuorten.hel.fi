<?php
/**
 * Define the PageCategoryLanding class.
 */

namespace Geniem\Theme\Model;

/**
 * PageCategoryLanding class
 */
class PageCategoryLanding extends PageTheme {

    /**
     * This defines the name of this model.
     */
    const NAME = 'PageCategoryLanding';

    /**
     * This defines the template of this model.
     */
    const TEMPLATE = 'page-templates/page-category-landing.php';

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
     * Get child menu items of the current page.
     *
     * @return array|false array of menu items or false if not items found.
     */
    public function get_child_menu_items() {
        global $post;

        $parent = \wp_get_associated_nav_menu_items( $post->ID );

        if ( empty( $parent ) ) {
            return false;
        }

        $parent_id = $parent[0];
        $menu_name = 'primary_navigation';
        $locations = \get_nav_menu_locations();

        if ( empty( $locations ) || ! isset( $locations[ $menu_name ] ) ) {
            return false;
        }

        $menu       = \wp_get_nav_menu_object( $locations[ $menu_name ] );
        $menu_items = \wp_get_nav_menu_items( $menu->term_id );

        // Return only parent children
        $menu_items = array_filter( $menu_items, function( $item ) use ( $parent_id ) {
            $menu_item_parent_id = (int) $item->menu_item_parent;
            return $menu_item_parent_id === $parent_id;
        } );

        return array_map( function( $item ) {
            return [
                'title' => $item->title,
                'url'   => \get_the_permalink( $item->object_id ),
            ];
        }, $menu_items );
    }
}
