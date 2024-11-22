<?php
/**
 * PeopleList ACF-layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;
use Geniem\Theme\Model\PageVolunteering;

/**
 * Define the PeopleList class.
 */
class PeopleList extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_people_list';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( $key ) {
        $label = 'Henkilöesittely';
        $key   = $key . self::KEY;
        $name  = 'people_list';

        parent::__construct( $label, $key, $name );

        $this->add_layout_fields();
        $this->set_excluded_templates( [
            PageVolunteering::TEMPLATE,
        ] );
    }

    /**
     * Add layout fields
     *
     * @return void
     */
    private function add_layout_fields() : void {
        $key = $this->key;

        $strings = [
            'title' => [
                'label' => 'Otsikko',
            ],
            'description' => [
                'label' => 'Kuvausteksti',
            ],
            'people' => [
                'label' => 'Henkilöt',
            ],
            'person_name' => [
                'label' => 'Nimi',
            ],
            'person_title' => [
                'label' => 'Titteli',
            ],
            'person_description' => [
                'label' => 'Kuvausteksti',
            ],
            'person_image' => [
                'label' => 'Kuva',
            ],
            'video_link' => [
                'label' => 'Linkki videoon',
            ],
        ];

        $title_field = new Field\Text( $strings['title']['label'] );
        $title_field->set_key( "${key}_title" );
        $title_field->set_name( 'title' );
        $title_field->redipress_include_search();
        $this->add_field( $title_field );

        $description_field = new Field\Text( $strings['description']['label'] );
        $description_field->set_key( "${key}_description" );
        $description_field->set_name( 'description' );
        $description_field->redipress_include_search();
        $this->add_field( $description_field );

        $people_field = new Field\Repeater( $strings['people']['label'] );
        $people_field->set_key( "${key}_people" );
        $people_field->set_name( 'people' );
        $people_field->set_min( 1 );
        $people_field->set_max( 8 );
        $people_field->set_button_label( 'Lisää henkilö' );
        $people_field->set_layout( 'block' );

        $person_image_field = new Field\Image( $strings['person_image']['label'] );
        $person_image_field->set_key( "${key}_person_image" );
        $person_image_field->set_name( 'person_image' );
        $person_image_field->set_preview_size();

        $person_name_field = new Field\Text( $strings['person_name']['label'] );
        $person_name_field->set_key( "${key}_person_name" );
        $person_name_field->set_name( 'person_name' );
        $person_name_field->redipress_include_search();

        $person_title_field = new Field\Text( $strings['person_title']['label'] );
        $person_title_field->set_key( "${key}_person_title" );
        $person_title_field->set_name( 'person_title' );
        $person_title_field->redipress_include_search();

        $person_description_field = new Field\Text( $strings['person_description']['label'] );
        $person_description_field->set_key( "${key}_person_description" );
        $person_description_field->set_name( 'person_description' );
        $person_description_field->redipress_include_search();

        $video_link_field = new Field\Link( $strings['video_link']['label'] );
        $video_link_field->set_key( "${key}_video_link" );
        $video_link_field->set_name( 'video_link' );

        $people_field->add_fields( [
            $person_image_field,
            $person_name_field,
            $person_title_field,
            $person_description_field,
            $video_link_field,
        ] );

        $this->add_field( $people_field );

    }

}
