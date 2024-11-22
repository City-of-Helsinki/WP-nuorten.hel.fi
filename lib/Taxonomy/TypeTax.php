<?php
/**
 * The class representation of the taxonomy 'type'.
 */

namespace Geniem\Theme\Taxonomy;

/**
 * This class defines the taxonomy.
 *
 * @package Geniem\Theme\Taxonomy
 */
class TypeTax implements \Geniem\Theme\Interfaces\Taxonomy {

    /**
     * This defines the slug of this taxonomy.
     * This needs to be unique so that there's no
     * clashes with e.G. query variables.
     */
    public const SLUG = 'type_tax';

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
        $this->url_slug = 'type_tax';
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

        $labels = [
            'name'          => _x( 'Tyypit', 'theme TAX General Name', 'nuhe' ),
            'singular_name' => _x( 'Tyyppi', 'theme TAX Singular Name', 'nuhe' ),
            'menu_name'     => _x( 'Tyypit', 'theme TAX', 'nuhe' ),
            'all_items'     => _x( 'Kaikki tyypit', 'theme TAX', 'nuhe' ),
            'add_new_item'  => _x( 'Lisää tyyppi', 'theme TAX', 'nuhe' ),
            'edit_item'     => _x( 'Muokkaa tyyppiä', 'theme TAX', 'nuhe' ),
            'update_item'   => _x( 'Päivitä tyyppi', 'theme TAX', 'nuhe' ),
            'view_item'     => _x( 'Näytä tyyppi', 'theme TAX', 'nuhe' ),
        ];

        $rewrite = [
            'slug'         => $this->url_slug,
            'with_front'   => false,
            'hierarchical' => true,
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

        $post_types = \apply_filters( 'nuhe_type_tax_associated_post_types', [] );

        register_taxonomy( static::SLUG, $post_types, $args );
    }
}
