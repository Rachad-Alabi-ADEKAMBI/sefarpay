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

    // On met à jour la première ligne, ou on en insère une si elle n'existe pas
    $existing = $wpdb->get_var("SELECT id FROM $table LIMIT 1");

    if ($existing) {
        $wpdb->update($table, $data, ['id' => $existing]);
    } else {
        $wpdb->insert($table, $data);
    }

    wp_send_json_success("Configuration enregistrée avec succès.");
}
