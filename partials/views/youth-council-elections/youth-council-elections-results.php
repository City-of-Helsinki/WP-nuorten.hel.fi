<?php
/**
 * Youth Council Elections results
 */

/**
 * Args type
 *
 * @var array $args
 */
$results = $args;
?>

<div class="youth-council-elections__grid">
    <?php foreach ( $results as $item ) : ?>
        <?php
        get_template_part( 'partials/views/youth-council-elections/candidate', '', $item );
        ?>
    <?php endforeach; ?>
</div>
