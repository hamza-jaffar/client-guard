<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * administrative area. This file also includes all of the dependencies used by
 * the plugin, registers the activation and deactivation functions, and defines
 * a function that starts the plugin.
 *
 * @link              https://degvora.com
 * @since             1.0.0
 * @package           ClientGuard
 *
 * @wordpress-plugin
 * Plugin Name:       ClientGuard
 * Plugin URI:        https://degvora.com/
 * Description:       Protect WordPress sites from client mistakes by controlling what non-admin users can see and do.
 * Version:           1.0.0
 * Author:            ClientGuard Team
 * Author URI:        https://degvora.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       clientguard
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 */
define( 'CLIENTGUARD_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/Core/Activator.php
 */
function activate_clientguard() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Core/Activator.php';
	ClientGuard\Core\Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/Core/Deactivator.php
 */
function deactivate_clientguard() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/Core/Deactivator.php';
	ClientGuard\Core\Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_clientguard' );
register_deactivation_hook( __FILE__, 'deactivate_clientguard' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/Autoloader.php';
ClientGuard\Autoloader::register();

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 */
function run_clientguard() {

	$plugin = new ClientGuard\Core\Plugin( 'clientguard', CLIENTGUARD_VERSION );
	$plugin->run();

}
run_clientguard();
