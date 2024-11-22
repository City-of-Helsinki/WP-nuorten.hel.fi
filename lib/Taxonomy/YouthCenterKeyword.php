<?php
/**
 * The class representation of the taxonomy 'youth_center_keyword_tax'.
 */

namespace Geniem\Theme\Taxonomy;

/**
 * This class defines the taxonomy.
 *
 * @package Geniem\Theme\Taxonomy
 */
class YouthCenterKeyword implements \Geniem\Theme\Interfaces\Taxonomy {

    /**
     * This defines the slug of this taxonomy.
     * This needs to be unique so that there's no
     * clashes with e.G. query variables.
     */
    public const SLUG = 'youth_center_keyword_tax';

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
        $this->url_slug = 'youth_center_keyword_tax';
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
            'name'          => _x( 'Hakusanat', 'theme TAX General Name', 'nuhe' ),
            'singular_name' => _x( 'Hakusana', 'theme TAX Singular Name', 'nuhe' ),
            'menu_name'     => _x( 'Hakusanat', 'theme TAX', 'nuhe' ),
            'all_items'     => _x( 'Kaikki hakusanat', 'theme TAX', 'nuhe' ),
            'add_new_item'  => _x( 'Lisää hakusana', 'theme TAX', 'nuhe' ),
            'edit_item'     => _x( 'Muokkaa hakusanaa', 'theme TAX', 'nuhe' ),
            'update_item'   => _x( 'Päivitä hakusana', 'theme TAX', 'nuhe' ),
            'view_item'     => _x( 'Näytä hakusana', 'theme TAX', 'nuhe' ),
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
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
            'rewrite'           => $rewrite,
            'show_in_rest'      => true,
        ];

        register_taxonomy( static::SLUG, [ \Geniem\Theme\PostType\YouthCenter::SLUG ], $args );
    }
}
