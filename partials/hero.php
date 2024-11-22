<?php
/**
 * Page hero partial
 */

$args = wp_parse_args( $args, [
    'modifier_class'   => '',
    'class'            => '',
    'title'            => '',
    'description'      => '',
    'links'            => [],
    'classes'          => [],
    'icon-end'         => '',
    'icon-start'       => '',
    'background_image' => '',
    'breadcrumbs'      => [],
    'hero_image'       => '',
] );

$modifier_class   = empty( $args['modifier_class'] ) ? '' : " hero--{$args['modifier_class']}";
$icon_start       = $args['icon-start'] ?? '';
$icon_end         = $args['icon-end'] ?? '';
$background_style = ! empty( $args['background_image'] )
                    ? ' style="background-image: url(' . esc_url( $args['background_image'] ) . ')"'
                    : '';

?>

<div id="main-content" class="hero<?php echo esc_attr( $modifier_class . ' ' . $args['class'] ); ?>">
    <div class="hero__grid container">
        <div class="hero-content">
            <?php
            if ( ! empty( $args['breadcrumbs'] ) ) {
                \get_template_part( 'partials/breadcrumbs', '', [
                    'breadcrumbs' => $args['breadcrumbs'],
                ] );
            }
            ?>
            <?php
            // H1 heading
            \get_template_part( 'partials/heading', '', [
                'title' => ! empty( $args['title'] ) ? $args['title'] : null,
                'level' => 'h1',
                'class' => 'page-heading',
            ] );
            ?>

            <?php if ( ! empty( $args['description'] ) ) : ?>
                <div class="hero-content__description">
                    <?php echo wp_kses_post( $args['description'] ); ?>
                </div>
            <?php endif; ?>

            <?php
            get_template_part( 'partials/links', '', [
                'links'        => $args['links'],
                'list_classes' => [ 'button-links' ],
                'type'         => 'button',
                'classes'      => $args['classes'],
                'icon-start'   => $icon_start,
                'icon-end'     => $icon_end,
            ] );
            ?>
        </div>
        <?php if ( ! empty( $args['hero_image']['sizes']['medium'] ) ) : ?>
        <div class="hero-image img-container">
            <img
                src="<?php echo \esc_url( $args['hero_image']['sizes']['medium'] ); ?>"
                alt="<?php echo \esc_html( $args['hero_image']['alt'] ?? '' ); ?>"
                class="objectfit-image"
            />
        </div>
        <?php endif; ?>
    </div>
    <div class="hero__bg-image absolute-bg-image"<?php echo wp_kses_post( $background_style ); ?>></div>
    <div class="koro"></div>
</div>
