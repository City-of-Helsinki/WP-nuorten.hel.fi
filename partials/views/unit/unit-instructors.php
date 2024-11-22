<?php
/**
 * Unit instructors
 */

use Geniem\Theme\PostType;

/**
 * Intructors is passed as args.
 *
 * @var array $args
 */
$instructors       = $args;
$partial_path      = 'partials/views/unit/unit';
?>

<div class="container unit-section" id=<?php echo esc_attr( $instructors['anchor']['url'] ); ?>>
    <h2 class="unit-section__title">
        <?php echo esc_html( $instructors['title'] ); ?>
    </h2>

    <?php if ( ! empty( $instructors['instructors'] ) ) : ?>
        <ul class="unit-instructors-list grid-columns grid-columns--3">
            <?php
            array_walk( $instructors['instructors'], function( $instructor ) use ( $partial_path ) {
                get_template_part( $partial_path, 'instructor', $instructor['instructor'] );
            } );
            ?>
        </ul>
    <?php endif; ?>

    <?php if ( ! empty( $instructors['phone_number'] ) ) : ?>
        <p>
            <?php echo esc_html__( 'Youth center phone number', 'nuhe' ) . ': ' . esc_html( $instructors['phone_number'] ); ?>
        </p>
    <?php endif; ?>

    <?php if ( ! empty( $instructors['instructors_text'] ) ) : ?>
        <div class="grid-columns grid-columns--2">
            <div class="grid-columns__column">
                <?php echo wp_kses_post( $instructors['instructors_text'] ); ?>
            </div>
        </div>
    <?php endif; ?>

    <?php if ( ! empty( $instructors['some_links'] ) ) : ?>
        <ul class="unit-some">
            <?php foreach ( $instructors['some_links'] as $link ) : ?>
            <li>
                <a href="<?php echo esc_url( $link['some_link'] ); ?>">
                    <?php
                    get_template_part( 'partials/icon', '', [
                        'icon' => $link['some_source'],
                    ] );
                    ?>
                </a>
            </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

</div>
