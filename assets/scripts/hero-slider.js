/**
 * Gallery JS controller.
 */

// Import only necessary Glide JS components
import Glide, { Controls, Swipe, Autoplay } from '@glidejs/glide/dist/glide.modular.esm';

/**
 * Export the class reference.
 */
export default class HeroSlider {
    /**
     * Cache dom elements for use in the class's methods
     */
    cache() {
        this.glide = document.getElementById( 'hero-slider' );
        this.glideBullet = document.getElementsByClassName( 'glide__bullet' );
        if ( this.glide ) {
            this.glideSlides = this.glide.querySelectorAll( '.glide__slide' );
        }
    }

    /**
     * All common events
     */
    events() {

        if ( ! this.glide ) {
            return;
        }

        // Use type: 'slider' over 'carousel', as carousel creates
        // clone slides which cause duplicate content IDs.
        const glide = new Glide( this.glide, {
            type: 'slider',
            autoplay: 5000,
            perView: 1,
            focusAt: 'center',
        } );

        glide.mount( { Controls, Swipe, Autoplay } );
    }

    /**
     * Run when the document is ready.
     */
    docReady() {
        this.cache();
        this.events();
    }
}
