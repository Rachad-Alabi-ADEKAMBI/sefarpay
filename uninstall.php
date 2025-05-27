<?php
// Exit si accédé directement
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}

// Exemple : suppression des options enregistrées dans la base
delete_option('sefarpay_settings');

// Exemple : suppression des options multisite si applicable
if (is_multisite()) {
    global $wpdb;
    $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
    foreach ($blog_ids as $blog_id) {
        switch_to_blog($blog_id);
        delete_option('sefarpay_settings');
        restore_current_blog();
    }
}
