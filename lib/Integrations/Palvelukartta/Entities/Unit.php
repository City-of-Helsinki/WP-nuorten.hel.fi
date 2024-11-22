<?php
/**
 * Palvelukartta Unit
 */

namespace Geniem\Theme\Integrations\Palvelukartta\Entities;

use Geniem\Theme\Integrations\Common\ServiceLinks;

/**
 * Class OntologyWord
 *
 * @package Geniem\Theme\Integrations\Palvelukartta\Entities
 */
class Unit extends Entity {

    /**
     * Get Id.
     *
     * @return int
     */
    public function get_id() {
        return $this->entity_data->id;
    }

    /**
     * Get name
     *
     * @return string|null
     */
    public function get_name() {
        return $this->get_key_by_language( 'name' );
    }

    /**
     * Get street address
     *
     * @return string|null
     */
    public function get_street_address() {
        return $this->get_key_by_language( 'street_address' );
    }

    /**
     * Get address city
     *
     * @return string|null
     */
    public function get_address_city() {
        return $this->get_key_by_language( 'address_city' );
    }

    /**
     * Get address zip
     *
     * @return string|null
     */
    public function get_address_zip() {
        return $this->entity_data->address_zip ?? null;
    }


    /**
     * Get full postal address
     *
     * @return string|null
     */
    public function get_address_postal_full() {
        return $this->get_key_by_language( 'address_postal_full' );
    }

    /**
     * Get Google Maps link
     *
     * @return false|string
     */
    public function get_google_maps_link() {
        return ServiceLinks::get_google_maps_link( [
            $this->get_street_address(),
            $this->get_address_city(),
        ] );
    }

    /**
     * Get www address
     *
     * @return string|null
     */
    public function get_www() {
        return $this->get_key_by_language( 'www' );
    }

    /**
     * Get phone number
     *
     * @return string|null
     */
    public function get_phone() {
        return $this->entity_data->phone ?? null;
    }

    /**
     * Get call charge info
     *
     * @return string|null
     */
    public function get_call_charge_info() {
        return $this->get_key_by_language( 'call_charge_info' );
    }

    /**
     * Get entrance picture url
     *
     * @return string|null
     */
    public function get_picture_entrance_url() {
        return $this->entity_data->picture_entrance_url ?? null;
    }

    /**
     * Get accessibility phone
     *
     * @return string|null
     */
    public function get_accessibility_phone() {
        return $this->entity_data->accessibility_phone ?? null;
    }

    /**
     * Get accessibility email
     *
     * @return string|null
     */
    public function get_accessibility_email() {
        return $this->entity_data->accessibility_email ?? null;
    }

    /**
     * Get accessibility www
     *
     * @return string|null
     */
    public function get_accessibility_www() {
        return $this->entity_data->accessibility_www ?? null;
    }

    /**
     * Get accessibility viewpoints
     *
     * @return array|null
     */
    public function get_accessibility_viewpoints() {
        if ( empty( $this->entity_data->accessibility_viewpoints ) ) {
            return null;
        }

        $points        = explode( ',', $this->entity_data->accessibility_viewpoints );
        $parsed_points = [];

        foreach ( $points as $point ) {
            $pieces = explode( ':', $point );

            $parsed_points[] = [
                'id'     => $pieces[0],
                'status' => $pieces[1],
            ];
        }

        return $parsed_points;
    }

    /**
     * Get accessibility sentences
     *
     * @return bool|UnitAccessibilitySentence[]
     */
    public function get_accessibility_sentences() {
        if ( empty( $this->entity_data->acessiblity_sentences ) ) {
            return false;
        }

        return array_map(
            fn( $sentence ) => new UnitAccessibilitySentence( $sentence ),
            $this->entity_data->acessiblity_sentences
        );
    }

    /**
     * Get connections
     *
     * @return false|UnitConnection[]
     */
    public function get_connections() {
        if ( empty( $this->entity_data->connections ) ) {
            return false;
        }

        return array_map( fn( $connection ) => new UnitConnection( $connection ), $this->entity_data->connections );
    }

    /**
     * Get latitude
     *
     * @return float|null
     */
    public function get_latitude() {
        return floatval( $this->entity_data->latitude ) ?? null;
    }

    /**
     * Get longitude
     *
     * @return float|null
     */
    public function get_longitude() {
        return floatval( $this->entity_data->longitude ) ?? null;
    }

    /**
     * Get HSL directions link
     *
     * @return false|string
     */
    public function get_hsl_directions_link() {
        return ServiceLinks::get_hsl_directions_link(
            $this->get_street_address(),
            $this->get_address_city()
        );
    }

    /**
     * Get Google directions link
     *
     * @return false|string
     */
    public function get_google_directions_link() {
        return ServiceLinks::get_google_directions_link(
            $this->get_street_address(),
            $this->get_address_city()
        );
    }

    /**
     * Get description
     *
     * @return string|null
     */
    public function get_desc() {
        return $this->get_key_by_language( 'desc' );
    }
}
