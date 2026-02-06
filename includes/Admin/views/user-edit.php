<?php
/**
 * User Edit Permissions View.
 *
 * @package ClientGuard
 */

use ClientGuard\Core\PermissionManager;
use ClientGuard\Admin\UserManager;

$user_id = isset( $_GET['user_id'] ) ? intval( $_GET['user_id'] ) : 0;
$user = get_userdata( $user_id );

if ( ! $user ) {
    echo '<div class="wrap"><p>' . esc_html__( 'User not found.', 'clientguard' ) . '</p><a href="?page=clientguard" class="button">' . esc_html__( 'Back to List', 'clientguard' ) . '</a></div>';
    return;
}

$permission_map = PermissionManager::get_permission_map();
$current_overrides = UserManager::get_user_overrides( $user_id );

// Helper to check state: 1 (Allow), -1 (Deny), 0 (Default)
?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        <?php printf( esc_html__( 'Edit Permissions: %s', 'clientguard' ), esc_html( $user->display_name ) ); ?>
    </h1>
    <a href="?page=clientguard" class="page-title-action"><?php esc_html_e( 'Back to Users', 'clientguard' ); ?></a>
    
    <hr class="wp-header-end">

    <form method="post" action="">
        <?php wp_nonce_field( 'clientguard_save_user_permissions', 'clientguard_nonce' ); ?>
        <input type="hidden" name="user_id" value="<?php echo esc_attr( $user_id ); ?>">
        <input type="hidden" name="action" value="save_permissions">

        <div id="poststuff">
            <div id="post-body" class="metabox-holder columns-1">
                <?php foreach ( $permission_map as $group_key => $group ) : ?>
                    <div class="postbox">
                        <h3 class="hndle"><span><?php echo esc_html( $group['label'] ); ?></span></h3>
                        <div class="inside">
                            <table class="form-table striped">
                                <thead>
                                    <tr>
                                        <th style="width: 40%;"><?php esc_html_e( 'Capability', 'clientguard' ); ?></th>
                                        <th style="text-align: center;"><?php esc_html_e( 'Default (Role)', 'clientguard' ); ?></th>
                                        <th style="text-align: center;"><?php esc_html_e( 'Allow', 'clientguard' ); ?></th>
                                        <th style="text-align: center;"><?php esc_html_e( 'Deny', 'clientguard' ); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ( $group['caps'] as $cap_key => $cap_label ) : ?>
                                        <?php
                                            // Determine current value
                                            $val = isset( $current_overrides[ $cap_key ] ) ? (int) $current_overrides[ $cap_key ] : 0;
                                            
                                            // Check what the role native default is for display info
                                            $role_has_cap = user_can( $user_id, $cap_key ); // CAUTION: this might recurse if we aren't careful, but user_can checks dynamically.
                                            // To get raw role cap without our filter, we might need to check role object directly.
                                            // For simplicity, let's just show the 3 options.
                                        ?>
                                        <tr>
                                            <td>
                                                <strong><?php echo esc_html( $cap_label ); ?></strong><br>
                                                <small class="description"><?php echo esc_html( $cap_key ); ?></small>
                                            </td>
                                            <td style="text-align: center;">
                                                <label>
                                                    <input type="radio" name="caps[<?php echo esc_attr( $cap_key ); ?>]" value="0" <?php checked( $val, 0 ); ?>>
                                                    <?php esc_html_e( 'Inherit', 'clientguard' ); ?>
                                                </label>
                                            </td>
                                            <td style="text-align: center;">
                                                <label>
                                                    <input type="radio" name="caps[<?php echo esc_attr( $cap_key ); ?>]" value="1" <?php checked( $val, 1 ); ?>>
                                                    <?php esc_html_e( 'Allow', 'clientguard' ); ?>
                                                </label>
                                            </td>
                                            <td style="text-align: center;">
                                                <label>
                                                    <input type="radio" name="caps[<?php echo esc_attr( $cap_key ); ?>]" value="-1" <?php checked( $val, -1 ); ?>>
                                                    <?php esc_html_e( 'Deny', 'clientguard' ); ?>
                                                </label>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php submit_button( __( 'Save Permission Overrides', 'clientguard' ), 'primary', 'submit_overrides' ); ?>
    </form>
</div>
