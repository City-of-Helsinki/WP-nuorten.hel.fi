<?php
/**
 * TextBoxImage partial
 *
 * Accepts args:
 *  - box_bg_color: <string>
 *  - title: <string>
 *  - description: <string>
 *  - link: <array>
 *  - image: <array>
 */

$args = wp_parse_args( $args, [
    'box_bg_color' => '',
    'title'        => '',
    'description'  => '',
    'link'         => [],
    'image'        => [],
] );

if ( empty( $args['image'] ) ) {
    return;
}

$bg_color = empty( $args['box_bg_color'] ) ? 'white' : $args['box_bg_color'];

?>

<div class="nuhe-layout nuhe-layout--main-content nuhe-text-box-image">
    <div class="container">
        <div class="image-content">
            <figure class="img-container">
                <?php
                    echo wp_get_attachment_image( $args['image']['id'], 'large' );
                ?>
            </figure>
        </div>
        <div class="text-content has-bg-color has-bg-color--<?php echo esc_attr( $bg_color ); ?>">
            <?php
            if ( ! empty( $args['title'] ) ) {
                get_template_part( 'partials/heading', '', [
                    'title' => $args['title'],
                    'level' => 'h2',
                    'class' => 'nuhe-text-box-image__title',
                ] );
            }
            ?>

            <?php if ( ! empty( $args['description'] ) ) : ?>
                <div class="nuhe-text-box-image__description">
                    <?php echo wp_kses_post( $args['description'] ); ?>
                </div>
            <?php endif; ?>

            <?php if ( ! empty( $args['link'] ) ) : ?>
                <?php
                get_template_part( 'partials/button-link', '', [
                    'classes' => [ 'outlined', 'border-black' ],
                    'title'   => $args['link']['title'],
                    'label'   => $args['link']['title'],
                    'url'     => $args['link']['url'],
                ] );
                ?>
            <?php endif; ?>
        </div>
    </div>
</div>
