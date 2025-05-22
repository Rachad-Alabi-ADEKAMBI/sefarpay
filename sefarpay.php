<?php
/*
Plugin Name: Sefarpay
Description: Plugin de paiement WordPress pour Sefar Capital.
Version: 1.0
Author: Rachad
*/

if (!defined('ABSPATH')) exit;

define('SEFARPAY_PATH', plugin_dir_path(__FILE__));

// Inclure le fichier qui gère les menus admin
require_once SEFARPAY_PATH . 'admin/sefarpay-admin-menu.php';

// Notification à l’activation du plugin
function sefarpay_activation_notice()
{
    set_transient('sefarpay_activation_notice', true, 5);
}
register_activation_hook(__FILE__, 'sefarpay_activation_notice');

// Affichage de la notification
function sefarpay_admin_notice()
{
    if (get_transient('sefarpay_activation_notice')) {
        echo '<div class="notice notice-success is-dismissible"><p>✅ Vous avez activé le plugin <strong>Sefarpay</strong>.</p></div>';
        delete_transient('sefarpay_activation_notice');
    }
}

function sefarpay_render_html_view($filename)
{
    $path = plugin_dir_path(__FILE__) . 'views/' . $filename;
    if (file_exists($path)) {
        include $path;
    } else {
        echo '<div class="notice notice-error"><p>Vue non trouvée : ' . esc_html($filename) . '</p></div>';
    }
}

add_action('admin_notices', 'sefarpay_admin_notice');
