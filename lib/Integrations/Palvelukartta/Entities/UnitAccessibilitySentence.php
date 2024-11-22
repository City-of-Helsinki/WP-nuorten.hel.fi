<?php
/**
 * Unit Accessibility Sentence
 */

namespace Geniem\Theme\Integrations\Palvelukartta\Entities;

/**
 * Class UnitAccessibilitySentence
 *
 * @package Geniem\Theme\Integrations\Palvelukartta\Entities
 */
class UnitAccessibilitySentence extends Entity {

    /**
     * Get group name
     *
     * @return string|null
     */
    public function get_group_name() {
        return $this->entity_data->sentence_group_name ?? null;
    }

    /**
     * Get group
     *
     * @return string|null
     */
    public function get_group() {
        return $this->get_key_by_language( 'sentence_group' );
    }

    /**
     * Get sentence
     *
     * @return string|null
     */
    public function get_sentence() {
        return $this->get_key_by_language( 'sentence' );
    }
}
