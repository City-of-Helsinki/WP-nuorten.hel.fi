<?php
/**
 * Attachment custom field group
 */

namespace Geniem\Theme\ACF;

use \Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;

add_action( 'init', function () {
    $group_title = _x( 'Attachment', 'theme ACF', 'nuhe' );

    $key = 'attachment';

    // Create a field group
    $field_group = new Group( $group_title );
    $field_group->set_key( 'attachment' );

    // Define the rules for the group
    $rule_group = new RuleGroup();
    $rule_group->add_rule( 'attachment', '==', 'all' );
    $field_group->add_rule_group( $rule_group );

    // Set group position.
    $field_group->set_position( 'normal' );

    $attachment_strings = [
        'photographer_name' => 'Valokuvaajan nimi (muista kuvaoikeudet!)',
        'image_link'        => 'Valitse linkki',
    ];

    // Add photographer info field
    $photographer_name = new Field\Text( $attachment_strings['photographer_name'] );
    $photographer_name->set_key( "{$key}_photographer_name" );
    $photographer_name->set_name( 'photographer_name' );
    $photographer_name->set_required();
    $field_group->add_field( $photographer_name );

    // Add link field.
    $image_link = new Field\Link( $attachment_strings['image_link'] );
    $image_link->set_key( "{$key}_image_link" );
    $image_link->set_name( 'image_link' );
    $field_group->add_field( $image_link );

    // Register the field group
    $field_group->register();
} );
