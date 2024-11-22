<?php
/**
 * ApiClient
 */

namespace Geniem\Theme\Integrations\LinkedEvents;

use Geniem\Theme\Integrations\LinkedEvents\Entities\Keyword;
use Geniem\Theme\Settings;
use Geniem\Theme\Integrations\LinkedEvents\Entities\Event;
use Geniem\Theme\Integrations\Common\ApiBaseClient;
use Geniem\Theme\Localization;

/**
 * Class ApiClient
 *
 * @package Geniem\Theme\Integrations\LinkedEvents
 */
class ApiClient extends ApiBaseClient {

    /**
     * Cache group key.
     */
    const CACHE_GROUP = 'linked-events';

    /**
     * Get API base url
     *
     * @return string
     */
    protected function get_base_url() : string {
        return 'https://api.hel.fi/linkedevents/v1';
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

    /**
     * Get event by id
     *
     * @param string $id Event Id.
     *
     * @return mixed|bool|string
     */
    public function get_event_by_id( string $id ) {
        $cache_key = sprintf( 'event-%s', $id );
        $response  = wp_cache_get( $cache_key, static::CACHE_GROUP );

        if ( $response ) {
            return $response;
        }

        $response = $this->get( [ 'event', $id ], [ 'include' => 'organizer,location,keywords' ] );

        if ( $response ) {
            $event = new Event( $response );

            wp_cache_set( $cache_key, $event, static::CACHE_GROUP, HOUR_IN_SECONDS * 2 );

            return $event;
        }

        return false;
    }

    /**
     * Get events
     *
     * @param array    $params Event search params.
     * @param bool|int $limit  Event limit.
     *
     * @return Event[]|bool
     */
    public function get_events( array $params = [], $limit = false ) {
        $start = new \DateTime();

        $default_params = [
            'include'     => 'organizer,location,keywords',
            'super_event' => 'none',
            'sort'        => 'start_time',
            'start'       => $start->format( 'Y-m-d' ),
            'publisher'   => 'ahjo:u48040030', //Nuorisopalvelukokonaisuus
        ];

        $params = array_merge( $default_params, $params );

        if ( ! empty( $params['disable_youth_service_restriction'] ) ) {            
            unset( $params['disable_youth_service_restriction'] );
            unset( $params['publisher'] );
        }

        $cache_key = sprintf(
            'events-%s-%s',
            md5( wp_json_encode( $params ) ),
            $limit
        );
        $response  = wp_cache_get( $cache_key, static::CACHE_GROUP );

        if ( $response ) {
            return $response;
        }

        $response = $this->get( 'event', $params );

        if ( $response && ! empty( $response->data ) ) {
            $events = array_map( fn( $event ) => new Event( $event ), $response->data );
            $events = $this->filter_by_location( $events );

            if ( $limit ) {
                $events = array_slice( $events, 0, $limit );
            }

            wp_cache_set( $cache_key, $events, static::CACHE_GROUP, HOUR_IN_SECONDS * 2 );
        }

        return $events ?? false;
    }

    /**
     * Get events
     *
     * @param array    $params Event search params.
     * @param bool|int $limit  Event limit.
     *
     * @return Event[]|bool
     */
    public function get_nuta_events( array $params = [], $limit = false ) {
        $start = new \DateTime();

        $default_params = [
            'include'     => 'organizer,location,keywords',
            'super_event' => 'none',
            'sort'        => 'start_time',
            'start'       => $start->format( 'Y-m-d' ), // enable this after testing
            'event_type'  => 'General,Course',
        ];

        $params = array_merge( $default_params, $params );

        $cache_key = sprintf(
            'events-nuta-%s-%s',
            md5( wp_json_encode( $params ) ),
            $limit
        );
        $response  = wp_cache_get( $cache_key, static::CACHE_GROUP );

        if ( $response ) {
            return $response;
        }

        $response = $this->get( 'event', $params );

        if ( $response && ! empty( $response->data ) ) {
            $events = array_map( fn( $event ) => new Event( $event ), $response->data );

            if ( $limit ) {
                $events = array_slice( $events, 0, $limit );
            }

            wp_cache_set( $cache_key, $events, static::CACHE_GROUP, HOUR_IN_SECONDS * 2 );
        }

        return $events ?? false;
    }

    /**
     * Filter by location
     *
     * @param Event[] $events Events.
     *
     * @return array
     */
    public function filter_by_location( array $events = [] ) {
        return array_filter( $events, function ( $event ) {
            $location = $event->get_location();

            return in_array( $location->get_address_locality(), [ 'Helsinki', 'Helsingfors' ], true );
        } );
    }

    /**
     * Search events
     * Results are limited by default keyword
     *
     * @param string $text  Search query.
     * @param int    $limit Result limit, defaults to 6.
     *
     * @return array|Event[]
     */
    public function search( string $text = '', int $limit = 6 ) : array {
        if ( empty( $text ) ) {
            return [];
        }

        $params = [
            'text'     => $text,
            'language' => Localization::get_current_language(),
        ];

        $cache_key = sprintf(
            'event-search-%s',
            md5( wp_json_encode( $params ) ),
        );
        $response  = wp_cache_get( $cache_key, static::CACHE_GROUP );

        if ( $response ) {
            return $response;
        }

        $events = $this->get_events( $params, $limit );

        $events = $events ? $events : [];

        wp_cache_set( $cache_key, $events, static::CACHE_GROUP, HOUR_IN_SECONDS * 2 );

        return $events;
    }

    /**
     * Get all keywords
     *
     * @return Keyword[]|false
     */
    public function get_all_keywords() {
        return [];
        $cache_key = 'all-keywords';
        $keywords  = wp_cache_get( $cache_key, static::CACHE_GROUP );

        if ( $keywords ) {
            return $keywords;
        }

        $keywords = $this->do_get_all_keywords();

        if ( $keywords ) {
            wp_cache_set( $cache_key, $keywords, static::CACHE_GROUP, HOUR_IN_SECONDS );
        }

        return $keywords;
    }

    /**
     * Get all keywords
     *
     * @param string $next_url Next url.
     * @param array  $keywords Array of keywords.
     *
     * @return Keyword[]|false
     */
    protected function do_get_all_keywords( $next_url = '', $keywords = [] ) {
        if ( empty( $next_url ) ) {
            $response = $this->get( 'keyword' );
        }
        else {
            $response = $this->next( $next_url );
        }

        if ( $response && ! empty( $response->data ) ) {
            foreach ( $response->data as $data ) {
                $keywords[] = new Keyword( $data );
            }

            if ( ! empty( $response->meta->next ) ) {
                $keywords = $this->do_get_all_keywords(
                    $response->meta->next,
                    $keywords
                );
            }
        }

        return $keywords;
    }
}
