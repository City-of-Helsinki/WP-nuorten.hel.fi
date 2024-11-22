<?php
/**
 * TextBanner ACF-layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;
use Geniem\Theme\Model\PageVolunteering;

/**
 * Define the TextBanner class.
 */
class TextBanner extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_text_banner';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( $key ) {
        $label = 'Tekstibanneri';
        $key   = $key . self::KEY;
        $name  = 'text_banner';

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
            'citation' => [
                'label' => 'Sitaatti',
            ],
            'link' => [
                'label' => 'Linkki',
            ],
        ];

        $title = new Field\Text( $strings['title']['label'] );
        $title->set_key( "${key}_title" );
        $title->set_name( 'title' );

        $citation = new Field\Text( $strings['citation']['label'] );
        $citation->set_key( "${key}_citation" );
        $citation->set_name( 'citation' );

        $link = new Field\Link( $strings['link']['label'] );
        $link->set_key( "${key}_link" );
        $link->set_name( 'link' );

        $this->add_fields( [
            $title,
            $citation,
            $link,
        ] );
    }

}
