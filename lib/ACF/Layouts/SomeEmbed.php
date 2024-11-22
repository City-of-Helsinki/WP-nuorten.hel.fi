<?php
/**
 * SomeEmbed ACF-layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;
use \Geniem\Theme\PostType;
use Geniem\Theme\Model\PageVolunteering;

/**
 * Define the SomeEmbed class.
 */
class SomeEmbed extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_some-embed';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( $key ) {
        $label = 'Someseinä';
        $key   = $key . self::KEY;
        $name  = 'some_embed';

        parent::__construct( $label, $key, $name );

        $this->add_layout_fields();

        $this->set_excluded_post_types( [
            PostType\Post::SLUG,
            PostType\Attachment::SLUG,
            PostType\Instructor::SLUG,
            PostType\Settings::SLUG,
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
            'anchor_link_text' => [
                'label' => 'Ankkurilinkin teksti',
            ],
            'some_embed' => [
                'label' => 'Someseinän upotuskoodi',
            ],
        ];

        $title_field = new Field\Text( $strings['title']['label'] );
        $title_field->set_key( "${key}_title" );
        $title_field->set_name( 'title' );
        $title_field->redipress_include_search();
        $this->add_field( $title_field );

        $anchor_link_text = new Field\Text( $strings['anchor_link_text']['label'] );
        $anchor_link_text->set_key( "${key}_anchor_link_text" );
        $anchor_link_text->set_name( 'anchor_link_text' );
        $this->add_field( $anchor_link_text );

        $some_embed_field = new Field\TextArea( $strings['some_embed']['label'] );
        $some_embed_field->set_key( "${key}_some_embed" );
        $some_embed_field->set_name( 'some_embed' );
        $some_embed_field->set_new_lines( '' );
        $some_embed_field->set_required();
        $this->add_field( $some_embed_field );
    }

}
