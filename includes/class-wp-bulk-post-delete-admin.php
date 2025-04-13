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
        add_filter('bulk_actions-edit-post', [$this, 'mi_register_bulk_action']);
        add_filter('handle_bulk_actions-edit-post', [$this, 'mi_handle_bulk_action'], 10, 3);
        add_filter('bulk_post_updated_messages', [$this, 'imr_bulk_action_notice'], 10, 2);
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
    
        if ($screen && $screen->id === 'edit-post' && $post_status !== 'trash') {
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
                wp_delete_post($post_id, true); // true for force delete
                $deleted++;
            }
        }

        $redirect_to = add_query_arg('dpp_deleted', $deleted, $redirect_to);
        return $redirect_to;
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
    public function imr_bulk_action_notice($bulk_messages, $bulk_counts) {
        if (!empty($_REQUEST['dpp_deleted'])) {
            $deleted = intval($_REQUEST['dpp_deleted']);
            $bulk_messages['post']['dpp_deleted'] = sprintf(_n('%s post permanently deleted.', '%s posts permanently deleted.', $deleted, 'wp-bulk-post-delete'), $deleted);
        }
        return $bulk_messages;
    }
}
