<?php
/**
 * PageEventsCollection ACF fields
 *
 * @package Geniem\Theme\ACF
 */

namespace Geniem\Theme\ACF;

use \Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use Geniem\Theme\Model\PageEventsCollection;

add_action( 'init', function () {
    $key = 'page_events_collection';

    $field_group = new Group(
        'Tapahtumien koontisivu',
        $key
    );

    // Define the rules for the group
    $rule_group = new RuleGroup();
    $rule_group->add_rule( 'page_template', '==', PageEventsCollection::TEMPLATE );
    $field_group->add_rule_group( $rule_group );

    $image = ( new Field\Image( 'Kuva' ) )
        ->set_key( "${key}_image" )
        ->set_name( 'image' )
        ->set_wrapper_width( 50 )
        ->update_value(
            function( $value, $post_id ) {
                \update_post_meta( $post_id, '_thumbnail_id', $value );
                return $value;
            }
        );

    $background_color_choices = [
        'coat-of-arms' => 'Vaakuna - sininen',
        'gold'         => 'Kulta - kulta',
        'silver'       => 'Hopea - hopea',
        'brick'        => 'Tiili - tummanpunainen',
        'bus'          => 'Bussi - tummansininen',
        'copper'       => 'Kupari - turkoosi',
        'engel'        => 'Engel -vaaleankeltainen',
        'fog'          => 'Sumu - vaaleansininen',
        'metro'        => 'Metro - oranssi',
        'summer'       => 'Kesä - keltainen',
        'suomenlinna'  => 'Suomenlinna - pinkki',
        'tram'         => 'Spåra - vihreä',
    ];

    $background_color = ( new Field\Select( 'Taustaväri' ) )
        ->set_key( "${key}_background_color" )
        ->set_name( 'background_color' )
        ->set_choices( $background_color_choices )
        ->set_wrapper_width( 50 );

    $description = ( new Field\Textarea( 'Kuvausteksti' ) )
        ->set_key( "${key}_description" )
        ->set_name( 'description' )
        ->set_new_lines( 'wpautop' )
        ->set_rows( 4 );

    $modules = ( new Field\FlexibleContent( 'Moduulit' ) )
        ->set_key( "${key}_modules" )
        ->set_name( 'modules' );

    $modules = apply_filters(
        'hkih_acf_events_collection_modules_layouts',
        $modules
    );

    $field_group->add_fields( [
        $image,
        $background_color,
        $description,
        $modules,
    ] );

    $field_group->register();
} );
