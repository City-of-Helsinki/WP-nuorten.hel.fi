<?php
/**
 * Respa ApiClient
 *
 * @link https://dev.hel.fi/apis/respa
 */

namespace Geniem\Theme\Integrations\Respa;

use Geniem\Theme\Integrations\Common\ApiBaseClient;
use Geniem\Theme\Integrations\Respa\Entities\Space;

/**
 * Class ApiClient
 *
 * @package Geniem\Theme\Integrations\Respa
 */
class ApiClient extends ApiBaseClient {

    /**
     * Get API base url
     *
     * @return string
     */
    protected function get_base_url() : string {
        return 'https://api.hel.fi/respa/v1';
    }

    /**
     * Get spaces by unit id.
     *
     * @param mixed $unit_id Unit id.
     *
     * @return Space[]|bool
     */
    public function get_spaces_by_unit_id( $unit_id ) {
        $cache_key = sprintf( 'spaces-by-unit-id-%s', $unit_id );
        $spaces    = wp_cache_get( $cache_key );

        if ( $spaces ) {
            return $spaces;
        }

        $spaces = $this->do_get_all_keywords( $unit_id );

        if ( $spaces ) {
            wp_cache_set( $cache_key, $spaces, '', HOUR_IN_SECONDS * 2 );
        }

        return $spaces;
    }

    /**
     * Get all spaces
     *
     * @param mixed  $unit_id Unit id.
     *
     * @return Space[]|false
     */
    protected function do_get_all_keywords( $unit_id ) {

        $query = <<<GRAPHQL
        {
            reservationUnits(isVisible: true, tprekId: "$unit_id") {
                edges {
                    node {
                        pk,
                        nameFi,
                        nameEn,
                        nameSv,
                        descriptionFi,
                        descriptionSv,
                        descriptionEn,
                        pricings {
                            pricingType,
                            lowestPrice,
                            highestPrice
                        },
                        images {
                            largeUrl
                        }
                    }
                }
            }
        }
        GRAPHQL;

        $body = json_encode( ['query' => $query] );

        $response = \wp_remote_post(
            'https://tilavaraus.hel.fi/graphql/',
            [
                'method'  => 'POST',
                'headers' => [
                    'Accept-Encoding' => 'gzip, deflate, br',
                    'Content-Type'    => 'application/json',
                    'Accept'          => 'application/json',
                    'Connection'      => 'keep-alive',
                    'DNT'             => '1',
                    'Referer'         => 'https://tilavaraus.hel.fi/',
                    'Cookie'          => 'csrftoken=' . TILAVARAUS_TOKEN,
                    'x-csrftoken'     => TILAVARAUS_TOKEN
                ],
                'body'    => $body,
            ]
        );

        if ( 200 !== \wp_remote_retrieve_response_code( $response ) ) {
            return false;
        }

        $response = json_decode( \wp_remote_retrieve_body( $response ) );

        $spaces = array_map( fn( $item ) => new Space( $item ), $response->data->reservationUnits->edges );

        return $spaces;
    }

    /**
     * Do request to 'next' url returned by the API.
     *
     * @param string $request_url Request url.
     *
     * @return false|mixed
     */
    protected function next( string $request_url ) {
        $response = wp_remote_get( $request_url );

        if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
            return json_decode( wp_remote_retrieve_body( $response ) );
        }

        return false;
    }
}
