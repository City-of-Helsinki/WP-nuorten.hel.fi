<?php
/**
 * The class representation of the taxonomy 'example'.
 * Use this as a copy-paste base for all custom taxonomies.
 */

namespace Geniem\Theme\Taxonomy;

use Geniem\Theme\PostType;
use \Geniem\Theme\Interfaces\Taxonomy;

/**
 * This class defines the taxonomy.
 *
 * @package Geniem\Theme\Taxonomy
 */
class Example implements Taxonomy {

    /**
     * This defines the slug of this taxonomy.
     * This needs to be unique so that there's no
     * clashes with e.G. query variables.
     */
    public const SLUG = 'example-tax';

    /**
     * This defines what is shown in the url. This can
     * be different than the slug which is used to register the taxonomy.
     *
     * @var string
     */
    private $url_slug = '';

    /**
     * Constructor
     */
    public function __construct() {

        $this->url_slug = 'taxonomy';
    }

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        \add_action( 'init', \Closure::fromCallable( [ $this, 'register' ] ), 15 );
    }

    /**
     * This registers the taxonomy.
     *
     * @return void
     */
    private function register() {

        $this->url_slug = 'taxonomy';

        $labels = [
            'name'                       => _x( 'Taxonomies', 'theme TAX General Name', 'nuhe' ),
            'singular_name'              => _x( 'Taxonomy', 'theme TAX Singular Name', 'nuhe' ),
            'menu_name'                  => _x( 'Taxonomy', 'theme TAX', 'nuhe' ),
            'all_items'                  => _x( 'All Items', 'theme TAX', 'nuhe' ),
            'parent_item'                => _x( 'Parent Item', 'theme TAX', 'nuhe' ),
            'parent_item_colon'          => _x( 'Parent Item:', 'theme TAX', 'nuhe' ),
            'new_item_name'              => _x( 'New Item Name', 'theme TAX', 'nuhe' ),
            'add_new_item'               => _x( 'Add New Item', 'theme TAX', 'nuhe' ),
            'edit_item'                  => _x( 'Edit Item', 'theme TAX', 'nuhe' ),
            'update_item'                => _x( 'Update Item', 'theme TAX', 'nuhe' ),
            'view_item'                  => _x( 'View Item', 'theme TAX', 'nuhe' ),
            'separate_items_with_commas' => _x( 'Separate items with commas', 'theme TAX', 'nuhe' ),
            'add_or_remove_items'        => _x( 'Add or remove items', 'theme TAX', 'nuhe' ),
            'choose_from_most_used'      => _x( 'Choose from the most used', 'theme TAX', 'nuhe' ),
            'popular_items'              => _x( 'Popular Items', 'theme TAX', 'nuhe' ),
            'search_items'               => _x( 'Search Items', 'theme TAX', 'nuhe' ),
            'not_found'                  => _x( 'Not Found', 'theme TAX', 'nuhe' ),
            'no_terms'                   => _x( 'No items', 'theme TAX', 'nuhe' ),
            'items_list'                 => _x( 'Items list', 'theme TAX', 'nuhe' ),
            'items_list_navigation'      => _x( 'Items list navigation', 'theme TAX', 'nuhe' ),
        ];

        $rewrite = [
            'slug'         => $this->url_slug,
            'with_front'   => false,
            'hierarchical' => false,
        ];

        $args = [
            'labels'            => $labels,
            'hierarchical'      => true,
            'public'            => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'show_in_nav_menus' => true,
            'show_tagcloud'     => true,
            'rewrite'           => $rewrite,
            'show_in_rest'      => true,
        ];

        // Uncomment this to actually register the taxonomy.
        // register_taxonomy( static::SLUG, [ PostType\Page::SLUG, PostType\Post::SLUG ], $args );
    }
}
