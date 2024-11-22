<?php
/**
 * Front page ACF fields
 *
 * @package Geniem\Theme\ACF
 */

namespace Geniem\Theme\ACF;

use \Geniem\ACF\Field;
use Geniem\ACF\Field\TrueFalse;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use \Geniem\Theme\Model\FrontPage;
use \Geniem\ACF\ConditionalLogicGroup;
use \Geniem\Theme\Localization;

/**
 * Class PageFront
 *
 * @package Geniem\Theme\ACF
 */
class PageFront {

    /**
     * PageFront constructor.
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_slider' ] );
        add_action( 'init', [ $this, 'register_hero' ] );
        add_action( 'init', [ $this, 'register_content_sections' ] );
    }

    /**
     * Register hero fields
     */
    public function register_slider() {
        $group_title = _x( 'Kuvakarusellin kentät', 'theme ACF', 'nuhe' );

        $key = 'page_front_slider';

        // Create a field group
        $field_group = new Group( $group_title );
        $field_group->set_key( 'page_front_slider' );

        // Define the rules for the group
        $rule_group = new RuleGroup();
        $rule_group->add_rule( 'page_template', '==', Frontpage::TEMPLATE );
        $field_group->add_rule_group( $rule_group );

        // Set group position.
        $field_group->set_position( 'normal' );
        $field_group->set_menu_order( 1 );

        // Define which elements are hidden from the admin.
        $field_group->set_hidden_elements(
            [
                'discussion',
                'comments',
                'format',
                'send-trackbacks',
                'editor',
            ]
        );

        $strings = [
            'slides' => [
                'label'        => 'Kuvakaruselli',
            ],
            'title' => [
                'label'        => 'Otsikko',
                'instructions' => 'Käytä maksimissaan 400 merkkiä.',
            ],
            'description' => [
                'label'        => 'Kuvausteksti',
                'instructions' => 'Käytä maksimissaan 400 merkkiä.',
            ],
            'image' => [
                'label' => 'Taustakuva',
            ],
            'link' => [
                'label' => 'Linkki',
            ],
        ];

        // Add slides repeater field
        $slides = new Field\Repeater( $strings['slides']['label'] );
        $slides->set_key( "{$key}_slides" );
        $slides->set_name( 'slides' );
        $slides->set_max( 5 );
        $slides->set_button_label( 'Lisää kuva' );
        $slides->hide_label();

        // Add title field
        $title = new Field\Text( $strings['title']['label'] );
        $title->set_key( "{$key}_title" );
        $title->set_name( 'title' );
        $title->set_instructions( $strings['title']['instructions'] );
        $title->set_required();
        $title->redipress_include_search();
        $slides->add_field( $title );

        // Add description field
        $description = new Field\Textarea( $strings['description']['label'] );
        $description->set_key( "{$key}_description" );
        $description->set_name( 'description' );
        $description->set_instructions( $strings['description']['instructions'] );
        $description->set_new_lines();
        $description->redipress_include_search();
        $slides->add_field( $description );

        // Add image field
        $image = new Field\Image( $strings['image']['label'] );
        $image->set_key( "{$key}_image" );
        $image->set_name( 'image' );
        $image->set_required();
        $slides->add_field( $image );

        // Add link field
        $link = new Field\Link( $strings['link']['label'] );
        $link->set_key( "{$key}_link" );
        $link->set_name( 'link' );
        $slides->add_field( $link );

        // Add fields to the field grouo
        $field_group->add_fields( [
            $slides,
        ] );

        // Register the field group
        $field_group->register();
    }

    /**
     * Register hero fields
     */
    public function register_hero() {
        $group_title = _x( 'Heroalueen kentät', 'theme ACF', 'nuhe' );

        $key = 'page_front_hero';

        // Create a field group
        $field_group = new Group( $group_title );
        $field_group->set_key( 'page_front_hero' );

        // Define the rules for the group
        $rule_group = new RuleGroup();
        $rule_group->add_rule( 'page_template', '==', Frontpage::TEMPLATE );
        $field_group->add_rule_group( $rule_group );

        // Set group position.
        $field_group->set_position( 'normal' );
        $field_group->set_menu_order( 2 );

        // Define which elements are hidden from the admin.
        $field_group->set_hidden_elements(
            [
                'discussion',
                'comments',
                'format',
                'send-trackbacks',
                'editor',
            ]
        );

        $strings = [
            'description' => [
                'label'        => 'Heron otsikko( eli sivun pääotsikko )',
                'instructions' => 'Käytä maksimissaan 400 merkkiä.',
            ],
            'description_2' => [
                'label'        => 'Kuvausteksti',
                'instructions' => 'Käytä maksimissaan 400 merkkiä.',
            ],
            'hero_bg_color' => [
                'label' => 'Taustaväri',
            ],
            'link' => [
                'label' => 'Linkki',
            ],
            'hide_gutenberg' => [
                'label' => 'Piilota sisältöalue',
            ],
        ];

        // Add description(title) field
        $desc = new Field\Text( $strings['description']['label'] );
        $desc->set_key( "{$key}_description" );
        $desc->set_name( 'hero_description' );
        $desc->set_instructions( $strings['description']['instructions'] );
        $desc->redipress_include_search();

        // Add description field
        $desc_2 = new Field\Textarea( $strings['description_2']['label'] );
        $desc_2->set_key( "{$key}_description_2" );
        $desc_2->set_name( 'hero_description_2' );
        $desc_2->set_instructions( $strings['description_2']['instructions'] );
        $desc_2->set_new_lines();
        $desc_2->redipress_include_search();

        // Add hero bg color field
        $hero_bg_color = new Field\Select( $strings['hero_bg_color']['label'] );
        $hero_bg_color->set_key( "{$key}_hero_bg_color" );
        $hero_bg_color->set_name( 'hero_bg_color' );
        $colors = [
            'default' => 'Oletus(musta)',
            'pink'    => 'Pinkki',
        ];
        $colors = apply_filters( 'nuhe_acf_color_choices', $colors );
        $hero_bg_color->set_choices( $colors );

        // Add link field
        $link = new Field\Link( $strings['link']['label'] );
        $link->set_key( "{$key}_link" );
        $link->set_name( 'hero_link' );

        // Add fields to the field grouo
        $field_group->add_fields( [
            $desc,
            $desc_2,
            $hero_bg_color,
            $link,
        ] );

        // Hide Gutenberg
        $hide_gutenberg = new Fields\HideGutenberg( $strings['hide_gutenberg']['label'] );
        $hide_gutenberg->set_key( "{$key}_hide_gutenberg" );
        $hide_gutenberg->set_name( 'hide_gutenberg' );
        $hide_gutenberg->hide_label();

        $field_group->add_field( $hide_gutenberg );

        // Register the field group
        $field_group->register();
    }

    /**
     * Register content sections
     */
    public function register_content_sections() {
        $group_title = _x( 'Sisältöosiot', 'theme ACF', 'nuhe' );

        $key = 'page_front_cs';

        // Create a field group
        $field_group = new Group( $group_title );
        $field_group->set_key( 'page_front_cs' );

        // Define the rules for the group
        $rule_group = new RuleGroup();
        $rule_group->add_rule( 'page_template', '==', Frontpage::TEMPLATE );
        $field_group->add_rule_group( $rule_group );

        // Set group position.
        $field_group->set_position( 'normal' );
        $field_group->set_menu_order( 2 );

        // Define which elements are hidden from the admin.
        $field_group->set_hidden_elements(
            [
                'discussion',
                'comments',
                'format',
                'send-trackbacks',
            ]
        );

        $menu_items = $this->get_menu_items();

        if ( empty( $menu_items ) ) {
            return;
        }

        $content_sections = $this->generate_fields( $key, $menu_items );

        // Add field to the field grouo
        $field_group->add_fields( $content_sections );

        // Register the field group
        $field_group->register();
    }

    /**
     * Get menu items.
     *
     * @return bool|array Returns false is navigation not found.
     *                    Returns an array of items with parent 0.
     */
    private function get_menu_items() {
        $options  = \get_option( 'polylang' );
        $theme    = \get_option( 'stylesheet' );
        $cur_lang = Localization::get_current_language();

        if ( empty( $cur_lang ) ) {
            return false;
        }

        $menu_id  = $options['nav_menus'][ $theme ]['primary_navigation'][ $cur_lang ];

        if ( empty( $menu_id ) ) {
            return false;
        }

        $menu       = \wp_get_nav_menu_object( $menu_id );
        $menu_items = \wp_get_nav_menu_items( $menu->term_id );

        if ( empty( $menu_items ) ) {
            return false;
        }

        // Return only items with parent 0
        return array_filter( $menu_items, function( $item ) {
            $menu_item_parent_id = (int) $item->menu_item_parent;
            return $menu_item_parent_id === 0;
        } );
    }

    /**
     * Generate fields.
     *
     * @param string $key        Field group key.
     * @param array  $menu_items Array of menu items.
     * @return array Fields.
     */
    private function generate_fields( $key, $menu_items ) : array {
        $strings = [
            'show_sections' => [
                'label' => 'Näytä osiot',
            ],
            'description' => [
                'label'        => 'Kuvausteksti',
                'instructions' => 'Linkkejä ei näytetä jos lisäät kuvaustekstin.',
            ],
            'link' => [
                'label' => 'Linkit',
            ],
            'links' => [
                'label' => 'Linkit',
            ],
            'read_more' => [
                'label'   => 'Lue lisää -linkin teksti',
                'default' => 'Lue lisää',
            ],
            'section_title' => [
                'label' => 'Osion otsikko',
            ],
        ];

        // Add show sections toggle
        $show_sections = new TrueFalse( $strings['show_sections']['label'] );
        $show_sections->set_key( "{$key}_show_sections" );
        $show_sections->set_name( 'show_sections' );
        $show_sections->set_default_value( 1 );

        $conditional_logic = new ConditionalLogicGroup();
        $conditional_logic->add_rule( $show_sections, '==', 1 );

        // Add sections group field
        $content_sections = new Field\Group( 'Sisältöosiot' );
        $content_sections->set_key( "{$key}_content_sections" );
        $content_sections->set_name( 'content_sections' );
        $content_sections->hide_label();
        $content_sections->add_conditional_logic( $conditional_logic );

        // Add link field
        $link = new Field\Link( $strings['link']['label'] );
        $link->set_key( "{$key}_link" );
        $link->set_name( 'link' );

        // Add links repeater field
        $links = new Field\Repeater( $strings['links']['label'] );
        $links->set_key( "{$key}_links" );
        $links->set_name( 'section_links' );
        $links->set_max( 3 );
        $links->set_button_label( 'Lisää linkki' );
        $links->hide_label();
        $links->add_field( $link );

        // Add section description field
        $desc = new Field\Textarea( $strings['description']['label'] );
        $desc->set_key( "{$key}_section_description" );
        $desc->set_name( 'section_description' );
        $desc->set_instructions( $strings['description']['instructions'] );
        $desc->set_new_lines();

        // Add read more link text
        $read_more = new Field\Text( $strings['read_more']['label'] );
        $read_more->set_key( "{$key}_read_more" );
        $read_more->set_name( 'read_more' );
        $read_more->set_default_value( $strings['read_more']['default'] );

        array_walk( $menu_items, function( $item ) use ( $key, $links, $desc, $read_more, $strings, $content_sections ) {
            $group = new Field\Group( $item->title );
            $group->set_key( "{$key}_{$item->object_id}" );
            $group->set_name( $item->object_id );
            $group->set_wrapper_width( 50 );

            // Add section group title
            $section_title = new Field\Text( $strings['section_title']['label'] );
            $section_title->set_key( "{$key}_{$item->object_id}_section_title" );
            $section_title->set_name( 'section_title' );
            $section_title->set_default_value( $item->title );

            $group->add_fields( [
                $section_title,
                $links,
                $desc,
                $read_more,
            ] );

            $content_sections->add_field( $group );
        } );

        return [
            $show_sections,
            $content_sections,
        ];
    }
}

new PageFront();
