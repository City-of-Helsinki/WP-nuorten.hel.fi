<?php
/**
 * Initiatives ApiClient
 *
 * @link https://dev.hel.fi/apis/open311
 */

namespace Geniem\Theme\Integrations\Initiatives;

use Geniem\Theme\Integrations\Common\ApiBaseClient;
use Geniem\Theme\Integrations\Initiatives\Entities\Service;

/**
 * Class ApiClient
 *
 * @package Geniem\Theme\Integrations\Initiatives
 */
class ApiClient extends ApiBaseClient {

    /**
     * Get API base url
     *
     * @return string
     */
    protected function get_base_url() : string {
        return 'https://asiointi.hel.fi/palautews/rest/v1';
    }

    /**
     * Get services
     *
     * @param array $filters
     *
     * @return Service[]|bool
     */
    public function get_services( array $filters = [] ) {
        // Get services by current locale. If locale is not available from api, default will be en.
        $default_filters = [ 'locale' => pll_current_language() ];

        $request_filters   = array_merge( $default_filters, $filters );
        $filters_to_string = implode( '-', $request_filters );
        $cache_key         = sprintf( 'services-%s-%s', $filters_to_string,  pll_current_language() );

        $services = wp_cache_get( $cache_key );

        if ( $services ) {
            return $services;
        }

        $services = $this->get( 'services.json', $request_filters );

        if ( empty( $services ) ) {
            return false;
        }

        $services = array_map( fn( $item ) => new Service( $item ), $services );

        if ( $services ) {
            wp_cache_set( $cache_key, $services, '', HOUR_IN_SECONDS * 2 );
        }

        return $services;
    }

    /**
     * Do an API POST request
     *
     * @param string|array $path   Request path.
     * @param array        $params Request parameters.
     *
     * @return bool|mixed
     */
    public function post( $path, array $params = [] ) {
        $base_url = $this->get_base_url();

        if ( empty( $base_url ) ) {
            return false;
        }

        $default_params = [
            'api_key' => INITIATIVES_API_KEY,
        ];

        $params      = array_merge( $default_params, $params );
        $request_url = $this->create_request_url( $base_url, $path, $params );
        $response    = wp_remote_post( $request_url );

        if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
            return json_decode( wp_remote_retrieve_body( $response ) );
        }

        return false;
    }

    /**
     * Post initiative to api.
     *
     * @param array $params
     *
     * @return mixed|bool
     */
    public function post_initiative( array $params = [] ) {
        return $this->post( 'requests.json', $params );
    }

    /**
     * Get initiatives by id.
     *
     * @param array $service_request_ids Array of service request ids.
     *
     * @return array|null
     */
    public function get_initiatives_by_id( array $service_request_ids = [] ) {
        if ( empty( $service_request_ids ) ) {
            return null;
        }

        $args = [
            'extensions'         => 'true',
            'service_request_id' => implode( ',', $service_request_ids ),
        ];

        return $this->get( 'requests.json', $args );
    }
}
