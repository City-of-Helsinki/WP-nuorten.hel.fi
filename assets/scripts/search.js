/**
 * Search controller.
 */

// Use jQuery as $ within this file scope.
const $ = jQuery; // eslint-disable-line no-unused-vars

/**
 * Export the class reference.
 */
export default class Search {

    /**
     * Run when the document is ready.
     *
     * @return {void}
     */
    docReady() {
        $( '.hds-button--js-more-toggle' ).on( 'click', ( event ) => {
            const toggle = $( event.currentTarget );
            const toggleTarget = $( '.' + toggle.data( 'controls' ) );
            const toggleParent = toggle.parent();

            toggleParent.addClass( 'is-hidden' );

            toggleTarget.removeClass( 'is-hidden' );
        } );
    }
}
