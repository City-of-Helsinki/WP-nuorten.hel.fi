<?php
/**
 * PageList ACF-layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;
use Geniem\Theme\Model\PageVolunteering;

/**
 * Define the PageList class.
 */
class PageList extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_page_list';

    /**
     * Create the layout
     *
     * @param string $key            Key from the flexible content.
     * @param array  $include_fields Array of fields to be included.
     */
    public function __construct( $key, $include_fields ) {
        $label = 'Sisältösivunostot';
        $key   = $key . self::KEY;
        $name  = 'page_list';

        parent::__construct( $label, $key, $name );

        $this->add_layout_fields( $include_fields );
        $this->set_excluded_templates( [
            PageVolunteering::TEMPLATE,
        ] );
    }

    /**
     * Add layout fields
     *
     * @param array $include_fields Array of fields to be included.
     * @return void
     */
    private function add_layout_fields( $include_fields ) : void {
        $key = $this->key;

        $strings = [
            'bg_color' => [
                'label' => 'Taustaväri',
            ],
            'title' => [
                'label' => 'Otsikko',
            ],
            'description' => [
                'label' => 'Kuvausteksti',
            ],
            'pages' => [
                'label' => 'Lisää / poista sivunostoja',
            ],
            'page' => [
                'label' => 'Sivu',
            ],
            'short_desc' => [
                'label' => 'Ingressi',
            ],
            'link' => [
                'title'        => 'Linkki',
                'instructions' => 'Vapaavalintainen linkki nostojen jälkeen',
            ],
            'anchor_link_text' => [
                'title'        => 'Ankkurilinkki',
                'instructions' => 'Jos haluat osioon johtavan linkin olevan muu kuin osion otsikko on, laita tähän haluamasi linkkiteksti.',
            ],
        ];

        $short_desc = false;

        if ( in_array( 'bg_color', $include_fields, true ) ) {
            $bg_color_field = new Field\Select( $strings['bg_color']['label'] );
            $bg_color_field->set_key( "${key}_bg_color" );
            $bg_color_field->set_name( 'bg_color' );
            $colors = [
                'white' => 'Valkoinen',
                'grey'  => 'Harmaa',
            ];
            $colors = apply_filters( 'nuhe_acf_color_choices', $colors );
            $bg_color_field->set_choices( $colors );
            $bg_color_field->set_default_value( 'white' );
            $this->add_field( $bg_color_field );
        }

        if ( in_array( 'title', $include_fields, true ) ) {
            $title_field = new Field\Text( $strings['title']['label'] );
            $title_field->set_key( "${key}_title" );
            $title_field->set_name( 'title' );
            $title_field->redipress_include_search();
            $this->add_field( $title_field );
        }

        if ( in_array( 'anchor_link_text', $include_fields, true ) ) {
            // Anchor link
            $anchor_link_text = new Field\Text( $strings['anchor_link_text']['title'] );
            $anchor_link_text->set_key( "${key}_anchor_link_text" );
            $anchor_link_text->set_name( 'anchor_link_text' );
            $anchor_link_text->set_instructions( $strings['anchor_link_text']['instructions'] );
            $this->add_field( $anchor_link_text );
        }

        if ( in_array( 'description', $include_fields, true ) ) {
            $description_field = new Field\TextArea( $strings['description']['label'] );
            $description_field->set_key( "${key}_description" );
            $description_field->set_name( 'description' );
            $description_field->set_new_lines();
            $description_field->redipress_include_search();
            $this->add_field( $description_field );
        }

        if ( in_array( 'short_desc', $include_fields, true ) ) {
            $short_desc_field = new Field\Textarea( $strings['short_desc']['label'] );
            $short_desc_field->set_key( "${key}_short_desc" );
            $short_desc_field->set_name( 'short_desc' );
            $short_desc_field->redipress_include_search();
            $short_desc = true;
        }

        if ( in_array( 'pages', $include_fields, true ) ) {
            $pages_field = new Field\Repeater( $strings['pages']['label'] );
            $pages_field->set_key( "${key}_pages" );
            $pages_field->set_name( 'pages' );
            $pages_field->set_min( 1 );
            $pages_field->set_max( 16 );
            $pages_field->set_button_label( 'Lisää sivunosto' );

            $page_field = new Field\PostObject( $strings['page']['label'] );
            $page_field->set_key( "${key}_page" );
            $page_field->set_name( 'page' );
            $page_field->set_post_types( [ 'page' ] );
            $page_field->disallow_null();
            $pages_field->add_field( $page_field );

            if ( $short_desc === true ) {
                $pages_field->add_field( $short_desc_field );
            }

            $this->add_field( $pages_field );
        }

        if ( in_array( 'link', $include_fields, true ) ) {
            $link_label = $strings['link']['title'];
            $link_field = new Field\Link( $link_label );
            $link_field->set_key( "${key}_link" );
            $link_field->set_name( 'link' );
            $link_field->set_instructions( $strings['link']['instructions'] );
            $this->add_field( $link_field );
        }
    }

}
