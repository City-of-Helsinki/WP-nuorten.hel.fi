<?php
/**
 * This file controls what modifications are done to the site admin.
 */

namespace Geniem\Theme;

/**
 * Define the controller class.
 */
class Admin implements Interfaces\Controller {

    /**
     * Initialize the class' variables and add methods
     * to the correct action hooks.
     *
     * @return void
     */
    public function hooks() : void {
        // Remove Unnecessary styles from tinymce.
        \add_filter( 'tiny_mce_before_init', \Closure::fromCallable( [ $this, 'modify_tinymce_headings' ] ) );

        // Add current post template to body class.
        \add_action( 'admin_body_class', \Closure::fromCallable( [ $this, 'add_template_slug_to_body_class' ] ) );

        // Disabling editor full screen mode by default.
        \add_action(
            'enqueue_block_editor_assets',
            \Closure::fromCallable( [ $this, 'disable_editor_fullscreen_by_default' ] )
        );

        // remove comments meta-box
        add_action( 'init', \Closure::fromCallable( [ $this, 'remove_comment_support' ] ) );

        // remove comments page from admin
        add_action( 'admin_menu', \Closure::fromCallable( [ $this, 'remove_comments_page' ] ) );
    }

    /**
     * Modify TinyMCE
     * Based on https://gist.github.com/psorensen/ab45d9408be658b6f90dfeabf1c9f4e6
     *
     * @param array $tags TinyMCE tags.
     * @return array $tags
     */
    private function modify_tinymce_headings( $tags = [] ) {
        // H1, H5, H6, pre and address removed.
        $formats = [
            'p'  => 'Tekstikappale',
            'h2' => 'Otsikko 2',
            'h3' => 'Otsikko 3',
            'h4' => 'Otsikko 4',
        ];

        // Glue array elements to string
        \array_walk( $formats, function ( $key, $val ) use ( &$block_formats ) {
            $block_formats .= esc_attr( $key ) . '=' . esc_attr( $val ) . ';';
        }, $block_formats = '' );
        $tags['block_formats'] = $block_formats;

        return $tags;
    }

    /**
     * Disable editor full screen mode by default.
     */
    private function disable_editor_fullscreen_by_default() {

        $script = "
window.onload = function() {
    if ( wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ) ) {
        wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' );
    }
}";

        \wp_add_inline_script( 'wp-blocks', $script );
    }

    /**
     * This adds a class to the body class list. The class is determined
     * by the template of the edited page. Only for pages.
     *
     * @param string $classes The original body class string.
     *
     * @return string $class The possibly modified body class string.
     */
    private function add_template_slug_to_body_class( $classes = '' ) {

        // Global object containing current admin page
        global $pagenow;

        // We should check against nonce, but we wont, so ignore recommendation.
        // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( 'post.php' === $pagenow && ! empty( $_GET['post'] ) ) {

            $id   = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
            $type = \get_post_type( $id );

            if ( $type === 'page' ) {

                $template = \get_page_template_slug( $id );

                // If template is empty, we are editing the default template.
                $file_name = 'page-default';

                // If not empty use the template name in the class string.
                if ( $template !== '' ) {
                    $file_name_with_suffix = substr( $template, ( strpos( $template, '/' ) + 1 ) );
                    $file_name             = substr( $file_name_with_suffix, 0, strpos( $file_name_with_suffix, '.' ) );
                }

                $classes .= " geniem-${file_name}";
            }
        }

        return $classes;
    }

    /**
     * This removes comments meta-box from defined post-types.
     *
     * @return void
     */
    private function remove_comment_support() : void {
        \remove_post_type_support( 'post', 'comments' );
        \remove_post_type_support( 'page', 'comments' );
    }

    /**
     * This removes comments page from admin.
     *
     * @return void
     */
    private function remove_comments_page() {
        \remove_menu_page( 'edit-comments.php' );
    }
}
