<?php
/**
 * Search item external
 */

/**
 * Partial args.
 *
 * @var \Geniem\Theme\Integrations\LinkedEvents\Entities\Event $args
 */
$item    = $args['item'];
$classes = [
    'search__item',
    'search-item',
    "search-item--{$args['post_type']}",
    $args['group_class'],
    $args['group'],
];

$classes = implode( ' ', $classes );
?>
<li class="<?php echo esc_attr( $classes ); ?>">
    <?php $image = $item->get_primary_image(); ?>

    <?php if ( $image ) : ?>
        <div class="search-item__image">
            <img src="<?php echo esc_url( $image->get_url() ); ?>"
                class="objectfit-image"
                alt="<?php echo esc_html( $image->get_alt_text() ); ?>">
        </div>
    <?php endif; ?>

    <span class="search-item__post-type keyword">
        <?php echo esc_html( $args['tag'] ); ?>
    </span>

    <div class="search-item__inner">
        <div class="search-item__meta">
            <?php echo esc_html( $item->get_formatted_time_string() ); ?>
        </div>

        <h3 class="search-item__title">
            <a href="<?php echo esc_url( $item->get_permalink() ); ?>"
                class="search-item__link">
                <?php echo esc_html( $item->get_name() ); ?>
            </a>
        </h3>
    </div>
</li>
