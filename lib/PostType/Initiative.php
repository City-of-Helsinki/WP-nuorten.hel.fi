<?php
/**
 * The class representation of the post type 'initiative'.
 */

namespace Geniem\Theme\PostType;

use \Geniem\Theme\Interfaces\PostType;
use \Geniem\Theme\Integrations\Initiatives\ApiClient;

/**
 * Class Initiative
 *
 * @package Geniem\Theme\PostType
 */
class Initiative implements PostType {

    /**
     * This defines the slug of this post type.
     */
    public const SLUG = 'initiative-cpt';

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
    private $menu_order = 21;

    /**
     * This defines the CPT icon.
     *
     * @var string
     */
    private $icon = 'dashicons-menu';

    /**
     * Admin notice.
     *
     * @var int
     */
    private $admin_notice = 0;

    /**
     * Constructor
     */
    public function __construct() {

        $this->url_slug = 'initiatives';

        // Make possible description text translatable.
        $this->description = _x( 'Aloite', 'theme CPT', 'nuhe' );
    }

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        \add_action( 'init', \Closure::fromCallable( [ $this, 'register' ] ), 15 );
        \add_action( 'pre_get_posts', \Closure::fromCallable( [ $this, 'order_by_date' ] ) );
    }

    /**
     * This registers the post type.
     *
     * @return void
     */
    private function register() {
        $labels = [
            'name'                  => _x( 'Initiatives', 'theme CPT', 'nuhe' ),
            'singular_name'         => _x( 'Initiative', 'theme CPT Singular Name', 'nuhe' ),
            'menu_name'             => _x( 'Aloitteet', 'theme CPT', 'nuhe' ),
            'name_admin_bar'        => _x( 'Aloite', 'theme CPT', 'nuhe' ),
            'archives'              => _x( 'Aloitteiden arkistot', 'theme CPT', 'nuhe' ),
            'attributes'            => _x( 'Aloitteen ominaisuudet', 'theme CPT', 'nuhe' ),
            'parent_item_colon'     => _x( 'Yläsivu:', 'theme CPT', 'nuhe' ),
            'all_items'             => _x( 'All initiatives', 'theme CPT', 'nuhe' ),
            'add_new_item'          => _x( 'Lisää uusi aloite', 'theme CPT', 'nuhe' ),
            'add_new'               => _x( 'Lisää uusi', 'theme CPT', 'nuhe' ),
            'new_item'              => _x( 'New Item', 'theme CPT', 'nuhe' ),
            'edit_item'             => _x( 'Edit Item', 'theme CPT', 'nuhe' ),
            'update_item'           => _x( 'Update Item', 'theme CPT', 'nuhe' ),
            'view_item'             => _x( 'Näytä aloite', 'theme CPT', 'nuhe' ),
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
            'has_archive'         => 'all-initiatives',
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'rewrite'             => $rewrite,
            'capability_type'     => 'initiative',
            'map_meta_cap'        => true,
            'show_in_rest'        => false,
            'supports'            => [
                'title',
                'editor',
                'thumbnail',
                'revisions',
                'custom-fields',
            ],
        ];

        register_post_type( static::SLUG, $args );
    }

    /**
     * Order Initiatives by date in admin area.
     *
     * @param WP_Query $query.
     *
     * @return void
     */
    private function order_by_date( $query ) {
        if ( ! is_admin() ) {
            return;
        }

        // Check post type.
        if ( $query->query['post_type'] !== static::SLUG ) {
            return;
        }

        // Set order.
        $query->set('orderby', 'date');
        $query->set('order', 'DESC');

      }
}
