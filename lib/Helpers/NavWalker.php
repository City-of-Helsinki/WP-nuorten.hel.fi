<?php
/**
 * This file customizes the Nav menu.
 */

namespace Geniem\Theme\Helpers;

/**
 * Nav Walker class.
 */
class NavWalker extends \Walker_Nav_Menu {

    /**
     * Start of menu.
     *
     * @param string  $output HTML output.
     * @param integer $depth  Menu depth we are at.
     * @param array   $args   Args to wp_nav_menu.
     * @return void
     */
    public function start_lvl( &$output, $depth = 0, $args = [] ) {
        $indent  = str_repeat( "\t", $depth );
        $output .= "\n$indent<button class=\"menu__submenu-toggle js-submenu-toggle hds-button hds-button--supplementary hds-button--theme-black\" aria-expanded=\"false\"><span aria-hidden=\"true\" class=\"hds-icon hds-icon--angle-down hds-icon--size-xs\"></span></button>\n<div class=\"menu__submenu\"><ul class=\"container container--full-small menu__list\">\n";
    }

    /**
     * End of menu.
     *
     * @param string  $output HTML output.
     * @param integer $depth  Menu depth we are at.
     * @param array   $args   Args to wp_nav_menu.
     * @return void
     */
    public function end_lvl( &$output, $depth = 0, $args = [] ) {
        $indent  = str_repeat( "\t", $depth );
        $output .= "$indent</ul></div>\n";
    }
}
