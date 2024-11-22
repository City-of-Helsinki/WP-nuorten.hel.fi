<?php
/**
 * Entity
 */

namespace Geniem\Theme\Integrations\Respa\Entities;

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
        $this->entity_data = $entity_data->node;
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

        $lang_keys = [
            $current_language,
            $default_language,
        ];

        foreach ( $lang_keys as $lang_key ) {
            if ( isset( $this->entity_data->{ $key . ucfirst( $lang_key ) } ) ) {
                return $this->entity_data->{ $key . ucfirst( $lang_key ) };
            }
        }

        return null;
    }
}
