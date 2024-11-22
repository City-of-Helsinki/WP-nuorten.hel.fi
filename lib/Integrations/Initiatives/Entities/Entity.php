<?php
/**
 * Entity
 */

namespace Geniem\Theme\Integrations\Initiatives\Entities;

use Geniem\Theme\Localization;

/**
 * Class Entity
 *
 * @package Geniem\Theme\Integrations\Initiatives/Entities
 */
class Entity {

    /**
     * Entity data
     *
     * @var mixed
     */
    protected $entity_data;

    /**
     * Entity constructor.
     *
     * @param mixed $entity_data Entity data.
     */
    public function __construct( $entity_data ) {
        $this->entity_data = $entity_data;
    }
}
