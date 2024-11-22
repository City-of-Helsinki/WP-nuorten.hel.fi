<?php
/**
 * Create and register the Accordion layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;
use Geniem\Theme\Model\SingleYouthElections;
use Geniem\Theme\PostType\Initiative;
use Geniem\Theme\PostType\Instructor;
use Geniem\Theme\PostType\YouthCouncilElections;

/**
 * Accordion class.
 */
class Accordion extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_accordion';

    /**
     * Create the block and register it.
     */
    public function __construct( $key ) {
        $label = 'Haitari';
        $key   = $key . self::KEY;
        $name  = 'accordion';

        parent::__construct( $label, $key, $name );

        $this->add_layout_fields();
        $this->set_excluded_post_types( [
            YouthCouncilElections::SLUG,
            Instructor::SLUG,
            Initiative::SLUG,
        ] );
    }

    /**
     * Create block fields.
     *
     * @return array
     */
    public function add_layout_fields() : void {
        $key = $this->key;

        $strings = [
            'title' => [
                'label' => 'Otsikko',
            ],
            'description' => [
                'label' => 'Kuvausteksti',
            ],
            'rows' => [
                'label' => 'Haitarin otsikko-sisältö -rivit',
            ],
            'anchor_link_text' => [
                'title'        => 'Ankkurilinkki',
                'instructions' => 'Jos haluat osioon johtavan linkin olevan muu kuin osion otsikko on, laita tähän haluamasi linkkiteksti.',
            ],
            'row_title' => [
                'label' => 'Otsikko',
            ],
            'row_content' => [
                'label' => 'Sisältö',
            ],
        ];

        $title_field = new Field\Text( $strings['title']['label'] );
        $title_field->set_key( "{$key}_title" );
        $title_field->set_name( 'title' );
        $title_field->redipress_include_search();
        $this->add_field( $title_field );

        // Anchor link
        $anchor_link_text = new Field\Text( $strings['anchor_link_text']['title'] );
        $anchor_link_text->set_key( "{$key}_anchor_link_text" );
        $anchor_link_text->set_name( 'anchor_link_text' );
        $anchor_link_text->set_instructions( $strings['anchor_link_text']['instructions'] );
        $this->add_field( $anchor_link_text );

        $description_field = new Field\TextArea( $strings['description']['label'] );
        $description_field->set_key( "{$key}_description" );
        $description_field->set_name( 'description' );
        $description_field->set_new_lines();
        $description_field->redipress_include_search();
        $this->add_field( $description_field );

        // rows
        $rows = new Field\Repeater( $strings['rows']['label'] );
        $rows->set_key( "{$key}_rows" );
        $rows->set_name( 'rows' );
        $rows->set_layout( 'block' );

        $row_title = new Field\Text( $strings['row_title']['label'] );
        $row_title->set_key( "{$key}_row_title" );
        $row_title->set_name( 'row_title' );

        $rows->add_field( $row_title );

        $row_content = new Field\Wysiwyg( $strings['row_content']['label'] );
        $row_content->set_key( "{$key}_row_content" );
        $row_content->set_name( 'row_content' );

        $rows->add_field( $row_content );

        $this->add_fields( [
            $title_field,
            $description_field,
            $rows,
            $anchor_link_text,
        ] );
    }

    /**
     * This filters the block ACF data.
     *
     * @param array $data Block's ACF data.
     *
     * @return array The block data.
     */
    public function filter_data( $data ) : array {
        if ( empty( $data['rows'] ) ) {
            return [];
        }

        return array_filter(
            $data['rows'],
            fn( $row ) => ! empty( $row['row_title'] ) && ! empty( $row['row_content'] )
        );
    }
}
