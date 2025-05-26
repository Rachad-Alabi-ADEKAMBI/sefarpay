<?php

if (!defined('ABSPATH')) exit;

function sefarpay_add_admin_menu()
{
    add_menu_page(
        'Sefarpay',
        'Sefarpay',
        'manage_options',
        'sefarpay',
        'sefarpay_render_accueil_page', // première page affichée
        'dashicons-money-alt',
    );

    add_submenu_page(
        'sefarpay',
        'Enregistrement',
        'Enregistrement',
        'manage_options',
        'sefarpay-enregistrement',
        'sefarpay_render_enregistrement_page'
    );

    add_submenu_page(
        'sefarpay',
        'Configuration',
        'Configuration',
        'manage_options',
        'sefarpay-cofiguration',
        'sefarpay_render_configuration_page'
    );

    add_submenu_page(
        'sefarpay',
        'Liste des paiements',
        'Paiements',
        'manage_options',
        'sefarpay-paiements',
        'sefarpay_render_paiements_page'
    );
}
add_action('admin_menu', 'sefarpay_add_admin_menu');

// Fonctions de rendu pour chaque page

function sefarpay_render_accueil_page()
{
    global $wpdb;

    $validation_url = 'https://localhost/sefarpay_management/wp-json/sefarpay_management/v1/account_status';

    // Récupérer le sefarpay_id depuis la table sefarpay_enregistrements
    $table_enregistrements = $wpdb->prefix . 'sefarpay_enregistrements';
    $sefarpay_id = $wpdb->get_var("SELECT sefarpay_id FROM $table_enregistrements LIMIT 1");

    $status = 'inconnu';

    if ($sefarpay_id) {
        // Construire l’URL avec paramètre GET
        $request_url = add_query_arg('sefarpay_id', urlencode($sefarpay_id), $validation_url);

        // Requête GET
        $response = wp_remote_get($request_url);

        if (!is_wp_error($response)) {
            $body = wp_remote_retrieve_body($response);
            $data = json_decode($body, true);
            if (isset($data['status'])) {
                $status = $data['status'];
            }
        } else {
            $status = 'erreur';
        }
    }

    // Afficher la vue accueil.php avec le statut
    sefarpay_render_html_view('accueil.php', ['status' => $status]);
}



function sefarpay_render_configuration_page()
{
    sefarpay_render_html_view('configuration.php');
}

function sefarpay_render_enregistrement_page()
{
    sefarpay_render_html_view('enregistrement.php');
}

function sefarpay_render_paiements_page()
{
    global $wpdb;
    $table = $wpdb->prefix . 'sefarpay_paiements';
    $paiements = $wpdb->get_results("SELECT * FROM $table ORDER BY created_at DESC");

    sefarpay_render_html_view('paiements.php', ['paiements' => $paiements]);
}
