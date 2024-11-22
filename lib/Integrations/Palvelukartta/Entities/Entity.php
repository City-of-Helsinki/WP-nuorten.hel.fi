<?php
/**
 * Entity
 */

namespace Geniem\Theme\Integrations\Palvelukartta\Entities;

use Geniem\Theme\Localization;

/**
 * Class Entity
 *
 * @package Geniem\Theme\Integrations\LinkedEvents\Entities
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

    /**
     * Get key by language
     *
     * @param string $key Event object key.
     *
     * @return string|null
     */
    protected function get_key_by_language( string $key ) {
        $current_language = Localization::get_current_language();
        $default_language = Localization::get_default_language();

        $keys = [
            $key . '_' . $current_language,
            $key . '_' . $default_language,
        ];

        foreach ( $keys as $lang_key ) {
            if ( isset( $this->entity_data->{$lang_key} ) ) {
                return $this->entity_data->{$lang_key};
            }
        }

        return null;
    }
}
