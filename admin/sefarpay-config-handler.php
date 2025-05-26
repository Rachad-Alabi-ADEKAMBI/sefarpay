<?php
if (!defined('ABSPATH')) exit;

add_action('wp_ajax_sefarpay_save_config', 'sefarpay_save_config');

function sefarpay_save_config()
{
    global $wpdb;
    $table = $wpdb->prefix . 'sefarpay_configuration';

    // Données sécurisées
    $data = [
        'client_identifier'      => sanitize_text_field($_POST['source_identifier']),
        'username'               => sanitize_text_field($_POST['username']),
        'password'               => sanitize_text_field($_POST['password']),
        'base_url_api'           => esc_url_raw($_POST['base_url']),
        'currency'               => sanitize_text_field($_POST['currency']),
        'language'               => sanitize_text_field($_POST['language']),
        'json_params'            => sanitize_textarea_field($_POST['json_params']),
        'return_url'             => esc_url_raw($_POST['return_url']),
        'fail_url'               => esc_url_raw($_POST['fail_url']),
        'captcha_site_key'       => sanitize_text_field($_POST['captcha_site_key']),
        'captcha_secret_key'     => sanitize_text_field($_POST['captcha_secret_key']),
        'cgu_url'                => esc_url_raw($_POST['terms_url']),
        'button_color'           => sanitize_text_field($_POST['button_color']),
        'button_size'            => sanitize_text_field($_POST['button_size']),
        'button_text'            => sanitize_text_field($_POST['button_text']),
        'created_at'             => current_time('mysql'),
    ];

    // Vérifie s’il existe déjà une ligne
    $existing_row = $wpdb->get_row("SELECT * FROM $table LIMIT 1", ARRAY_A);

    if ($existing_row) {
        $id = $existing_row['id'];

        // Ne mettre à jour que si les données sont différentes
        $diff = array_diff_assoc($data, $existing_row);
        unset($diff['created_at']); // Ne pas comparer la date
        if (!empty($diff)) {
            $wpdb->update($table, $data, ['id' => $id]);
        }
    } else {
        // Aucune ligne existante, on insère
        $wpdb->insert($table, $data);
    }

    wp_send_json_success("Configuration enregistrée avec succès.");
}
