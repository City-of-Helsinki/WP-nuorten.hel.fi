<?php
/**
 * This file controls what the theme supports.
 */

namespace Geniem\Theme;

/**
 * Define the controller class.
 */
class ThemeSupports implements Interfaces\Controller {

    /**
     * Initialize the class' variables and add methods
     * to the correct action hooks.
     *
     * @return void
     */
    public function hooks() : void {
        // Add supported functionality.
        \add_action( 'after_setup_theme', \Closure::fromCallable( [ $this, 'add_supported_functionality' ] ) );

        \add_action( 'admin_menu', \Closure::fromCallable( [ $this, 'remove_meta_boxes' ] ) );

        // Get site icon url.
        \add_filter( 'get_site_icon_url', \Closure::fromCallable( [ $this, 'favicon_url' ] ), 10, 3 );

        \add_filter( 'query_vars', \Closure::fromCallable( [ $this, 'query_vars' ] ), 10, 1 );

        \add_filter( 'wp_mail_from', \Closure::fromCallable( [ $this, 'change_mail_from_address' ] ) );

        \add_filter( 'acf/fields/flexible_content/layout_title/name=modules', \Closure::fromCallable( [ $this, 'add_more_descriptive_title_to_event_modules' ] ), 10, 1 );

        // This removes the unnecessary new feature called block patterns.
        \remove_theme_support( 'core-block-patterns' );
    }

    /**
     * This adds all functionality.
     *
     * @return void
     */
    private function add_supported_functionality() {

        // Enable plugins to manage the document title
        // http://codex.wordpress.org/Function_Reference/add_theme_support#Title_Tag
        \add_theme_support( 'title-tag' );

        // Enable post thumbnails
        // http://codex.wordpress.org/Post_Thumbnails
        \add_theme_support( 'post-thumbnails' );

        // Enable HTML5 markup support
        // http://codex.wordpress.org/Function_Reference/add_theme_support#HTML5
        \add_theme_support( 'html5', [ 'caption', 'comment-form', 'comment-list', 'gallery', 'search-form' ] );

        // Register wp_nav_menu() menus
        // http://codex.wordpress.org/Function_Reference/register_nav_menus
        \register_nav_menus(
            [
                'primary_navigation' => __( 'Primary Navigation', 'nuhe' ),
            ]
        );
    }

    /**
     * Returns favicon url. This overrides the theme settings and we prefer to use favicon
     * straight by hardcoding the file url. If client wants to be able to modify the favicon, remove this.
     *
     * @return string
     */
    private function favicon_url() {
        return DPT_ASSETS_URI . '/images/favicon.png';
    }

    /**
     * Register query vars
     *
     * @param array $vars Array of query vars.
     *
     * @return array
     */
    private function query_vars( array $vars ) : array {
        $vars[] = 'event_id';
        $vars[] = 'type';
        $vars[] = 'view';
        $vars[] = 'unit-filter';
        $vars[] = 'search-term';

        return $vars;
    }

    /**
     * Remove meta boxes
     */
    private function remove_meta_boxes() {
        $post_types = [
            'high-school-cpt',
            'vocational-cpt',
        ];
        remove_meta_box( 'school_keyword_taxdiv', $post_types, 'side' );
    }

    /**
     * Change WP mail from address.
     *
     * @param string $mail_from Mail from address.
     * @return string
     */
    private function change_mail_from_address( $mail_from ) {
        return \getenv( 'SMTP_FROM' );
    }

    /**
     * Add more descriptive title to PageEventsCollection modules to separate them better from each others.
     * 
     *
     * @param string $title Module title.
     * @return string
     */
    function add_more_descriptive_title_to_event_modules( $title ) {

        $module_title = \get_sub_field( 'title' );
        
        // If module has title field, add it to module title as suffix. 
        if ( $module_title ) {
            $title .= ': ' . $module_title;
        }

        return $title;
    }
}
