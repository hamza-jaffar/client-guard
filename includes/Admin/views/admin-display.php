<?php
/**
 * Admin Role List View.
 *
 * @package ClientGuard
 */

use ClientGuard\Core\RoleManager;

$roles = RoleManager::get_roles();
$permission_map = RoleManager::get_permission_map();
$current_tab = isset( $_GET['role'] ) ? sanitize_text_field( $_GET['role'] ) : '';

// Default to first non-admin role if possible, or just the first one.
if ( empty( $current_tab ) ) {
    $role_keys = array_keys( $roles );
    $current_tab = isset( $role_keys[0] ) ? $role_keys[0] : 'administrator';
}

$current_role_object = get_role( $current_tab );
?>

<div class="wrap">
    <h1><?php esc_html_e( 'ClientGuard Permissions', 'clientguard' ); ?></h1>
    
    <h2 class="nav-tab-wrapper">
        <?php foreach ( $roles as $role_slug => $role_details ) : ?>
            <a href="?page=clientguard&role=<?php echo esc_attr( $role_slug ); ?>" class="nav-tab <?php echo $current_tab === $role_slug ? 'nav-tab-active' : ''; ?>">
                <?php echo esc_html( $role_details['name'] ); ?>
            </a>
        <?php endforeach; ?>
    </h2>

    <?php if ( $current_role_object ) : ?>
        <form method="post" action="">
            <?php wp_nonce_field( 'clientguard_save_permissions', 'clientguard_nonce' ); ?>
            <input type="hidden" name="role_slug" value="<?php echo esc_attr( $current_tab ); ?>">

            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-1">
                    <div id="post-body-content">
                        <?php foreach ( $permission_map as $group_key => $group ) : ?>
                            <div class="postbox">
                                <h3 class="hndle"><span><?php echo esc_html( $group['label'] ); ?></span></h3>
                                <div class="inside">
                                    <table class="form-table">
                                        <?php foreach ( $group['caps'] as $cap_key => $cap_label ) : ?>
                                            <?php 
                                            // Check if role has this cap.
                                            $has_cap = $current_role_object->has_cap( $cap_key );
                                            // Admins always have all caps, maybe disable editing for admin to prevent lockout?
                                            $is_disabled = ( 'administrator' === $current_tab ) ? 'disabled' : '';
                                            ?>
                                            <tr>
                                                <th scope="row"><?php echo esc_html( $cap_label ); ?> (<code><?php echo esc_html( $cap_key ); ?></code>)</th>
                                                <td>
                                                    <label class="switch">
                                                        <input type="checkbox" name="caps[<?php echo esc_attr( $cap_key ); ?>]" value="1" <?php checked( $has_cap ); ?> <?php echo $is_disabled; ?>>
                                                        <span class="slider round"></span>
                                                    </label>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </table>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>

            <?php if ( 'administrator' !== $current_tab ) : ?>
                <?php submit_button( __( 'Save Permissions', 'clientguard' ), 'primary', 'submit_permissions' ); ?>
            <?php else: ?>
                <p class="description"><?php esc_html_e( 'Administrator permissions cannot be modified.', 'clientguard' ); ?></p>
            <?php endif; ?>
        </form>
    <?php else : ?>
        <p><?php esc_html_e( 'Role not found.', 'clientguard' ); ?></p>
    <?php endif; ?>
</div>

<style>
/* Simple CSS for toggle switch - can handle this in better css file later */
.switch { position: relative; display: inline-block; width: 40px; height: 24px; }
.switch input { opacity: 0; width: 0; height: 0; }
.slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #ccc; -webkit-transition: .4s; transition: .4s; border-radius: 24px; }
.slider:before { position: absolute; content: ""; height: 16px; width: 16px; left: 4px; bottom: 4px; background-color: white; -webkit-transition: .4s; transition: .4s; border-radius: 50%; }
input:checked + .slider { background-color: #2271b1; }
input:focus + .slider { box-shadow: 0 0 1px #2271b1; }
input:checked + .slider:before { -webkit-transform: translateX(16px); -ms-transform: translateX(16px); transform: translateX(16px); }
</style>
