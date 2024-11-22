<?php
/**
 * Define the PageIframe class.
 */

namespace Geniem\Theme\Model;

use Geniem\Theme\Interfaces\Model;

/**
 * The PageIframe class.
 *
 * @package Geniem\Theme\Model
 */
class PageIframe implements Model {

    /**
     * This defines the name of this model.
     */
    const NAME = 'PageIframe';

    /**
     * This defines the template of this model.
     */
    const TEMPLATE = 'page-templates/page-iframe.php';

    /**
     * Add hooks and filters from this model
     *
     * @return void
     */
    public function hooks() : void {
        \add_action(
            'admin_head',
            \Closure::fromCallable( [ $this, 'clean_page_edit_view' ] )
        );
        \add_filter(
            'use_block_editor_for_post_type',
            \Closure::fromCallable( [ $this, 'disable_gutenberg' ] ),
            10,
            1
        );
    }

    /**
     * Get class name constant
     *
     * @return string Class name constant
     */
    public function get_name() : string {
        return self::NAME;
    }

    /**
     * Add classes to html.
     *
     * @param array $classes Array of classes.
     * @return array Array of classes.
     */
    public function add_classes_to_html( $classes ) : array {
        return $classes;
    }

    /**
     * Disable Gutenberg.
     *
     * @param bool $can_edit
     *
     * @return bool
     */
    private function disable_gutenberg( bool $can_edit ) : bool {
        if ( ! ( is_admin() && ! empty( $_GET['post'] ) ) ) {
            return $can_edit;
        }

        return \get_page_template_slug( \get_the_ID() ) === self::TEMPLATE ? false : true;
    }

    /**
     * Clean page edit view.
     *
     * @return void
     */
    private function clean_page_edit_view() : void {
        $screen = get_current_screen();
        if ( 'page' !== $screen->id || ! isset( $_GET['post'] ) ) {
            return;
        }

        if ( \get_page_template_slug( \get_the_ID() ) === self::TEMPLATE ) {
            \remove_post_type_support( 'page', 'editor' );
            \remove_meta_box( 'postimagediv', 'page', 'side' );
        }
    }
}
