<?php
/**
 * Define the Search class.
 */

namespace Geniem\Theme\Model;

use Geniem\Theme\Integrations\LinkedEvents\ApiClient;
use \Geniem\Theme\Interfaces\Model;
use \Geniem\Theme\Localization;
use Geniem\Theme\PostType\HighSchool;
use Geniem\Theme\PostType\Post;
use Geniem\Theme\PostType\YouthCenter;
use Geniem\Theme\PostType\Page;
use Geniem\Theme\PostType\VocationalSchool;
use \Geniem\Theme\Settings;
use \Geniem\Theme\Traits;

/**
 * Search class
 */
class Search implements Model {

    use Traits\FeaturedImage;

    /**
     * This defines the name of this model.
     */
    const NAME = 'Search';

    /**
     * Cache group key for core content
     */
    const CACHE_KEY_CORE = 'main-search-core';

    /**
     * Cache group key for external content
     */
    const CACHE_KEY_EXTERNAL = 'main-search-external';

    /**
     * Has search results
     *
     * @var bool $has_search_results
     */
    private $has_search_results = false;

    /**
     * Search results types
     *
     * @var array $search_types
     */
    private $search_types = [];

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {
        \add_filter( 'html_classes', [ $this, 'add_classes_to_html' ] );
        \add_filter( 'the_seo_framework_title_from_generation', [ $this, 'modify_html_title' ] );
    }

    /**
     * Get class name constant
     *
     * @return string Class name constant
     */
    public function get_name() : string {
        return self::NAME;
    }

    /**
     * Add classes to html.
     *
     * @param array $classes Array of classes.
     *
     * @return array Array of classes.
     */
    public function add_classes_to_html( $classes ) : array {
        if ( is_search() ) {
            $classes[] = $this->get_name();
        }

        return $classes;
    }

    /**
     * Modify html title.
     *
     * @param string $title Title of search page.
     *
     * @return string Modified title.
     */
    public function modify_html_title( $title ) : string {
        if ( ! is_search() ) {
            return $title;
        }

        $core_results = $this->get_results();
        $ext_results  = $this->get_external_results( [ 'ext-event' ] );

        $merged = array_merge( $core_results, $ext_results );

        $total_results = 0;
        if ( ! empty( $merged ) ) {
            foreach ( $merged as $merge ) {
                $total_results += $merge['post_count'];
            }
        }

        if ( $total_results > 0 ) {
            $this->has_search_results = true;
        }

        $result_slugs = $this->get_results_slugs()['all_search_results'];
        $title        = $title . ', ' . sprintf(
            // translators:
            __( '%1$s: %2$s', 'nuhe' ),
            $total_results,
            _n( $result_slugs[0], $result_slugs[1], $total_results, 'nuhe' )  // phpcs:ignore
        );

        $active_search_type       = $this->get_active_search_type();
        $active_search_type_label = array_filter( $this->get_search_types(), function ( $type ) use ( $active_search_type ) {
            return $active_search_type === $type['type'];
        } );

        if ( empty( $active_search_type_label ) ) {
            return $title;
        }

        $active_search_type_label = array_values( $active_search_type_label );

        return $title . ', ' . __( 'results by filter', 'nuhe' ) . ': ' . $active_search_type_label[0]['title'];
    }

    /**
     * Get results slugs.
     *
     * @return array Array of result slugs.
     */
    private function get_results_slugs() : array {
        return apply_filters( 'nuhe_modify_search_results_slugs', [
            YouthCenter::SLUG => [
                _x( 'youth center', 'youth center in search results', 'nuhe' ),
                _x( 'youth centers', 'youth centers in search results', 'nuhe' ),
            ],
            Page::SLUG => [
                _x( 'page', 'page in search results', 'nuhe' ),
                _x( 'pages', 'pages in search results', 'nuhe' ),
            ],
            Post::SLUG => [
                _x( 'article', 'article in search results', 'nuhe' ),
                _x( 'articles', 'articles in search results', 'nuhe' ),
            ],
            HighSchool::SLUG => [
                _x( 'high school', 'high school in search results', 'nuhe' ),
                _x( 'high schools', 'high schools in search results', 'nuhe' ),
            ],
            VocationalSchool::SLUG => [
                _x( 'vocational school', 'vocational school in search results', 'nuhe' ),
                _x( 'vocational schools', 'vocational schools in search results', 'nuhe' ),
            ],
            'ext-event' => [
                _x( 'event', 'event in search results', 'nuhe' ),
                _x( 'events', 'events in search results', 'nuhe' ),
            ],
            'ext-course' => [
                _x( 'course', 'course in search results', 'nuhe' ),
                _x( 'courses', 'courses in search results', 'nuhe' ),
            ],
            'all_search_results' => [
                _x( 'search result', 'search result', 'nuhe' ),
                _x( 'search results', 'search results', 'nuhe' ),
            ],
        ] );
    }

    /**
     * Get search action
     *
     * @return string
     */
    public function get_search_action() : string {
        return ( DPT_PLL_ACTIVE )
            ? pll_home_url()
            : home_url();
    }

    /**
     * Set search types
     *
     * @return array Array of search types.
     */
    private function set_search_types() : array {
        $search_types = Settings::get_setting( 'search_post_types' );

        if ( empty( $search_types ) ) {
            return [];
        }

        $search_query = get_search_query( true );
        $url          = $this->get_search_action();

        $search_type_all = [
            'value' => 'all',
            'label' => __( 'All', 'nuhe' ),
        ];

        array_unshift( $search_types, $search_type_all );

        $types = [];

        foreach ( $search_types as $search_type ) {
            $query_args = [
                'type' => $search_type['value'],
                's'    => $search_query,
            ];

            $is_active = $this->get_active_search_type() === $search_type['value'];

            $type_args = [
                'url'         => add_query_arg( [ $query_args ], $url ),
                'title'       => $search_type['label'],
                'type'        => $search_type['value'],
                'is_active'   => $is_active,
                'is_external' => ! post_type_exists( $search_type['value'] ) && $search_type['value'] !== 'all',
            ];

            if ( $is_active ) {
                $type_args['attributes'] = [
                    'aria-current' => 'page',
                ];
            }

            $types[] = $type_args;
        }

        $this->search_types = $types;

        return $types;
    }

    /**
     * Get search types
     *
     * @return array
     */
    public function get_search_types() : array {
        if ( ! empty( $this->search_types ) ) {
            return $this->search_types;
        }

        return $this->set_search_types();
    }

    /**
     * Get active search type
     *
     * @return bool|string
     */
    public function get_active_search_type() {
        $type = get_query_var( 'type', false );
        return ! empty( $type ) ? $type : 'all';
    }

    /**
     * Get search results
     *
     * @return array
     */
    public function get_results() : array {
        $post_type = $this->get_active_search_type();

        $cache_key = sprintf(
            'main-search-%1$s-%2$s-%3$s-%4$s',
            md5( static::CACHE_KEY_EXTERNAL ),
            get_search_query( true ),
            $post_type,
            Localization::get_current_language()
        );
        $response  = wp_cache_get( $cache_key, static::CACHE_KEY_CORE );

        if ( $response ) {
            return $response;
        }

        $results_slugs = $this->get_results_slugs();
        $search_types  = $this->get_search_types();

        $available_post_types = array_filter(
            $search_types,
            function( $search_type ) use ( $post_type ) {
                return post_type_exists( $search_type['type'] )
                && ( $post_type === $search_type['type']
                || $post_type === 'all' );
            }
        );

        if ( empty( $available_post_types ) ) {
            return [];
        }

        $post_types = array_map( function( $type ) {
            return $type['type'];
        }, $available_post_types );

        $posts = $this->get_post_type_posts( $post_types );

        $post_count = count( $posts );

        if ( $post_type === 'all' ) {
            $results = [
                'all_search_results' => [
                    'title'      => $this->get_search_query_string( $post_count, $results_slugs['all_search_results'] ),
                    'posts'      => $posts,
                    'post_count' => $post_count,
                ],
            ];
        }
        else {
            $results[ $post_type ] = [
                'title'      => $this->get_search_query_string( $post_count, $results_slugs[ $post_type ] ),
                'posts'      => $posts,
                'post_count' => $post_count,
            ];
        }

        $results = $this->get_results_in_chunks( $results, 9 );

        wp_cache_set( $cache_key, $results, static::CACHE_KEY_CORE, HOUR_IN_SECONDS );

        return $results;
    }

    /**
     * Get external search results
     *
     * @param array $ext_result_types Array of external result types to get.
     *
     * @return array
     */
    public function get_external_results( array $ext_result_types = [ 'ext-event', 'ext-course' ] ) : array {
        if ( empty( $ext_result_types ) ) {
            return [];
        }

        $post_type = $this->get_active_search_type();

        $cache_key = sprintf(
            'main-search-%1$s-%2$s-%3$s-%4$s',
            md5( static::CACHE_KEY_EXTERNAL ),
            get_search_query( true ),
            $post_type,
            Localization::get_current_language()
        );
        $response  = wp_cache_get( $cache_key, static::CACHE_KEY_EXTERNAL );

        if ( $response ) {
            return $response;
        }

        $ext_result_types = array_flip( $ext_result_types );
        $results_slugs    = $this->get_results_slugs();
        $search_types     = $this->get_search_types();

        $available_ext_result_types = array_filter(
            $search_types,
            function( $type ) use ( $post_type, $ext_result_types ) {
                return $type['is_external']
                && ( $post_type === $type['type'] || $post_type === 'all' )
                && array_key_exists( $type['type'], $ext_result_types );
            }
        );

        if ( empty( $available_ext_result_types ) ) {
            return [];
        }

        $results = [];

        foreach ( $available_ext_result_types as $ext_result_type ) {
            if ( $ext_result_type['type'] === 'ext-event' ) {
                $posts = $this->get_events(
                    get_search_query( true ),
                    100,
                );

                $post_count = count( $posts );
            }

            $results[ $ext_result_type['type'] ] = [
                'title'      => $this->get_search_query_string( $post_count, $results_slugs[ $ext_result_type['type'] ] ),
                'posts'      => $posts,
                'pt_tag'     => $results_slugs[ $ext_result_type['type'] ][0],
                'post_count' => $post_count,
            ];
        }

        $results = $this->get_results_in_chunks( $results, 9 );

        wp_cache_set( $cache_key, $results, static::CACHE_KEY_EXTERNAL, HOUR_IN_SECONDS );

        return $results;
    }

    /**
     * Get has search results
     *
     * @return bool
     */
    public function has_search_results() : bool {
        return $this->has_search_results;
    }

    /**
     * Get results in chunks
     *
     * @param array $results           Array of results.
     * @param int   $posts_per_chunk Posts per chunk.
     *
     * @return array
     */
    private function get_results_in_chunks( array $results, int $posts_per_chunk ) : array {
        foreach ( $results as $key => $result ) {
            $posts       = array_chunk( $result['posts'], $posts_per_chunk );
            $chunks      = count( $posts );
            $post_chunks = [];

            if ( $chunks !== 0 && ! $this->has_search_results ) {
                $this->has_search_results = true;
            }

            for ( $i = 0; $i < $chunks; $i ++ ) {
                $show_button = $posts_per_chunk === count( $posts[ $i ] ) && isset( $posts[ $i + 1 ] );

                $post_chunks[] = [
                    'posts'           => $posts[ $i ],
                    'show_button'     => $show_button,
                    'button_controls' => "group-{$key}-" . ( $i + 1 ),
                    'group'           => "group-{$key}-{$i}",
                    'group_class'     => $i > 0 ? ' is-hidden' : '',
                ];
            }

            $results[ $key ]['posts'] = $post_chunks;
        }

        return $results;
    }

    /**
     * Get post type posts
     *
     * @param array $post_types Array of post types.
     * @param array $args       \WP_Query additional args.
     *
     * @return array
     */
    private function get_post_type_posts( array $post_types, $args = [] ) {
        $default_args = [
            'post_type'      => $post_types,
            'post_status'    => 'publish',
            'order'          => 'DESC',
            'orderby'        => 'date',
            'posts_per_page' => 200,
            's'              => get_search_query( true ),
        ];

        $args      = array_merge( $args, $default_args );
        $the_query = new \WP_Query( $args );

        $cpt_tags = [];
        $slugs    = $this->get_results_slugs();

        foreach ( $post_types as $type ) {
            $cpt_tags[ $type ] = $slugs[ $type ][0];
        }

        return array_map( function ( $post ) use ( $cpt_tags ) {
            $post->cpt_tag = $cpt_tags[ $post->post_type ] ?? '';
            $post->image   = $post->post_type !== YouthCenter::SLUG
                            ? $this->get_featured_image( 'thumbnail', $post->ID )
                            : [];
            return $post;
        }, $the_query->posts );

    }

    /**
     * Get events
     *
     * @param string $search_query Search query.
     * @param int    $limit        Event limit.
     *
     * @return \Geniem\Theme\Integrations\LinkedEvents\Entities\Event[]
     */
    private function get_events( string $search_query = '', int $limit = 6 ) {
        $events_api = new ApiClient();

        return $events_api->search( $search_query, $limit );
    }

    /**
     * Get search query string.
     *
     * @param int   $count        Result count.
     * @param array $result_slugs Single and plural form of result.
     *
     * @return string
     */
    private function get_search_query_string( int $count, array $result_slugs ) : string {
        return sprintf(
            // translators:
            __( 'Your search "%1$s" returned %2$s %3$s', 'nuhe' ),
            get_search_query(),
            $count,
            _n( $result_slugs[0], $result_slugs[1], $count, 'nuhe' )  // phpcs:ignore
        );
    }
}
