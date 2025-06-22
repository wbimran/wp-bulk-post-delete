=== WP Bulk Post Delete ===
Contributors: imranmd
Tags: bulk delete, delete posts, delete pages, custom post types, cpt, bulk actions, admin tools
Requires at least: 5.0
Tested up to: 6.8
Requires PHP: 7.2
Stable tag: 1.1.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Easily bulk delete posts, pages, and custom post types permanently from the WordPress admin. Adds a "Delete Permanently" option to the bulk actions dropdown.

== Description ==

This lightweight plugin allows you to **permanently delete multiple posts, pages, or custom post types (CPTs)** directly from the WordPress admin post list screens — skipping the trash entirely.

Perfect for cleaning up large amounts of content quickly and securely.

**Features:**
- Adds a **"Delete Permanently"** bulk action to Posts, Pages, and Custom Post Types.
- Supports custom post types via `apply_filters`.
- Checks user capabilities (`delete_post`).
- Displays a success notice after deletion.
- Bypasses the trash — content is deleted immediately.

== Installation ==

1. Upload the plugin folder to `/wp-content/plugins/wp-bulk-post-delete/` or install it via the WordPress admin.
2. Activate the plugin through the "Plugins" screen.
3. Go to the Posts, Pages, or CPT listing screen and select multiple items.
4. Use the "Delete Permanently" bulk action and click Apply.

== Frequently Asked Questions ==

= Will this delete content permanently? =
Yes. This bypasses the trash and deletes the selected content immediately and permanently.

= Can I recover deleted content? =
No, deleted items are permanently removed. Be cautious before applying this action.

= Does it support custom post types? =
Yes. Use the filter hook to enable your CPTs:

