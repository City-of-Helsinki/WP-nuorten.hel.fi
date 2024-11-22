<?php
/**
 * This file contains the model interface
 */

namespace Geniem\Theme\Interfaces;

interface Model {

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void;

    /**
     * Get class name constant
     *
     * @return string Class name constant
     */
    public function get_name() : string;

    /**
     * Add classes to html.
     *
     * @param array $classes Array of classes.
     * @return array Array of classes.
     */
    public function add_classes_to_html( $classes ) : array;

}
