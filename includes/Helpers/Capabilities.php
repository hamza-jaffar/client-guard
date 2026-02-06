<?php
/**
 * Capability Helper Class.
 *
 * @package ClientGuard\Helpers
 */

namespace ClientGuard\Helpers;

/**
 * Class Capabilities
 */
class Capabilities {

	/**
	 * Capability to manage ClientGuard settings.
	 */
	const MANAGE_PERMISSIONS = 'clientguard_manage_permissions';

    /**
     * Get the capability required to manage this plugin.
     * 
     * @return string
     */
    public static function get_manage_capability() {
        return self::MANAGE_PERMISSIONS;
    }

	/**
	 * Check if current user can manage the plugin.
	 *
	 * @return bool
	 */
	public static function current_user_can_manage() {
        if ( ! current_user_can('administrator') ) {
            return false;
        }

        $user_id = get_current_user_id();
        $trusted = get_option('clientguard_trusted_admins');
		
        if ( empty( $trusted ) ) {
            return true;
        }

        if ( ! is_array( $trusted ) || ! in_array( $user_id, $trusted ) ) {
            return false;
        }

		return true;
	}
}
