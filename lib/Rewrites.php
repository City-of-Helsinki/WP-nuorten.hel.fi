<?php
/**
 * Theme rewrite controlling.
 */

namespace Geniem\Theme;

/**
 * Class Rewrites
 * This class controls rewrite rules and other url-manipulating settings.
 * Mainly used for the search and archive pagination.
 * Please note that if you are using Polylang you should double check that
 * the urls and translations work properly.
 *
 * @package Geniem\Theme
 */
class Rewrites implements Interfaces\Controller {

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {

        if ( ! function_exists( 'pll_get_post_language' ) ) {
            \add_action( 'init', \Closure::fromCallable( [ $this, 'change_pagination_base' ] ) );
            \add_action( 'init', \Closure::fromCallable( [ $this, 'translate_search_base_rewrite' ] ), 10, 0 );
        }
    }

    /**
     * This changes the default pagination base url.
     * This is done, so that the 'page' can be translated when polylang is not in use.
     */
    private function change_pagination_base() {

        global $wp_rewrite;
        $wp_rewrite->pagination_base = _x( 'page', 'pagination base to show in url', 'nuhe' );
    }

    /**
     * This changes the default search base.
     */
    private function translate_search_base_rewrite() {

        global $wp_rewrite;
        $wp_rewrite->search_base = _x( 'search', 'search base to show in url', 'nuhe' );
    }
}
