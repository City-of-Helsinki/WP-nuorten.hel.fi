// Use jQuery as $ within this file scope.
const $ = jQuery;

/**
 * Class Dropdown
 */
export default class Dropdown {

    /**
     * Cache dom elements for use in the class's methods
     *
     * @return {void}
     */
    cache() {
        this.dropdowns = document.querySelectorAll( '[data-dropdown]' );
    }

    /**
     * Add event listeners.
     *
     * @return {void}
     */
    events() {

        if ( this.dropdowns ) {
            for ( let i = 0; i < this.dropdowns.length; i++ ) {
                this.dropdowns[ i ]
                    .querySelector( '[data-dropdown-trigger]' )
                    .addEventListener(
                        'click',
                        ( event ) => this.toggleDropdown( event )
                    );
            }

            document.addEventListener( 'click', ( event ) => this.closeOnOutsideClick( event ) );
        }
    }

    /**
     * Handle dropdown toggling when a dropdown is clicked.
     *
     * @return {void}
     */
    toggleMenu() {
        this.navbarBurger.classList.toggle( 'is-active' );
        this.navbarMenu.classList.toggle( 'is-active' );
        this.toggleAriaExpanded( this.navbarBurger );
    }

    /**
     * Toggles a dropdown menu visibility.
     *
     * @param {Event} event A click event.
     *
     * @return {void}
     */
    toggleDropdown( event ) {
        const clicked = event.target;

        const target = $( clicked ).get( 0 );
        const container = $( clicked ).closest( '[data-dropdown]' ).get( 0 );

        if ( container.classList.contains( 'is-active' ) ) {

            // Close all dropdowns.
            this.closeAllDropdowns();
        }
        else {

            // Close all dropdowns.
            this.closeAllDropdowns();

            this.toggleAriaExpanded( target );
            container.classList.add( 'is-active' );
        }

    }

    /**
     * Close dropdowns if click is outside one.
     *
     * @param {Event} event Click event.
     *
     * @return {void}
     */
    closeOnOutsideClick( event ) {
        let targetElement = event.target;
        const body = document.getElementsByTagName( 'body' )[ 0 ];

        do {
            if (
                ( typeof targetElement.classList !== 'undefined' &&
                targetElement.classList.contains( 'dropdown' ) ) ||
                body.classList.contains( 'page-template-page-vet-search' )
            ) {

                // Click was inside dropdown element, do nothing.
                return;
            }

            // Go up the DOM
            targetElement = targetElement.parentNode;
        } while ( targetElement );

        // Close all dropdowns if we get this far.
        this.closeAllDropdowns();
    }

    /**
     * Method to close all dropdowns.
     *
     * @return {void}
     */
    closeAllDropdowns() {
        for ( let i = 0; i < this.dropdowns.length; i++ ) {
            this.dropdowns[ i ].classList.remove( 'is-active' );
            this.dropdowns[ i ].querySelector( '[data-dropdown-trigger]' ).setAttribute( 'aria-expanded', false );
        }
    }

    /**
     * Get the toggler's aria-expanded current state and set a new opposite state to it.
     * Also set the opened container's aria-hidden state to the new value's opposite.
     *
     * @param {HTMLElement} toggler The toggler element.
     *
     * @return {void}
     */
    toggleAriaExpanded( toggler ) {
        const ariaExpandedState = toggler.getAttribute( 'aria-expanded' ) === 'false' ? true : false;
        toggler.setAttribute( 'aria-expanded', ariaExpandedState );
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
