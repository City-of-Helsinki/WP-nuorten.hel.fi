<?php
/**
 * Unit Connections
 */

namespace Geniem\Theme\Integrations\Palvelukartta\Entities;

/**
 * Class UnitConnection
 *
 * @package Geniem\Theme\Integrations\Palvelukartta\Entities
 */
class UnitConnection extends Entity {

    /**
     * Get section type
     *
     * @return string|null
     */
    public function get_section_type() {
        return $this->entity_data->section_type ?? null;
    }

    /**
     * Get name
     *
     * @return string|null
     */
    public function get_name() {
        return $this->entity_data->name ?? null;
    }

    /**
     * Get www
     *
     * @return string|null
     */
    public function get_www() {
        return $this->entity_data->www ?? null;
    }


}
