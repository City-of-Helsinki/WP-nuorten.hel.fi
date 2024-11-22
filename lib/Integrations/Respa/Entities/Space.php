<?php
/**
 * Respa Space
 */

namespace Geniem\Theme\Integrations\Respa\Entities;

/**
 * Class Space
 *
 * @package Geniem\Theme\Integrations\Respa\Entities
 */
class Space extends Entity {

    /**
     * Get Id.
     *
     * @return int
     */
    public function get_id() {
        return $this->entity_data->pk;
    }

    /**
     * Get name
     *
     * @return string|null
     */
    public function get_name() {
        return $this->get_key_by_language( 'name' );
    }

    /**
     * Get image
     *
     * @return string|null
     */
    public function get_image() {
        return $this->entity_data->images[0]->largeUrl ?? null;
    }

    /**
     * Get url
     *
     * @return string|null
     */
    public function get_url() {
        $id = $this->get_id();
        return "https://tilavaraus.hel.fi/reservation-unit/{$id}";
    }

    /**
     * Get description
     *
     * @return string|null
     */
    public function get_description() {
        return $this->get_key_by_language( 'description' );
    }

    /**
     * Get price
     *
     * @return string|null
     */
    public function get_price() {
        if ( $this->entity_data->pricings[0]->pricingType === 'FREE' ) {
            return \__( 'Free of charge', 'nuhe' );
        }

        return sprintf(
            '%s-%s €/h',
            $this->entity_data->pricings[0]->lowestPrice,
            $this->entity_data->pricings[0]->highestPrice
        );
    }
}
