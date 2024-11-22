/**
 * Menu controller.
 *
 * This class controls the site menu functions.
 */
// Use jQuery as $ within this file scope.
const $ = jQuery;

const MENU_OPEN = 'is-menu-open';
const MENU_ICON_OPEN = 'hds-icon--menu-hamburger';
const MENU_ICON_CLOSED = 'hds-icon--cross';

/**
 * Export the class reference.
 */
export default class Menu {
    /**
     * Cache dom elements for use in the class's methods
     *
     * @return {void}
     */
    cache() {
        this.$menuToggle = $( '#js-menu-toggle' );
        this.$menuWrapper = $( '#js-menu-wrapper' );
        this.$submenuToggle = $( '.js-submenu-toggle' );
        this.$body = $( 'body' );
    }

    /**
     * Add event listeners.
     *
     * @return {void}
     */
    events() {

        // Hamburger click event.
        this.$menuToggle.click( () => this.toggleMenu() );

        // Go through each submenu toggler to toggle the wanted menu open
        this.$submenuToggle.each( ( index, toggler ) => {

            const $subMenu = $( toggler ).next();
            const subMenuVisible = $subMenu.css( 'display' ) === 'block';

            if ( subMenuVisible ) {
                $( toggler ).attr( 'aria-expanded', true );
            }
            else {
                $( toggler ).attr( 'aria-expanded', false );
            }

            $( toggler ).on( 'click', ( e ) => {

                const $clickedButton = $( e.currentTarget );
                this.toggleSubMenu( $clickedButton, $subMenu );

            } );

        } );
    }

    /**
     * Handle menu toggling when the navbar burger is clicked.
     *
     * @return {void}
     */
    toggleMenu() {

        const currentState = this.$menuToggle.attr( 'aria-expanded' ) === 'true' ? true : false;

        this.$menuToggle.attr( 'aria-expanded', ! currentState );
        if ( currentState ) {
            this.$body.removeClass( MENU_OPEN );
            this.$menuToggle
                .find( '.hds-icon' )
                .removeClass( MENU_ICON_CLOSED )
                .addClass( MENU_ICON_OPEN );
        }
        else {
            this.$body.addClass( MENU_OPEN );
            this.$menuToggle
                .find( '.hds-icon' )
                .removeClass( MENU_ICON_OPEN )
                .addClass( MENU_ICON_CLOSED );
        }
    }

    /**
     * Toggles the next submenu related to the clicked button
     *
     * @param {Object} $clickedButton Clicked toggle button
     * @param {Object} $subMenu       Submenu
     * @return {void}
     */
    toggleSubMenu( $clickedButton, $subMenu ) {

        if ( $subMenu.is( ':visible' ) ) {
            $subMenu.hide();
            $clickedButton.attr( 'aria-expanded', false );
        }
        else {
            $subMenu.show();
            $clickedButton.attr( 'aria-expanded', true );
        }

    }

    /**
     * Run when the document is ready.
     *
     * @return {void}
     */
    docReady() {
        this.cache();
        this.events();
    }
}
