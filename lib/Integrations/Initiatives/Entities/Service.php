<?php
/**
 * Initiative service (service request type, sort of a category).
 */

namespace Geniem\Theme\Integrations\Initiatives\Entities;

/**
 * Class Service
 *
 * @package Geniem\Theme\Integrations\Initiatives\Entities
 */
class Service extends Entity {

    /**
     * Get service code.
     *
     * @return string
     */
    public function get_code() {
        return $this->entity_data->service_code;
    }

    /**
     * Get service name.
     *
     * @return string
     */
    public function get_name() {
        return $this->entity_data->service_name;
    }

    /**
     * Get service description.
     *
     * @return string
     */
    public function get_description() {
        return $this->entity_data->description;
    }
}
