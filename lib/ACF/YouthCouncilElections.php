<?php
/**
 * Fields for PostType\YouthCouncilElections
 *
 * @package Geniem\Theme\ACF
 */

namespace Geniem\Theme\ACF;

use Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use \Geniem\Theme\PostType;

/**
 * Class YouthCouncilElections
 *
 * @package Geniem\Theme\ACF
 */
class YouthCouncilElections {

    /**
     * CPT key.
     */
    const CPT_KEY = 'fg_youth_elections';

    /**
     * YouthCouncilElections constructor.
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_cpt_fields' ] );
    }

    /**
     * Register fields
     */
    public function register_cpt_fields() {
        $field_group = new Group( 'Ehdokkaan tiedot' );
        $key         = self::CPT_KEY;

        $field_group->set_key( $key );

        $rule_group = new RuleGroup();
        $rule_group->add_rule( 'post_type', '==', PostType\YouthCouncilElections::SLUG );
        $field_group->add_rule_group( $rule_group );

        $field_group->set_position( 'normal' );

        $strings = [
            'candidate_number' => [
                'label' => 'Ehdokasnumero',
            ],
            'age' => [
                'label' => 'Syntymävuosi',
            ],
            'postal_code' => [
                'label' => 'Postinumero',
            ],
            'slogan' => [
                'label' => 'Slogan / Iskulause',
            ],
            'description' => [
                'label' => 'Kuvausteksti',
            ],
        ];

        $candidate_number = new Field\Number( $strings['candidate_number']['label'] );
        $candidate_number->set_key( 'candidate_number' );
        $candidate_number->set_name( 'candidate_number' );
        $candidate_number->redipress_add_queryable();

        $age = new Field\Number( $strings['age']['label'] );
        $age->set_key( 'age' );
        $age->set_name( 'age' );

        $postal_code = new Field\Number( $strings['postal_code']['label'] );
        $postal_code->set_key( 'postal_code' );
        $postal_code->set_name( 'postal_code' );
        $postal_code->redipress_include_search();

        $slogan = new Field\Text( $strings['slogan']['label'] );
        $slogan->set_key( 'slogan' );
        $slogan->set_name( 'slogan' );

        $description = new Field\Wysiwyg( $strings['description']['label'] );
        $description->set_key( 'description' );
        $description->set_name( 'description' );

        $field_group->add_fields( [
            $candidate_number,
            $age,
            $postal_code,
            $slogan,
            $description,
        ] );

        $field_group->register();
    }
}

new YouthCouncilElections();
