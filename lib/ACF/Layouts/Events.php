<?php
/**
 * Event flexible content layout
 */

namespace Geniem\Theme\ACF\Layouts;

use Geniem\ACF\Field;
use Geniem\Theme\Integrations\LinkedEvents\ApiClient;
use Geniem\Theme\Integrations\LinkedEvents\Entities\Keyword;
use Geniem\Theme\Model\PageVolunteering;

/**
 * Class Events
 *
 * @package Geniem\Theme\ACF\Layouts
 */
class Events extends Field\Flexible\Layout {

    /**
     * Layout key
     */
    const KEY = '_events';

    /**
     * Create the layout
     *
     * @param string $key Key from the flexible content.
     */
    public function __construct( $key ) {
        $label = 'Tapahtumanosto';
        $key   = $key . self::KEY;
        $name  = 'events';

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
            'title'    => [
                'label' => 'Otsikko',
            ],
            'keywords' => [
                'label' => 'Avainsanat',
            ],
            'limit'    => [
                'label' => 'Nostojen määrä',
            ],
            'disable_youth_service_restriction' => [
                'label' => 'Poista Nuorisopalvelukokonaisuus julkaisija -rajoitus hausta.',
            ],
        ];

        $title_field = ( new Field\Text( $strings['title']['label'] ) )
            ->set_key( "{$key}_title" )
            ->set_name( 'title' )
            ->set_wrapper_width( 50 )
            ->redipress_include_search();

        $keywords_field = ( new Field\Select( $strings['keywords']['label'] ) )
            ->set_key( "{$key}_keywords" )
            ->set_name( 'keywords' )
            ->use_ui()
            ->allow_multiple()
            ->set_choices( $this->get_keyword_choices() )
            ->set_default_value( 'yso:p11617' )
            ->set_wrapper_width( 50 );

        $limit_field = ( new Field\Radio( $strings['limit']['label'] ) )
            ->set_key( "{$key}_limit" )
            ->set_name( 'limit' )
            ->set_choices( [
                2 => 2,
                3 => 3,
                4 => 4,
                6 => 6,
            ] )
            ->set_default_value( 6 )
            ->set_wrapper_width( 50 );

        $disable_youth_service_restriction_field = ( new Field\TrueFalse( $strings['disable_youth_service_restriction']['label'] ) )
            ->set_key( "{$key}_disable_youth_service_restriction" )
            ->set_name( 'disable_youth_service_restriction' )
            ->set_default_value( false )
            ->set_wrapper_width( 50 )
            ->use_ui();

        $this->add_fields( [
            $title_field,
            $keywords_field,
            $limit_field,
            $disable_youth_service_restriction_field
        ] );
    }

    /**
     * Get keyword choices
     *
     * @return array
     */
    private function get_keyword_choices() : array {
        $client          = new ApiClient();
        $keywords        = $client->get_all_keywords();
        $keyword_choices = [];

        if ( ! empty( $keywords ) ) {
            foreach ( $keywords as $keyword ) {
                $keyword_choices[ $keyword->get_id() ] = $keyword->get_name();
            }
        }

        return $keyword_choices;
    }
}
