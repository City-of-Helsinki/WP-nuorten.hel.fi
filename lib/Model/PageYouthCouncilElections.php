<?php
/**
 * Define the PageYouthCouncilElections class.
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use Geniem\Theme\PostType\YouthCouncilElections;
use Geniem\Theme\Traits;

/**
 * PageYouthCouncilElections class
 */
class PageYouthCouncilElections implements Model {

    use Traits\Breadcrumbs;
    use Traits\FeaturedImage;

    /**
     * This defines the name of this model.
     */
    const NAME = 'PageYouthCouncilElections';

    /**
     * Template
     */
    const TEMPLATE = 'page-templates/page-youth-council-elections.php';

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
     * Get youth center posts
     *
     * @return array
     */
    public function get_posts() : array {
        $args = [
            'post_type'      => YouthCouncilElections::SLUG,
            'posts_per_page' => 100,
            'order'          => 'ASC',
            'orderby'        => 'meta_value_num',
            'meta_key'       => 'candidate_number',
        ];

        $search_term = $this->get_search_term();

        if ( ! empty( $search_term ) ) {
            $args['s'] = $search_term;
        }

        $the_query = new \WP_Query( $args );

        if ( ! $the_query->have_posts() ) {
            return [];
        }

        return array_map( function ( $item ) {
            $stripped = new \StdClass();

            $stripped->candidate_number = get_field( 'candidate_number', $item->ID );
            $stripped->age              = get_field( 'age', $item->ID );
            $stripped->postal_code      = get_field( 'postal_code', $item->ID );
            $stripped->slogan           = get_field( 'slogan', $item->ID );
            $stripped->url              = get_the_permalink( $item->ID );
            $stripped->title            = get_the_title( $item->ID );
            $stripped->image            = $this->get_featured_image( 'large', $item->ID )['id'];

            return $stripped;
        }, $the_query->posts );
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
     * Get search term
     *
     * @return string|null
     */
    public function get_search_term() {
        $search_term = get_query_var( 'search-term', false );

        if ( ! empty( $search_term ) ) {
            return sanitize_text_field( $search_term );
        }

        return null;
    }
}
