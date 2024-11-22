<?php
/**
 * InitiativeList ACF-layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;

/**
 * Define the InitiativeList class.
 */
class InitiativeList extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_initiative_list';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( $key ) {
        $label = 'Aloitenostot';
        $key   = $key . self::KEY;
        $name  = 'initiative_list';

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
            'description' => [
                'label' => 'Kuvausteksti',
            ],
            'link' => [
                'title' => 'Teksti linkkiin, joka vie kaikkien aloitteiden sivulle.',
            ],
            'anchor_link_text' => [
                'title'        => 'Ankkurilinkki',
                'instructions' => 'Jos haluat osioon johtavan linkin olevan muu kuin osion otsikko on, laita tähän haluamasi linkkiteksti.',
            ],
            'show_only_open' => [
                'title'        => 'Näytä vain käsittelyssä olevat aloitteet',
                'instructions' => '',
            ],
        ];

        $title_field = new Field\Text( $strings['title']['label'] );
        $title_field->set_key( "${key}_title" );
        $title_field->set_name( 'title' );
        $title_field->redipress_include_search();
        $this->add_field( $title_field );

        // Anchor link
        $anchor_link_text = new Field\Text( $strings['anchor_link_text']['title'] );
        $anchor_link_text->set_key( "${key}_anchor_link_text" );
        $anchor_link_text->set_name( 'anchor_link_text' );
        $anchor_link_text->set_instructions( $strings['anchor_link_text']['instructions'] );
        $this->add_field( $anchor_link_text );

        $description_field = new Field\TextArea( $strings['description']['label'] );
        $description_field->set_key( "${key}_description" );
        $description_field->set_name( 'description' );
        $description_field->set_new_lines();
        $description_field->redipress_include_search();
        $this->add_field( $description_field );

        $link_label = $strings['link']['title'];
        $link_field = new Field\Text( $link_label );
        $link_field->set_key( "${key}_link_title" );
        $link_field->set_name( 'link_title' );
        $this->add_field( $link_field );

        $show_only_open_label = $strings['show_only_open']['title'];
        $show_only_open_field = new Field\TrueFalse( $show_only_open_label );
        $show_only_open_field->set_key( "${key}_show_only_open" );
        $show_only_open_field->set_name( 'show_only_open' );
        $show_only_open_field->use_ui();
        $show_only_open_field->set_default_value( false );
        $this->add_field( $show_only_open_field );
    }

}
