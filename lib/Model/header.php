<?php
/**
 * Header model file.
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Settings;
use \Geniem\Theme\Localization;
use \Geniem\Theme\Interfaces\Model;
use \Geniem\Theme\Traits;
use \Geniem\Jobs\PostTypes;

/**
 * Header class
 */
class Header implements Model {


    use Traits\Brand;

    /**
     * This defines the name of this model.
     */
    const NAME = 'Header';

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {}

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
     * Return the ACF field 'search_results_url' of the header.
     *
     * @return string|null
     */
    public function search_results_url() {

        return Settings::get_setting( 'search_results_url' );

    }

    /**
     * Data for language navigation.
     *
     * @return array|void Returns an array with two items; 'current' and 'others'. Current is the current language, and
     *                    others is an array of the other available languages. Returns void if no language data found.
     */
    public function languages() {

        $lang_nav_data = Localization::get_language_nav();

        if ( ! empty( $lang_nav_data ) ) {
            return $lang_nav_data;
        }

    }

    /**
     * This method adds the Class name as a CSS class to
     * the HTML tag in the markup, so that the JS code
     * is able to use the class when running page specific code.
     *
     * @return string
     */
    public function html_classes() {
        $classes = apply_filters( 'html_classes', [] );
        return implode( ' ', $classes );
    }

    /**
     * Get head scripts.
     *
     * @return mixed
     */
    public function get_head_scripts() {
        return Settings::get_setting( 'head_scripts' );
    }

    /**
     * Get chatbot scripts.
     *
     * @return mixed
     */
    public function get_chatbot_scripts() {

        // Summer voucher chatbot page ids.
        $allowed_page_ids = Settings::get_setting( 'summer_voucher_chatbot_scripts_allowed' ) ?? [];

        if ( in_array( \get_the_ID(), $allowed_page_ids ) || \is_post_type_archive( \Geniem\Jobs\PostTypes\Job::get_post_type() ) ) {
            return Settings::get_setting( 'summer_voucher_chatbot_scripts' ) ?? [];
        }

        // Sote chatbot
        $allowed_page_urls = Settings::get_setting( 'sote_chatbot_scripts_allowed_urls' ) ?? [];
        $disabled_pages    = Settings::get_setting( 'sote_chatbot_scripts_disabled' ) ?? [];

        if ( empty( $allowed_page_urls ) || in_array( \get_the_ID(), $disabled_pages ) ) {
            return [];
        }

        foreach( $allowed_page_urls as $allowed_page_url ) {
            if ( str_starts_with( \get_permalink(), $allowed_page_url['sote_chatbot_scripts_allowed_url'] ) ) {
                return Settings::get_setting( 'sote_chatbot_scripts' );
            }
        }
    }

    /**
     * Prepare Cookiebot data-culture ID
     *
     * @param string $current_language get current language code in string from, e.g en-US.
     *
     * @return string language code, e.g EN.
     */
    private function get_cookiebot_lang_id( $current_language ) {

        switch ( $current_language ) {
            // Finnish
            case 'fi':
                $cookiebot_lang = 'FI';
                break;
            // Swedish
            case 'sv':
                $cookiebot_lang = 'SV';
                break;
            case 'en-US':
            default:
                $cookiebot_lang = 'EN';
        }

        return $cookiebot_lang;
    }

    /**
     * Get language code for Cookiebot.
     *
     * @return string Cookiebot data-culture ID.
     */
    public function cookiebot_lang() {

        // Check if Polylang is active.
        if ( ! function_exists( 'pll_current_language' ) ) {
            return $this->get_cookiebot_lang_id( get_bloginfo( 'language' ) );
        }

        return $this->get_cookiebot_lang_id( pll_current_language() );
    }

    /**
     * This returns notification.
     *
     * @return array|null Notification.
     */
    public function get_notification() {

        $notification_title   = Settings::get_setting( 'notification_title' ) ?: '';
        $notification_content = Settings::get_setting( 'notification_content' ) ?: '';
        $notification_in_use  = Settings::get_setting( 'notification_in_use' ) ?: false;

        if ( empty( $notification_title ) && empty( $notification_content ) || empty( $notification_in_use ) ) {
            return null;
        }

        $notification_id = 'notification-' . md5( $notification_title ) . md5( $notification_content );

        return [
            'classes'         => [ 'js', 'alert' ],
            'label'           => $notification_title,
            'text'            => $notification_content,
            'icon'            => 'info-circle-fill',
            'close'           => true,
            'notification_id' => $notification_id,
        ];
    }
}
