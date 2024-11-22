<?php
/**
 * Create and register the AccordionBlock
 */

namespace Geniem\Theme\Blocks;

use \Geniem\ACF\Field;

/**
 * AccordionBlock class.
 */
class AccordionBlock extends BaseBlock {
    /**
     * The block name (slug, not shown in admin).
     *
     * @var string
     */
    const NAME = 'accordion';

    /**
     * Create the block and register it.
     */
    public function __construct() {
        // Define block title.
        $this->title             = 'Haitari';
        $this->prefix            = static::NAME . '_';
        $this->icon              = 'editor-justify';
        $this->description       = 'Haitari';
        $this->supports['align'] = false;
        $this->mode              = 'edit';

        parent::__construct();
    }

    /**
     * Create block fields.
     *
     * @return array
     */
    public function fields() : array {
        $strings = [
            'rows' => [
                'label' => 'Haitarin otsikko-sisältö -rivit',
            ],
            'row_title' => [
                'label' => 'Otsikko',
            ],
            'row_content' => [
                'label' => 'Sisältö',
            ],
        ];

        // rows
        $rows = new Field\Repeater( $strings['rows']['label'] );
        $rows->set_key( $this->prefix . 'rows' );
        $rows->set_name( 'rows' );
        $rows->set_layout( 'block' );
        $this->fields[] = $rows;

        $row_title = new Field\Text( $strings['row_title']['label'] );
        $row_title->set_key( $this->prefix . 'row_title' );
        $row_title->set_name( 'row_title' );

        $rows->add_field( $row_title );

        $row_content = new Field\Wysiwyg( $strings['row_content']['label'] );
        $row_content->set_key( $this->prefix . 'row_content' );
        $row_content->set_name( 'row_content' );

        $rows->add_field( $row_content );

        return $this->fields;
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
