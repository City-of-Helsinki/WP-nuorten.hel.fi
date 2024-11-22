<?php
/**
 * This class handles the registration of Gutenberg blocks
 * that have been created with ACF Codifier.
 */

namespace Geniem\Theme;

/**
 * Class Blocks.
 */
class BlocksController implements Interfaces\Controller {

    /**
     * Initialize the class' variables and add methods
     * to the correct action hooks.
     *
     * @return void
     */
    public function hooks() : void {

        // Define allowed block types.
        \add_filter( 'allowed_block_types', \Closure::fromCallable( [ $this, 'allowed_block_types' ] ), 10, 2 );

        // Require ACF blocks
        \add_action( 'acf/init', \Closure::fromCallable( [ $this, 'require_block_files' ] ) );

        // Add post date after lead paragraph block in single post
        \add_filter( 'render_block', [ $this, 'add_post_date_after_lead_paragraph' ], 10, 2 );

    }

    /**
     * This method loops through all files in the
     * Blocks directory and requires them.
     */
    private function require_block_files() : void {
        $files = array_diff( scandir( __DIR__ . '/Blocks' ), [ '.', '..', 'BaseBlock.php' ] );

        // Require
        array_walk(
        // Loop through all files and directories
            $files,
            function ( $block ) {
                $block_class_name = str_replace( '.php', '', $block );
                $class_name       = __NAMESPACE__ . "\\Blocks\\{$block_class_name}";

                if ( class_exists( $class_name ) ) {
                    new $class_name();
                }
            }
        );
    }

    /**
     * Set the allowed block types. By default the allowed_blocks array
     * is empty, which means that all block types are allowed. We simply
     * fill the array with the block types that the theme supports.
     *
     * @param bool|array $allowed_blocks An empty array.
     * @param \WP_Post   $post           The post resource data.
     *
     * @return array An array of allowed block types.
     */
    private function allowed_block_types( $allowed_blocks, $post ) {
        $blocks = [
            'core/block'         => [],
            'core/template'      => [],
            'core/quote'         => [],
            'core/html'          => [],
            'core/list'          => [
                'post_types' => [
                    PostType\Page::SLUG,
                    PostType\Post::SLUG,
                ],
            ],
            'core/list-item'     => [
                'post_types' => [
                    PostType\Page::SLUG,
                    PostType\Post::SLUG,
                ],
            ],
            'acf/image'          => [
                'post_types' => [
                    PostType\Page::SLUG,
                    PostType\Post::SLUG,
                ],
            ],
            'acf/video'          => [
                'post_types' => [
                    PostType\Page::SLUG,
                    PostType\Post::SLUG,
                ],
            ],
            'acf/podcast'        => [
                'post_types' => [
                    PostType\Page::SLUG,
                    PostType\Post::SLUG,
                ],
            ],
            'acf/gallery'        => [
                'post_types' => [
                    PostType\Page::SLUG,
                    PostType\Post::SLUG,
                ],
            ],
            'acf/notification'    => [
                'post_types' => [
                    PostType\Page::SLUG,
                    PostType\Post::SLUG,
                ],
            ],
            'ggb/heading'        => [
                'post_types' => [
                    PostType\Page::SLUG,
                    PostType\Post::SLUG,
                    PostType\YouthCenter::SLUG,
                ],
            ],
            'ggb/lead-paragraph' => [
                'post_types' => [
                    PostType\Page::SLUG,
                    PostType\Post::SLUG,
                    PostType\YouthCenter::SLUG,
                ],
            ],
            'ggb/paragraph'      => [
                'post_types' => [
                    PostType\Page::SLUG,
                    PostType\Post::SLUG,
                    PostType\YouthCenter::SLUG,
                ],
            ],
            'gravityforms/form'  => [
                'post_types' => [
                    PostType\Page::SLUG,
                ],
            ],
            'acf/accordion'      => [
                'post_types' => [
                    PostType\Page::SLUG,
                    PostType\Post::SLUG,
                    PostType\YouthCenter::SLUG,
                ],
            ],
        ];

        $blocks = apply_filters( 'modify_allowed_block_types', $blocks );

        $post_type     = \get_post_type( $post );
        $page_template = \get_page_template_slug( $post->ID );

        $allowed_blocks = [];

        foreach ( $blocks as $block => $rules ) {
            if ( empty( $rules ) ) {
                $allowed_blocks[] = $block;
                continue;
            }

            $allowed_post_type = false;

            if ( isset( $rules['post_types'] ) ) {
                $allowed_post_type = in_array( $post_type, $rules['post_types'], true );
            }

            $allowed_template = false;

            if ( isset( $rules['templates'] ) ) {
                $allowed_template = in_array( $page_template, $rules['templates'], true );
            }

            if ( $allowed_post_type || $allowed_template ) {
                $allowed_blocks[] = $block;
            }
        }

        return $allowed_blocks;
    }

    /**
     * Adds a date div before or after lead-paragraph.
     *
     * @param string $block_content The block content about to be appended.
     * @param array  $block         The full block, including name and attributes.
     *
     * @return string The block HTML.
     */
    public function add_post_date_after_lead_paragraph( $block_content, array $block = [] ) {
        global $post;

        // Remove filter in order to run the function only once
        \remove_filter( 'render_block', [ $this, 'add_post_date_after_lead_paragraph' ], 10, 2 );

        if ( ! \has_blocks() || \get_field( 'hide_publish_date', $post->ID ) || ! \is_single() ) {
            return $block_content;
        }

        $published_string_format = '<time class="datetime published" datetime="%1$s">%2$s</time>';
        $published_time_string   = sprintf(
            $published_string_format,
            esc_attr( \get_the_date() ),
            esc_html( \get_the_date() )
        );

        $updated_time_string = '';
        if ( \get_the_date() !== \get_the_modified_date() ) {
            $updated_string_format = '<time class="datetime updated" datetime="%1$s">%2$s</time>';
            $updated_time_string   = sprintf(
                $updated_string_format,
                esc_attr( \get_the_modified_date() ),
                esc_html( ', ' . __( 'updated', 'nuhe' ) . ' ' . \get_the_modified_date() )
            );
        }

        $post_date_div = '<div class="post-meta">' . $published_time_string . $updated_time_string . '</div>';

        $blocks = \parse_blocks( $post->post_content );
        if ( $blocks[0]['blockName'] === 'ggb/lead-paragraph' && $block['blockName'] === 'ggb/lead-paragraph' ) {
            return $block['innerHTML'] . $post_date_div;
        }

        $block_html = $block['innerHTML'];

        if ( false !== strpos( $block['blockName'], 'acf/' ) ) {
            $block_html = render_block( $block );
        }

        return $post_date_div . $block_html;
    }
}
