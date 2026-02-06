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
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
