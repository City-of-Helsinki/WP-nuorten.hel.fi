<?php
/**
 * Entity
 */

namespace Geniem\Theme\Integrations\LinkedEvents\Entities;

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
     * Get key by language.
     *
     * @param string      $key         Event object key.
     * @param bool|object $entity_data Entity data.
     * @param bool        $allow_empty Skip empty values, use default language.
     *
     * @return string|null
     */
    protected function get_key_by_language( string $key, $entity_data = false, $allow_empty = true ) {
        $current_language = Localization::get_current_language();

        if ( ! $entity_data ) {
            $entity_data = $this->entity_data;
        }

        if ( isset( $entity_data->{$key} ) ) {
            if ( isset( $entity_data->{$key}->{$current_language} ) ) {
                $value = $entity_data->{$key}->{$current_language};

                if ( ! $allow_empty && empty( $value ) ) {
                    return $this->get_key_default_language( $key );
                }

                return $value;
            }

            return $this->get_key_default_language( $key );
        }

        return null;
    }

    /**
     * Get key by default language.
     *
     * @param string      $key         Event object key.
     * @param bool|object $entity_data Entity data.
     * @return string|null
     */
    protected function get_key_default_language( $key, $entity_data = false ) {
        $default_language = Localization::get_default_language();

        if ( ! $entity_data ) {
            $entity_data = $this->entity_data;
        }

        if ( isset( $entity_data->{$key}->{$default_language} ) ) {
            return $entity_data->{$key}->{$default_language};
        }

        return null;
    }
}
