<?php
/**
 * Breadcrumbs
 */

?>

<ul class="breadcrumb-list">
    <?php foreach ( $args['breadcrumbs'] as $breadcrumb ) : ?>
        <li class="breadcrumb-list__item">
            <?php
            $is_current    = isset( $breadcrumb['current'] );
            $aria_attr     = $is_current ? ' aria-current="page"' : '';
            $has_permalink = ! empty( $breadcrumb['permalink'] );
            ?>

            <?php if ( $is_current || ! $has_permalink ) : ?>
                <span <?php echo esc_attr( $aria_attr ); ?>>
                    <?php echo esc_html( $breadcrumb['title'] ); ?>
                </span>
            <?php else : // phpcs:ignore ?>
                <a href="<?php echo esc_url( $breadcrumb['permalink'] ); ?>">
                    <?php echo esc_html( $breadcrumb['title'] ); ?>
                </a>
            <?php endif; ?>
        </li>
    <?php endforeach; ?>
</ul>
