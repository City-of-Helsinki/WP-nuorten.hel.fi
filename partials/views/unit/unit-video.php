<?php
/**
 * Unit video
 */

/**
 * Video is passed as args.
 *
 * @var array $args
 */
$video = $args;
?>

<div class="container unit-section" id=<?php echo esc_attr( $video['anchor']['url'] ); ?>>
    <h2 class="unit-section__title unit-section__title--video">
        <?php echo esc_html( $video['title'] ); ?>
    </h2>
    <div class="iframe is-16by9">
        <iframe
            class="has-ratio"
            title="<?php echo esc_html( $video['title'] ); ?>"
            src="<?php echo esc_url( $video['video_url'] ); ?>">
        </iframe>
    </div>
</div>
