<?php
/**
 * Permission Manager Class.
 *
 * @package ClientGuard\Core
 */

namespace ClientGuard\Core;

/**
 * Class PermissionManager
 */
class PermissionManager {

	/**
	 * Meta key for storing permission overrides.
	 */
	const CAP_META_KEY = '_clientguard_permissions';

	/**
	 * Initialize the class.
	 */
	public function init() {
		add_filter( 'user_has_cap', array( $this, 'filter_user_caps' ), 10, 3 );
        add_filter( 'map_meta_cap', array( $this, 'map_custom_caps' ), 10, 4 );
	}

    /**
     * Map custom capabilities.
     *
     * @param array  $caps    The user's capabilities.
     * @param string $cap     Capability name.
     * @param int    $user_id The user ID.
     * @param array  $args    Arguments.
     * @return array
     */
    public function map_custom_caps( $caps, $cap, $user_id, $args ) {
        // REMOVED: Do not auto-map to manage_options. Only explict capability holders (Agency Role) can access.
        return $caps;
    }

	/**
	 * Filter user capabilities.
	 *
	 * @param array $allcaps All capabilities of the user.
	 * @param array $caps    The specific capability being checked.
	 * @param array $args    Additional arguments (args[0] = cap, args[1] = user_id).
	 * @return array Modified capabilities.
	 */
	public function filter_user_caps( $allcaps, $caps, $args ) {
		// Ensure we have a user ID.
		if ( ! isset( $args[1] ) ) {
			return $allcaps;
		}

		$user_id = $args[1];
		$cap_checked = $args[0];

        // Check if we are checking our own meta key to safely exit if needed, though not expected here.

        // SAFETY: If the user is an Administrator, never deny critical management capabilities.
        // This prevents accidental lockouts if an admin denies 'manage_options' or 'edit_users' for themselves.
        $user = get_userdata( $user_id );
        if ( $user && in_array( 'administrator', (array) $user->roles ) ) {
            $critical_caps = array( 
                'manage_options', 
                'edit_users', 
                'list_users', 
                'promote_users', 
                'create_users', 
                'delete_users',
                // \ClientGuard\Helpers\Capabilities::MANAGE_PERMISSIONS 
            );
            
            // Map the custom cap to checks if needed, essentially if we are checking a critical cap,
            // we trust the Admin role (which has them) and ignore overrides.
            if ( in_array( $cap_checked, $critical_caps ) ) {
                 // Return purely based on role (which is true), ignoring overrides.
                 return $allcaps;
            }
        }



		// Get Overrides.
		$overrides = get_user_meta( $user_id, self::CAP_META_KEY, true );

		if ( ! is_array( $overrides ) || empty( $overrides ) ) {
			return $allcaps;
		}

		// Check if there is an explicit override for this specific capability.
		// Override values: 1 = Allow, -1 = Deny, 0 (or unset) = Default/Inherit.
		if ( isset( $overrides[ $cap_checked ] ) ) {
			if ( 1 === (int) $overrides[ $cap_checked ] ) {
				$allcaps[ $cap_checked ] = true;
			} elseif ( -1 === (int) $overrides[ $cap_checked ] ) {
				$allcaps[ $cap_checked ] = false;
			}
		}

        // Also handle "primitive" capabilities if map_meta_cap isn't used or if we need to force it.
        // But normally user_has_cap is enough for the final check.

		return $allcaps;
	}

    /**
     * Get permission groups for UI.
     * 
     * @return array
     */
	public static function get_permission_map() {
        // Reusing the map structure, expanding if needed.
		return array(
            'content_management' => array(
                'label' => __( 'Content Management', 'clientguard' ),
                'caps'  => array(
                    'edit_posts' => __( 'Edit Posts', 'clientguard' ),
                    'publish_posts' => __( 'Publish Posts', 'clientguard' ),
                    'delete_posts' => __( 'Delete Posts', 'clientguard' ),
                    'edit_pages' => __( 'Edit Pages', 'clientguard' ),
                    'publish_pages' => __( 'Publish Pages', 'clientguard' ),
                    'upload_files' => __( 'Upload Files', 'clientguard' ),
                ),
            ),
            'plugin_management' => array(
                'label' => __( 'Plugins & Themes', 'clientguard' ),
                'caps'  => array(
                    'activate_plugins' => __( 'Activate Plugins', 'clientguard' ),
                    'install_plugins'  => __( 'Install Plugins', 'clientguard' ),
                    'edit_plugins'     => __( 'Edit Plugins', 'clientguard' ),
                    'switch_themes'    => __( 'Switch Themes', 'clientguard' ),
                    'update_plugins'   => __( 'Update Plugins', 'clientguard' ),
                    'update_themes'    => __( 'Update Themes', 'clientguard' ),
                ),
            ),
            'site_settings' => array(
                'label' => __( 'Site Settings', 'clientguard' ),
                'caps'  => array(
                    'manage_options' => __( 'Manage Options', 'clientguard' ),
                    'edit_theme_options' => __( 'Edit Theme Options', 'clientguard' ),
                    'customize' => __( 'Customize Site', 'clientguard' ),
                ),
            ),
            'users' => array(
                'label' => __( 'Users', 'clientguard' ),
                'caps' => array(
                    'edit_users' => __( 'Edit Users', 'clientguard' ),
                    'delete_users' => __( 'Delete Users', 'clientguard' ),
                    'create_users' => __( 'Create Users', 'clientguard' ),
                    'list_users' => __( 'List Users', 'clientguard' ),
                ),
            ),
        );
	}
}
