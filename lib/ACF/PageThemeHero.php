<?php
/**
 * PageTheme custom field group
 */

namespace Geniem\Theme\ACF;

use \Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use \Geniem\Theme\Model;

add_action( 'init', function () {
    $group_title = _x( 'Heroalueen kentät', 'theme ACF', 'nuhe' );

    $key = 'page_theme_hero';

    // Create a field group
    $field_group = new Group( $group_title );
    $field_group->set_key( 'page_theme_hero' );

    // Define 'or' rules for the field group
    $rules = [
        [
            'key'      => 'page_template',
            'value'    => Model\PageTheme::TEMPLATE,
            'operator' => '==',
        ],
        [
            'key'      => 'page_template',
            'value'    => Model\PageInitiatives::TEMPLATE,
            'operator' => '==',
        ],
    ];

    // add rules
    array_walk( $rules, function( array $rule ) use ( $field_group ) {
        $rule_group = new RuleGroup();
        $rule_group->add_rule( $rule['key'], $rule['operator'], $rule['value'] );
        $field_group->add_rule_group( $rule_group );
    } );

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
        ]
    );

    $strings = [
        'title' => [
            'label'        => 'Otsikko',
            'instructions' => 'Syötä otsikko, jos haluat käyttää heron otsikkona muuta tekstiä kuin sivun nimeä',
        ],
        'description' => [
            'label'        => 'Kuvausteksti',
            'instructions' => 'Käytä maksimissaan 400 merkkiä.',
        ],
        'hero_bg_color' => [
            'label' => 'Taustaväri',
        ],
        'link' => [
            'label' => 'Linkki',
        ],
    ];

    // Add title field
    $title = new Field\Text( $strings['title']['label'] );
    $title->set_key( "${key}_title" );
    $title->set_name( 'title' );
    $title->set_instructions( $strings['title']['instructions'] );
    $title->redipress_include_search();

    // Add description field
    $desc = new Field\Textarea( $strings['description']['label'] );
    $desc->set_key( "${key}_description" );
    $desc->set_name( 'hero_description' );
    $desc->set_instructions( $strings['description']['instructions'] );
    $desc->set_new_lines();
    $desc->redipress_include_search();

    // Add hero bg color field
    $hero_bg_color = new Field\Select( $strings['hero_bg_color']['label'] );
    $hero_bg_color->set_key( "${key}_hero_bg_color" );
    $hero_bg_color->set_name( 'hero_bg_color' );
    $colors = [
        'default' => 'Oletus(musta)',
        'blue'    => 'Sininen',
        'beige'   => 'Beige',
        'pink'    => 'Pinkki',
        'purple'  => 'Violetti',
    ];
    $colors = apply_filters( 'nuhe_acf_color_choices', $colors );
    $hero_bg_color->set_choices( $colors );

    // Add link field
    $link = new Field\Link( $strings['link']['label'] );
    $link->set_key( "${key}_link" );
    $link->set_name( 'hero_link' );

    // Add fields to the field grouo
    $field_group->add_fields( [
        $title,
        $desc,
        $hero_bg_color,
        $link,
    ] );

    // Register the field group
    $field_group->register();
} );
