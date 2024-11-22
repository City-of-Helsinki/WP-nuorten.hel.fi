<?php
/**
 * Fields for PostType\HighSchool and PostType\VocationalSchool
 *
 * @package Geniem\Theme\ACF
 */

namespace Geniem\Theme\ACF;

use Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use Geniem\Theme\Integrations\Palvelukartta\ApiClient;
use \Geniem\Theme\PostType;
use Geniem\Theme\Taxonomy\SchoolKeyword;
use Geniem\Theme\ACF\Fields;

/**
 * Class SingleSchool
 *
 * @package Geniem\Theme\ACF
 */
class SingleSchool {

    /**
     * CPT hero key.
     */
    const CPT_HERO_KEY = 'fg_school_cpt_hero';

    /**
     * CPT key.
     */
    const CPT_KEY = 'fg_school_cpt';

    /**
     * SingleSchool constructor.
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_hero' ] );
        add_action( 'init', [ $this, 'register_cpt_fields' ] );
        add_filter( 'acf/load_field/name=school_unit', [ $this, 'load_school_unit_choices' ] );
    }

    /**
     * Register hero fields
     */
    public function register_hero() {
        $group_title = _x( 'Heroalueen kentät', 'theme ACF', 'nuhe' );

        $key = self::CPT_HERO_KEY;

        // Create a field group
        $field_group = new Group( $group_title );
        $field_group->set_key( self::CPT_HERO_KEY );

        $field_group = $this->add_rules( $field_group );

        // Set group position.
        $field_group->set_position( 'normal' );
        $field_group->set_menu_order( 1 );

        // Define which elements are hidden from the admin.
        $field_group->set_hidden_elements(
            [
                'discussion',
                'comments',
                'format',
                'send-trackbacks',
            ]
        );

        $strings = [
            'bg_color' => [
                'label' => 'Taustaväri',
            ],
        ];

        // Add bg color selection field
        $bg_color = new Field\Select( $strings['bg_color']['label'] );
        $bg_color->set_key( "${key}_bg_color" );
        $bg_color->set_name( 'hero_bg_color' );
        $bg_color->set_choices( [
            'color-bus-light'          => 'Bussi',
            'color-brick-light'        => 'Tiili',
            'color-tram-light'         => 'Spåra',
            'color-coat-of-arms-light' => 'Vaakuna',
            'color-metro-light'        => 'Metro',
            'color-copper-light'       => 'Kupari',
            'color-fog-light'          => 'Sumu',
            'color-suomenlinna-light'  => 'Suomenlinna',
            'color-silver-light'       => 'Hopea',
        ] );

        // Add fields to the field grouo
        $field_group->add_field( $bg_color );

        // Register the field group
        $field_group->register();
    }

    /**
     * Register fields
     */
    public function register_cpt_fields() {
        $field_group = new Group( 'Oppilaitoksen tiedot' );
        $key         = self::CPT_KEY;

        $field_group->set_key( $key );

        $field_group = $this->add_rules( $field_group );

        $field_group->set_position( 'normal' );
        $field_group->set_menu_order( 2 );

        $strings = [
            'school_unit'         => [
                'label' => 'Palvelukartta Yksikkö',
            ],
            'school_latitude'     => [
                'label' => 'Sijainti: leveysaste',
            ],
            'school_longitude'    => [
                'label' => 'Sijainti: pituusaste',
            ],
            'school_search_terms' => [
                'label'        => 'Hakusanat',
                'instructions' => 'Kaupunginosa tai joku muu liittyvä asia',
                'button_label' => 'Lisää hakusana',
            ],
            'search_term'         => [
                'label' => 'Hakusana',
            ],
            'school_group'        => [
                'label' => 'Oppilaitoksen sisältöosiot',
            ],
        ];

        $palvelukartta_unit = new Field\Select( $strings['school_unit']['label'] );
        $palvelukartta_unit->set_key( 'school_unit' );
        $palvelukartta_unit->set_name( 'school_unit' );
        $palvelukartta_unit->allow_null();

        $latitude = ( new Field\Text( $strings['school_latitude']['label'] ) )
            ->set_key( 'school_latitude' )
            ->set_name( 'school_latitude' )
            ->set_readonly()
            ->set_wrapper_width( 50 );

        $longitude = ( new Field\Text( $strings['school_longitude']['label'] ) )
            ->set_key( 'school_longitude' )
            ->set_name( 'school_longitude' )
            ->set_readonly()
            ->set_wrapper_width( 50 );

        $search_terms = ( new Field\Taxonomy( $strings['school_search_terms']['label'] ) )
            ->set_key( 'school_search_terms' )
            ->set_name( 'school_search_terms' )
            ->set_taxonomy( SchoolKeyword::SLUG )
            ->allow_load_terms()
            ->allow_save_terms()
            ->allow_add_term()
            ->set_instructions( $strings['school_search_terms']['instructions'] );

        $school_group = new Field\Group( $strings['school_group']['label'] );
        $school_group->set_key( "${key}_school_group" );
        $school_group->set_name( 'school_group' );

        // Intro group
        $intro_group = $this->create_intro_group();

        // Video group
        $video_group = $this->create_video_group();

        $school_group->add_fields( [
            $intro_group,
            new Fields\UnitLinkList( 'school' ),
            $video_group,
        ] );

        $field_group->add_fields( [
            $palvelukartta_unit,
            $latitude,
            $longitude,
            $search_terms,
            $school_group,
        ] );

        $field_group->register();
    }

    /**
     * Load school unit choices
     *
     * @param array $field Field array.
     * @return array
     */
    public function load_school_unit_choices( array $field ) : array {
        global $post;

        $high_schools = [
            '658',
            '148',
            '598',
            '650',
        ];

        $vocational_schools = [
            '872',
        ];

        $selectable_schools = $post->post_type === PostType\HighSchool::SLUG ? $high_schools : $vocational_schools;

        $api     = new ApiClient();
        $choices = $api->get_units_by_ontology_word_ids( $selectable_schools );

        if ( empty( $choices ) ) {
            $field['choices']['0'] = 'Ei valittavia oppilaitoksia';
            return $field;
        }

        $field['choices'] = [];

        foreach ( $choices as $choice ) {
            $field['choices'][ $choice->get_id() ] = $choice->get_name();
        }

        asort( $field['choices'] );

        return $field;
    }

    /**
     * Create intro group
     *
     * @return Field acf-field.
     */
    private function create_intro_group() : Field {
        $key = self::CPT_KEY;

        $strings = [
            'intro_group'      => [
                'label' => 'Esittelyosio',
            ],
            'intro_title'      => [
                'label' => 'Otsikko',
            ],
        ];

        $intro_group = new Field\Group( $strings['intro_group']['label'] );
        $intro_group->set_key( "${key}_intro_group" );
        $intro_group->set_name( 'intro_group' );

        // Intro title
        $intro_title = new Field\Text( $strings['intro_title']['label'] );
        $intro_title->set_key( "${key}_intro_group_title" );
        $intro_title->set_name( 'title' );
        $intro_title->set_default_value( __( 'Youth center intro title', 'nuhe' ) );

        $intro_group->add_fields( [
            $intro_title,
        ] );

        return $intro_group;
    }

    /**
     * Create video group.
     *
     * @return Field acf-field.
     */
    private function create_video_group() : Field {
        $key = self::CPT_KEY;

        $strings = [
            'video_group' => [
                'label' => 'Video',
            ],
            'video_title' => [
                'label' => 'Otsikko',
            ],
            'video_url' => [
                'label' => 'Videon osoite',
            ],
            'anchor_link_text' => [
                'label' => 'Ankkurilinkin teksti',
            ],
        ];

        $video = new Field\Group( $strings['video_group']['label'] );
        $video->set_key( "${key}_video_group" );
        $video->set_name( 'video_group' );

        $video_title = new Field\Text( $strings['video_title']['label'] );
        $video_title->set_key( "${key}_video_title" );
        $video_title->set_name( 'title' );

        $anchor_link_text = new Field\Text( $strings['anchor_link_text']['label'] );
        $anchor_link_text->set_key( "${key}_video_group_anchor_link_text" );
        $anchor_link_text->set_name( 'anchor_link_text' );

        $video_url = new Field\URL( $strings['video_url']['label'] );
        $video_url->set_key( "${key}_video_url" );
        $video_url->set_name( 'video_url' );

        $video->add_fields( [
            $video_title,
            $anchor_link_text,
            $video_url,
        ] );

        return $video;
    }

    /**
     * Add rules to Group
     *
     * @param Group $field_group Field group.
     * @return Group
     */
    private function add_rules( Group $field_group ) : Group {
        $rules = [
            [
                'key'      => 'post_type',
                'value'    => PostType\HighSchool::SLUG,
                'operator' => '==',
            ],
            [
                'key'      => 'post_type',
                'value'    => PostType\VocationalSchool::SLUG,
                'operator' => '==',
            ],
        ];

        // add 'or rules
        array_walk( $rules, function ( array $rule ) use ( $field_group ) {
            $rule_group = new RuleGroup();
            $rule_group->add_rule( $rule['key'], $rule['operator'], $rule['value'] );
            $field_group->add_rule_group( $rule_group );
        } );

        return $field_group;
    }
}

new SingleSchool();
