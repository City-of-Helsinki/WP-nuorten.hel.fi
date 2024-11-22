<?php
/**
 * Unit Search results
 */

/**
 * Args type
 *
 * @var array $args
 */
$units = $args;
?>

<div class="unit-search__grid">
    <?php foreach ( $units as $unit ) : ?>
        <?php
        $item_classes = [
            'unit-search__item',
            'has-bg-color',
            'has-bg-color--' . $unit->background,
        ];
        ?>
        <div class="<?php echo esc_attr( implode( ' ', $item_classes ) ); ?>">
            <a href="<?php the_permalink( $unit->ID ); ?>" class="unit-search__link">
                <?php echo esc_html( get_the_title( $unit->ID ) ); ?>
            </a>
        </div>
    <?php endforeach; ?>
</div>
