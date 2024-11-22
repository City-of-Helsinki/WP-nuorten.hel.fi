<?php
/**
 * UnitSearch ACF group.
 */

namespace Geniem\Theme\ACF;

use \Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use \Geniem\Theme\Model\UnitSearch;
use \Geniem\Theme\PostType;

add_action( 'init', function () {
    $group_title = _x( 'Yksikön tyyppi', 'theme ACF', 'nuhe' );

    $key = 'unit';

    // Create a field group
    $field_group = new Group( $group_title );
    $field_group->set_key( 'fg_unit_type' );

    // Define the rules for the group
    $rules = [
        [
            'key'      => 'page_template',
            'value'    => UnitSearch::TEMPLATE,
            'operator' => '==',
        ],
    ];

    // add 'or rules
    array_walk( $rules, function ( array $rule ) use ( $field_group ) {
        $rule_group = new RuleGroup();
        $rule_group->add_rule( $rule['key'], $rule['operator'], $rule['value'] );
        $field_group->add_rule_group( $rule_group );
    } );

    // Set group position.
    $field_group->set_position( 'side' );

    // Strings for field titles and instructions.
    $strings = [
        'post_type' => [
            'title' => 'Valitse haettava sisältötyyppi',
        ],
    ];

    // Post type field
    $post_type_label = $strings['post_type']['title'];
    $post_type_field = new Field\Radio( $post_type_label );
    $post_type_field->set_key( "${key}_post_type" );
    $post_type_field->set_name( 'unit_post_type' );
    $post_type_field->set_choices( [
        PostType\YouthCenter::SLUG      => 'Nuorisotalot',
        PostType\HighSchool::SLUG       => 'Lukiot',
        PostType\VocationalSchool::SLUG => 'Ammattioppilaitokset',
    ] );
    $post_type_field->set_required();

    $field_group->add_field( $post_type_field );

    // Register the field group
    $field_group->register();
} );
