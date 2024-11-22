<?php
/**
 * Define the ArchiveInitiatives
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use \Geniem\Theme\Traits;
use \Geniem\Theme\Taxonomy;
use \Geniem\Theme\PostType\Initiative;

/**
 * ArchiveInitiatives class.
 */
class ArchiveInitiatives implements Model {

    use Traits\Breadcrumbs;
    use Traits\FlexibleContent;

    /**
     * This defines the name of this model.
     */
    const NAME = 'ArchiveInitiatives';

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {
        \add_filter( 'pre_get_posts', \Closure::fromCallable( [ $this, 'modify_query' ] ) );
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
     * @return array Array of classes.
     */
    public function add_classes_to_html( $classes ) : array {
        return $classes;
    }

    /**
     * Get initiatives.
     *
     * @return array
     */
    public function get_initiatives() : array {
        global $wp_query;

        if ( ! $wp_query->have_posts() ) {
            return [];
        }

        $data['initiatives'] = $wp_query->posts;

        return $this->initiative_list( $data );
    }

    /**
     * Modify query.
     *
     * @param \WP_Query $query The query to modify.
     *
     * @return void
     */
    private function modify_query( \WP_Query $query ) : void {

        // bail early if in admin  or not archive
        if ( is_admin() || ( ! $query->is_main_query() || ! is_post_type_archive( Initiative::SLUG ) ) ) {
            return;
        }
        $query->set( 'posts_per_page', 12 );
    }

}
