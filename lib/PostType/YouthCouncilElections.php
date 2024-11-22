<?php
/**
 * The class representation of the post type 'youth council elections'.
 */

namespace Geniem\Theme\PostType;

use \Geniem\Theme\Interfaces\PostType;

/**
 * Class YouthCouncilElections
 *
 * @package Geniem\Theme\PostType
 */
class YouthCouncilElections implements PostType {

    /**
     * This defines the slug of this post type.
     */
    public const SLUG = 'youth-elections-cpt';

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
    private $menu_order = 20;

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

        // Make url slug translatable
        $this->url_slug = 'youth-council-elections';

        // Make possible description text translatable.
        $this->description = _x( 'Nuorisoneuvoston vaalit', 'theme CPT', 'nuhe' );
    }

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        add_action( 'init', \Closure::fromCallable( [ $this, 'register' ] ), 15 );
    }

    /**
     * This registers the post type.
     *
     * @return void
     */
    private function register() {
        $labels = [
            'name'                  => 'Nuorisoneuvoston vaalit',
            'singular_name'         => 'Nuorisoneuvoston vaalit',
            'menu_name'             => 'Nuorisoneuvoston vaalit',
            'name_admin_bar'        => 'Nuorisoneuvoston vaalit',
            'archives'              => 'Nuorisoneuvoston vaalien arkistot',
            'attributes'            => 'Nuorisoneuvoston vaalien ominaisuudet',
            'parent_item_colon'     => 'Yläsivu:',
            'all_items'             => 'Kaikki nuorisoneuvoston vaalit',
            'add_new_item'          => 'Lisää uusi nuorisoneuvoston vaalit -sisältö',
            'add_new'               => 'Lisää uusi',
            'new_item'              => 'New Item',
            'edit_item'             => 'Edit Item',
            'update_item'           => 'Update Item',
            'view_item'             => 'Näytä nuorisoneuvoston vaalit',
            'view_items'            => 'View Items',
            'search_items'          => 'Search Item',
            'not_found'             => 'Not found',
            'not_found_in_trash'    => 'Not found in Trash',
            'featured_image'        => 'Artikkelikuva',
            'set_featured_image'    => 'Set featured image',
            'remove_featured_image' => 'Remove featured image',
            'use_featured_image'    => 'Use as featured image',
            'insert_into_item'      => 'Insert into item',
            'uploaded_to_this_item' => 'Uploaded to this item',
            'items_list'            => 'Items list',
            'items_list_navigation' => 'Items list navigation',
            'filter_items_list'     => 'Filter items list',
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
            'capability_type'     => 'youth_council_election',
            'map_meta_cap'        => true,
            'show_in_rest'        => true,
            'supports'            => [
                'title',
                'thumbnail',
                'revisions',
                'custom-fields',
            ],
        ];

        register_post_type( static::SLUG, $args );
    }
}
