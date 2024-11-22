<?php
/**
 * NewsList ACF-layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;

/**
 * Define the NewsList class.
 */
class NewsList extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_news_list';

    /**
     * Create the layout
     *
     * @param string $key            Key from the flexible content.
     */
    public function __construct( $key ) {
        $label = 'Uutisnostot';
        $key   = $key . self::KEY;
        $name  = 'news_list';

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
            'anchor_link_text' => [
                'title'        => 'Ankkurilinkki',
                'instructions' => 'Jos haluat osioon johtavan linkin olevan muu kuin osion otsikko on, laita tähän haluamasi linkkiteksti.',
            ],
            'links' => [
                'label' => 'Lisää uutislinkkejä',
            ],
            'link' => [
                'label' => 'Linkki',
            ],
            'article_title' => [
                'label'        => 'Artikkelin otsikko',
                'instructions' => '',
            ],
            'article_date' => [
                'label'        => 'Artikkelin päivämäärä',
                'instructions' => 'Voit lisätä artikkelille päivämäärän, muussa tapauksessa se haetaan artikkelin tiedoista.',
            ],
            'article_description' => [
                'label'        => 'Artikkelin kuvausteksti',
                'instructions' => 'Voit lisätä artikkelille kuvaustekstin, muussa tapauksessa se haetaan artikkelin tiedoista.',
            ],
        ];

        $title_field = ( new Field\Text( $strings['title']['label'] ) )
            ->set_key( "{$key}_title" )
            ->set_name( 'title' )
            ->redipress_include_search();

        $description_field = ( new Field\TextArea( $strings['description']['label'] ) )
            ->set_key( "{$key}_description" )
            ->set_name( 'description' )
            ->set_new_lines()
            ->redipress_include_search();

        // Anchor link
        $anchor_link_text = ( new Field\Text( $strings['anchor_link_text']['title'] ) )
            ->set_key( "{$key}_anchor_link_text" )
            ->set_name( 'anchor_link_text' )
            ->set_instructions( $strings['anchor_link_text']['instructions'] );

        $links_field = ( new Field\Repeater( $strings['links']['label'] ) )
            ->set_key( "{$key}_links" )
            ->set_name( 'links' )
            ->set_min( 0 )
            ->set_max( 16 )
            ->set_button_label( 'Lisää uutisnosto' );

        $link_field = ( new Field\Link( $strings['link']['label'] ) )
            ->set_key( "{$key}_link" )
            ->set_name( 'link' );

        $article_title_field = ( new Field\Text( $strings['article_title']['label'] ) )
            ->set_key( "{$key}_article_title" )
            ->set_name( 'article_title' );

        $article_date_field = ( new Field\DatePicker( $strings['article_date']['label'] ) )
            ->set_key( "{$key}_article_date" )
            ->set_return_format( 'j.n.Y' )
            ->set_display_format( 'j.n.Y' )
            ->set_name( 'article_date' );

        $article_description_field = ( new Field\Textarea( $strings['article_description']['label'] ) )
            ->set_key( "{$key}_article_description" )
            ->set_name( 'article_description' );

        $links_field->add_fields( [
            $link_field,
            $article_title_field,
            $article_date_field,
            $article_description_field,
        ] );

        $this->add_fields( [
            $title_field,
            $description_field,
            $anchor_link_text,
            $links_field,
        ] );
    }

}
