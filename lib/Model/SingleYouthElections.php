<?php
/**
 * Define the SingleYouthElections
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use \Geniem\Theme\Traits;

/**
 * SingleYouthElections class
 */
class SingleYouthElections implements Model {

    use Traits\Breadcrumbs;
    use Traits\FeaturedImage;

    /**
     * This defines the name of this model.
     */
    const NAME = 'SingleYouthElections';

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {}

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
     * Get candidate data.
     *
     * @param \WP_Post $post WP_Post object
     * 
     * @return object
     */
    public function get_candidate_data( \WP_Post $post ) {
        $candidate = new \StdClass();

        $candidate->candidate_number = get_field( 'candidate_number', $post->ID );
        $candidate->age              = get_field( 'age', $post->ID );
        $candidate->postal_code      = get_field( 'postal_code', $post->ID );
        $candidate->slogan           = get_field( 'slogan', $post->ID );
        $candidate->url              = get_the_permalink( $post->ID );
        $candidate->title            = get_the_title( $post->ID );
        $candidate->image            = $this->get_featured_image( 'large', $post->ID )['id'];
        $candidate->description      = get_field( 'description', $post->ID );
        $candidate->classes          = [ 'candidate--single' ];

        return $candidate;
    }
}
