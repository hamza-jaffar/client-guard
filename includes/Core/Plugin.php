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
