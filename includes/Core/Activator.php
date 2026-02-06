<?php
/**
 * Fired during plugin activation.
 *
 * @package ClientGuard\Core
 */

namespace ClientGuard\Core;

/**
 * Fired during plugin activation.
 */
class Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 */
	public static function activate() {
        $current_user_id = get_current_user_id();
        if ( $current_user_id ) {
            $trusted = get_option( 'clientguard_trusted_admins', array() );
            if ( ! is_array( $trusted ) ) {
                $trusted = array();
            }
            if ( ! in_array( $current_user_id, $trusted ) ) {
                $trusted[] = $current_user_id;
                update_option( 'clientguard_trusted_admins', $trusted );
            }
        }
	}

}
