<?php
/**
 * Exclude from sitemap ACF group.
 */

namespace Geniem\Theme\ACF;

use \Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use \Geniem\Theme\PostType;
use \Geniem\Theme\Model\PageSitemap;

add_action( 'init', function () {
    $group_title = _x( 'Sivukartta', 'theme ACF', 'nuhe' );

    $key = 'exclude_from_sitemap';

    // Create a field group
    $field_group = new Group( $group_title );
    $field_group->set_key( 'fg_exclude_from_sitemap' );

    // Define rules for the field group
    $rules = [
        [
            'key'      => 'post_type',
            'value'    => PostType\Page::SLUG,
            'operator' => '==',
            'and'      => [
                [
                    'key'      => 'page_template',
                    'value'    => PageSitemap::TEMPLATE,
                    'operator' => '!=',
                ],
            ],
        ],
    ];

    // add rules
    array_walk( $rules, function( array $rule ) use ( $field_group ) {
        $rule_group = new RuleGroup();
        $rule_group->add_rule( $rule['key'], $rule['operator'], $rule['value'] );

        if ( isset( $rule['and'] ) && is_array( $rule['and'] ) ) {
            array_walk( $rule['and'], function( array $and_rule ) use ( $rule_group ) {
                $rule_group->add_rule( $and_rule['key'], $and_rule['operator'], $and_rule['value'] );
            } );
        }

        $field_group->add_rule_group( $rule_group );
    } );

    // Set group position.
    $field_group->set_position( 'side' );

    // Strings for field titles and instructions.
    $strings = [
        'exclude_from_sitemap' => [
            'title' => 'Piilota sivukartasta',
        ],
    ];

    // Exclude from sitemap
    $exclude_from_sitemap_label = $strings['exclude_from_sitemap']['title'];
    $exclude_from_sitemap_field = new Field\TrueFalse( $exclude_from_sitemap_label );
    $exclude_from_sitemap_field->set_key( "${key}_exclude_from_sitemap" );
    $exclude_from_sitemap_field->use_ui();
    $exclude_from_sitemap_field->set_name( 'exclude_from_sitemap' );

    $field_group->add_field( $exclude_from_sitemap_field );

    // Register the field group
    $field_group->register();
} );
