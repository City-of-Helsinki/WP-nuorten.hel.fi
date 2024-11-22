<?php
/**
 * Front page hero partial
 */

$args = wp_parse_args( $args, [
    'hero_description'   => '',
    'hero_description_2' => '',
    'links'              => [],
    'classes'            => [],
    'icon-end'           => '',
    'icon-start'         => '',
    'background_image'   => '',
    'bg_color'           => '',
] );

$background_style = ! empty( $args['background_image'] )
                    ? ' style="background-image: url(' . esc_url( $args['background_image'] ) . ')"'
                    : '';
$bg_color_class   = empty( $args['bg_color'] ) || $args['bg_color'] === 'default' ? '' : " has-bg-color--{$args['bg_color']} text-black";
?>

<div id="main-content" class="hero hero--page-front<?php echo esc_attr( $bg_color_class ); ?>"<?php echo wp_kses_post( $background_style ); ?>>
    <div class="hero__grid container">
        <div class="hero-content">
            <?php if ( ! empty( $args['hero_description'] ) ) : ?>
                <?php
                get_template_part( 'partials/heading', '', [
                    'title' => $args['hero_description'],
                    'level' => 'h1',
                    'class' => 'page-heading',
                ] );
                ?>
            <?php endif; ?>

            <?php if ( ! empty( $args['hero_description_2'] ) ) : ?>
                <div class="hero-content__description">
                    <?php echo wp_kses_post( $args['hero_description_2'] ); ?>
                </div>
            <?php endif; ?>

            <?php
            get_template_part( 'partials/links', '', [
                'links'        => $args['links'],
                'list_classes' => [ 'button-links' ],
                'type'         => 'button',
                'classes'      => $args['classes'],
                'icon-start'   => $args['icon-start'] ?? '',
                'icon-end'     => $args['icon-end'] ?? '',
            ] );
            ?>
        </div>
    </div>
    <div class="koro"></div>
</div>
