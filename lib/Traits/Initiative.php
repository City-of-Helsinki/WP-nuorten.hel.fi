<?php
/**
 * Trait for Initiative
 */

namespace Geniem\Theme\Traits;

use Geniem\Theme\Integrations\Initiatives\ApiClient;

/**
 * Trait Initiative
 *
 * @package Geniem\Theme\Traits
 */
trait Initiative {

    /**
     * Get status.
     *
     * @param int|null $post_id
     *
     * @return array|null
     */
    public function get_status( $post_id = null ) {
        $post_id = empty( $post_id ) ? \get_the_ID() : $post_id;

        $original_status_text = get_field( 'ruuti_initiative_detailed_status', $post_id ) ?: null;

        if ( empty( $original_status_text ) ) {
            return null;
        }

        return [
            'text'  => $this->get_detailed_status( $original_status_text ),
            'class' => strtolower( $original_status_text ),
        ];
    }

    /**
     * Get status texts.
     *
     * @return array
     */
    private function get_status_texts() : array {
        return [
            'RECEIVED'   => _x( 'Received', 'initiative status', 'nuhe' ),
            'IN_PROCESS' => _x( 'In process', 'initiative status', 'nuhe' ),
            'PROCESSED'  => _x( 'Processed', 'initiative status', 'nuhe' ),
            'ARCHIVED'   => _x( 'Archived', 'initiative status', 'nuhe' ),
            'REJECTED'   => _x( 'Rejected', 'initiative status', 'nuhe' ),
        ];
    }

    /**
     * Get detailed status text by rest api status text
     *
     * @param string $status Original status text.
     *
     * @return string|null
     */
    private function get_detailed_status( $status ) {
        $status_texts = $this->get_status_texts();
        $status       = explode( ',', $status )[0];

        foreach ( $status_texts as $key => $value ) {
            if ( $key === $status ) {
                return $value;
            }
        }

        return null;
    }
}
