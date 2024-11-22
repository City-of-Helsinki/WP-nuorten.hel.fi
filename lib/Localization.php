<?php
/**
 * Localization settings file.
 */

namespace Geniem\Theme;

/**
 * This class holds all methods related to localization.
 */
class Localization implements Interfaces\Controller {

    /**
     * Initialize the class' variables and add methods
     * to the correct action hooks.
     *
     * @return void
     */
    public function hooks() : void {

        // Make theme available for translation
        \load_theme_textdomain( 'nuhe', get_template_directory() . '/lang' );

        // Add private post types to polylang.
        // Settings are added automatically if PLL is active.
        \add_filter( 'pll_get_post_types', \Closure::fromCallable( [ $this, 'add_cpts_to_polylang' ] ), 10, 2 );

        // Uncomment to add private taxonomies to polylang.
        // add_filter( 'pll_get_taxonomies', \Closure::fromCallable( [ $this, 'add_taxs_to_polylang' ] ), 10, 2 );
    }

    /**
     * This returns the current language either by using
     * PLL or WP's locale.
     *
     * @return string|bool The language slug or false if used in
     *                     admin and 'all languages' are chosen from PLL top bar filter.
     */
    public static function get_current_language() {

        if ( DPT_PLL_ACTIVE && function_exists( 'pll_current_language' ) ) {
            return \pll_current_language() ?? get_locale();
        }

        return get_locale();
    }

    /**
     * Get default language.
     * Returns Polylang's default language or current WP locale.
     *
     * @return bool|\PLL_Language|string
     */
    public static function get_default_language() {
        if ( DPT_PLL_ACTIVE && function_exists( 'pll_default_language' ) ) {
            return \pll_default_language() ?? get_locale();
        }

        return get_locale();
    }

    /**
     * This returns the current locale.
     *
     * @return string
     */
    public static function get_current_locale() : string {

        if ( DPT_PLL_ACTIVE && function_exists( 'pll_current_language' ) ) {
            $current_locale = \pll_current_language( 'locale' );
            return empty( $current_locale ) || $current_locale === 'fi' ? 'fi_FI' : $current_locale;
        }

        return 'fi_FI';
    }

    /**
     * Get data for the language navigation.
     *
     * @return array|null Returns an array with two items; 'current' and 'others'. Current is the current,
     *                          language, and others is an array of the other available languages.
     *                          Returns null if Polylang not active.
     */
    public static function get_language_nav() {

        // Return early if Polylang is not active.
        if ( ! DPT_PLL_ACTIVE || ! function_exists( 'pll_the_languages' ) ) {
            return null;
        }

        // Get the raw language data
        $args = [
            'raw' => 1,
        ];

        $languages = \pll_the_languages( $args );

        if ( empty( $languages ) ) {
            return null;
        }

        $lang_data = [];

        foreach ( $languages as $lang ) {
            if ( ! empty( $lang['current_lang'] ) ) {
                $lang_data['current'] = $lang;
            }
            $lang_data['all'][] = $lang;
        }

        return \apply_filters( 'nuhe_lang_nav', $lang_data );
    }

    /**
     * This adds the CPTs that are not public to Polylang translation.
     *
     * @param array   $post_types  The post type array.
     * @param boolean $is_settings A not used boolean flag to see if we're in settings.
     *
     * @return array The modified post_types -array.
     */
    private function add_cpts_to_polylang( $post_types, $is_settings ) { // phpcs:ignore

        // Return early if Polylang is not active.
        if ( ! DPT_PLL_ACTIVE ) {
            return $post_types;
        }
        // Enable language and translation management for theme settings automatically.
        $post_types[ PostType\Settings::SLUG ]              = PostType\Settings::SLUG;
        $post_types[ PostType\YouthCenter::SLUG ]           = PostType\YouthCenter::SLUG;
        $post_types[ PostType\Instructor::SLUG ]            = PostType\Instructor::SLUG;
        $post_types[ PostType\HighSchool::SLUG ]            = PostType\HighSchool::SLUG;
        $post_types[ PostType\VocationalSchool::SLUG ]      = PostType\VocationalSchool::SLUG;
        $post_types[ PostType\YouthCouncilElections::SLUG ] = PostType\YouthCouncilElections::SLUG;
        $post_types[ PostType\Initiative::SLUG ]            = PostType\Initiative::SLUG;

        $post_types = apply_filters( 'nuhe_add_cpts_to_polylang', $post_types );

        return $post_types;
    }

    /**
     * This adds the taxonomies that are not public to Polylang translation.
     *
     * @param array   $tax_types   The taxonomy type array.
     * @param boolean $is_settings A not used boolean flag to see if we're in settings.
     *
     * @return array The modified tax_types -array.
     */
    private function add_taxs_to_polylang( $tax_types, $is_settings ) {
        $tax_types[ Taxonomy\YouthCenterKeyword::SLUG ] = Taxonomy\YouthCenterKeyword::SLUG;
        $tax_types[ Taxonomy\CommunityCenter::SLUG ]    = Taxonomy\CommunityCenter::SLUG;
        $tax_types[ Taxonomy\SchoolKeyword::SLUG ]      = Taxonomy\SchoolKeyword::SLUG;

        return $tax_types;
    }

    /**
     * This is called in models to get theme strings.
     *
     * @return array The strings array.
     */
    public static function get_strings() : array {

        $strings = [
            'header' => [
                'skip_to_content' => _x( 'Skip to content', 'theme-frontend', 'nuhe' ),
                'main_navigation' => _x( 'Main navigation', 'theme-frontend', 'nuhe' ),
                'search'          => _x( 'Search', 'theme-frontend', 'nuhe' ),
            ],
            '404'    => [
                'title'         => _x( 'Page not found', 'theme-frontend', 'nuhe' ),
                'subtitle'      => _x(
                    'The content were looking for was not found',
                    'theme-frontend',
                    'nuhe'
                ),
                'home_link_txt' => _x( 'To home page', 'theme-frontend', 'nuhe' ),
            ],
        ];

        return $strings;
    }
}
