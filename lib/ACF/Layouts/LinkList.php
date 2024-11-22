<?php
/**
 * LinkList ACF-layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;
use \Geniem\Theme\PostType\YouthCenter;
use Geniem\Theme\Model\PageVolunteering;

/**
 * Define the LinkList class.
 */
class LinkList extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_link_list';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( $key ) {
        $label = 'Linkkilista';
        $key   = $key . self::KEY;
        $name  = 'link_list';

        parent::__construct( $label, $key, $name );

        $this->add_layout_fields();
        $this->set_excluded_post_types( [
            YouthCenter::SLUG,
        ] );
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
            'layout'      => 'Linkkilista',
            'title'       => [
                'label' => 'Otsikko',
            ],
            'description' => [
                'label' => 'Kuvausteksti',
            ],
            'repeater'    => [
                'label' => 'Linkit',
            ],
            'link'        => [
                'label'        => 'Linkki',
                'instructions' => '',
            ],
        ];

        $title = new Field\Text( $strings['title']['label'] );
        $title->set_key( "${key}_title" );
        $title->set_name( 'title' );
        $title->redipress_include_search();

        $description = new Field\Textarea( $strings['description']['label'] );
        $description->set_key( "${key}_description" );
        $description->set_name( 'description' );
        $description->set_new_lines();
        $description->redipress_include_search();

        $links = new Field\Repeater( $strings['repeater']['label'] );
        $links->set_key( "${key}_links" );
        $links->set_name( 'links' );

        $link = new Field\Link( $strings['link']['label'] );
        $link->set_key( "${key}_link" );
        $link->set_name( 'link' );
        $link->redipress_include_search();
        $links->add_field( $link );

        $this->add_fields( [
            $title,
            $description,
            $links,
        ] );
    }

}
