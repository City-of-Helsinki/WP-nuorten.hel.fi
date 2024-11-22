<?php
/**
 * Helpers for HDS partials
 */

/**
 * Get a string of attributes from an associative array of key value pairs.
 *
 * @param array $attributes Array of key-value pairs, eg. 'aria-hidden' => 'true'.
 * @return string String of attributes, e.g. disabled aria-hidden="true"
 */
function hds_get_escaped_attributes_string( array $attributes ) : string {

    // Return empty string if there are no attributes
    if ( empty( $attributes ) ) {
        return '';
    }

    return implode(
        ' ',
        array_map( function( $key ) use ( $attributes ) {

            // If attribute is e.g. 'disabled' => true, return only 'disabled'
            if ( is_bool( $attributes[ $key ] ) ) {
                return $attributes[ $key ] ? $key : '';
            }

            return $key . '="' . esc_attr( $attributes[ $key ] ) . '"';
        }, array_keys( $attributes ) )
    );

}

/**
 * Get a string of classes from an array, each prefixed with $prefix
 *
 * @param array  $classes Array of strings, eg. 'primary'.
 * @param string $prefix Prefix string, eg. '.hds-button--'.
 * @return string String of prefixed classes, eg .hds-button--primary
 */
function hds_get_prefixed_classes_string( array $classes = [], string $prefix = '' ) : string {
    return $prefix . implode(
        ' ' . $prefix,
        // Filter our items that are not strings
        array_filter(
            $classes,
            function( $class ) {
                return is_string( $class );
            }
        )
    );
}

/**
 * This checks if the given link is an external link.
 *
 * @param array|string $link A link data array or a URL.
 *
 * @return bool A flag that tells if the link is pointing to an external URL.
 */
function hds_is_external_link( $link = '' ) {

    $link_url = $link['url'] ?? $link;

    if ( empty( $link_url ) ) {
        return false;
    }

    $site_domain = ( defined( 'SITE_DOMAIN' ) ? SITE_DOMAIN : '' );
    $parsed_link = \wp_parse_url( $link_url );
    $is_external = false;

    if ( ! empty( $parsed_link['host'] ) && ( $site_domain !== $parsed_link['host'] ) ) {
        $is_external = true;
    }

    return $is_external;
}
