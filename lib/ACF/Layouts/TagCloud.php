<?php
/**
 * TagCloud ACF-layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;
use Geniem\Theme\Model\PageVolunteering;

/**
 * Define the TagCloud class.
 */
class TagCloud extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_tag_cloud';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( $key ) {
        $label = 'Avainsanapilvi';
        $key   = $key . self::KEY;
        $name  = 'tag_cloud';

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
            'tags' => [
                'label' => 'Avainsanat',
            ],
            'tag_link' => [
                'label' => 'Avainsanalinkki',
            ],
        ];

        $title_field = new Field\Text( $strings['title']['label'] );
        $title_field->set_key( "${key}_title" );
        $title_field->set_name( 'title' );
        $this->add_field( $title_field );

        $description_field = new Field\TextArea( $strings['description']['label'] );
        $description_field->set_key( "${key}_description" );
        $description_field->set_name( 'description' );
        $description_field->set_new_lines();
        $this->add_field( $description_field );

        $tags_field = new Field\Repeater( $strings['tags']['label'] );
        $tags_field->set_key( "${key}_tags" );
        $tags_field->set_name( 'tags' );
        $tags_field->set_min( 1 );
        $tags_field->set_button_label( 'Lisää avainsana' );
        $tags_field->set_layout( 'block' );

        $tag_link_field = new Field\Link( $strings['tag_link']['label'] );
        $tag_link_field->set_key( "${key}_tag_link" );
        $tag_link_field->set_name( 'tag_link' );

        $tags_field->add_field( $tag_link_field );

        $this->add_field( $tags_field );

    }

}
