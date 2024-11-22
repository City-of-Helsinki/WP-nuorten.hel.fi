<?php
/**
 * Define the PageHelbitJobs class.
 */

namespace Geniem\Theme\Model;

use \Geniem\Theme\Interfaces\Model;
use Geniem\Theme\Traits;
use \Geniem\Theme\Integrations\HelbitRekry\ApiClient;
use Geniem\Theme\Settings;

/**
 * PageHelbitJobs class
 */
class PageHelbitJobs implements Model {

    use Traits\Breadcrumbs;

    /**
     * This defines the name of this model.
     */
    const NAME = 'PageHelbitJobs';

    /**
     * This defines the template of this model.
     */
    const TEMPLATE = 'page-templates/page-helbit-jobs.php';

    /**
     * Instance of ApiClient
     *
     * @var ApiClient
     */
    private ApiClient $api;

    /**
     * PageEvent constructor.
     */
    public function __construct() {
        $this->api = new ApiClient();
    }

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
     * Get jobs from api.
     *
     * @return array
     */
    public function get_jobs() : array {
        return $this->api->get_jobs();
    }

    /**
     * Get custom background color from settings.
     *
     * @return array
     */
    public function get_background_color() : string {
        return Settings::get_setting( 'background_color' ) ?: '';
    }
}
