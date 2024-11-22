<?php
/**
 * Gravityforms form hooks
 */

namespace Geniem\Theme;

use Geniem\Theme\Traits;
use Geniem\Theme\PostType\Initiative;
use GFAPI;

/**
 * Class GravityForms
 *
 * @package Geniem\Theme
 */
class GravityForms implements Interfaces\Controller {

    use Traits\Initiative;

    /**
     * Hooks
     */
    public function hooks() : void {
        \add_action( 'gform_form_settings', \Closure::fromCallable( [ $this, 'modify_settings' ] ), 10, 2 );
        \add_action( 'gform_after_submission', \Closure::fromCallable( [ $this, 'create_initiative' ] ), 10, 2 );
        \add_action( 'gform_after_submission', \Closure::fromCallable( [ $this, 'delete_specific_field_from_entry' ] ), 10, 2);
        \add_filter( 'gform_submit_button', \Closure::fromCallable( [ $this, 'button' ] ) );
        \add_filter( 'gform_pre_form_settings_save', \Closure::fromCallable( [ $this, 'save_form_setting' ] ) );
    }

    /**
     * Alter button
     *
     * @param string $button Button HTML.
     *
     * @return string
     */
    private function button( $button ) {
        $dom = new \DOMDocument();
        $dom->loadHTML( '<?xml encoding="utf-8" ?>' . $button );

        $input      = $dom->getElementsByTagName( 'input' )->item( 0 );
        $new_button = $dom->createElement( 'button' );

        $label = $dom->createElement( 'span' );
        $label->setAttribute( 'class', 'hds-button__label' );
        $label->appendChild( $dom->createTextNode( $input->getAttribute( 'value' ) ) );

        $new_button->appendChild( $label );

        $input->removeAttribute( 'value' );

        foreach ( $input->attributes as $attribute ) {
            $new_button->setAttribute( $attribute->name, $attribute->value );
        }

        $new_button->setAttribute( 'class', 'hds-button hds-button--primary' );

        $input->parentNode->replaceChild( $new_button, $input );

        return $dom->saveHtml( $new_button );
    }

    /**
     * Modify form settings.
     *
     * @param array $settings Setting array.
     * @param array $form     Form array.
     *
     * @return array
     */
    private function modify_settings( array $settings, array $form ) : array {
        $checked = rgar( $form, 'is_initiative_form' );
        $checked = ! empty( $checked ) ? ' checked="checked"' : '';
        $settings[ __( 'Form Basics', 'gravityforms' ) ]['is_initiative_form'] = "
        <tr>
           <th><label for='is_initiative_form'>Tämä on aloitelomake</label></th>
            <td><input{$checked} value='1' name='is_initiative_form' type='checkbox'></td>
        </tr>";

        return $settings;
    }

    /**
     * Save form setting.
     *
     * @param array $form Form array.
     *
     * @return array
     */
    private function save_form_setting( array $form ) : array {
        $form['is_initiative_form'] = rgpost( 'is_initiative_form' );
        return $form;
    }

    /**
     * Create initiative.
     *
     * @param array $entry Submitted form data.
     * @param array $form  Form data as array.
     *
     * @return void
     */
    private function create_initiative( array $entry, array $form ) : void {
        // bail early if form is not initiative form
        if ( empty( rgar( $form, 'is_initiative_form' ) ) || rgar( $entry, 'status' ) === 'spam' ) {
            return;
        }

        $args = [
            'post_type'    => Initiative::SLUG,
            'post_title'   => $entry['6'] ?: 'aloitteen otsikko puuttuu',
            'post_status'  => 'pending',
            'post_content' => $entry['7'] ?: '',
            'meta_input'   => [
                'ruuti_initiative_category' => $entry['4'] ?: '',
                'ruuti_initiative_location' => $entry['23'] ?: '',
            ],
        ];

        $post_id = wp_insert_post( $args );

        if ( $post_id === 0 || $post_id instanceof \WP_Error || empty( $entry['8'] ) ) {
            return;
        }

        $this->generate_featured_image( $entry['8'], $post_id );
    }

    /**
     * Generate featured image.
     *
     * @param string $image_url
     *
     * @param int $post_id
     *
     * @return void
     */
    private function generate_featured_image( string $image_url, int $post_id ) : void {
        $upload_dir = wp_upload_dir();
        $image_data = file_get_contents( $image_url );
        $filename   = basename( $image_url );
        $file       = wp_mkdir_p( $upload_dir['path'] ) ? $upload_dir['path'] . '/' . $filename : $upload_dir['basedir'] . '/' . $filename;

        file_put_contents( $file, $image_data );

        $wp_filetype = wp_check_filetype( $filename, null );
        $attachment  = [
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name( $filename ),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ];
        $attach_id   = wp_insert_attachment( $attachment, $file, $post_id );

        require_once( ABSPATH . 'wp-admin/includes/image.php' );

        $attach_data = wp_generate_attachment_metadata( $attach_id, $file );

        wp_update_attachment_metadata( $attach_id, $attach_data );
        set_post_thumbnail( $post_id, $attach_id );
    }

    /**
     * Delete fields from a form after submit
     *
     * @param array $entry Submitted form data.
     * @param array $form  Form data as array.
     *
     * @return void
     */
    private function delete_specific_field_from_entry( $entry, $form ) {
        // Check if the form is an initiative form and the entry is not spam
        if ( empty( rgar( $form, 'is_initiative_form' ) ) || rgar( $entry, 'status' ) === 'spam' ) {
            return;
        }

        // Specify the field IDs you want to delete
        $field_ids_to_delete = [ 11, 12, 14, 21 ];

        // Iterate over each field ID and set its value to an empty string
        foreach ( $field_ids_to_delete as $field_id ) {
            GFAPI::update_entry_field( $entry['id'], $field_id, '' );
        }
    }
}
