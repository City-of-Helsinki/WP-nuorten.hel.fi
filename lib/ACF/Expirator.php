<?php
/**
 * Expiration ACF group.
 */

namespace Geniem\Theme\ACF;

use \Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use \Geniem\Theme\PostType;

add_action( 'init', function () {
    $group_title = _x( 'Ajastettu poisto', 'theme ACF', 'nuhe' );

    $key = 'expiration';

    // Create a field group
    $field_group = new Group( $group_title );
    $field_group->set_key( 'fg_expiration' );

    // Define the rules for the group
    $rule_group = new RuleGroup();
    $rule_group->add_rule( 'post_type', '==', PostType\Post::SLUG );
    $field_group->add_rule_group( $rule_group );

    // Set group position.
    $field_group->set_position( 'side' );

    // Strings for field titles and instructions.
    $strings = [
        'expiration_time' => [
            'title'        => 'Poistumisaika',
            'instructions' => 'Valitse aika, jolloin julkaisu siirtyy luonnokseksi',
        ],
    ];

    // Expiration field
    $expiration_time_label = $strings['expiration_time']['title'];
    $expiration_time_field = new Field\DateTimePicker( $expiration_time_label );
    $expiration_time_field->set_key( "${key}_time" );
    $expiration_time_field->set_name( 'expiration_time' );
    $expiration_time_field->set_display_format( 'j.n.Y H.i' );
    $expiration_time_field->set_return_format( 'j.n.Y H.i' );
    $expiration_time_field->set_instructions( $strings['expiration_time']['instructions'] );
    $expiration_time_field->hide_label();

    $field_group->add_field( $expiration_time_field );

    // Register the field group
    $field_group->register();
} );
