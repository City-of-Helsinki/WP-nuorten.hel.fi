<?php
/**
 * ArticleList ACF-layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;
use Geniem\Theme\Taxonomy;
use Geniem\Theme\Model\PageVolunteering;

/**
 * Define the ArticleList class.
 */
class ArticleList extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_article_list';

    /**
     * Create the layout
     *
     * @param string $key            Key from the flexible content.
     * @param array  $include_fields Array of fields to be included.
     */
    public function __construct( $key, $include_fields ) {
        $label = 'Artikkelinostot';
        $key   = $key . self::KEY;
        $name  = 'article_list';

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
            'category' => [
                'title' => 'Kategoria',
            ],
            'tag' => [
                'title'        => 'Avainsana',
                'instructions' => 'Listaukseen tulostetaan artikkelit,
                joilla on vähintään yksi valituista avainsanoista.',
            ],
            'community_center' => [
                'title'        => 'Nuorisotalo',
                'instructions' => 'Listaukseen tulostetaan artikkelit,
                joilla on vähintään yksi valituista nuorisotaloista.',
            ],
            'amount' => [
                'title'        => 'Lukumäärä',
                'instructions' => 'Valitse, kuinka monta artikkelia tahdot näkyvän listassa.',
            ],
            'articles' => [
                'title'        => 'Valitse artikkelit',
                'instructions' => 'Edelliset valinnat jätetään huomiotta jos lisäät tähän vähintään yhden artikkelin.',
            ],
            'article' => [
                'title' => 'Artikkeli',
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

        if ( in_array( 'bg_color', $include_fields, true ) ) {
            $bg_label       = $strings['bg_color']['label'];
            $bg_color_field = new Field\Select( $bg_label );
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
            $title_label = $strings['title']['label'];
            $title_field = new Field\Text( $title_label );
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

        if ( in_array( 'category', $include_fields, true ) ) {
            // Category filter.
            $category_label = $strings['category']['title'];
            $category_field = new Field\Taxonomy( $category_label );
            $category_field->set_key( "${key}_category" );
            $category_field->set_name( 'category' );
            $category_field->set_taxonomy( Taxonomy\Category::SLUG );
            $category_field->set_return_format( 'id' );
            $category_field->disallow_add_term();
            $category_field->set_field_type( 'multi_select' );
            $category_field->set_wrapper_width( 50 );
            $this->add_field( $category_field );
        }

        if ( in_array( 'tag', $include_fields, true ) ) {
            // Tag filter.
            $tag_label = $strings['tag']['title'];
            $tag_field = new Field\Taxonomy( $tag_label );
            $tag_field->set_key( "${key}_tag" );
            $tag_field->set_name( 'tag' );
            $tag_field->set_instructions( $strings['tag']['instructions'] );
            $tag_field->set_taxonomy( Taxonomy\PostTag::SLUG );
            $tag_field->set_return_format( 'id' );
            $tag_field->disallow_add_term();
            $tag_field->set_field_type( 'multi_select' );
            $tag_field->set_wrapper_width( 50 );
            $this->add_field( $tag_field );
        }

        if ( in_array( 'community_center', $include_fields, true ) ) {
            // Community center filter.
            $community_center_label = $strings['community_center']['title'];
            $community_center_field = new Field\Taxonomy( $community_center_label );
            $community_center_field->set_key( "${key}_community_center" );
            $community_center_field->set_name( 'community_center' );
            $community_center_field->set_instructions( $strings['community_center']['instructions'] );
            $community_center_field->set_taxonomy( Taxonomy\CommunityCenter::SLUG );
            $community_center_field->set_return_format( 'id' );
            $community_center_field->disallow_add_term();
            $community_center_field->set_field_type( 'multi_select' );
            $community_center_field->set_wrapper_width( 50 );
            $this->add_field( $community_center_field );
        }

        if ( in_array( 'amount', $include_fields, true ) ) {
            // Amount of articles in the list.
            $amount_label = $strings['amount']['title'];
            $amount_field = new Field\Number( $amount_label );
            $amount_field->set_key( "${key}_amount" );
            $amount_field->set_name( 'amount' );
            $amount_field->set_instructions( $strings['amount']['instructions'] );
            $amount_field->set_default_value( 3 );
            $amount_field->set_min( 1 );
            $amount_field->set_max( 16 );
            $amount_field->set_wrapper_width( 50 );
            $this->add_field( $amount_field );
        }

        if ( in_array( 'articles', $include_fields, true ) ) {
            // Articles repeater field
            $articles_label = $strings['articles']['title'];
            $articles_field = new Field\Repeater( $articles_label );
            $articles_field->set_key( "${key}_articles" );
            $articles_field->set_name( 'articles' );
            $articles_field->set_instructions( $strings['articles']['instructions'] );
            $articles_field->set_max( 16 );

            // Article field
            $article_label = $strings['article']['title'];
            $article_field = new Field\PostObject( $article_label );
            $article_field->set_key( "${key}_article" );
            $article_field->set_name( 'article' );
            $article_field->set_post_types( [ 'post' ] );
            $article_field->disallow_null();
            $articles_field->add_field( $article_field );
            $this->add_field( $articles_field );
        }

        if ( in_array( 'link', $include_fields, true ) ) {
            // Link
            $link_label = $strings['link']['title'];
            $link_field = new Field\Link( $link_label );
            $link_field->set_key( "${key}_link" );
            $link_field->set_name( 'link' );
            $link_field->set_instructions( $strings['link']['instructions'] );
            $this->add_field( $link_field );
        }
    }

}
