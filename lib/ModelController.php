<?php
/**
 * Theme controller. This class initializes other classes
 * related to theme functionality.
 */

namespace Geniem\Theme;

/**
 * Class ModelController
 *
 * This class sets up the theme functionalities.
 *
 * @package Geniem\Theme
 */
class ModelController {

    /**
     * The controller instance
     *
     * @var self|null
     */
    private static $instance = null;

    /**
     * The class instances
     *
     * @var array
     */
    private $classes = [];

    /**
     * Get the ModelController
     *
     * @return \Geniem\Theme\ModelController
     */
    public static function instance() {
        if ( ! static::$instance ) {
            static::$instance = new self();
        }

        return static::$instance;
    }

    /**
     * Constructor
     */
    public function __construct() {
        $this->init_classes();
    }

    /**
     * Get a single class instance from Theme Controller
     *
     * @param string|null $class Class name to retrieve. See init_classes().
     *
     * @return Interfaces\Model|null
     */
    public function get_class( ?string $class ) : ?Interfaces\Model {
        return $this->classes[ $class ] ?? null;
    }

    /**
     * Run setup for theme functionality.
     *
     * @return void
     */
    private function init_classes() {

        $instances = array_map(
            function( $field_class ) {

                $field_class = basename( $field_class, '.' . pathinfo( $field_class )['extension'] );
                $class_name  = __NAMESPACE__ . '\Model\\' . $field_class;

                // Bail early if the class does not exist for some reason
                if ( ! \class_exists( $class_name ) ) {
                    return null;
                }

                return new $class_name();
            },
            array_diff( scandir( __DIR__ . '/Model' ), [ '.', '..' ] )
        );

        $instances = \apply_filters( 'nuhe_modify_instances_array', $instances );

        foreach ( $instances as $instance ) {
            if ( $instance instanceof Interfaces\Model ) {
                $instance->hooks();

                $this->classes[ $instance->get_name() ] = $instance;
            }
        }
    }
}
