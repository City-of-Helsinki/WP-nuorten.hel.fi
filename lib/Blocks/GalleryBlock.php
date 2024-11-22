<?php
/**
 * Create and register the GalleryBlock
 */

namespace Geniem\Theme\Blocks;

use \Geniem\ACF\Field;

/**
 * GalleryBlock class.
 */
class GalleryBlock extends BaseBlock {
    /**
     * The block name (slug, not shown in admin).
     *
     * @var string
     */
    const NAME = 'gallery';

    /**
     * Create the block and register it.
     */
    public function __construct() {
        // Define block title.
        $this->title             = 'Kuvagalleria';
        $this->prefix            = static::NAME . '_';
        $this->icon              = 'format-image';
        $this->description       = 'Kuvagalleria';
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
            'images'  => [
                'label' => 'Kuvat',
            ],
            'per_row' => [
                'label' => 'Kuvia per rivi',
            ],
        ];

        // images
        $title = new Field\Gallery( $strings['images']['label'] );
        $title->set_key( $this->prefix . 'images' );
        $title->set_name( 'images' );
        $this->fields[] = $title;

        $per_row = new Field\Select( $strings['per_row']['label'] );
        $per_row->set_key( $this->prefix . 'per_row' );
        $per_row->set_name( 'per_row' );
        $per_row->set_choices( [
            2 => 2,
            3 => 3,
        ] );
        $this->fields[] = $per_row;

        return $this->fields;
    }

    /**
     * This filters the block ACF data.
     *
     * @param array             $data       Block's ACF data.
     * @param \Geniem\ACF\Block $instance   The block instance.
     * @param array             $block      The original ACF block array.
     * @param string            $content    The HTML content.
     * @param bool              $is_preview A flag that shows if we're in preview.
     * @param int               $post_id    The parent post's ID.
     *
     * @return array The block data.
     */
    public function filter_data( $data, $instance, $block, $content, $is_preview, $post_id ) : array {
        if ( empty( $data ) || empty( $data['images'] ) ) {
            return [];
        }

        $group_id = 'group-' . md5( uniqid( '', true ) );
        $per_row  = intval( $data['per_row'] );

        $data['images'] = array_filter( $data['images'], function ( $item ) {
            return ! empty( $item );
        } );

        $chunks = array_chunk( $data['images'], $per_row );
        $images = [];

        foreach ( $chunks as $group ) {
            $slides_in_group = count( $group );

            foreach ( $group as $slide ) {
                $images[] = [
                    'image'        => $slide,
                    'column_class' => $this->get_column_class( $per_row, $slides_in_group ),
                    'group_id'     => $group_id,
                ];
            }
        }

        $data['images'] = $images;

        return $data;
    }

    /**
     * Get column class
     *
     * @param int $per_row     Max amount per group.
     * @param int $slide_count Slides per group.
     *
     * @return string
     */
    protected function get_column_class( $per_row, $slide_count ) {
        $max_columns = 12;
        $classes     = [ 'is-6-mobile is-6-tablet' ];

        $column_map = [
            2 => 'is-6-desktop',
            3 => 'is-4-desktop',
        ];

        if ( $slide_count === $per_row ) {
            $classes[] = $column_map[ $per_row ];
        }

        // Fill rows
        if ( $slide_count < $per_row ) {
            if ( $slide_count === 1 ) {
                $classes = [ 'is-12-mobile is-12-tablet' ];
            }

            $column_count = ceil( $max_columns / $slide_count );
            $classes[]    = "is-{$column_count}-desktop";
        }

        return implode( ' ', $classes );
    }
}
