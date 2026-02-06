<?php
/**
 * Autoloader for the ClientGuard plugin.
 *
 * @package ClientGuard
 */

namespace ClientGuard;

/**
 * Autoloader class.
 */
class Autoloader {

	/**
	 * Registers the autoloader.
	 */
	public static function register() {
		spl_autoload_register( array( __CLASS__, 'autoload' ) );
	}

	/**
	 * Autoloads the class.
	 *
	 * @param string $class_name The name of the class to load.
	 */
	public static function autoload( $class_name ) {
		// distinct namespace prefix.
		$prefix = 'ClientGuard\\';

		// base directory for the namespace prefix.
		$base_dir = plugin_dir_path( dirname( __FILE__ ) ) . 'includes/';

		// does the class use the namespace prefix?
		$len = strlen( $prefix );
		if ( 0 !== strncmp( $prefix, $class_name, $len ) ) {
			// no, move to the next registered autoloader.
			return;
		}

		// get the relative class name.
		$relative_class = substr( $class_name, $len );

		// replace the namespace prefix with the base directory, replace namespace
		// separators with directory separators in the relative class name, append
		// with .php.
		$file = $base_dir . str_replace( '\\', '/', $relative_class ) . '.php';

		// if the file exists, require it.
		if ( file_exists( $file ) ) {
			require $file;
		}
	}
}
