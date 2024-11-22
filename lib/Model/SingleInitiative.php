<?php
/**
 * Define the single initiative post class.
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use Geniem\Theme\Traits;
use Geniem\Theme\PostType\Initiative;

/**
 * SingleInitiative class
 */
class SingleInitiative implements Model {

    use Traits\Breadcrumbs;
    use Traits\Initiative;

    /**
     * This defines the name of this model.
     */
    const NAME = 'SingleInitiative';

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {
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
     * Alter title.
     *
     * @param string $title Title.
     * @return string
     */
    protected function alter_title( string $title ) : string {
        if ( get_post_type() !== Initiative::SLUG || is_post_type_archive( Initiative::SLUG ) ) {
            return $title;
        }

        $title = _x( 'Initiative', 'theme CPT Singular Name', 'nuhe' ) . ' - ' . get_the_title();

        $status = $this->get_status();

        return empty( $status ) ? $title : $title . ' - ' . $status['text'];

    }
}
