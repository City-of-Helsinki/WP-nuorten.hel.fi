<?php
/**
 * Trait for Palvelukartta
 */

namespace Geniem\Theme\Traits;

/**
 * Trait Palvelukartta
 *
 * @package Geniem\Theme\Traits
 */
trait Palvelukartta {

    /**
     * Fill API data
     *
     * @param int    $post_id          \WP_Post ID.
     * @param string $field_to_get     Field name where to get data from.
     * @param array  $fields_to_update Name of the fields to be updated.
     */
    protected function fill_api_data( $post_id, $field_to_get, $fields_to_update ) : void {
        if ( get_post_type( $post_id ) !== static::SLUG ) {
            return;
        }

        $unit_id = get_field( $field_to_get, $post_id );

        if ( empty( $unit_id ) ) {
            return;
        }

        $api  = new \Geniem\Theme\Integrations\Palvelukartta\ApiClient();
        $unit = $api->get_unit_by_id( $unit_id );

        update_field( $fields_to_update[0], $unit->get_latitude(), $post_id );
        update_field( $fields_to_update[1], $unit->get_longitude(), $post_id );
    }
}
