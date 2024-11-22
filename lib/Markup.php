<?php
/**
 * This file controls theme markup modifications.
 */

namespace Geniem\Theme;

use \Geniem\Theme\Traits;

/**
 * Define the controller class.
 */
class Markup implements Interfaces\Controller {

    use Traits\ThemeColor;

    /**
     * Initialize the class' variables and add methods
     * to the correct action hooks.
     *
     * @return void
     */
    public function hooks() : void {
        // Remove unnecessary ID's from nav menus.
        \add_filter( 'nav_menu_item_id', '__return_empty_string' );

        // Add color theme class to body
        \add_filter( 'body_class', \Closure::fromCallable( [ $this, 'add_color_theme_class_to_body' ] ) );

        // Allow script tag in textarea
        \add_filter( 'wp_kses_allowed_html', \Closure::fromCallable( [ $this, 'allow_script_tag' ] ) );
    }


    /**
     * Add color theme class to body
     *
     * @param array $classes An array of body class names.
     * @return array
     */
    private function add_color_theme_class_to_body( $classes ) {
        $classes[] = $this->get_theme_color();

        return $classes;
    }

    /**
     * Allow script tag in textarea
     *
     * @param array $allowed_post_tags Array of tags.
     * @return array
     */
    private function allow_script_tag( array $allowed_post_tags ) : array {
        $allowed_post_tags['script'] = [
            'src'               => true,
            'async'             => true,
            'data-cbid'         => true,
            'data-blockingmode' => true,
            'type'              => true,
            'defer'             => true,
        ];

        return $allowed_post_tags;
    }
}
