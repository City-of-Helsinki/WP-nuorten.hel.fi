<?php
/**
 * WP-Geniem Roles functionality
 */

namespace Geniem\Theme;

use \Geniem\Theme\PostType;

/**
 * Class Roles
 *
 * @package Geniem\Theme
 */
class Roles implements Interfaces\Controller {

    /**
     * Instructor CPT Capabilities.
     * Default is to allow all.
     *
     * @see \Geniem\Theme\PostType\Instructor
     * @var array|bool[]
     */
    private array $caps_instructor = [
        'edit_instructor'              => true,
        'read_instructor'              => true,
        'delete_instructor'            => true,
        'edit_others_instructors'      => true,
        'delete_instructors'           => true,
        'publish_instructors'          => true,
        'publish_instructor'           => true,
        'read_private_instructors'     => true,
        'delete_private_instructors'   => true,
        'delete_published_instructors' => true,
        'delete_others_instructors'    => true,
        'edit_private_instructors'     => true,
        'edit_published_instructors'   => true,
        'edit_instructors'             => true,
    ];

    /**
     * Youth Center CPT Capabilities.
     * Default is to allow all.
     *
     * @see \Geniem\Theme\PostType\YouthCenter
     * @var array|bool[]
     */
    private array $caps_youth_center = [
        'delete_others_youth_centers'    => true,
        'delete_private_youth_centers'   => true,
        'delete_published_youth_centers' => true,
        'delete_youth_center'            => true,
        'delete_youth_centers'           => true,
        'edit_others_youth_centers'      => true,
        'edit_private_youth_centers'     => true,
        'edit_published_youth_centers'   => true,
        'edit_youth_center'              => true,
        'edit_youth_centers'             => true,
        'publish_youth_center'           => true,
        'publish_youth_centers'          => true,
        'read_private_youth_centers'     => true,
        'read_youth_center'              => true,
    ];

    /**
     * Initiative CPT Capabilities.
     * Default is to allow all.
     *
     * @see \Geniem\Theme\PostType\Initiative
     * @var array|bool[]
     */
    private array $caps_initiative = [
        'delete_others_initiatives'    => true,
        'delete_private_initiatives'   => true,
        'delete_published_initiatives' => true,
        'delete_initiative'            => true,
        'delete_initiatives'           => true,
        'edit_others_initiatives'      => true,
        'edit_private_initiatives'     => true,
        'edit_published_initiatives'   => true,
        'edit_initiative'              => true,
        'edit_initiatives'             => true,
        'publish_initiative'           => true,
        'publish_initiatives'          => true,
        'read_private_initiatives'     => true,
        'read_initiative'              => true,
    ];

    /**
     * Vocational School CPT Capabilities.
     * Default is to allow all.
     *
     * @see \Geniem\Theme\PostType\YouthCenter
     * @var array|bool[]
     */
    private array $caps_vocational_school = [
        'delete_others_vocational_schools'    => true,
        'delete_private_vocational_schools'   => true,
        'delete_published_vocational_schools' => true,
        'delete_vocational_school'            => true,
        'delete_vocational_schools'           => true,
        'edit_others_vocational_schools'      => true,
        'edit_private_vocational_schools'     => true,
        'edit_published_vocational_schools'   => true,
        'edit_vocational_school'              => true,
        'edit_vocational_schools'             => true,
        'publish_vocational_school'           => true,
        'publish_vocational_schools'          => true,
        'read_private_vocational_schools'     => true,
        'read_vocational_school'              => true,
    ];

    /**
     * High School CPT Capabilities.
     * Default is to allow all.
     *
     * @see \Geniem\Theme\PostType\YouthCenter
     * @var array|bool[]
     */
    private array $caps_high_school = [
        'delete_others_high_schools'    => true,
        'delete_private_high_schools'   => true,
        'delete_published_high_schools' => true,
        'delete_high_school'            => true,
        'delete_high_schools'           => true,
        'edit_others_high_schools'      => true,
        'edit_private_high_schools'     => true,
        'edit_published_high_schools'   => true,
        'edit_high_school'              => true,
        'edit_high_schools'             => true,
        'publish_high_school'           => true,
        'publish_high_schools'          => true,
        'read_private_high_schools'     => true,
        'read_high_school'              => true,
    ];

    /**
     * Youth Council Elections CPT Capabilities.
     * Default is to allow all.
     *
     * @see \Geniem\Theme\PostType\YouthCouncilElections
     * @var array|bool[]
     */
    private array $caps_youth_council_election = [
        'delete_others_youth_council_elections'    => true,
        'delete_private_youth_council_elections'   => true,
        'delete_published_youth_council_elections' => true,
        'delete_youth_council_election'            => true,
        'delete_youth_council_elections'           => true,
        'edit_others_youth_council_elections'      => true,
        'edit_private_youth_council_elections'     => true,
        'edit_published_youth_council_elections'   => true,
        'edit_youth_council_election'              => true,
        'edit_youth_council_elections'             => true,
        'publish_youth_council_election'           => true,
        'publish_youth_council_elections'          => true,
        'read_private_youth_council_elections'     => true,
        'read_youth_council_election'              => true,
    ];

    /**
     * Core Posts Capabilities
     *
     * @var array|bool[]
     */
    private array $caps_posts = [
        'read_private_posts'     => true,
        'publish_posts'          => true,
        'edit_posts'             => true,
        'edit_others_posts'      => true,
        'edit_published_posts'   => true,
        'delete_posts'           => true,
        'delete_others_posts'    => true,
        'delete_private_posts'   => true,
        'delete_published_posts' => true,
    ];

    /**
     * Core Pages Capabilities
     *
     * @var array|bool[]
     */
    private array $caps_pages = [
        'read_private_pages'     => true,
        'publish_pages'          => true,
        'edit_pages'             => true,
        'edit_others_pages'      => true,
        'delete_pages'           => true,
        'delete_others_pages'    => true,
        'delete_private_pages'   => true,
        'delete_published_pages' => true,
        'edit_published_pages'   => true,
    ];

    /**
     * Core Users Capabilities
     *
     * @var array|bool[]
     */
    private array $caps_users = [
        'edit_users'           => true,
        'delete_users'         => true,
        'create_users'         => true,
        'list_users'           => true,
        'remove_users'         => true,
        'add_users'            => true,
        'promote_users'        => true,
        'manage_network_users' => true,
    ];

    /**
     * Gravity Forms Capabilities
     *
     * @var array|bool[]
     */
    private array $caps_forms = [
        'gravityforms_create_form'      => true,
        'gravityforms_delete_forms'     => true,
        'gravityforms_edit_forms'       => true,
        'gravityforms_preview_forms'    => true,
        'gravityforms_view_entries'     => true,
        'gravityforms_edit_entries'     => true,
        'gravityforms_delete_entries'   => true,
        'gravityforms_view_entry_notes' => true,
        'gravityforms_edit_entry_notes' => true,
        'gravityforms_export_entries'   => true,
        'gravityforms_view_settings'    => true,
        'gravityforms_edit_settings'    => true,
        'gravityforms_view_updates'     => true,
        'gravityforms_view_addons'      => true,
        'gravityforms_system_status'    => true,
        'gravityforms_uninstall'        => true,
        'gravityforms_logging'          => true,
        'gravityforms_api_settings'     => true,
    ];

    /**
     * Hooks
     */
    public function hooks() : void {
        if ( class_exists( '\Geniem\Roles' ) ) {
            $this->add_site_admin_role();
            $this->add_site_editor_role();
            $this->add_site_contributor_role();
            $this->modify_administrator_caps();
            $this->add_site_trainee_role();

            $this->network_add_unfiltered_html_cap(
                [
                    'administrator',
                    'hel_admin',
                    'hel_editor',
                ]
            );
        }
    }

    /**
     * Add unfiltered html capability.
     * Note this cannot be done in network by defining role because WordPress blocks this cap from any other than
     * network admin.
     *
     * @param array $roles Roles to be modified.
     */
    public function network_add_unfiltered_html_cap( $roles ) {

        $current_user       = \wp_get_current_user();
        $current_user_roles = $current_user->roles ?? false;

        if ( ! empty( $roles ) && ! empty( $current_user->roles ) ) {

            \add_filter( 'map_meta_cap', function( $caps, $cap, $user_id, $args ) use ( $roles, $current_user_roles ) { // phpcs:ignore

                foreach ( $roles as $role ) {
                    if ( 'unfiltered_html' === $cap && $this->user_has_role( $role ) ) {

                        $caps = [ 'unfiltered_html' ];
                    }
                }

                return $caps;
            }, 1, 4 );
        }
    }

    /**
     * Check wether current user has the given role.
     *
     * @param string $role_name Role registered name.
     * @return boolean wether user has the role or not.
     */
    public function user_has_role( $role_name ) {

        global $current_user;

        $user_roles = $current_user->roles ?? false;

        if ( $user_roles ) {

            // If user has the given role..
            if ( in_array( $role_name, $user_roles, true ) ) {

                return true;
            }
        }

        return false;
    }

    /**
     * Add site admin role "Pääkäyttäjät"
     */
    public function add_site_admin_role() {
        $hel_admin = \Geniem\Roles::create(
            'hel_admin',
            __( 'HEL Admin', 'nuhe' ),
            array_merge(
                [
                    'read'                 => true,
                    'upload_files'         => true,
                    'manage_categories'    => true,
                    'moderate_comments'    => true,
                    'list_users'           => true,
                    'edit_users'           => true,
                    'manage_options'       => true,
                    'switch_themes'        => false,
                    'edit_theme_options'   => true,
                    'srm_manage_redirects' => true,
                    'unfiltered_html'      => true,
                ],
                $this->caps_posts,
                $this->caps_pages,
                $this->caps_users,
                $this->caps_forms,
                $this->caps_instructor,
                $this->caps_youth_center,
                $this->caps_initiative,
                $this->caps_high_school,
                $this->caps_vocational_school,
                $this->caps_youth_council_election
            )
        );

        // edit.php includes all in the left side sidebar,
        // post-new.php are the ones in the top bar
        $hel_admin->remove_menu_pages( [
            'tools.php'  => [
                'tools.php',
            ],
            'themes.php' => [
                'themes.php',
                'customize.php',
            ],
        ] );

        if ( is_wp_error( $hel_admin ) ) {
            ( new Logger() )->error( $hel_admin->get_error_messages() );
        }
    }

    /**
     * Add site editor role "Ylläpitäjät"
     */
    public function add_site_editor_role() : void {
        $hel_editor = \Geniem\Roles::create(
            'hel_editor',
            __( 'HEL Editor', 'nuhe' ),
            array_merge(
                [
                    'read'            => true,
                    'upload_files'    => true,
                    'unfiltered_html' => true,
                ],
                $this->caps_posts,
                $this->caps_pages,
                $this->caps_instructor,
                $this->caps_youth_center,
                $this->caps_initiative,
                $this->caps_high_school,
                $this->caps_vocational_school,
                $this->caps_youth_council_election
            )
        );

        // edit.php includes all in the left side sidebar,
        // post-new.php are the ones in the top bar
        $hel_editor->remove_menu_pages( [
            'edit.php?post_type=' . PostType\Settings::SLUG,
            'tools.php',
            'themes.php' => [
                'themes.php',
                'customize.php',
            ],
        ] );

        if ( is_wp_error( $hel_editor ) ) {
            ( new Logger() )->error( $hel_editor->get_error_messages() );
        }

    }

    /**
     * Add site contributor role "Sisällöntuottajat"
     */
    public function add_site_contributor_role() : void {
        $hel_contributor = \Geniem\Roles::create(
            'hel_contributor',
            __( 'HEL Contributor', 'nuhe' ),
            array_merge(
                [
                    'read'          => true,
                    'upload_files'  => true,
                    'publish_pages' => false,
                    'edit_pages'    => false,
                ],
                $this->caps_posts,
                $this->caps_instructor,
                $this->caps_youth_center,
                $this->caps_initiative,
                $this->caps_high_school,
                $this->caps_vocational_school,
                $this->caps_youth_council_election
            ),
        );

        // edit.php includes all in the left side sidebar,
        // post-new.php are the ones in the top bar
        $hel_contributor->remove_menu_pages( [
            'edit.php?post_type=' . PostType\Page::SLUG,
            'edit.php?post_type=' . PostType\Settings::SLUG,
            'post-new.php?post_type=' . PostType\Page::SLUG,
            'post-new.php?post_type=' . PostType\Settings::SLUG,
            'tools.php',
            'themes.php' => [
                'themes.php',
                'customize.php',
            ],
        ] );

        if ( is_wp_error( $hel_contributor ) ) {
            ( new Logger() )->error( $hel_contributor->get_error_messages() );
        }
    }

    /**
     * Add site trainee role "Harjoittelijat"
     */
    public function add_site_trainee_role() : void {
        $hel_trainee = \Geniem\Roles::create(
            'hel_trainee',
            __( 'HEL Trainee', 'nuhe' ),
            array_merge(
                [
                    'read'          => true,
                    'upload_files'  => true,
                    'publish_pages' => false,
                ],
                [
                    'edit_pages'         => true,
                    'edit_posts'         => true,
                    'edit_youth_center'  => true,
                    'edit_youth_centers' => true,
                ]
            ),
        );

        // edit.php includes all in the left side sidebar,
        // post-new.php are the ones in the top bar
        $pages = [
            'edit.php?post_type=' . PostType\Settings::SLUG,
            'post-new.php?post_type=' . PostType\Settings::SLUG,
            'tools.php',
            'themes.php' => [
                'themes.php',
                'customize.php',
            ],
        ];

        if ( class_exists( \Geniem\Events\PostTypes\Event::class ) ) {
            $pages[] = [
                'edit.php?post_type=' . \Geniem\Events\PostTypes\Event::get_post_type(),
                'post-new.php?post_type=' . \Geniem\Events\PostTypes\Event::get_post_type(),
            ];
        }

        if ( class_exists( \Geniem\Jobs\PostTypes\Job::class ) ) {
            $pages[] = [
                'edit.php?post_type=' . \Geniem\Jobs\PostTypes\Job::get_post_type(),
                'post-new.php?post_type=' . \Geniem\Jobs\PostTypes\Job::get_post_type(),
            ];
        }

        $hel_trainee->remove_menu_pages( $pages );

        if ( is_wp_error( $hel_trainee ) ) {
            ( new Logger() )->error( $hel_trainee->get_error_messages() );
        }
    }

    /**
     * Modify 'administrator' capabilities
     */
    public function modify_administrator_caps() : void {
        $admin_rights = array_merge(
            $this->caps_instructor,
            $this->caps_youth_center,
            $this->caps_initiative,
        );

        $admin_rights = array_map( function( $item, $key ) {
            return $key;
        }, $admin_rights, array_keys( $admin_rights ) );

        $admin = \Geniem\Roles::get( 'administrator' );

        $admin->add_caps( $admin_rights );

        if ( is_wp_error( $admin ) ) {
            ( new Logger() )->error( $admin->get_error_messages() );
        }

        unset( $admin );
    }
}
