<?php
/**
 * SingleInitiative
 *
 * @package Geniem\Theme\ACF
 */

namespace Geniem\Theme\ACF;

use Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use \Geniem\Theme\PostType;
use \Geniem\Theme\Traits;

/**
 * Class SingleInitiative
 *
 * @package Geniem\Theme\ACF
 */
class SingleInitiative {

    use Traits\Initiative;

    /**
     * CPT key.
     */
    const CPT_KEY = 'fg_initiative_cpt';

    /**
     * SingleInitiative constructor.
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_cpt_fields' ] );
    }

    /**
     * Register fields
     */
    public function register_cpt_fields() {
        $field_group = new Group( 'Aloitteen tiedot' );

        $key = self::CPT_KEY;

        $field_group->set_key( $key );

        $rule_group = new RuleGroup();
        $rule_group->add_rule( 'post_type', '==', PostType\Initiative::SLUG );

        $field_group->add_rule_group( $rule_group );
        $field_group->set_position( 'normal' );
        $field_group->set_menu_order( 2 );

        $strings = [
            'ruuti_initiative_category' => [
                'label' => 'Kategoria',
            ],
            'ruuti_initiative_status' => [
                'label' => 'Tila',
            ],
            'ruuti_initiative_detailed_status' => [
                'label' => 'Tarkempi tila',
            ],
            'ruuti_initiative_status_notes' => [
                'label' => 'Vastaus',
            ],
            'ruuti_initiative_location' => [
                'label' => 'Sijainti',
            ],
            'ruuti_initiative_city_api_id' => [
                'label' => 'ID aloitejärjestelmässä',
            ],
        ];

        $location = ( new Field\Text( $strings['ruuti_initiative_location']['label'] ) )
            ->set_key( 'ruuti_initiative_location' )
            ->set_name( 'ruuti_initiative_location' )
            ->set_wrapper_width( 50 );

        $category = ( new Field\Text( $strings['ruuti_initiative_category']['label'] ) )
            ->set_key( 'ruuti_initiative_category' )
            ->set_name( 'ruuti_initiative_category' )
            ->set_wrapper_width( 50 );

        $status = ( new Field\Text( $strings['ruuti_initiative_status']['label'] ) )
            ->set_key( 'ruuti_initiative_status' )
            ->set_name( 'ruuti_initiative_status' )
            ->set_wrapper_width( 50 );

        $detailed_status = ( new Field\Text( $strings['ruuti_initiative_detailed_status']['label'] ) )
            ->set_key( 'ruuti_initiative_detailed_status' )
            ->set_name( 'ruuti_initiative_detailed_status' )
            ->set_wrapper_width( 50 );

        $status_notes = ( new Field\TextArea( $strings['ruuti_initiative_status_notes']['label'] ) )
            ->set_key( 'ruuti_initiative_status_notes' )
            ->set_name( 'ruuti_initiative_status_notes' )
            ->set_new_lines( 'wpautop' );

        $api_id = ( new Field\Text( $strings['ruuti_initiative_city_api_id']['label'] ) )
            ->set_key( 'ruuti_initiative_city_api_id' )
            ->set_name( 'ruuti_initiative_city_api_id' )
            ->set_readonly();

        $field_group->add_fields( [
            $location,
            $category,
            $status,
            $detailed_status,
            $status_notes,
            $api_id,
        ] );

        $field_group->register();
    }
}

new SingleInitiative();
