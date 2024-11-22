<?php
/**
 * Theme setupping.
 */

namespace Geniem\Theme;

use Geniem\Theme\Model\UnitSearch;

/**
 * Class Assets
 *
 * This class sets up the theme assets.
 */
class Assets implements Interfaces\Controller {

    /**
     * Add hooks and filters from this controller
     *
     * @return void
     */
    public function hooks() : void {
        // Setup hooks.
        \add_action( 'wp_enqueue_scripts', \Closure::fromCallable( [ $this, 'enqueue_assets' ] ), 100 );
        \add_action( 'admin_enqueue_scripts', \Closure::fromCallable( [ $this, 'admin_assets' ] ), 100 );

        // Disable jQuery Migrate.
        \add_action( 'wp_default_scripts', \Closure::fromCallable( [ $this, 'disable_jquery_migrate' ] ) );

        // Add SVG definitions to footer.
        \add_action( 'wp_footer', \Closure::fromCallable( [ $this, 'include_svg_icons' ] ) );

        // Add our own block JS and CSS.
        \add_action( 'enqueue_block_editor_assets', \Closure::fromCallable( [ $this, 'editor' ] ) );
    }

    /**
     * Theme assets. These have automatic cache busting.
     */
    private function enqueue_assets() {

        $main_css_mod_time   = static::get_theme_asset_mod_time( 'main.css' );
        $main_js_mod_time    = static::get_theme_asset_mod_time( 'main.js' );
        $vendor_css_mod_time = static::get_theme_asset_mod_time( 'vendor.css' );
        $vendor_js_mod_time  = static::get_theme_asset_mod_time( 'vendor.js' );

        // Enqueue vendor styles.
        \wp_enqueue_style( 'vendor-css', DPT_ASSET_URI . '/vendor.css', [], $vendor_css_mod_time, 'all' );

        // Enqueue theme styles.
        \wp_enqueue_style( 'theme-css', DPT_ASSET_URI . '/main.css', [ 'vendor-css' ], $main_css_mod_time, 'all' );

        // Enqueue vendor scripts.
        \wp_enqueue_script(
            'vendor-js',
            DPT_ASSET_URI . '/vendor.js',
            [ 'jquery' ],
            $vendor_js_mod_time,
            true
        );

        $theme_js_deps = [
            'jquery',
            'vendor-js',
        ];

        if ( is_page_template( UnitSearch::TEMPLATE ) ) {
            $theme_js_deps[] = 'jquery-ui-autocomplete';
        }

        // Enqueue theme scripts.
        \wp_register_script(
            'theme-js',
            DPT_ASSET_URI . '/main.js',
            $theme_js_deps,
            $main_js_mod_time,
            true
        );

        $localized_data = [
            'stylesImagePath' => esc_url( get_template_directory_uri() . '/assets/styles/images/' ),
        ];

        $localized_data = apply_filters( 'nuhe_modify_localized_data', $localized_data );

        \wp_localize_script( 'theme-js', 'themeData', $localized_data );

        \wp_enqueue_script( 'theme-js' );

        // Dequeue Core block styles.
        \wp_dequeue_style( 'wp-block-library' );
    }

    /**
     * This adds assets (JS and CSS) to gutenberg in admin.
     *
     * @return void
     */
    private function editor() {

        $css_mod_time = static::get_theme_asset_mod_time( 'editor.css' );
        $js_mod_time  = static::get_theme_asset_mod_time( 'editor.js' );

        \wp_enqueue_script(
            'editor-js',
            DPT_ASSET_URI . '/editor.js',
            [
                'wp-i18n',
                'wp-blocks',
                'wp-dom-ready',
                'wp-edit-post',
            ],
            $js_mod_time,
            true
        );

        \wp_enqueue_style( 'editor-css', DPT_ASSET_URI . '/editor.css', [], $css_mod_time, 'all' );
    }

    /**
     * Admin assets.
     */
    private function admin_assets() {

        $css_mod_time = static::get_theme_asset_mod_time( 'admin.css' );
        $js_mod_time  = static::get_theme_asset_mod_time( 'admin.js' );

        // Enqueue admin scripts.
        \wp_enqueue_script(
            'admin-js',
            DPT_ASSET_URI . '/admin.js',
            [
                'jquery',
                'wp-data',
                'wp-core-data',
                'wp-editor',
            ],
            $js_mod_time,
            true
        );

        // Enqueue admin styles.
        \wp_enqueue_style( 'admin-css', DPT_ASSET_URI . '/admin.css', [], $css_mod_time, 'all' );
    }

    /**
     * This function disables jQuery Migrate.
     *
     * @param \WP_Scripts $scripts The scripts object.
     *
     * @return void
     */
    private function disable_jquery_migrate( $scripts ) {
        if ( ! empty( $scripts->registered['jquery'] ) ) {
            $scripts->registered['jquery']->deps = array_diff(
                $scripts->registered['jquery']->deps,
                [ 'jquery-migrate' ]
            );
        }
    }

    /**
     * Add SVG definitions to footer.
     */
    private function include_svg_icons() {

        // Define SVG sprite file.
        $svg_icons_path = \get_template_directory() . '/assets/dist/icons.svg';

        // If it exists, include it.
        if ( file_exists( $svg_icons_path ) ) {
            include_once $svg_icons_path;
        }
    }

    /**
     * This enables cache busting for theme CSS and JS files by
     * returning a microtime timestamp for the given files.
     * If the file is not found for some reason, it uses the theme version.
     *
     * @param string $filename The file to check.
     *
     * @return int|string A microtime amount or the theme version.
     */
    private static function get_theme_asset_mod_time( $filename = '' ) {

        $modified_time = file_exists( DPT_ASSET_CACHE_URI . '/' . $filename )
            ? filemtime( DPT_ASSET_CACHE_URI . '/' . $filename )
            : DPT_THEME_VERSION;

        return $modified_time;
    }
}
