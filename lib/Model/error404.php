<?php
/**
 * Error page
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use Geniem\Theme\Settings;

/**
 * Class for handling 404 errors
 */
class Error404 implements Model {

    /**
     * This defines the name of this model.
     */
    const NAME = 'Error404';

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
        return self::NAME;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function get_title() {
        return Settings::get_setting( '404_title' );
    }

    /**
     * Get content
     *
     * @return string
     */
    public function get_content() {
        return Settings::get_setting( '404_description' );
    }

    /**
     * Get background url
     *
     * @return string
     */
    public function get_background() {
        $image_id = Settings::get_setting( '404_image' );

        if ( empty( $image_id ) ) {
            $image    = Settings::get_setting( 'default_page_image' );
            $image_id = ( $image ) ? $image['ID'] : null;
        }

        return ! empty( $image_id )
            ? wp_get_attachment_image_url( $image_id, 'fullhd' )
            : null;
    }

    /**
     * Add classes to html.
     *
     * @param array $classes Array of classes.
     *
     * @return array Array of classes.
     */
    public function add_classes_to_html( $classes ) : array {
        $classes[] = $this->get_name();

        return $classes;
    }
}
