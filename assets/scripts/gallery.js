/**
 * Gallery controller.
 */

import 'modaal';

// Use jQuery as $ within this file scope.
const $ = jQuery; // eslint-disable-line no-unused-vars

/**
 * Export the class reference.
 */
export default class Gallery {

    /**
     * Run when the document is ready.
     *
     * @return {void}
     */
    docReady() {
        $( '.js-gallery-trigger' ).modaal( {
            type: 'image',
        } );
    }
}
