<?php
/**
 * ImageText ACF-layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;
use Geniem\Theme\Model\PageVolunteering;

/**
 * Define the ImageText class.
 */
class ImageText extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_image_text';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( $key ) {
        $label = 'Kuva-teksti';
        $key   = $key . self::KEY;
        $name  = 'image_text';

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
            'ctas' => [
                'label' => 'CTA-painikkeet',
            ],
            'cta' => [
                'label' => 'CTA-painike',
            ],
            'image' => [
                'label' => 'Kuva',
            ],
            'image_position' => [
                'label' => 'Asemoi kuva tekstin vasemmalle puolelle',
            ],
        ];

        $title = new Field\Text( $strings['title']['label'] );
        $title->set_key( "${key}_title" );
        $title->set_name( 'title' );

        $description = new Field\Textarea( $strings['description']['label'] );
        $description->set_key( "${key}_description" );
        $description->set_name( 'description' );
        $description->set_new_lines();

        $ctas = new Field\Repeater( $strings['ctas']['label'] );
        $ctas->set_key( "${key}_ctas" );
        $ctas->set_name( 'ctas' );

        $cta = new Field\Link( $strings['cta']['label'] );
        $cta->set_key( "${key}_cta" );
        $cta->set_name( 'cta' );
        $ctas->add_field( $cta );

        $image = new Field\Image( $strings['image']['label'] );
        $image->set_key( "${key}_image" );
        $image->set_name( 'image' );

        $image_position = new Field\TrueFalse( $strings['image_position']['label'] );
        $image_position->set_key( "${key}_image_position" );
        $image_position->set_name( 'image_position' );
        $image_position->use_ui();

        $this->add_fields( [
            $title,
            $description,
            $ctas,
            $image,
            $image_position,
        ] );
    }

}
