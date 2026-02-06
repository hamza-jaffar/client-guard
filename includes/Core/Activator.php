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
		// Add ClientGuard Manager Role.
        add_role(
            'clientguard_manager',
            __( 'ClientGuard Manager', 'clientguard' ),
            array(
                'read' => true,
                'level_0' => true,
                \ClientGuard\Helpers\Capabilities::MANAGE_PERMISSIONS => true,
            )
        );
	}

}
