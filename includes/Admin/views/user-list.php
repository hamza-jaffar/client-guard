<?php
/**
 * User List View.
 *
 * @package ClientGuard
 */

use ClientGuard\Admin\UserManager;

$users_list = UserManager::get_users();
?>
<div class="wrap">
    <h1><?php esc_html_e( 'ClientGuard User Management', 'clientguard' ); ?></h1>
    
    <table class="wp-list-table widefat fixed striped table-view-list users">
        <thead>
            <tr>
                <th><?php esc_html_e( 'Username', 'clientguard' ); ?></th>
                <th><?php esc_html_e( 'Name', 'clientguard' ); ?></th>
                <th><?php esc_html_e( 'Email', 'clientguard' ); ?></th>
                <th><?php esc_html_e( 'Role', 'clientguard' ); ?></th>
                <th><?php esc_html_e( 'Actions', 'clientguard' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ( $users_list as $user_obj ) : ?>
                <tr>
                    <td class="username column-username">
                        <strong><?php echo get_avatar( $user_obj->ID, 32 ); ?> <?php echo esc_html( $user_obj->user_login ); ?></strong>
                    </td>
                    <td><?php echo esc_html( $user_obj->display_name ); ?></td>
                    <td><a href="mailto:<?php echo esc_attr( $user_obj->user_email ); ?>"><?php echo esc_html( $user_obj->user_email ); ?></a></td>
                    <td><?php echo implode( ', ', $user_obj->roles ); ?></td>
                    <td>
                        <a href="?page=clientguard&view=edit&user_id=<?php echo esc_attr( $user_obj->ID ); ?>" class="button button-primary">
                            <?php esc_html_e( 'Manage Permissions', 'clientguard' ); ?>
                        </a>

                        <?php 
                        // Show Trust controls only for Administrators
                        if ( in_array( 'administrator', $user_obj->roles ) ) {
                            $trusted = get_option( 'clientguard_trusted_admins', array() );
                            $is_trusted = in_array( $user_obj->ID, $trusted );
                            $is_self = get_current_user_id() === $user_obj->ID;
                            
                            if ( $is_trusted ) {
                                if ( ! $is_self ) {
                                    $untrust_url = wp_nonce_url( admin_url( 'admin.php?page=clientguard&action=untrust_user&user_id=' . $user_obj->ID ), 'clientguard_trust_action_' . $user_obj->ID );
                                    echo ' <a href="' . esc_url( $untrust_url ) . '" class="button button-secondary" style="color: #b32d2e; border-color: #b32d2e;">' . esc_html__( 'Untrust', 'clientguard' ) . '</a>';
                                } else {
                                    echo ' <span class="dashicons dashicons-shield-alt" title="' . esc_attr__( 'You are a Trusted Admin based on your ID.', 'clientguard' ) . '" style="color:green; vertical-align: middle;"></span>';
                                }
                            } else {
                                $trust_url = wp_nonce_url( admin_url( 'admin.php?page=clientguard&action=trust_user&user_id=' . $user_obj->ID ), 'clientguard_trust_action_' . $user_obj->ID );
                                echo ' <a href="' . esc_url( $trust_url ) . '" class="button button-secondary">' . esc_html__( 'Trust Admin', 'clientguard' ) . '</a>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
