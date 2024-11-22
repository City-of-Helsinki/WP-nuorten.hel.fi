<?php
/**
 * Unit instructor
 */

/**
 * Intructor is passed as args.
 *
 * @var WP_Post $args
 */
$instructor     = $args;
$image          = get_post_thumbnail_id( $instructor->ID );
$contact_fields = get_fields( $instructor->ID );
?>

<li class="grid-columns__column">
    <div class="unit-instructors-list__item">
        <?php if ( ! empty( $image ) ) : ?>
            <figure class="instructor-image img-container">
                <?php echo wp_get_attachment_image( $image, 'large' ); ?>
            </figure>
        <?php endif; ?>
        <div class="instructor-contact-details">
            <?php
            echo wp_kses_post( sprintf(
                '<div><span class="instructor-name">%s</span>, %s</div> <div>%s</div> <div>%s</div>',
                $instructor->post_title,
                $contact_fields['title'],
                $contact_fields['email'],
                $contact_fields['phone']
            ) );
            ?>
        </div>
    </div>
</li>
