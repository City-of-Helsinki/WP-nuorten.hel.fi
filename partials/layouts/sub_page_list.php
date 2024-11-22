<?php
/**
 * Sub page list partial
 */

$args = wp_parse_args( $args );

$ul_classes     = "sub-page-list-items grid-columns grid-columns--{$args['columns']}";
$layout_classes = [
    'nuhe-layout',
    'nuhe-layout--main-content',
    'nuhe-sub-page-list',
    'has-bg-color',
    'has-bg-color--white',
];
$layout_classes = implode( ' ', $layout_classes );

?>

<div class="<?php echo esc_attr( $layout_classes ); ?>">
    <div class="container">
        <?php if ( ! empty( $args['title'] ) ) : ?>
            <h2 class="nuhe-sub-page-list__title">
                <?php echo esc_html( $args['title'] ); ?>
            </h2>
        <?php endif; ?>
        <ul class="<?php echo esc_attr( $ul_classes ); ?>">
            <?php foreach ( $args['items'] as $item ) : ?>
                <li class="grid-columns__column sub-page-list-item">
                    <div class="sub-page-list-item__subpage">
                        <?php if ( ! empty( $item['image'] ) ) : ?>
                            <figure class="img-container">
                                <?php
                                    echo wp_get_attachment_image( $item['image']['id'], 'large' );
                                ?>
                            </figure>
                        <?php endif; ?>

                        <?php if ( ! empty( $item['link'] ) ) : ?>
                            <?php
                            get_template_part( 'partials/link', '', [
                                'title' => $item['link']['title'],
                                'url'   => $item['link']['url'],
                                'icon'  => 'arrow-right',
                            ] );
                            ?>
                        <?php endif; ?>

                        <?php if ( ! empty( $item['textarea'] ) ) : ?>
                            <div class="text">
                                <?php echo wp_kses_post( $item['textarea'] ); ?>
                            </div>
                        <?php endif; ?>

                    </div>

                    <?php if ( ! empty( $item['links'] ) ) : ?>
                        <div class="sub-page-list-item__children">
                            <?php
                            get_template_part( 'partials/links', '', [
                                'links'      => $item['links'],
                                'type'       => 'link',
                                'icon-start' => 'arrow-right',
                            ] );
                            ?>
                        </div>
                    <?php endif; ?>

                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
