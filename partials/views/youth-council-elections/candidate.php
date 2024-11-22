<?php
/**
 * Candidate item
 */

/**
 * Args type
 *
 * @var object $args
 */
$item    = $args;
$classes = empty( $item->classes ) ? '' : ' ' . implode( ' ', $item->classes );
?>


<div class="candidate<?php echo esc_attr( $classes ); ?>">
    <div class="candidate__image-number-wrapper">
        <figure class="img-container candidate__img-container">
            <?php
            echo wp_get_attachment_image( $item->image, 'large' );
            ?>
        </figure>
        <div class="candidate__number">
            <span><?php echo esc_html( $item->candidate_number ); ?></span>
        </div>
    </div>
    <div class="candidate__text-wrapper">
        <h5 class="candidate__name">
            <a href="<?php echo esc_url( $item->url ); ?>" class="candidate__link">
                <?php echo esc_html( $item->title ); ?>
            </a>
        </h5>
        <div class="candidate__details">
            <span class="candidate__age">
                <?php echo esc_html( __( 'Year of birth', 'nuhe' ) . ': ' . $item->age ); ?>
            </span>
            <span class="candidate__separator"></span>
            <span class="candidate__postal-code">
                <?php echo esc_html( __( 'Postal code', 'nuhe' ) . ': ' . $item->postal_code ); ?>
            </span>
        </div>
        <div class="candidate__slogan text-sm">
            <span aria-hidden="true" class="quote-icon hds-icon hds-icon--size-s"></span>
            <p><?php echo esc_html( $item->slogan ); ?></p>
        </div>
    </div>
</div>
