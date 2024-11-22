<?php
/**
 * MultiColumns ACF-layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;

/**
 * Define the MultiColumns class.
 */
class MultiColumns extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_multi_columns';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( $key ) {
        $label = 'Monipalstainen';
        $key   = $key . self::KEY;
        $name  = 'multi_columns';

        parent::__construct( $label, $key, $name );

        $this->add_layout_fields();
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
            'bg_color' => [
                'label' => 'Taustaväri',
            ],
            'columns' => [
                'label' => 'Palstat',
            ],
            'column_title' => [
                'label' => 'Palstan otsikko',
            ],
            'column_content' => [
                'label' => 'Palstan sisältö',
            ],
        ];

        $title_field = new Field\Text( $strings['title']['label'] );
        $title_field->set_key( "${key}_title" );
        $title_field->set_name( 'title' );

        $bg_color = new Field\Select( $strings['bg_color']['label'] );
        $bg_color->set_key( "${key}_bg_color" );
        $bg_color->set_name( 'bg_color' );
        $colors = [
            'default'     => 'Ei taustaväriä',
            'blue'        => 'Sininen',
            'beige-light' => 'Beige',
        ];
        $colors = apply_filters( 'nuhe_acf_color_choices', $colors );
        $bg_color->set_choices( $colors );

        $columns = new Field\Repeater( $strings['columns']['label'] );
        $columns->set_key( "${key}_columns" );
        $columns->set_name( 'columns' );
        $columns->set_layout( 'block' );
        $columns->set_min( 2 );
        $columns->set_max( 3 );
        $columns->set_button_label( 'Lisää palsta' );
        $columns->set_layout( 'block' );
        $columns->set_wrapper_id( 'multi-column-wrapper' );

        $column_title = new Field\Text( $strings['column_title']['label'] );
        $column_title->set_key( "${key}_column_title" );
        $column_title->set_name( 'column_title' );

        $column_content = new Field\Wysiwyg( $strings['column_content']['label'] );
        $column_content->set_key( "${key}_column_content" );
        $column_content->set_name( 'column_content' );
        $column_content->set_tabs( 'visual' );

        $columns->add_fields( [
            $column_title,
            $column_content,
        ] );

        $this->add_fields( [
            $title_field,
            $bg_color,
            $columns,
        ] );
    }
}
