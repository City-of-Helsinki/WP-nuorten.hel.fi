<?php
/**
 * JobAdvertisement entity
 *
 * @link: https://api.hel.fi/linkedevents/v1/event/helsinki:afxsfaqz44/?include=keywords,location
 * @link: https://dev.hel.fi/apis/linkedevents#documentation
 */

namespace Geniem\Theme\Integrations\HelbitRekry;

use DateTime;
use Exception;
use Geniem\Theme\Logger;
use Geniem\Theme\Settings;

/**
 * Class JobAdvertisement
 *
 * @package Geniem\Theme\Integrations\HelbitRekry\Entities
 */
class JobAdvertisement {

    /**
     * Resource url base.
     */
    const URL_BASE = 'https://helbit.fi';

    /**
     * Job data
     *
     * @var mixed
     */
    protected $job_data;

    /**
     * Cnstructor.
     *
     * @param mixed $job_data Job data.
     * @param mixed $job_link Job link.
     */
    public function __construct( $job_data, $job_link ) {
        $this->job_data       = $job_data;
        $this->job_data->link = ! empty( $job_link ) && isset( $job_link->url ) ? self::URL_BASE . $job_link->url : '';
    }

    /**
     * Get title
     *
     * @return string|null
     */
    public function get_title() {
        return $this->job_data->title ?? null;
    }

    /**
     * Get link
     *
     * @return string|null
     */
    public function get_link() {
        return $this->job_data->link ?? null;
    }

    /**
     * Get Id
     *
     * @return string|null
     */
    public function get_id() {
        return $this->job_data->id ?? null;
    }

    /**
     * Get employment
     *
     * @return string|null
     */
    public function get_employment() {
        return $this->job_data->employment ?? null;
    }

    /**
     * Get employment type
     *
     * @return string|null
     */
    public function get_employment_type() {
        return $this->job_data->employmentType ?? null;
    }

    /**
     * Get organization
     *
     * @return string|null
     */
    public function get_organization() {
        return $this->job_data->organization ?? null;
    }

    /**
     * Get organization description
     *
     * @return string|null
     */
    public function get_organization_desc() {
        return $this->job_data->organizationDesc ?? null;
    }

    /**
     * Get description
     *
     * @return string|null
     */
    public function get_desc() {
        return $this->job_data->jobDesc ?? null;
    }

    /**
     * Get duration
     *
     * @return string|null
     */
    public function get_duration() {
        return $this->job_data->jobDuration ?? null;
    }

    /**
     * Get publication end time
     *
     * @return string|null
     */
    public function get_publication_end_time() {
        return $this->job_data->publicationEnds ?? null;
    }

    /**
     * Get salary
     *
     * @return string|null
     */
    public function get_salary() {
        return $this->job_data->salary ?? null;
    }

    /**
     * Get address
     *
     * @return string|null
     */
    public function get_address() {
        return $this->job_data->address ?? null;
    }

    /**
     * Get postal code
     *
     * @return string|null
     */
    public function get_postal_code() {
        return $this->job_data->postalCode ?? null;
    }

    /**
     * Get number of vacancy
     *
     * @return string|null
     */
    public function get_number_of_vacancy() {
        return $this->job_data->numberOfVacancy ?? null;
    }

    /**
     * Get image
     *
     * @return string|null
     */
    public function get_image() {
        $logo = $this->job_data->logo ?? Settings::get_setting( 'default_post_image' );

        if ( empty( $logo ) ) {
            return null;
        }

        if ( is_array( $logo ) ) {
            $id = $logo['ID'];
            return wp_get_attachment_image_url( $id, 'large' );
        }

        return self::URL_BASE . $logo;
    }

    /**
     * Get publication end time as DateTime instance.
     *
     * @return DateTime|null
     */
    public function get_publication_end_time_as_datetime() {
        if ( empty( $this->job_data->publicationEnds ) ) {
            return null;
        }

        try {
            $date_time   = new DateTime( $this->job_data->publicationEnds );
            $date_format = 'd.n.Y';
            $time_format = 'H:i';
            return sprintf(
                '%s %s %s',
                $date_time->format( $date_format ),
                __( 'at', 'nuhe', ),
                $date_time->format( $time_format )
            );
        }
        catch ( Exception $e ) {
            ( new Logger() )->info( $e->getMessage(), $e->getTrace() );
        }

        return null;
    }
}
