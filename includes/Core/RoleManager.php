<?php
/**
 * Role Manager Class.
 *
 * @package ClientGuard\Core
 */

namespace ClientGuard\Core;

/**
 * Class RoleManager
 */
class RoleManager {

	/**
	 * Get all editable roles.
	 *
	 * @return array List of Study roles.
	 */
	public static function get_roles() {
		if ( ! function_exists( 'get_editable_roles' ) ) {
			require_once ABSPATH . 'wp-admin/includes/user.php';
		}
		return get_editable_roles();
	}

	/**
	 * Get "Human Friendly" permission groups.
     * Maps abstract capabilities to understandable tasks.
	 *
	 * @return array
	 */
	public static function get_permission_map() {
		return array(
            'content_management' => array(
                'label' => __( 'Content Management', 'clientguard' ),
                'caps'  => array(
                    'edit_posts' => __( 'Edit Posts', 'clientguard' ),
                    'publish_posts' => __( 'Publish Posts', 'clientguard' ),
                    'delete_posts' => __( 'Delete Posts', 'clientguard' ),
                    'edit_pages' => __( 'Edit Pages', 'clientguard' ),
                    'publish_pages' => __( 'Publish Pages', 'clientguard' ),
                ),
            ),
            'plugin_management' => array(
                'label' => __( 'Plugins & Themes', 'clientguard' ),
                'caps'  => array(
                    'activate_plugins' => __( 'Activate Plugins', 'clientguard' ),
                    'install_plugins'  => __( 'Install Plugins', 'clientguard' ),
                    'edit_plugins'     => __( 'Edit Plugins', 'clientguard' ),
                    'switch_themes'    => __( 'Switch Themes', 'clientguard' ),
                ),
            ),
            'site_settings' => array(
                'label' => __( 'Site Settings', 'clientguard' ),
                'caps'  => array(
                    'manage_options' => __( 'Manage Options', 'clientguard' ),
                    'edit_theme_options' => __( 'Edit Theme Options', 'clientguard' ),
                ),
            ),
        );
	}

    /**
     * Update capabilities for a role.
     * 
     * @param string $role_slug The role to update.
     * @param array  $caps      Array of capabilities to set (true/false).
     * @return bool Success status.
     */
    public static function update_role_caps( $role_slug, $caps ) {
        $role = get_role( $role_slug );
        if ( ! $role ) {
            return false;
        }

        foreach ( $caps as $cap => $grant ) {
            if ( $grant ) {
                $role->add_cap( $cap );
            } else {
                $role->remove_cap( $cap );
            }
        }
        return true;
    }
}
