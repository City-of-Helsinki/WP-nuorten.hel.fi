<?php
/**
 * SubPageList ACF-layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;
use \Geniem\Theme\PostType;
use \Geniem\ACF\ConditionalLogicGroup;
use Geniem\Theme\Model\PageVolunteering;

/**
 * Define the SubPageList class.
 */
class SubPageList extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_sub_page_list';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( $key ) {
        $label = 'Alasivulistaus';
        $key   = $key . self::KEY;
        $name  = 'sub_page_list';

        parent::__construct( $label, $key, $name );

        $this->add_layout_fields();
        $this->set_excluded_post_types( [
            PostType\YouthCenter::SLUG,
            PostType\Post::SLUG,
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
            'title' => [
                'label' => 'Otsikko',
            ],
            'list_type' => [
                'label' => 'Listaa alasivut:',
            ],
            'items' => [
                'label' => 'Manuaalinen listaus',
            ],
            'image' => [
                'label' => 'Kuva',
            ],
            'link' => [
                'label' => 'Linkki',
            ],
            'textarea' => [
                'label' => 'Teksti',
            ],
            'links' => [
                'label' => 'Linkkilista',
            ],
        ];

        $title_field = new Field\Text( $strings['title']['label'] );
        $title_field->set_key( "${key}_title" );
        $title_field->set_name( 'title' );

        $list_type = new Field\Radio( $strings['list_type']['label'] );
        $list_type->set_key( "${key}_list_type" );
        $list_type->set_name( 'list_type' );
        $list_type->set_choices( [
            'auto'   => 'Automaattisesti',
            'manual' => 'Manuaalisesti',
        ] );
        $list_type->set_default_value( 'auto' );

        $conditional_logic = new ConditionalLogicGroup();
        $conditional_logic->add_rule( $list_type, '==', 'manual' );

        $items = new Field\Repeater( $strings['items']['label'] );
        $items->set_key( "${key}_items" );
        $items->set_name( 'items' );
        $items->set_layout( 'block' );
        $items->add_conditional_logic( $conditional_logic );

        $image = new Field\Image( $strings['image']['label'] );
        $image->set_key( "${key}_image" );
        $image->set_name( 'image' );
        $image->set_wrapper_width( 50 );
        $image->set_preview_size();

        $link = new Field\Link( $strings['link']['label'] );
        $link->set_key( "${key}_link" );
        $link->set_name( 'link' );
        $link->set_wrapper_width( 50 );

        $textarea = new Field\Textarea( $strings['textarea']['label'] );
        $textarea->set_key( "${key}_textarea" );
        $textarea->set_name( 'textarea' );
        $textarea->set_new_lines();
        $textarea->set_wrapper_width( 50 );

        $links = new Field\Repeater( $strings['links']['label'] );
        $links->set_key( "${key}_links" );
        $links->set_name( 'links' );
        $links->add_field( $link );
        $links->set_wrapper_width( 50 );

        $items->add_fields( [
            $image,
            $link,
            $textarea,
            $links,
        ] );

        $this->add_fields( [
            $title_field,
            $list_type,
            $items,
        ] );
    }

}
