<?php
/**
 * The core plugin class.
 *
 * @package ClientGuard\Core
 */

namespace ClientGuard\Core;

use ClientGuard\Admin\Menu;
use ClientGuard\Helpers\Capabilities;

/**
 * The main plugin class.
 */
class Plugin {

	/**
	 * The unique identifier of this plugin.
	 *
	 * @var string
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @var string
	 */
	protected $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of the plugin.
	 * @param string $version     The version of the plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 */
	public function run() {
		$this->define_admin_hooks();
		$this->define_public_hooks();
        $this->define_core_hooks();
	}

    /**
     * Register core hooks.
     */
    private function define_core_hooks() {
        $permission_manager = new \ClientGuard\Core\PermissionManager();
        $permission_manager->init();

        // Auto-update check to refresh roles if version changes.
        add_action( 'admin_init', array( $this, 'check_version' ) );
    }

    /**
     * Check version and ensure setup is correct.
     */
    public function check_version() {
        // 1. Version Check & Upgrade
        $db_version = get_option( 'clientguard_version' );
        if ( version_compare( $db_version, $this->version, '<' ) ) {
            \ClientGuard\Core\Activator::activate();
            update_option( 'clientguard_version', $this->version );
        }

        // 2. Empty Trust List Check (Fix for "empty db" issue)
        // If the trusted list is empty, trust the current Administrator immediately.
        $trusted = get_option( 'clientguard_trusted_admins', array() );
        if ( empty( $trusted ) && is_user_logged_in() && current_user_can( 'administrator' ) ) {
            $user_id = get_current_user_id();
            $trusted[] = $user_id;
            update_option( 'clientguard_trusted_admins', $trusted );
        }

        // 3. Emergency Fix (Manual Override)
        // If an admin is locked out (e.g. migration change User IDs), they can visit ?cg_fix_me=1 to restore access.
        if ( isset( $_GET['cg_fix_me'] ) && is_user_logged_in() && current_user_can( 'administrator' ) ) {
            $user_id = get_current_user_id();
            if ( ! in_array( $user_id, $trusted ) ) {
                $trusted[] = $user_id;
                update_option( 'clientguard_trusted_admins', $trusted );
                // Redirect to remove the param
                wp_safe_redirect( remove_query_arg( 'cg_fix_me' ) );
                exit;
            }
        }
    }

	/**
	 * Register all of the hooks related to the admin area functionality.
	 */
	private function define_admin_hooks() {
		// Initialize Admin Menu
		$plugin_admin = new Menu( $this->plugin_name, $this->version );
		add_action( 'admin_menu', array( $plugin_admin, 'register_menus' ) );
        
        // Add capability filters later here
	}

	/**
	 * Register all of the hooks related to the public facing functionality.
	 */
	private function define_public_hooks() {
        // Public hooks if any (none for now as per MVP scope mostly admin)
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return string The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @return string The version of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
}
