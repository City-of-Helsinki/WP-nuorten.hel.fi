<?php
/**
 * Single initiative template
 */

use Geniem\Theme\Model\SingleInitiative;

get_header();

/**
 * Model
 *
 * @var \Geniem\Theme\Model\SingleInitiative $model
 */
$model       = new SingleInitiative();
$breadcrumbs = $model->get_breadcrumbs();
$status      = $model->get_status();
?>
<div id="main-content" class="container initiative-container page-grid<?php echo ! empty( $breadcrumbs ) ? ' page-grid--has-breadcrumbs' : ''; ?> ">
    <main class="page-grid__content">
        <?php
        if ( ! empty( $breadcrumbs ) ) {
            \get_template_part( 'partials/breadcrumbs', '', [
                'breadcrumbs' => $breadcrumbs,
            ] );
        }

        the_post();

        // Featured image
        if ( \has_post_thumbnail() ) {
            \get_template_part( 'partials/featured-image' );
        }

        ?>

        <?php if ( ! empty( $status ) ) : ?>
            <span class="keyword initiative-status initiative-status--<?php echo esc_attr( $status['class'] ); ?>">
                <?php echo esc_html( $status['text'] ); ?>
            </span>
        <?php endif; ?>

        <?php
        // H1 heading
        \get_template_part( 'partials/heading', '', [
            'level' => 'h1',
            'class' => 'page-heading initiative-heading',
        ] );
		?>

        <div class="initiative-content">
            <?php the_content(); ?>
        </div>

        <?php if ( ! empty( get_field( 'ruuti_initiative_status_notes' ) ) ) : ?>
            <div class="initiative-status-notes">
                <p>
                    <?php echo wp_kses_post( get_field( 'ruuti_initiative_status_notes' ) ); ?>
                </p>
            </div>
        <?php endif; ?>
    </main>
</div>
<?php

get_footer();
