<?php
/**
 * User Manager Class.
 *
 * @package ClientGuard\Admin
 */

namespace ClientGuard\Admin;

use ClientGuard\Core\PermissionManager;
use ClientGuard\Helpers\Capabilities;

/**
 * Class UserManager
 */
class UserManager {

    /**
     * Get users with their roles.
     *
     * @return array List of users.
     */
    public static function get_users() {
        return get_users( array( 'orderby' => 'display_name' ) );
    }

    /**
     * Save permission overrides for a user.
     * 
     * @param int   $user_id The user ID.
     * @param array $overrides Key-value pair of cap => value (1, -1, 0).
     */
    public static function save_user_overrides( $user_id, $overrides ) {
        // 1. Strict Capability Check
        if ( ! Capabilities::current_user_can_manage() ) {
            return false;
        }

        // 2. Prevent Self-Editing
        if ( get_current_user_id() === $user_id ) {
             // Security hardening: Admins cannot edit their own permissions to prevent locking themselves out 
             // or bypassing safe-guards inadvertently.
            return false;
        }

        // Clean up 0s (defaults) to keep meta size small.
        foreach ( $overrides as $cap => $val ) {
            if ( 0 === (int) $val ) {
                unset( $overrides[ $cap ] );
            }
        }

        update_user_meta( $user_id, PermissionManager::CAP_META_KEY, $overrides );
        return true;
    }

    /**
     * Get current overrides for a user.
     * 
     * @param int $user_id User ID.
     * @return array
     */
    public static function get_user_overrides( $user_id ) {
        $overrides = get_user_meta( $user_id, PermissionManager::CAP_META_KEY, true );
        return is_array( $overrides ) ? $overrides : array();
    }

    /**
     * Trust an admin user.
     *
     * @param int $user_id User ID.
     */
    public static function trust_user( $user_id ) {
        if ( ! Capabilities::current_user_can_manage() ) { return; }
        
        $trusted = get_option( 'clientguard_trusted_admins', array() );
        if ( ! in_array( $user_id, $trusted ) ) {
            $trusted[] = $user_id;
            update_option( 'clientguard_trusted_admins', $trusted );
        }
    }

    /**
     * Untrust an admin user.
     *
     * @param int $user_id User ID.
     */
    public static function untrust_user( $user_id ) {
        if ( ! Capabilities::current_user_can_manage() ) { return; }

        if ( get_current_user_id() === $user_id ) {
            return; // Cannot untrust yourself.
        }

        $trusted = get_option( 'clientguard_trusted_admins', array() );
        $key = array_search( $user_id, $trusted );
        if ( false !== $key ) {
            unset( $trusted[$key] );
            update_option( 'clientguard_trusted_admins', array_values( $trusted ) );
        }
    }
}
