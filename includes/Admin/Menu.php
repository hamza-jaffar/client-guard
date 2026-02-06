<?php
/**
 * The Admin Menu class.
 *
 * @package ClientGuard\Admin
 */

namespace ClientGuard\Admin;

use ClientGuard\Helpers\Capabilities;

/**
 * Class Menu
 */
class Menu {

	/**
	 * The ID of this plugin.
	 *
	 * @var string
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @var string
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $plugin_name The name of this plugin.
	 * @param string $version     The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	/**
	 * Register the admin menu.
	 */
	public function register_menus() {
		if ( ! Capabilities::current_user_can_manage() ) {
			return;
		}

		add_menu_page(
			__( 'ClientGuard', 'clientguard' ),
			__( 'ClientGuard', 'clientguard' ),
			Capabilities::get_manage_capability(),
			'clientguard',
			array( $this, 'display_main_page' ),
			'dashicons-shield',
			80
		);

		add_submenu_page(
			'clientguard',
			__( 'Dashboard', 'clientguard' ),
			__( 'Dashboard', 'clientguard' ),
			Capabilities::get_manage_capability(),
			'clientguard',
			array( $this, 'display_main_page' )
		);
	}

	/**
	 * Render the main admin page.
	 */
	public function display_main_page() {
        $view = isset( $_GET['view'] ) ? sanitize_text_field( $_GET['view'] ) : 'list';
        $user_id = isset( $_GET['user_id'] ) ? intval( $_GET['user_id'] ) : 0;

        if ( 'edit' === $view && $user_id > 0 ) {
             $this->handle_user_save();
             include plugin_dir_path( dirname( __FILE__ ) ) . 'Admin/views/user-edit.php';
        } else {
             include plugin_dir_path( dirname( __FILE__ ) ) . 'Admin/views/user-list.php';
        }
	}

    /**
     * Handle form submission for permissions.
     */
    private function handle_user_save() {
        if ( ! isset( $_POST['submit_overrides'] ) ) {
            return;
        }

        if ( ! isset( $_POST['clientguard_nonce'] ) || ! wp_verify_nonce( $_POST['clientguard_nonce'], 'clientguard_save_user_permissions' ) ) {
            return;
        }

        if ( ! Capabilities::current_user_can_manage() ) {
            return;
        }

        $user_id = isset( $_POST['user_id'] ) ? intval( $_POST['user_id'] ) : 0;
        if ( ! $user_id ) {
            return;
        }

        $caps = isset( $_POST['caps'] ) ? $_POST['caps'] : array();
        
        // Validate each cap value
        $sanitized_caps = array();
        foreach ( $caps as $cap_key => $val ) {
            $sanitized_caps[ $cap_key ] = (int) $val;
        }

        if ( \ClientGuard\Admin\UserManager::save_user_overrides( $user_id, $sanitized_caps ) ) {
            add_settings_error( 'clientguard_messages', 'clientguard_message', __( 'User permissions saved.', 'clientguard' ), 'updated' );
            settings_errors( 'clientguard_messages' );
        }
    }
}
