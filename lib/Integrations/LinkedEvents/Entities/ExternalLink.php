<?php
/**
 * ExternalLink
 */

namespace Geniem\Theme\Integrations\LinkedEvents\Entities;

/**
 * Class ExternalLink
 *
 * @package Geniem\Theme\Integrations\LinkedEvents\Entities
 */
class ExternalLink extends Entity {

    /**
     * Get name
     *
     * @return mixed
     */
    public function get_name() {
        return $this->entity_data->name ?? null;
    }

    /**
     * Get link
     *
     * @return mixed
     */
    public function get_link() {
        return $this->entity_data->link ?? null;
    }

    /**
     * Get language
     *
     * @return mixed
     */
    public function get_language() {
        return $this->entity_data->language ?? null;
    }
}
