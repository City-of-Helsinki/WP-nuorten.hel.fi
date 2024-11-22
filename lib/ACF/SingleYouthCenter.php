<?php
/**
 * SingleYouthCenter
 *
 * @package Geniem\Theme\ACF
 */

namespace Geniem\Theme\ACF;

use Geniem\ACF\Field;
use \Geniem\ACF\Group;
use \Geniem\ACF\RuleGroup;
use Geniem\Theme\Integrations\Palvelukartta\ApiClient;
use Geniem\Theme\Integrations\Respa\ApiClient as RespaApiClient;
use \Geniem\Theme\PostType;
use Geniem\Theme\Settings;
use Geniem\Theme\Taxonomy\YouthCenterKeyword;
use Geniem\Theme\ACF\Fields;

/**
 * Class SingleYouthCenter
 *
 * @package Geniem\Theme\ACF
 */
class SingleYouthCenter {

    /**
     * CPT hero key.
     */
    const CPT_HERO_KEY = 'fg_youth_center_cpt_hero';

    /**
     * CPT key.
     */
    const CPT_KEY = 'fg_youth_center_cpt';

    /**
     * SingleYouthCenter constructor.
     */
    public function __construct() {
        add_action( 'init', [ $this, 'register_slider' ] );
        add_action( 'init', [ $this, 'register_hero' ] );
        add_action( 'init', [ $this, 'register_cpt_fields' ] );
        add_filter( 'acf/load_field/name=respa_spaces_selection', [ $this, 'load_respa_spaces_choices' ] );
    }

    /**
     * Register hero fields
     */
    public function register_slider() {
        $key = 'page_youth_center_slider';

        // Create a field group
        $field_group = new Group( 'Kuvakarusellin kentät' );
        $field_group->set_key( 'youth_center_slider' );

        // Define the rules for the group
        $rule_group = new RuleGroup();
        $rule_group->add_rule( 'post_type', '==', PostType\YouthCenter::SLUG );
        $field_group->add_rule_group( $rule_group );

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
                'editor',
            ]
        );

        $strings = [
            'slides' => [
                'label'        => 'Kuvakaruselli',
            ],
            'height' => [
                'label'        => 'Karusellin korkeus (px)',
                'instructions' => 'Määritä karusellille korkeus. Tyhjänä (oletus) minimikorkeus 590px.',
            ],
            'title' => [
                'label'        => 'Otsikko',
                'instructions' => 'Käytä maksimissaan 400 merkkiä.',
            ],
            'description' => [
                'label'        => 'Kuvausteksti',
                'instructions' => 'Käytä maksimissaan 400 merkkiä.',
            ],
            'image' => [
                'label' => 'Taustakuva',
            ],
            'image_copyright' => [
                'label' => 'Tekijänoikeudet',
            ],
            'link' => [
                'label' => 'Linkki',
            ],
        ];

        // Add slides repeater field
        $slides = new Field\Repeater( $strings['slides']['label'] );
        $slides->set_key( "{$key}_slides" );
        $slides->set_name( 'slides' );
        $slides->set_max( 5 );
        $slides->set_button_label( 'Lisää kuva' );
        $slides->hide_label();

        $height = new Field\Number( $strings['height']['label'] );
        $height->set_key( "{$key}_height" );
        $height->set_name( 'height' );
        $height->set_instructions( $strings['height']['instructions'] );

        // Add title field
        $title = new Field\Text( $strings['title']['label'] );
        $title->set_key( "{$key}_title" );
        $title->set_name( 'title' );
        $title->set_instructions( $strings['title']['instructions'] );
        $title->redipress_include_search();
        $slides->add_field( $title );

        // Add description field
        $description = new Field\Textarea( $strings['description']['label'] );
        $description->set_key( "{$key}_description" );
        $description->set_name( 'description' );
        $description->set_instructions( $strings['description']['instructions'] );
        $description->set_new_lines();
        $description->redipress_include_search();
        $slides->add_field( $description );

        // Add image field
        $image = new Field\Image( $strings['image']['label'] );
        $image->set_key( "{$key}_image" );
        $image->set_name( 'image' );
        $image->set_required();
        $slides->add_field( $image );

        $image_copyright = new Field\Text( $strings['image_copyright']['label'] );
        $image_copyright->set_key( "{$key}_image_copyright" );
        $image_copyright->set_name( 'image_copyright' );
        $image_copyright->redipress_include_search();
        $slides->add_field( $image_copyright );

        // Add link field
        $link = new Field\Link( $strings['link']['label'] );
        $link->set_key( "{$key}_link" );
        $link->set_name( 'link' );
        $slides->add_field( $link );

        // Add fields to the field grouo
        $field_group->add_fields( [
            $slides,
            $height,
        ] );

        // Register the field group
        $field_group->register();
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

        // Define the rules for the group
        $rule_group = new RuleGroup();
        $rule_group->add_rule( 'post_type', '==', PostType\YouthCenter::SLUG );
        $field_group->add_rule_group( $rule_group );

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
            'image' => [
                'label' => 'Kuva',
            ],
        ];

        // Add bg color selection field
        $bg_color = new Field\Select( $strings['bg_color']['label'] );
        $bg_color->set_key( "{$key}_bg_color" );
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
            'color-summer'             => 'Kesä',
            'color-young-voice'        => 'Nuorten Ääni',
        ] );

        // Add image field
        $image = new Field\Image( $strings['image']['label'] );
        $image->set_key( "{$key}_image" );
        $image->set_name( 'hero_image' );

        // Add fields to the field grouo
        $field_group->add_fields( [ $bg_color, $image ] );

        // Register the field group
        $field_group->register();
    }

    /**
     * Register fields
     */
    public function register_cpt_fields() {
        $field_group = new Group( 'Nuorisotalon tiedot' );

        $key = self::CPT_KEY;

        $field_group->set_key( $key );

        $rule_group = new RuleGroup();
        $rule_group->add_rule( 'post_type', '==', PostType\YouthCenter::SLUG );

        $field_group->add_rule_group( $rule_group );
        $field_group->set_position( 'normal' );
        $field_group->set_menu_order( 2 );

        $strings = [
            'unit'               => [
                'label' => 'Palvelukartta Yksikkö',
            ],
            'latitude'           => [
                'label' => 'Sijainti: leveysaste',
            ],
            'longitude'          => [
                'label' => 'Sijainti: pituusaste',
            ],
            'search_terms'       => [
                'label'        => 'Hakusanat',
                'instructions' => 'Kaupunginosa tai joku muu liittyvä asia',
                'button_label' => 'Lisää hakusana',
            ],
            'search_term'        => [
                'label' => 'Hakusana',
            ],
            'is_events_hidden'        => [
                'label' => 'Piilota tapahtumat -osio',
            ],
            'youth_center_group' => [
                'label' => 'Nuorisotalon sisältöosiot',
            ],
        ];

        $choices            = $this->get_unit_choices();
        $palvelukartta_unit = new Field\Select( $strings['unit']['label'] );
        $palvelukartta_unit->set_key( 'unit' );
        $palvelukartta_unit->set_name( 'unit' );
        $palvelukartta_unit->allow_null();
        $palvelukartta_unit->set_choices( $choices ?? false );

        $latitude = ( new Field\Text( $strings['latitude']['label'] ) )
            ->set_key( 'latitude' )
            ->set_name( 'latitude' )
            ->set_readonly()
            ->set_wrapper_width( 50 );

        $longitude = ( new Field\Text( $strings['longitude']['label'] ) )
            ->set_key( 'longitude' )
            ->set_name( 'longitude' )
            ->set_readonly()
            ->set_wrapper_width( 50 );

        $search_terms = ( new Field\Taxonomy( $strings['search_terms']['label'] ) )
            ->set_key( 'search_terms' )
            ->set_name( 'search_terms' )
            ->set_taxonomy( YouthCenterKeyword::SLUG )
            ->allow_load_terms()
            ->allow_save_terms()
            ->allow_add_term()
            ->set_instructions( $strings['search_terms']['instructions'] );

        // Events is hidden
        $is_events_hidden = ( new Field\TrueFalse( $strings['is_events_hidden']['label'] ) )
            ->set_key( "is_events_hidden" )
            ->set_name( 'is_events_hidden' )
            ->set_default_value( false )
            ->use_ui();

        $youth_center_group = new Field\Group( $strings['youth_center_group']['label'] );
        $youth_center_group->set_key( "{$key}_youth_center_group" );
        $youth_center_group->set_name( 'youth_center_group' );

        // Intro group
        $intro_group = $this->create_intro_group();

        // Instructors group
        $instructors_group = $this->create_instructors_group();

        // Opening hours group
        $opening_hours_group = $this->create_opening_hours_group();

        // Respa spaces
        $respa_spaces = $this->create_respa_spaces_group();

        $youth_center_group->add_fields( [
            $intro_group,
            new Fields\UnitLinkList( 'youth_center' ),
            $instructors_group,
            $opening_hours_group,
            $respa_spaces,
        ] );

        $field_group->add_fields( [
            $palvelukartta_unit,
            $latitude,
            $longitude,
            $search_terms,
            $is_events_hidden,
            $youth_center_group,
        ] );

        $field_group->register();
    }

    /**
     * Get unit choices
     *
     * @return array
     */
    private function get_unit_choices() {
        $words = Settings::get_setting( 'youth_center_keyword' );

        if ( empty( $words ) ) {
            return [];
        }

        $api   = new ApiClient();
        $units = $api->get_units_by_ontology_word_ids( $words );

        if ( empty( $units ) ) {
            return [];
        }

        foreach ( $units as $unit ) {
            $choices[ $unit->get_id() ] = $unit->get_name();
        }

        asort( $choices );

        return $choices;
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
            'intro_lead_text'  => [
                'label' => 'Esittelyn ingressi',
            ],
            'intro_text'       => [
                'label' => 'Esittelyn teksti',
            ],
            'anchor_link_text' => [
                'label' => 'Ankkurilinkin teksti',
            ],
        ];

        $intro_group = new Field\Group( $strings['intro_group']['label'] );
        $intro_group->set_key( "{$key}_intro_group" );
        $intro_group->set_name( 'intro_group' );

        // Intro title
        $intro_title = new Field\Text( $strings['intro_title']['label'] );
        $intro_title->set_key( "{$key}_intro_group_title" );
        $intro_title->set_name( 'title' );
        $intro_title->set_default_value( __( 'Youth center intro title', 'nuhe' ) );

        // Intro lead text
        $intro_lead_text = new Field\Textarea( $strings['intro_lead_text']['label'] );
        $intro_lead_text->set_key( "{$key}_intro_lead_text" );
        $intro_lead_text->set_name( 'intro_lead_text' );
        $intro_lead_text->set_new_lines();

        // Intro text
        $intro_text = new Field\Wysiwyg( $strings['intro_text']['label'] );
        $intro_text->set_key( "{$key}_intro_text" );
        $intro_text->set_name( 'intro_text' );

        $anchor_link_text = new Field\Text( $strings['anchor_link_text']['label'] );
        $anchor_link_text->set_key( "{$key}_intro_group_anchor_link_text" );
        $anchor_link_text->set_name( 'anchor_link_text' );

        $intro_group->add_fields( [
            $intro_title,
            $anchor_link_text,
            $intro_lead_text,
            $intro_text,
        ] );

        return $intro_group;
    }

    /**
     * Create instructors group
     *
     * @return Field acf-field.
     */
    private function create_instructors_group() : Field {
        $key = self::CPT_KEY;

        $strings = [
            'instructors_group' => [
                'label' => 'Ohjaajat',
            ],
            'instructors_title' => [
                'label' => 'Otsikko',
            ],
            'instructors_text'  => [
                'label' => 'Ohjaajien teksti',
            ],
            'anchor_link_text'  => [
                'label' => 'Ankkurilinkin teksti',
            ],
            'some_links'        => [
                'label' => 'Sosiaalisen median linkit',
            ],
            'some_link'         => [
                'label' => 'Linkki',
            ],
            'some_source'       => [
                'label' => 'Valitse palvelun ikoni',
            ],
            'phone_number'      => [
                'label' => 'Nuorisotalon puhelinnumero',
            ],
            'instructors'       => [
                'label' => 'Valitse nuorisotalon ohjaajat',
            ],
            'instructor'        => [
                'label' => 'Ohjaaja',
            ],
        ];

        $instructors_group = new Field\Group( $strings['instructors_group']['label'] );
        $instructors_group->set_key( "{$key}_instructors_group" );
        $instructors_group->set_name( 'instructors_group' );

        // instructors title
        $instructors_title = new Field\Text( $strings['instructors_title']['label'] );
        $instructors_title->set_key( "{$key}_instructors_group_title" );
        $instructors_title->set_name( 'title' );
        $instructors_title->set_default_value( __( 'Youth center instructors title', 'nuhe' ) );

        // instructors
        $instructors = new Field\Repeater( $strings['instructors']['label'] );
        $instructors->set_key( "{$key}_instructors" );
        $instructors->set_name( 'instructors' );

        // instructor
        $instructor = new Field\PostObject( $strings['instructor']['label'] );
        $instructor->set_key( "{$key}_instructor" );
        $instructor->set_name( 'instructor' );
        $instructor->set_post_types( [
            'instructor-cpt',
        ] );

        $instructors->add_field( $instructor );

        // instructors text
        $instructors_text = new Field\Wysiwyg( $strings['instructors_text']['label'] );
        $instructors_text->set_key( "{$key}_instructors_text" );
        $instructors_text->set_name( 'instructors_text' );

        // Some links
        $some_links = new Field\Repeater( $strings['some_links']['label'] );
        $some_links->set_key( "{$key}_some_links" );
        $some_links->set_name( 'some_links' );

        // Some link
        $some_link = new Field\URL( $strings['some_link']['label'] );
        $some_link->set_key( "{$key}_some_link" );
        $some_link->set_name( 'some_link' );

        // Some source
        $some_source = new Field\Select( $strings['some_source']['label'] );
        $some_source->set_key( "{$key}_some_source" );
        $some_source->set_name( 'some_source' );
        $some_source->set_choices( [
            'instagram' => 'Instagram',
            'snapchat'  => 'SnapChat',
            'tiktok'    => 'TikTok',
            'facebook'  => 'Facebook',
            'discord'   => 'Discord',
            'twitter'   => 'Twitter',
        ] );

        $some_links->add_fields( [
            $some_link,
            $some_source,
        ] );

        // Youth center phone number
        $phone_number = new Field\Text( $strings['phone_number']['label'] );
        $phone_number->set_key( "{$key}_phone_number" );
        $phone_number->set_name( 'phone_number' );

        $anchor_link_text = new Field\Text( $strings['anchor_link_text']['label'] );
        $anchor_link_text->set_key( "{$key}_instructors_group_anchor_link_text" );
        $anchor_link_text->set_name( 'anchor_link_text' );

        $instructors_group->add_fields( [
            $instructors_title,
            $anchor_link_text,
            $instructors,
            $phone_number,
            $instructors_text,
            $some_links,
        ] );

        return $instructors_group;
    }

    /**
     * Create opening hours group
     *
     * @return Field acf-field.
     */
    private function create_opening_hours_group() : Field {
        $key = self::CPT_KEY;

        $strings = [
            'opening_hours_group' => [
                'label' => 'Aukioloajat',
            ],
            'opening_hours_is_hidden' => [
                'label' => 'Piilota aukioloajat',
            ],
            'opening_hours_title' => [
                'label' => 'Aukioloajat otsikko',
            ],
            'opening_hours_text'  => [
                'label' => 'Aukioloajat tekstisisältö',
            ],
            'anchor_link_text'    => [
                'label' => 'Ankkurilinkin teksti',
            ],
        ];

        $opening_hours_group = new Field\Group( $strings['opening_hours_group']['label'] );
        $opening_hours_group->set_key( "{$key}_opening_hours_group" );
        $opening_hours_group->set_name( 'opening_hours_group' );

        // Opening hours is hidden
        $opening_hours_is_hidden = new Field\TrueFalse( $strings['opening_hours_is_hidden']['label'] );
        $opening_hours_is_hidden->set_key( "{$key}_opening_hours_group_is_hidden" );
        $opening_hours_is_hidden->set_name( 'is_hidden' );
        $opening_hours_is_hidden->set_default_value( false );
        $opening_hours_is_hidden->use_ui();

        // Opening hours title
        $opening_hours_title = new Field\Text( $strings['opening_hours_title']['label'] );
        $opening_hours_title->set_key( "{$key}_opening_hours_group_title" );
        $opening_hours_title->set_name( 'title' );
        $opening_hours_title->set_default_value( __( 'Youth center opening hours title', 'nuhe' ) );

        // Opening hours text content
        $opening_hours_text = new Field\Wysiwyg( $strings['opening_hours_text']['label'] );
        $opening_hours_text->set_key( "{$key}_opening_hours_text" );
        $opening_hours_text->set_name( 'opening_hours_text' );

        $anchor_link_text = new Field\Text( $strings['anchor_link_text']['label'] );
        $anchor_link_text->set_key( "{$key}_opening_hours_group_anchor_link_text" );
        $anchor_link_text->set_name( 'anchor_link_text' );

        $opening_hours_group->add_fields( [
            $opening_hours_is_hidden,
            $opening_hours_title,
            $anchor_link_text,
            $opening_hours_text,
        ] );

        return $opening_hours_group;
    }

    /**
     * Create respa spaces group
     *
     * @return Field acf-field.
     */
    private function create_respa_spaces_group() : Field {
        $key = self::CPT_KEY;

        $strings = [
            'respa_spaces_group' => [
                'label' => 'Varattavissa olevat tilat',
            ],
            'respa_spaces_title' => [
                'label' => 'Otsikko',
            ],
            'anchor_link_text' => [
                'label' => 'Ankkurilinkin teksti',
            ],
            'respa_spaces_selection' => [
                'label' => 'Valitse varattavissa olevat tilat, jotka haluat listata.',
            ],
        ];

        $respa_spaces_group = new Field\Group( $strings['respa_spaces_group']['label'] );
        $respa_spaces_group->set_key( "{$key}_respa_spaces_group" );
        $respa_spaces_group->set_name( 'respa_spaces_group' );

        // Respa spaces title
        $respa_spaces_title = new Field\Text( $strings['respa_spaces_title']['label'] );
        $respa_spaces_title->set_key( "{$key}_respa_spaces_group_title" );
        $respa_spaces_title->set_name( 'title' );

        $anchor_link_text = new Field\Text( $strings['anchor_link_text']['label'] );
        $anchor_link_text->set_key( "{$key}_respa_spaces_group_anchor_link_text" );
        $anchor_link_text->set_name( 'anchor_link_text' );

        // Respa spaces selection
        $respa_spaces_selection = new Field\Select( $strings['respa_spaces_selection']['label'] );
        $respa_spaces_selection->set_key( "{$key}_respa_spaces_group_selection" );
        $respa_spaces_selection->set_name( 'respa_spaces_selection' );
        $respa_spaces_selection->allow_multiple();
        $respa_spaces_selection->allow_null();

        $respa_spaces_group->add_fields( [
            $respa_spaces_title,
            $anchor_link_text,
            $respa_spaces_selection,
        ] );

        return $respa_spaces_group;
    }

    /**
     * Get respa spaces
     *
     * @param array $field Field array.
     * @return array
     */
    public function load_respa_spaces_choices( array $field ) : array {
        global $post;

        $field['choices'] = [];

        $api     = new RespaApiClient();
        $unit_id = \get_field( 'unit', $post->ID );

        $no_selectable_spaces_text = 'Palvelukartta Yksikkö puuttuu tai varattavia tiloja ei ole';

        if ( empty( $unit_id ) ) {
            $field['choices']['0'] = $no_selectable_spaces_text;
            return $field;
        }

        $choices = $api->get_spaces_by_unit_id( $unit_id );

        if ( empty( $choices ) ) {
            $field['choices']['0'] = $no_selectable_spaces_text;
            return $field;
        }

        foreach ( $choices as $choice ) {
            $field['choices'][ $choice->get_id() ] = $choice->get_name();
        }

        return $field;
    }
}

new SingleYouthCenter();
