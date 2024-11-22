<?php
/**
 * YouthCenterRelationship ACF group.
 */

namespace Geniem\Theme\ACF;

use \Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use \Geniem\Theme\PostType;

add_action( 'init', function () {
    if ( ! \is_main_site() ) {
        return;
    }

    $group_title = _x( 'Artikkeliin liittyvät nuorisotalot', 'theme ACF', 'nuhe' );

    $key = 'youthcenterrelation';

    // Create a field group
    $field_group = new Group( $group_title );
    $field_group->set_key( 'fg_youthcenterrelation' );

    // Define the rules for the group
    $rule_group = new RuleGroup();
    $rule_group->add_rule( 'post_type', '==', PostType\Post::SLUG );
    $field_group->add_rule_group( $rule_group );

    // Strings for field titles and instructions.
    $strings = [
        'youthcenter' => [
            'title' => 'Valitse artikkeliin liittyvät nuorisotalot',
        ],
    ];

    $youthcenter_label = $strings['youthcenter']['title'];
    $youthcenter_field = new Field\Relationship( $youthcenter_label );
    $youthcenter_field->set_key( "${key}_time" );
    $youthcenter_field->set_name( 'youthcenter' );
    $youthcenter_field->set_post_types( [ PostType\YouthCenter::SLUG ] );
    $youthcenter_field->set_filters( [ 'search' ] );

    $field_group->add_field( $youthcenter_field );

    // Register the field group
    $field_group->register();
} );
