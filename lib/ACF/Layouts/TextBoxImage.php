<?php
/**
 * TextBoxImage ACF-layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;

/**
 * Define the TextBoxImage class.
 */
class TextBoxImage extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_text_box_image';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( $key ) {
        $label = 'Tekstilaatikko-kuva';
        $key   = $key . self::KEY;
        $name  = 'text_box_image';

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
            'box_bg_color' => [
                'label' => 'Laatikon taustaväri',
            ],
            'title' => [
                'label' => 'Otsikko',
            ],
            'description' => [
                'label' => 'Kuvausteksti',
            ],
            'link' => [
                'label' => 'Linkki',
            ],
            'image' => [
                'label' => 'Kuva',
            ],
        ];

        $box_bg_color = new Field\Select( $strings['box_bg_color']['label'] );
        $box_bg_color->set_key( "${key}_box_bg_color" );
        $box_bg_color->set_name( 'box_bg_color' );
        $colors = [
            'white' => 'Oletus (valkoinen)',
            'pink'  => 'Pinkki',
            'blue'  => 'Sininen',
            'beige' => 'Beige',
        ];
        $colors = apply_filters( 'nuhe_acf_color_choices', $colors );
        $box_bg_color->set_choices( $colors );

        $title = new Field\Text( $strings['title']['label'] );
        $title->set_key( "${key}_title" );
        $title->set_name( 'title' );

        $description = new Field\Textarea( $strings['description']['label'] );
        $description->set_key( "${key}_description" );
        $description->set_name( 'description' );
        $description->set_new_lines();

        $link = new Field\Link( $strings['link']['label'] );
        $link->set_key( "${key}_link" );
        $link->set_name( 'link' );

        $image = new Field\Image( $strings['image']['label'] );
        $image->set_key( "${key}_image" );
        $image->set_name( 'image' );
        $image->set_required();

        $this->add_fields( [
            $box_bg_color,
            $title,
            $description,
            $link,
            $image,
        ] );
    }

}
