<?php
/**
 * Template Name: Helsinkirekryn työpaikat
 */

use \Geniem\Theme\Model\PageHelbitJobs;

$model            = new PageHelbitJobs();
$breadcrumbs      = $model->get_breadcrumbs();
$jobs             = $model->get_jobs();
$background_color = $model->get_background_color();

get_header();
?>
    <main id="main-content" class="main-content helbit-jobs">
        <header class="helbit-jobs__header">
            <div class="container">
                <?php
                if ( ! empty( $breadcrumbs ) ) {
                    \get_template_part( 'partials/breadcrumbs', '', [
                        'breadcrumbs' => $breadcrumbs,
                    ] );
                }
                ?>
                <h1 class="helbit-jobs__title">
                    <?php the_title(); ?>
                </h1>

                <?php \get_template_part( 'partials/blocks' ); ?>
            </div>
        </header>

        <div class="helbit-jobs__wrapper">
            <div class="container">
                <ul class="helbit-jobs__list accordion">
                    <?php
                    $count = 0;
                    foreach ( $jobs as $job ) :
                        ?>
                        <?php
                        $image     = $job->get_image();
                        $has_image = ! empty( $image );
                        ?>
                        <div class="accordion-item helbit-job-item" <?php if ( ! empty( $background_color ) && $count % 2 === 0 ) { echo 'style="background-color: '. esc_html( $background_color ). ';" '; } ?>>
                            <div class="helbit-job-item__info">
                                <h2 class="helbit-job-item__title accordion-item__title">
                                    <button type="button" id="helbit-job-item-button-<?php echo esc_attr( $count ); ?>" class="accordion-item__button js-toggle" aria-expanded="false" aria-controls="helbit-job-item-content-<?php echo esc_attr( $count ); ?>">
                                        <span class="accordion-item__button-text">
                                            <?php echo esc_html( $job->get_title() ); ?>
                                            <?php 
                                                if ( ! empty( $job->get_number_of_vacancy() ) ) {
                                                    echo esc_html(
                                                        sprintf(
                                                            _n( '(%d vacancy)', '(%d vacancies)', (int) $job->get_number_of_vacancy(), 'nuhe' ),
                                                            (int) $job->get_number_of_vacancy()
                                                        )
                                                    );
                                                }
                                            ?>
                                        </span>
                                        <span class="accordion-item__button-icon" aria-hidden="true">
                                            <?php
                                            get_template_part( 'partials/icon', '', [
                                                'icon' => 'angle-down',
                                            ] );
                                            ?>
                                        </span>
                                    </button>
                                </h2>
                                <div class="helbit-job-item__primary-details">
                                    <div class="helbit-job-item__primary-detail-box">
                                        <?php if ( ! empty( $job->get_employment() ) || ! empty( $job->get_employment_type() ) ) : ?>
                                            <div class="helbit-job-item__job-type">
                                                <p class="helbit-job-item__detail-title">
                                                    <?php
                                                    if ( ! empty( $job->get_employment_type() ) ) {
                                                        echo esc_html( $job->get_employment_type() );
                                                    }

                                                    if ( ! empty( $job->get_employment() ) ) {
                                                        echo ', ' . esc_html( $job->get_employment() );
                                                    }
                                                    ?>
                                                </p>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ( ! empty( $job->get_organization() ) ) : ?>
                                            <div class="helbit-job-item__organization">
                                                <p><?php echo esc_html( $job->get_organization() ); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="helbit-job-item__primary-detail-box">
                                        <?php if ( ! empty( $job->get_duration() ) ) : ?>
                                            <div class="helbit-job-item__date">
                                                <p class="helbit-job-item__detail-title">
                                                    <?php esc_html_e( 'Employment contract', 'nuhe' ); ?>
                                                </p>
                                                <p><?php echo esc_html( $job->get_duration() ); ?></p>
                                            </div>
                                        <?php endif; ?>

                                        <?php if ( ! empty( $job->get_publication_end_time_as_datetime() ) ) : ?>
                                            <div class="helbit-job-item__application-ends">
                                                <p class="helbit-job-item__detail-title">
                                                    <?php esc_html_e( 'Application ends', 'nuhe' ); ?>
                                                </p>
                                                <p><?php echo esc_html( $job->get_publication_end_time_as_datetime() ); ?></p>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>

                                <div id="helbit-job-item-content-<?php echo esc_attr( $count ); ?>" class="helbit-job-item__secondary-details accordion__content">
                                    <?php if ( ! empty( $job->get_address() ) && ! empty( $job->get_postal_code() ) ) : ?>
                                        <div class="helbit-job-item__address">
                                            <p class="helbit-job-item__detail-title">
                                                <?php esc_html_e( 'Workplace address', 'nuhe' ); ?>
                                            </p>
                                            <p>
                                                <?php echo esc_html( $job->get_address() . ', ' . $job->get_postal_code() ); ?>
                                            </p>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ( ! empty( $job->get_desc() ) ) : ?>
                                        <div class="helbit-job-item__job-desc">
                                            <p><?php echo nl2br( esc_html( $job->get_desc() ) ); ?></p>
                                        </div>
                                    <?php endif; ?>

                                    <div class="helbit-job-item__more-info">
                                        <?php
                                            get_template_part( 'partials/button-link', '', [
                                                'classes' => [ 'primary' ],
                                                'label'   => __( 'Apply', 'nuhe' ),
                                                'url'     => $job->get_link(),
                                                'target'  => '_blank',
                                            ] );
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $count++;
                        endforeach;
                    ?>
                </ul>
            </div>
        </div>
    </main>
<?php
get_footer();
