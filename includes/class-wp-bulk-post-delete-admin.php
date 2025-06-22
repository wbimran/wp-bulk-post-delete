<?php

if (!defined('ABSPATH')) {
    exit;
}

class Wp_Bulk_Post_Delete_Admin {

    /**
     * Initialize all plugin hooks.
     *
     * Registers filters for adding the custom bulk action, handling its logic,
     * and displaying the corresponding admin notice.
     */
    public function init() {
        $post_types = apply_filters('mi_supported_post_types', ['post', 'page']);

        foreach ($post_types as $post_type) {
            $screen = "edit-{$post_type}";

            add_filter("bulk_actions-{$screen}", [$this, 'mi_register_bulk_action']);
            add_filter("handle_bulk_actions-{$screen}", [$this, 'mi_handle_bulk_action'], 10, 3);
        }

        add_action('admin_notices', [$this, 'imr_bulk_action_notice']);
    }


    /**
     * Register custom bulk action to permanently delete posts.
     *
     * Adds the "Delete Permanently" option to the bulk actions dropdown
     * on the main posts listing page, excluding the Trash view.
     *
     * @param array $bulk_actions Existing bulk actions.
     * @return array Modified bulk actions.
     */
    public function mi_register_bulk_action($bulk_actions) {
        $screen = get_current_screen();
        $post_status = isset($_GET['post_status']) ? $_GET['post_status'] : '';
    
        if ($screen && in_array($screen->id, ['edit-post', 'edit-page']) && $post_status !== 'trash') {
            $bulk_actions['delete_permanently'] = __('Delete Permanently', 'wp-bulk-post-delete');
        }
        return $bulk_actions;
    }

    /**
     * Handle the custom "Delete Permanently" bulk action.
     *
     * Permanently deletes selected posts and appends the count to the redirect URL.
     *
     * @param string $redirect_to The URL to redirect to after the action.
     * @param string $doaction The selected bulk action.
     * @param array $post_ids The IDs of the selected posts.
     * @return string Modified redirect URL with deletion count.
     */
    public function mi_handle_bulk_action($redirect_to, $doaction, $post_ids) {
        if ($doaction !== 'delete_permanently') {
            return $redirect_to;
        }
    
        $deleted = 0;
        foreach ($post_ids as $post_id) {
            if (current_user_can('delete_post', $post_id)) {
                wp_delete_post($post_id, true); // true = force delete
                $deleted++;
            }
        }
    
        return add_query_arg('dpp_deleted', $deleted, $redirect_to);
    }

    /**
     * Customize the bulk action admin notice.
     *
     * Displays a message showing how many posts were permanently deleted.
     *
     * @param array $bulk_messages Existing bulk messages.
     * @param array $bulk_counts   Number of affected items.
     * @return array Modified bulk messages.
     */
    public function imr_bulk_action_notice() {
        if (!empty($_REQUEST['dpp_deleted'])) {
            $deleted = intval($_REQUEST['dpp_deleted']);
            $screen = get_current_screen();
            $post_type = $screen->post_type ?? 'post';
    
            $post_type_obj = get_post_type_object($post_type);
            $singular = $post_type_obj ? $post_type_obj->labels->singular_name : 'item';
            $plural   = $post_type_obj ? $post_type_obj->labels->name : 'items';
    
            $message = sprintf(
                _n('%s %s permanently deleted.', '%s %s permanently deleted.', $deleted, 'wp-bulk-post-delete'),
                number_format_i18n($deleted),
                $deleted === 1 ? $singular : $plural
            );
    
            echo '<div class="notice notice-success is-dismissible"><p>' . esc_html($message) . '</p></div>';
        }
    }
}
