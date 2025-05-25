<?php
if (!defined('ABSPATH')) {
    exit;
}


function sefarpay_handle_payment()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        wp_send_json_error('Méthode non autorisée');
    }

    // Nettoyage des données
    $currency    = sanitize_text_field($_POST['currency'] ?? '');
    $language    = sanitize_text_field($_POST['language'] ?? '');
    $userName    = sanitize_text_field($_POST['userName'] ?? '');
    $password    = sanitize_text_field($_POST['password'] ?? '');
    $returnUrl   = esc_url_raw($_POST['returnUrl'] ?? '');
    $failUrl     = esc_url_raw($_POST['failUrl'] ?? '');
    $jsonParams  = sanitize_text_field($_POST['jsonParams'] ?? '');
    $amount      = sanitize_text_field($_POST['amount'] ?? '');
    $description = sanitize_text_field($_POST['description'] ?? '');
    $orderNumber = sanitize_text_field($_POST['orderNumber'] ?? uniqid());

    // Construction de l’URL GET
    $query = http_build_query([
        'currency'    => $currency,
        'amount'      => $amount,
        'language'    => $language,
        'description' => $description,
        'orderNumber' => $orderNumber,
        'userName'    => $userName,
        'password'    => $password,
        'returnUrl'   => $returnUrl,
        'failUrl'     => $failUrl,
        'jsonParams'  => $jsonParams,
    ]);

    $url = 'http://localhost/satimtest/do.php?' . $query;

    // Envoi de la requête
    $response = wp_remote_get($url, ['timeout' => 20]);

    if (is_wp_error($response)) {
        wp_send_json_error('Erreur requête SATIM : ' . $response->get_error_message());
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    if (!empty($data['redirect_url'])) {
        wp_send_json_success(['redirect_url' => $data['redirect_url']]);
    } else {
        wp_send_json_error('Réponse invalide de SATIM.');
    }
}
