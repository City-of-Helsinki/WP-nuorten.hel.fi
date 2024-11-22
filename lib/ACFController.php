<?php
/**
 * This file controls ACF functionality.
 */

namespace Geniem\Theme;

use \Geniem\Theme\Model;

/**
 * Define the controller class.
 */
class ACFController implements Interfaces\Controller {

    /**
     * Initialize the class' variables and add methods
     * to the correct action hooks.
     *
     * @return void
     */
    public function hooks() : void {
        // ACF options and fields.
        \add_action( 'acf/init', \Closure::fromCallable( [ $this, 'require_acf_files' ] ) );

        // Register the API key for Google Maps usage.
        \add_action( 'acf/init', \Closure::fromCallable( [ $this, 'register_gmaps_apikey' ] ) );

        // Hide ACF menu page.
        \add_filter( 'acf/settings/show_admin', '__return_false' );

        // Prevent important links group from being rendered in specific templates
        \add_action( 'acf/prepare_field/name=important_links_group', \Closure::fromCallable( [ $this, 'disable_important_links' ] ) );
    }

    /**
     * This method loops through all files in the
     * ACF directory and requires them.
     */
    private function require_acf_files() {
        $files = array_diff( scandir( __DIR__ . '/ACF' ), [ '.', '..', 'Fields', 'Layouts' ] );

        // Loop through all files and directories except Fields where we store utility fields.
        array_walk(
            $files,
            function ( $file ) {
                require_once __DIR__ . '/ACF/' . basename( $file );
            }
        );
    }

    /**
     * Register Google Maps API key
     */
    private function register_gmaps_apikey() {
        if ( defined( 'GOOGLE_MAPS_APIKEY' ) && function_exists( 'acf_update_setting' ) ) {
            \acf_update_setting( 'google_api_key', GOOGLE_MAPS_APIKEY );
        }
    }

    /**
     * Prevent important links group from being rendered in specific templates.
     *
     * @param array $field
     * @return bool|array Returns false if field needs to be disabled.
     */
    private function disable_important_links( $field ) {
        global $post;
        $template = \get_page_template_slug( $post->ID );

        if ( $template === Model\PageCategoryLanding::TEMPLATE || $template === Model\PageTheme::TEMPLATE ) {
            return $field;
        }

        return false;
    }
}
