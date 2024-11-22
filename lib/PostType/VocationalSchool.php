<?php
/**
 * The class representation of the post type 'vocational school'.
 */

namespace Geniem\Theme\PostType;

use \Geniem\Theme\Interfaces\PostType;

/**
 * Class VocationalSchool
 *
 * @package Geniem\Theme\PostType
 */
class VocationalSchool implements PostType {

    /**
     * This defines the slug of this post type.
     */
    public const SLUG = 'vocational-cpt';

    /**
     * This defines what is shown in the url. This can
     * be different than the slug which is used to register the post type.
     *
     * @var string
     */
    private $url_slug = '';

    /**
     * Define the CPT description
     *
     * @var string
     */
    private $description = '';

    /**
     * This is used to position the post type menu in admin.
     *
     * @var int
     */
    private $menu_order = 22;

    /**
     * This defines the CPT icon.
     *
     * @var string
     */
    private $icon = 'dashicons-menu';

    /**
     * Constructor
     */
    public function __construct() {

        $this->url_slug = 'vocational-schools';

        // Make possible description text translatable.
        $this->description = _x( 'Ammattioppilaitos', 'theme CPT', 'nuhe' );
    }

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        // Uncomment this to do the actual registration.
        add_action( 'init', \Closure::fromCallable( [ $this, 'register' ] ), 15 );
        add_action( 'acf/save_post', \Closure::fromCallable( [ $this, 'fill_api_data' ] ) );
    }

    /**
     * This registers the post type.
     *
     * @return void
     */
    private function register() {
        $labels = [
            'name'                  => _x( 'Ammattioppilaitokset', 'theme CPT', 'nuhe' ),
            'singular_name'         => _x( 'Ammattioppilaitos', 'theme CPT Singular Name', 'nuhe' ),
            'menu_name'             => _x( 'Ammattioppilaitokset', 'theme CPT', 'nuhe' ),
            'name_admin_bar'        => _x( 'Ammattioppilaitos', 'theme CPT', 'nuhe' ),
            'archives'              => _x( 'Ammattioppilaitosten arkistot', 'theme CPT', 'nuhe' ),
            'attributes'            => _x( 'Ammattioppilaitoksen ominaisuudet', 'theme CPT', 'nuhe' ),
            'parent_item_colon'     => _x( 'Yläsivu:', 'theme CPT', 'nuhe' ),
            'all_items'             => _x( 'Kaikki ammattioppilaitokset', 'theme CPT', 'nuhe' ),
            'add_new_item'          => _x( 'Lisää uusi ammattioppilaitos', 'theme CPT', 'nuhe' ),
            'add_new'               => _x( 'Lisää uusi', 'theme CPT', 'nuhe' ),
            'new_item'              => _x( 'New Item', 'theme CPT', 'nuhe' ),
            'edit_item'             => _x( 'Edit Item', 'theme CPT', 'nuhe' ),
            'update_item'           => _x( 'Update Item', 'theme CPT', 'nuhe' ),
            'view_item'             => _x( 'Näytä ammattioppilaitos', 'theme CPT', 'nuhe' ),
            'view_items'            => _x( 'View Items', 'theme CPT', 'nuhe' ),
            'search_items'          => _x( 'Search Item', 'theme CPT', 'nuhe' ),
            'not_found'             => _x( 'Not found', 'theme CPT', 'nuhe' ),
            'not_found_in_trash'    => _x( 'Not found in Trash', 'theme CPT', 'nuhe' ),
            'featured_image'        => _x( 'Artikkelikuva', 'theme CPT', 'nuhe' ),
            'set_featured_image'    => _x( 'Set featured image', 'theme CPT', 'nuhe' ),
            'remove_featured_image' => _x( 'Remove featured image', 'theme CPT', 'nuhe' ),
            'use_featured_image'    => _x( 'Use as featured image', 'theme CPT', 'nuhe' ),
            'insert_into_item'      => _x( 'Insert into item', 'theme CPT', 'nuhe' ),
            'uploaded_to_this_item' => _x( 'Uploaded to this item', 'theme CPT', 'nuhe' ),
            'items_list'            => _x( 'Items list', 'theme CPT', 'nuhe' ),
            'items_list_navigation' => _x( 'Items list navigation', 'theme CPT', 'nuhe' ),
            'filter_items_list'     => _x( 'Filter items list', 'theme CPT', 'nuhe' ),
        ];

        $rewrite = [
            'slug'       => $this->url_slug,
            'with_front' => false,
            'pages'      => true,
            'feeds'      => true,
        ];

        $args = [
            'label'               => $labels['name'],
            'description'         => $this->description,
            'labels'              => $labels,
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'menu_position'       => $this->menu_order,
            'menu_icon'           => $this->icon,
            'show_in_admin_bar'   => true,
            'show_in_nav_menus'   => true,
            'can_export'          => true,
            'has_archive'         => false,
            'exclude_from_search' => true,
            'publicly_queryable'  => true,
            'rewrite'             => $rewrite,
            'capability_type'     => 'vocational_school',
            'map_meta_cap'        => true,
            'show_in_rest'        => true,
            'supports'            => [
                'title',
                'revisions',
                'custom-fields',
            ],
        ];

        register_post_type( static::SLUG, $args );
    }

    /**
     * Fill API data
     *
     * @param int $post_id \WP_Post ID.
     */
    protected function fill_api_data( $post_id ) : void {
        if ( get_post_type( $post_id ) !== static::SLUG ) {
            return;
        }

        $unit_id = get_field( 'school_unit', $post_id );

        if ( empty( $unit_id ) ) {
            return;
        }

        $api  = new \Geniem\Theme\Integrations\Palvelukartta\ApiClient();
        $unit = $api->get_unit_by_id( $unit_id );

        update_field( 'school_latitude', $unit->get_latitude(), $post_id );
        update_field( 'school_longitude', $unit->get_longitude(), $post_id );
    }
}
