=== ClientGuard ===
Contributors: clientguard-team
Tags: permissions, capability manager, admin access, client protection
Requires at least: 5.0
Tested up to: 6.4
Stable tag: 1.0.1
Requires PHP: 7.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Protect WordPress sites from client mistakes by controlling what non-admin users can see and do, and restrict plugin management to Trusted Admins only.

== Description ==

ClientGuard is a lightweight and robust permission management plugin designed for agencies and developers who hand off sites to clients.

Most permission plugins are bloated or complex. ClientGuard focuses on two things:

1.  **Strictly limiting access** to the ClientGuard dashboard itself to a specific list of "Trusted Administrators".
2.  **Granularly controlling** what other users (including other Administrators) can do via a simple Allow/Deny override system.

**Key Features:**

- **Trusted Admin System:** Only administrators you explicitly "Trust" can access the ClientGuard settings. Other administrators will not even see the menu item.
- **User-Level Overrides:** Grant or deny specific capabilities (like `activate_plugins`, `edit_themes`) on a per-user basis, regardless of their role.
- **Fail-Safe Architecture:**
  - **Auto-Trust:** The administrator who activates the plugin is automatically trusted.
  - **Fail-Open:** If the trusted list ever becomes empty (e.g., database migration), the system temporarily opens access to all admins so you can re-configure it without being locked out.
  - **Emergency Fix:** If you are ever locked out due to a user ID mismatch, you can use a secret query parameter (see FAQ) to restore access.
- **Safety Locks:** Prevents you from accidentally removing your own "manage permissions" capability.

== Installation ==

1.  Upload the plugin files to the `/wp-content/plugins/wp-client-shield` directory, or install the plugin through the WordPress plugins screen.
2.  Activate the plugin through the 'Plugins' screen in WordPress.
3.  **Important:** Upon activation, your current user account is automatically added to the "Trusted Admins" list.
4.  Navigate to **ClientGuard** in the admin sidebar to start managing users.

== Frequently Asked Questions ==

= I created a new Administrator but they can't see the ClientGuard menu. Why? =
This is by design. Only "Trusted Admins" can see the menu. To fix this:

1. Log in as your Trusted Admin account.
2. Go to **ClientGuard > Dashboard**.
3. Find the new Administrator in the User List.
4. Click the **"Trust Admin"** button next to their name.

= Can I restrict the 'Administrator' role? =
ClientGuard works on a **User Level**, not a Role Level. This allows for granular control. You can take a specific user with the Administrator role and "Deny" them specific capabilities (like `delete_users`) without affecting other administrators.

== Changelog ==

= 1.0.1 =

- Implemented Fail-Safe logic: If the trusted list is empty, any admin can access the dashboard to configure it.
- Added Auto-Heal on Admin Init: Automatically trusts the current admin if the list is empty.
- Added UI in User List to Trust/Untrust other administrators.
- Fixed capability mapping to ensure Trusted Admins always have `clientguard_manage_permissions`.

= 1.0.0 =

- Initial Release.
