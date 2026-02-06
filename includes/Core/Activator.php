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
		// Get Administrator capabilities to inherit.
        $admin_role = get_role( 'administrator' );
        $admin_caps = $admin_role ? $admin_role->capabilities : array();

        // Add custom capability.
        $admin_caps[ \ClientGuard\Helpers\Capabilities::MANAGE_PERMISSIONS ] = true;

		// Add ClientGuard Manager Role.
        // Remove first to ensure we update caps if it exists.
        remove_role( 'clientguard_manager' );
        
        add_role(
            'clientguard_manager',
            __( 'ClientGuard Manager', 'clientguard' ),
            $admin_caps
        );
	}

}
