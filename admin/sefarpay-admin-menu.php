<?php

if (!defined('ABSPATH')) exit;

function sefarpay_add_admin_menu()
{
    add_menu_page(
        'Sefarpay',
        'Sefarpay',
        'manage_options',
        'sefarpay',
        'sefarpay_render_configuration_page', // première page affichée
        'dashicons-money-alt',
        26
    );

    add_submenu_page(
        'sefarpay',
        'Configuration',
        'Configuration',
        'manage_options',
        'sefarpay',
        'sefarpay_render_configuration_page'
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
        'Liste des paiements',
        'Paiements',
        'manage_options',
        'sefarpay-paiements',
        'sefarpay_render_paiements_page'
    );
}
add_action('admin_menu', 'sefarpay_add_admin_menu');

// Fonctions de rendu pour chaque page
function sefarpay_render_configuration_page()
{
    echo '<div class="wrap">';
    sefarpay_render_html_view('configuration.html');
    echo '</div>';
}

function sefarpay_render_enregistrement_page()
{
    echo '<div class="wrap">';
    sefarpay_render_html_view('enregistrement.html');
    echo '</div>';
}

function sefarpay_render_paiements_page()
{
    echo '<div class="wrap">';
    sefarpay_render_html_view('paiements.html');
    echo '</div>';
}
