<?php
/**
 * Trait for brand
 */

namespace Geniem\Theme\Traits;

use Geniem\Theme\Localization;
use \Geniem\Theme\Settings;

/**
 * Trait Brand
 *
 * @package Geniem\Theme\Traits
 */
trait Brand {
    /**
     * Return the ACF field 'logo' of the header.
     *
     * @return array|null
     */
    public function logo() {

        $logo = Settings::get_setting( 'logo' );

        // If the setting is empty, fall back to default.
        if ( empty( $logo ) ) {

            $language  = Localization::get_current_language();
            $logo_file = $language === 'sv' ? 'helsinki-sv.svg' : 'helsinki.svg';
            $logo      = get_template_directory_uri() . '/assets/images/' . $logo_file;

        }
        else {

            $logo = $logo['url'];

        }

        return $logo;
    }

    /**
     * Return the ACF field 'service_name' of the header.
     *
     * @return string|null
     */
    public function service_name() {

        return Settings::get_setting( 'service_name' );

    }

    /**
     * Return white version of the logo.
     *
     * @return string
     */
    public function logo_white() : string {
        $language  = Localization::get_current_language();
        $logo_file = $language === 'sv' ? 'helsingfors_white.png' : 'helsinki_white.png';
        return get_template_directory_uri() . '/assets/images/' . $logo_file;
    }

}
