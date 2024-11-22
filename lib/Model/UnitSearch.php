<?php
/**
 * Define the UnitSearch class.
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use Geniem\Theme\PostType;
use Geniem\Theme\Taxonomy\YouthCenterKeyword;
use Geniem\Theme\Taxonomy\SchoolKeyword;
use Geniem\Theme\Traits;

/**
 * UnitSearch class
 */
class UnitSearch implements Model {

    use Traits\Breadcrumbs;

    /**
     * This defines the name of this model.
     */
    const NAME = 'UnitSearch';

    /**
     * Template
     */
    const TEMPLATE = 'page-templates/page-unit-search.php';

    /**
     * This holds the unit type and taxonomy.
     *
     * @var array $unit_settings
     */
    private $unit_settings;

    /**
     * UnitSearch constructor.
     */
    public function __construct() {
        \add_filter(
            'nuhe_modify_localized_data',
            \Closure::fromCallable( [ $this, 'localize_data' ] )
        );

        $this->set_unit_settings();
    }

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {
        \add_filter( 'html_classes', [ $this, 'add_classes_to_html' ] );
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
        if ( is_page_template( static::TEMPLATE ) ) {
            $classes[] = $this->get_name();
        }

        return $classes;
    }

    /**
     * Set unit settings
     *
     * @return void
     */
    private function set_unit_settings() : void {
        if ( ! function_exists( 'get_field' ) ) {
            return;
        }

        $post_type = get_field( 'unit_post_type' ) ?? PostType\YouthCenter::SLUG;
        $taxonomy  = $post_type === PostType\YouthCenter::SLUG ? YouthCenterKeyword::SLUG : SchoolKeyword::SLUG;

        $latitude_field_name  = $post_type === PostType\YouthCenter::SLUG ? 'latitude' : 'school_latitude';
        $longitude_field_name = $post_type === PostType\YouthCenter::SLUG ? 'longitude' : 'school_longitude';

        $this->unit_settings = [
            'post_type' => $post_type,
            'taxonomy'  => $taxonomy,
            'latitude'  => $latitude_field_name,
            'longitude' => $longitude_field_name,
        ];
    }

    /**
     * Get search suggestions
     *
     * @return array
     */
    public function get_search_filters() : array {
        $filters  = $this->get_active_filters();
        $terms    = get_terms( [ 'taxonomy' => $this->unit_settings['taxonomy'] ] );
        $page_url = get_the_permalink();
        $view     = $this->is_map_view() ? 'map' : 'list';
        $keywords = [
            [
                'label'     => __( 'All', 'nuhe' ),
                'value'     => false,
                'is_active' => empty( $filters ),
                'url'       => add_query_arg( 'view', $view, $page_url ),
            ],
        ];

        if ( empty( $terms ) ) {
            return $keywords;
        }

        foreach ( $terms as $term ) {
            $keywords[] = [
                'label'     => $term->name,
                'value'     => $term->term_id,
                'is_active' => in_array( $term->term_id, $filters, true ),
                'url'       => add_query_arg(
                    [
                        'unit-filter[]' => $term->term_id,
                        'view'          => $view,
                    ],
                    $page_url
                ),
            ];
        }

        return $keywords;
    }

    /**
     * Get active filters
     *
     * @return array
     */
    public function get_active_filters() : array {
        $filters = get_query_var( 'unit-filter', [] );

        if ( ! empty( $filters ) ) {
            $filters = array_map( 'absint', $filters );
        }

        return $filters;
    }

    /**
     * Get youth center posts
     *
     * @return array
     */
    public function get_posts() : array {
        $args = [
            'post_type'      => $this->unit_settings['post_type'],
            'posts_per_page' => 100,
            'order'          => 'ASC',
            'orderby'        => 'post_title',
            'no_found_rows'  => true,
        ];

        $search_term = get_query_var( 'search-term', false );

        if ( ! empty( $search_term ) ) {
            $args['s'] = sanitize_text_field( $search_term );
        }

        $filters = $this->get_active_filters();

        if ( ! empty( $filters ) ) {
            $args['tax_query'] = [
                [
                    'taxonomy' => $this->unit_settings['taxonomy'],
                    'terms'    => $filters,
                ],
            ];
        }

        $the_query = new \WP_Query( $args );

        if ( ! $the_query->have_posts() ) {
            return [];
        }

        return array_map( function ( $item ) {
            $stripped = new \StdClass();

            $stripped->ID         = $item->ID;
            $stripped->background = get_field( 'hero_bg_color', $item->ID );
            $stripped->latitude   = get_field( $this->unit_settings['latitude'], $item->ID ) ?? false;
            $stripped->longitude  = get_field( $this->unit_settings['longitude'], $item->ID ) ?? false;
            $stripped->url        = get_the_permalink( $item->ID );
            $stripped->title      = get_the_title( $item->ID );

            return $stripped;
        }, $the_query->posts );
    }

    /**
     * View links
     *
     * @return array[]
     */
    public function view_links() {
        return [
            [
                'url'       => add_query_arg(
                    [
                        'view'        => 'list',
                        'unit-filter' => $this->get_active_filters(),
                        'search-term' => get_query_var( 'search-term', false ),
                    ],
                    get_the_permalink()
                ),
                'is_active' => ! $this->is_map_view(),
                'label'     => __( 'Show as list', 'nuhe' ),
            ],
            [
                'url'       => add_query_arg(
                    [
                        'view'        => 'map',
                        'unit-filter' => $this->get_active_filters(),
                        'search-term' => get_query_var( 'search-term', false ),
                    ],
                    get_the_permalink()
                ),
                'is_active' => $this->is_map_view(),
                'label'     => __( 'Show on map', 'nuhe' ),
            ],
        ];
    }

    /**
     * Is map view
     *
     * @return bool
     */
    public function is_map_view() : bool {
        return get_query_var( 'view', false ) === 'map';
    }

    /**
     * Form action link
     *
     * @return false|string|\WP_Error
     */
    public function get_form_action() {
        return get_the_permalink();
    }

    /**
     * Localize data for JS use.
     *
     * @param array $localized_data Localized data.
     *
     * @return array
     */
    private function localize_data( array $localized_data ) : array {
        if ( $this->is_map_view() ) {
            $localized_data['units'] = $this->get_posts();
        }

        return $localized_data;
    }
}
