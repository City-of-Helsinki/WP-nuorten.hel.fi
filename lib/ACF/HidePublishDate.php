<?php
/**
 * HidePublishDate ACF group.
 */

namespace Geniem\Theme\ACF;

use \Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use \Geniem\Theme\PostType;

add_action( 'init', function () {
    $group_title = _x( 'Piilota julkaisupäivämäärä', 'theme ACF', 'nuhe' );

    $key = 'hide_publish_date';

    // Create a field group
    $field_group = new Group( $group_title );
    $field_group->set_key( 'fg_hide_publish_date' );

    // Define the rules for the group
    $rule_group = new RuleGroup();
    $rule_group->add_rule( 'post_type', '==', PostType\Post::SLUG );
    $field_group->add_rule_group( $rule_group );

    // Set group position.
    $field_group->set_position( 'side' );

    // Strings for field.
    $strings = [
        'hide_publish_date' => [
            'title' => 'Piilota julkaisupäivämäärä',
        ],
    ];

    // Expiration field
    $hide_publish_date_label = $strings['hide_publish_date']['title'];
    $hide_publish_date_field = new Field\TrueFalse( $hide_publish_date_label );
    $hide_publish_date_field->set_key( $key );
    $hide_publish_date_field->set_name( 'hide_publish_date' );
    $hide_publish_date_field->hide_label();
    $hide_publish_date_field->use_ui();

    $field_group->add_field( $hide_publish_date_field );

    // Register the field group
    $field_group->register();
} );
