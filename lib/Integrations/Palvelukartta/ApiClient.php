<?php
/**
 * Palvelukartta ApiClient
 *
 * @link https://www.hel.fi/palvelukarttaws/restpages/ver4.html
 */

namespace Geniem\Theme\Integrations\Palvelukartta;

use Geniem\Theme\Integrations\Common\ApiBaseClient;
use Geniem\Theme\Integrations\Palvelukartta\Entities\Unit;

/**
 * Class ApiClient
 *
 * @package Geniem\Theme\Integrations\Palvelukartta
 */
class ApiClient extends ApiBaseClient {

    /**
     * Get API base url
     *
     * @return string
     */
    protected function get_base_url() : string {
        return 'https://www.hel.fi/palvelukarttaws/rest/v4';
    }

    /**
     * Get unit by id
     *
     * @param int $id Unit Id.
     *
     * @return bool|Unit
     */
    public function get_unit_by_id( $id ) {
        $cache_key = sprintf( 'unit-%s', $id );
        $response  = wp_cache_get( $cache_key );

        if ( $response ) {
            return $response;
        }

        $response = $this->get( [ 'unit', $id ] );

        if ( empty( $response ) ) {
            return false;
        }

        $response = new Unit( $response );
        wp_cache_set( $cache_key, $response, '', HOUR_IN_SECONDS * 2 );

        return $response;
    }

    /**
     * Get units by ontology word id or array of ids.
     *
     * @param int|array $ontology_word_ids Ontology word id or array of ids.
     *
     * @return Unit[]|bool
     */
    public function get_units_by_ontology_word_ids( $ontology_word_ids ) {
        $ids = is_array( $ontology_word_ids )
            ? implode( '+', $ontology_word_ids )
            : $ontology_word_ids;

        $cache_key = sprintf( 'units-by-ontology-word-%s', $ids );
        $response  = wp_cache_get( $cache_key );

        if ( $response ) {
            return $response;
        }

        $response = $this->get( 'unit', [
            'ontologyword' => $ids,
            'organization' => '83e74666-0836-4c1d-948a-4b34a8b90301',
        ] );

        if ( empty( $response ) ) {
            return false;
        }

        $response = array_map( fn( $item ) => new Unit( $item ), $response );
        wp_cache_set( $cache_key, $response, '', HOUR_IN_SECONDS * 2 );

        return $response;
    }

    /**
     * Get ontology words
     *
     * @return bool|array
     */
    public function get_all_ontology_words() {
        $cache_key = 'ontology-words';
        $response  = wp_cache_get( $cache_key );

        if ( $response ) {
            return $response;
        }

        $response = $this->get( 'ontologyword' );

        if ( empty( $response ) ) {
            return false;
        }

        wp_cache_set( $cache_key, $response, '', HOUR_IN_SECONDS * 2 );

        return $response;
    }
}
