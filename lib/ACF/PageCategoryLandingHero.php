<?php
/**
 * CategoryLandingHero custom field group
 */

namespace Geniem\Theme\ACF;

use \Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use \Geniem\Theme\Model\PageCategoryLanding;

add_action( 'init', function () {
    $group_title = _x( 'Heroalueen kentät', 'theme ACF', 'nuhe' );

    $key = 'page_category_landing_hero';

    // Create a field group
    $field_group = new Group( $group_title );
    $field_group->set_key( 'page_category_landing_hero' );

    // Define the rules for the group
    $rule_group = new RuleGroup();
    $rule_group->add_rule( 'page_template', '==', PageCategoryLanding::TEMPLATE );
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
            'thumbnail',
        ]
    );

    $strings = [
        'description' => [
            'label'        => 'Kuvausteksti',
            'instructions' => 'Käytä maksimissaan 400 merkkiä.',
        ],
        'link' => [
            'label' => 'Linkit',
        ],
        'links' => [
            'label' => 'Linkit',
        ],
    ];

    // Add description field
    $desc = new Field\Textarea( $strings['description']['label'] );
    $desc->set_key( "${key}_description" );
    $desc->set_name( 'hero_description' );
    $desc->set_instructions( $strings['description']['instructions'] );
    $desc->set_new_lines();

    // Add link field
    $link = new Field\Link( $strings['link']['label'] );
    $link->set_key( "${key}_link" );
    $link->set_name( 'link' );

    // Add links repeater field
    $links = new Field\Repeater( $strings['links']['label'] );
    $links->set_key( "${key}_links" );
    $links->set_name( 'hero_links' );
    $links->set_max( 4 );
    $links->set_button_label( 'Lisää linkki' );
    $links->hide_label();
    $links->add_field( $link );

    // Add fields to the field grouo
    $field_group->add_fields( [
        $desc,
        $links,
    ] );

    // Register the field group
    $field_group->register();
} );
