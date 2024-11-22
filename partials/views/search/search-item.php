<?php
/**
 * Search item
 */

 use \Geniem\Theme\PostType;

/**
 * Partial args.
 *
 * @var \WP_Post $args
 */
$wp_post   = $args['item'];
$id        = $wp_post->ID;
$post_type = get_post_type( $id );
$classes   = [
    'search-item--' . $post_type,
    $args['group_class'],
    $args['group'],
];
$is_unit_item = false;

if (
    $post_type === PostType\YouthCenter::SLUG
    || $post_type === PostType\HighSchool::SLUG
    || $post_type === PostType\VocationalSchool::SLUG
    ) {
        $classes[] = 'search-item--unit-item';
        $is_unit_item = true;
    }

$classes = implode( ' ', $classes );
?>

<li class="search__item search-item <?php echo esc_attr( $classes ); ?>">
    <?php if ( ! $is_unit_item ) : ?>
        <div class="search-item__image">
            <?php
            echo wp_get_attachment_image(
                $wp_post->image['id'],
                'thumbnail',
                false,
                [ 'class' => 'objectfit-image' ]
            );
            ?>
        </div>
    <?php endif; ?>
    <span class="search-item__post-type keyword">
        <?php echo esc_html( $wp_post->cpt_tag ); ?>
    </span>
    <div class="search-item__inner">
        <h3 class="search-item__title">
            <a href="<?php the_permalink( $id ); ?>" class="search-item__link">
                <?php echo esc_html( get_the_title( $id ) ); ?>
            </a>
        </h3>
    </div>
</li>
