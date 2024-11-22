<?php
/**
 * Instructor
 *
 * @package Geniem\Theme\ACF
 */

namespace Geniem\Theme\ACF;

use Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use \Geniem\Theme\PostType;

/**
 * Class Instructor
 *
 * @package Geniem\Theme\ACF
 */
class Instructor {

    /**
     * Instructor constructor.
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_cpt_fields' ] );
    }

    /**
     * Register fields
     */
    public function register_cpt_fields() {
        $key         = 'fg_instructor_cpt';
        $field_group = new Group( 'Ohjaaja' );
        $field_group->set_key( $key );

        $rule_group = new RuleGroup();
        $rule_group->add_rule( 'post_type', '==', PostType\Instructor::SLUG );

        $field_group->add_rule_group( $rule_group );
        $field_group->set_position( 'normal' );

        $strings = [
            'title' => [
                'label' => 'Titteli',
            ],
            'email' => [
                'label' => 'Sähköpostiosoite',
            ],
            'phone' => [
                'label' => 'Puhelinnumero',
            ],
        ];

        // Add the title
        $title = new Field\Text( $strings['title']['label'] );
        $title->set_key( "${key}_title" );
        $title->set_name( 'title' );

        // Add the email
        $email = new Field\Email( $strings['email']['label'] );
        $email->set_key( "${key}_email" );
        $email->set_name( 'email' );

        // Add the phone number
        $phone_number = new Field\Text( $strings['phone']['label'] );
        $phone_number->set_key( "${key}_phone" );
        $phone_number->set_name( 'phone' );

        $field_group->add_fields( [
            $title,
            $email,
            $phone_number,
        ] );

        $field_group->register();
    }
}

new Instructor();
