<?php
/**
 * Define the Tag class.
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use \Geniem\Theme\Traits;
use \Geniem\Theme\PostType;
use \Geniem\Theme\Taxonomy;

/**
 * Tag class.
 */
class Tag implements Model {

    use Traits\Breadcrumbs;
    use Traits\FlexibleContent;

    /**
     * This defines the name of this model.
     */
    const NAME = 'Tag';

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {
        \add_filter( 'pre_get_posts', \Closure::fromCallable( [ $this, 'modify_query' ] ) );
        \add_filter(
            'the_seo_framework_title_from_generation',
            \Closure::fromCallable( [ $this, 'alter_title' ] )
        );

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
     * Get posts.
     *
     * @return array
     */
    public function get_posts() : array {
        global $wp_query;

        if ( ! $wp_query->have_posts() ) {
            return [];
        }

        $data['context']       = 'main-content';
        $data['use_container'] = true;
        $data['use_grid']      = true;
        $data['articles']      = $wp_query->posts;

        return $this->article_list( $data, true );
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
        if ( is_admin() || ( ! $query->is_main_query() || ! $query->is_tag() ) ) {
            return;
        }
        $query->set( 'posts_per_page', -1 );
        $query->set( 'post_type', PostType\Post::SLUG );
    }


    /**
     * Alter title.
     *
     * @param string $title Title.
     * @return string
     */
    protected function alter_title( string $title ) : string {
        if ( ! is_tag() || \get_queried_object()->taxonomy !== Taxonomy\PostTag::SLUG ) {
            return $title;
        }

        $term_name = \get_queried_object()->name;

        return __( 'Keyword', 'nuhe' ) . ': ' . $term_name;
    }
}
