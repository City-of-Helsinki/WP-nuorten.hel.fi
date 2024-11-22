<?php
/**
 * PageVolunteering custom field group
 */

namespace Geniem\Theme\ACF;

use \Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use \Geniem\Theme\Model\PageVolunteering;

add_action( 'init', function () {
    $group_title = _x( 'Heroalueen kentät', 'theme ACF', 'nuhe' );

    $key = 'page_volunteering_hero';

    // Create a field group
    $field_group = new Group( $group_title );
    $field_group->set_key( $key );

    // Define the rules for the group
    $rule_group = new RuleGroup();
    $rule_group->add_rule( 'page_template', '==', PageVolunteering::TEMPLATE );
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
    ];

    // Add title field
    $title = new Field\Text( $strings['title']['label'] );
    $title->set_key( "${key}_hero_title" );
    $title->set_name( 'hero_title' );
    $title->set_instructions( $strings['title']['instructions'] );
    $title->redipress_include_search();

    // Add description field
    $desc = new Field\Textarea( $strings['description']['label'] );
    $desc->set_key( "${key}_description" );
    $desc->set_name( 'hero_description' );
    $desc->set_instructions( $strings['description']['instructions'] );
    $desc->set_new_lines();
    $desc->redipress_include_search();

    // Add fields to the field grouo
    $field_group->add_fields( [
        $title,
        $desc,
    ] );

    // Register the field group
    $field_group->register();
} );
