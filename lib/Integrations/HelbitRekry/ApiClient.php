<?php
/**
 * ApiClient
 */

namespace Geniem\Theme\Integrations\HelbitRekry;

use Geniem\Theme\Integrations\HelbitRekry\JobAdvertisement;
use Geniem\Theme\Localization;
use \Geniem\Theme\Settings;

/**
 * Class ApiClient
 *
 * @package Geniem\Theme\Integrations\HelbitRekry
 */
class ApiClient {

    /**
     * Cache group key.
     */
    const CACHE_GROUP = 'helbit-rekry';

    /**
     * Get API base url
     *
     * @return string
     */
    protected function get_base_url() : string {
        $client_id = HELSINKIREKRY_CLIENT_ID ?? '';

        // Bail early. Request without client_id makes unnecessary error log in their end.
        if( empty( $client_id ) ) {
            return "";
        }

        return "https://helbit.fi/portal-api/recruitment/v2.2/open-jobs?client={$client_id}";
    }

    /**
     * Create request url.
     *
     * @param string       $base_url Request base url.
     * @param string|array $path     Request path.
     * @param array        $params   Request parameters.
     *
     * @return string Request url
     */
    protected function create_request_url( string $base_url, $path, array $params ) : string {
        if ( ! empty( $path ) ) {
            if ( is_array( $path ) ) {
                $path = trailingslashit( implode( '/', $path ) );
            }

            $path = trailingslashit( $path );

            if ( empty( $params ) ) {
                $path = trailingslashit( $path );
            }
        }

        return add_query_arg(
            $params,
            sprintf(
                '%s',
                $base_url,
                $path
            )
        );
    }

    /**
     * Do an API request
     *
     * @param string|array $path   Request path.
     * @param array        $params Request parameters.
     *
     * @return bool|mixed
     */
    public function get( $path, array $params = [] ) {
        $base_url = $this->get_base_url();

        if ( empty( $base_url ) ) {
            return false;
        }

        $request_url = $this->create_request_url( $base_url, $path, $params );
        $response    = wp_remote_get( $request_url );

        if ( 200 === wp_remote_retrieve_response_code( $response ) ) {
            return json_decode( wp_remote_retrieve_body( $response ) );
        }

        return false;
    }

    /**
     * Get jobs
     *
     * @param array $params Job advertisement params.
     * @return JobAdvertisement[]|array
     */
    public function get_jobs( array $params = [] ) {
        $default_params = [
            'lang' => Localization::get_current_locale(),
        ];

        $params = array_merge( $default_params, $params );

        $cache_key = sprintf(
            'jobAdvertisements-%s',
            md5( wp_json_encode( $params ) )
        );
        $response  = wp_cache_get( $cache_key, static::CACHE_GROUP );

        if ( $response ) {
            return $response;
        }

        $jobs     = [];
        $response = $this->get( '', $params );

        if ( $response && ! empty( $response->jobAdvertisements ) ) {
            $jobs = array_map(
                fn( $job ) => new JobAdvertisement( $job->jobAdvertisement, $job->link ),
                $response->jobAdvertisements
            );

            $jobs = $this->filter_jobs( $jobs );

            wp_cache_set( $cache_key, $jobs, static::CACHE_GROUP, HOUR_IN_SECONDS * 2 );
        }

        return $jobs;
    }

    /**
     * Filter jobs.
     *
     * @param array $jobs Array of jobs from api.
     */
    private function filter_jobs( array $jobs ) : array {
        if ( empty( $jobs ) ) {
            return $jobs;
        }

        $filters = $this->get_employment_filters();

        if ( empty( $filters ) ) {
            return $jobs;
        }

        return array_filter(
            $jobs,
            fn( $job ) => array_key_exists( $job->get_employment(), $filters )
        );
    }

    /**
     * Get employment filters.
     *
     * @return array
     */
    private function get_employment_filters() : array {
        $filters = Settings::get_setting( 'employments' ) ?: [];

        $ret_filters = [];

        foreach ( $filters as $filter ) {
            if ( ! empty( $filter['employment'] ) ) {
                $ret_filters[ $filter['employment'] ] = $filter['employment'];
            }
        }

        return $ret_filters;
    }
}
