<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package nuhe
 */

use Geniem\Theme\Model\Footer;

?>
</div><!-- Page wrapper -->

    <?php
    $model            = new Footer();
    $fields           = $model->get_footer_fields();
    $bg_color_class   = empty( $fields['footer_bg_color'] ) ||
                    $fields['footer_bg_color'] === 'default' ?
                    ' has-bg-color--light-blue' : " has-bg-color--{$fields['footer_bg_color']}";
    $text_color_class = $fields['footer_bg_color'] !== 'default' ? ' text-white' : '';

    $logo = $fields['footer_bg_color'] === 'default' ? $model->logo() : $model->logo_white();

    get_template_part( 'partials/footer', '', [
        'askem_scripts'    => $model->get_askem_scripts(),
        'logo'             => $logo,
        'service_name'     => $model->service_name(),
        'fields'           => $fields,
        'bg_color_class'   => $bg_color_class,
        'text_color_class' => $text_color_class,
    ] );
    ?>

<?php wp_footer(); ?>

</body>
</html>
