<?php
/**
 * News list partial
 *
 * Accepts args:
 *  - data: <array>
 */

$args = \wp_parse_args( $args, [
    'data' => [],
] );

$layout_classes = [
    'nuhe-layout',
    'nuhe-card-list',
];
$layout_classes = implode( ' ', $layout_classes );
// Determine the anchor text
$anchor_text = ! empty( $args['anchor_link_text'] ) ? $args['anchor_link_text'] : ( ! empty( $args['title'] ) ? $args['title'] : '' );
$anchor_id   = ! empty( $anchor_text ) ? \sanitize_title( $anchor_text ) : '';
?>

<div class="<?php echo \esc_attr( $layout_classes ); ?> nuhe-layout--main-content nuhe-news-list"<?php echo ! empty( $anchor_id ) ? ' id="' . \esc_attr( $anchor_id ) . '"' : ''; ?>>
    <div class="container">

        <?php if ( ! empty( $args['title'] ) ) : ?>
            <h2 class="nuhe-card-list__title">
                <?php echo \esc_html( $args['title'] ); ?>
            </h2>
        <?php endif; ?>

        <?php if ( ! empty( $args['description'] ) ) : ?>
            <div class="nuhe-card-list__description">
                <?php echo \wp_kses_post( $args['description'] ); ?>
            </div>
        <?php endif; ?>

        <?php if ( ! empty( $args['links'] ) ) : ?>
            <ul class="nuhe-card-list__items grid-columns grid-columns--4">
                <?php foreach ( $args['links'] as $link ) {

                $url = $link['link']['url'];
                $response = \wp_remote_get( $url );

                if ( ! \is_wp_error( $response ) && $response['response']['code'] === 200 ) {
                $html = \wp_remote_retrieve_body( $response );

                $doc = new DOMDocument();
                $doc->loadHTML( $html );

                // Get the title
                $title = '';
                if ( ! empty( $link['article_title'] ) ) {
                    $title = $link['article_title'];
                } else {
                    $meta_tags = $doc->getElementsByTagName( 'meta' );
                    foreach ( $meta_tags as $tag ) {
                        if ( $tag->getAttribute( 'property' ) === 'og:title' ) {
                            $title = $tag->getAttribute( 'content' );
                            break;
                        }
                    }
                }

                // Get the date posted
                $date_published = '';
                // Prioritize the filled field over the meta date
                if ( ! empty( $link['article_date'] ) ) {
                    // Handle $link as an array and use the filled field
                    $date_published = date('j.n.Y', strtotime($link['article_date']));
                } else {
                    // Handle $link as a DOMElement object
                    $meta_tags = $doc->getElementsByTagName('meta');
                    foreach ($meta_tags as $tag) {
                        if ($tag->getAttribute('itemprop') === 'datePublished') {
                            $date_published = $tag->getAttribute('content');
                            // Parse and format the date
                            $date_published = date('j.n.Y', strtotime($date_published));
                            break;
                        }
                    }
                }

                // Get the description
                $description = '';
                if ( ! empty( $link['article_description'] ) ) {
                    $description = $link['article_description'];
                } else {
                    $meta_tags = $doc->getElementsByTagName( 'meta' );
                    foreach ( $meta_tags as $tag ) {
                        if ( $tag->getAttribute( 'property' ) === 'og:description' ) {
                            $description = $tag->getAttribute( 'content' );
                            break;
                        }
                    }
                    // Fallback to meta description
                    if ( empty( $description ) ) {
                        foreach ( $meta_tags as $tag ) {
                            if ( $tag->getAttribute( 'name' ) === 'description' ) {
                                $description = $tag->getAttribute( 'content' );
                                break;
                            }
                        }
                    }
                }

                // Get the featured image
                $featured_image_url = '';
                // Get the og:image meta tag
                $meta_tags = $doc->getElementsByTagName( 'meta' );
                foreach ( $meta_tags as $tag ) {
                    if ( $tag->getAttribute( 'property' ) === 'og:image' ) {
                        $featured_image_url = $tag->getAttribute( 'content' );
                        break;
                    }
                }

                // Get the domain
                $domain = parse_url( $url, PHP_URL_HOST );
                $domain = str_replace( 'www.', '', $domain );
                ?>
                <li class="nuhe-card-list__item card-list-item grid-columns__column">
                    <div class="custom-block">
                        <h3 class="nuhe-news-list__item-title card-list-item-title"><?php echo $title; ?></h3>
                        <?php if ( ! empty( $date_published ) ) : ?>
                            <p><?php echo $date_published; ?></p>
                        <?php endif; ?>
                        <?php if ( ! empty( $featured_image_url ) ) : ?>
                        <div class="nuhe-news-list__item-image">
                            <a href="<?php echo esc_url( $url ); ?>" target="_blank">
                                <img src="<?php echo $featured_image_url; ?>" alt="Featured Image">
                            </a>
                            <?php if ( ! empty( $domain ) ) : ?>
                                <span class="nuhe-news-list__item-domain"><?php echo $domain; ?></span>
                            <?php endif; ?>
                        </div>
                        <?php endif; ?>
                        <?php if ( ! empty( $description ) ) : ?>
                            <p class="nuhe-news-list__item-description"><?php echo $description; ?></p>
                        <?php endif; ?>
                    </div>
                </li>
                <?php }
                } ?>
            </ul>
        <?php endif; ?>

        <?php if ( $args['context'] === 'main-content' && ! empty( $args['link'] ) ) : ?>
            <div class="call-to-action">
                <a href="<?php echo \esc_url( $args['link']['url'] ); ?>" class="call-to-action__link" target="<?php echo \esc_attr( $args['link']['target'] ); ?>">
                    <span class="call-to-action__text">
                        <?php echo \esc_html( $args['link']['title'] ); ?>
                    </span>
                    <?php \get_template_part( 'partials/icon', '', [ 'icon' => 'arrow-right' ] ); ?>
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>
