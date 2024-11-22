<?php
/**
 * Footer model file
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Settings;
use \Geniem\Theme\Interfaces\Model;
use \Geniem\Theme\Traits;

/**
 * Footer class
 */
class Footer implements Model {


    use Traits\Brand;

    /**
     * This defines the name of this model.
     */
    const NAME = 'Footer';

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {
    }

    /**
     * Get class name constant
     *
     * @return string Class name constant
     */
    public function get_name() : string {

        return static::NAME;

    }

    /**
     * Add classes to html.
     *
     * @param array $classes Array of classes.
     * @return array Array of classes.
     */
    public function add_classes_to_html( $classes ) : array {
        return $classes;
    }

    /**
     * Get Askem scripts.
     *
     * @return mixed
     */
    public function get_askem_scripts() {
        $disabled_pages = Settings::get_setting( 'askem_scripts_disabled' ) ?? [];

        if ( in_array( (string) \get_the_ID(), $disabled_pages, true ) ) {
            return null;
        }

        return Settings::get_setting( 'askem_script');
    }

    /**
     * Get footer group field
     *
     * @return array|null
     */
    public function get_footer_fields() {
        $footer_fields = Settings::get_setting( 'footer' );

        if ( ! empty( $footer_fields['footer_links'] ) ) {
            $footer_fields['footer_links'] = array_map( function ( $link ) {
                return $link['footer_link'];
            }, $footer_fields['footer_links'] );
        }

        $sub_bar = $footer_fields['footer_sub_bar'] ?? false;

        if ( ! empty( $sub_bar ) && ! empty( $sub_bar['footer_sub_bar_links'] ) ) {
            $footer_fields['footer_sub_bar']['footer_sub_bar_links'] = array_map( function ( $link ) {
                return $link['footer_link'];
            }, $sub_bar['footer_sub_bar_links'] );
        }

        return $footer_fields;
    }
}
