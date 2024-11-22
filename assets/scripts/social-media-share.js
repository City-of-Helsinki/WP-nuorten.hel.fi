/**
 * Socia media share scripts
 */

// Use jQuery as $ within this file scope.
const $ = jQuery;
import ClipboardJS from 'clipboard';
/**
 * Export the class reference.
 */
export default class SocialMediaShare {
    /**
     * Cache dom elements for use in the class's methods
     *
     * @return {void}
     */
    cache() {
        this.clipboardTooltip = $( '#js-clipboard-tooltip' );
    }

    /**
     * All common events
     *
     * @return {void}
     */
    events() {
        const clipboard = new ClipboardJS( '#js-copy-url' );

        clipboard.on( 'success', ( e ) => {
            e.clearSelection();

            if ( ! this.clipboardTooltip ) {
                return;
            }

            this.clipboardTooltip.removeClass( 'is-hidden' ).focus();

            setTimeout( () => {
                this.clipboardTooltip.addClass( 'is-hidden' );
            }, 2000 );
        } );
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
