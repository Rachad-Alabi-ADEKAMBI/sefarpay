<?php
/*
Plugin Name: Sefarpay
Description: Plugin de paiement WordPress pour Sefar Capital.
Version: 1.0
Author: Sefar Capital
*/

if (!defined('ABSPATH')) exit;

define('SEFARPAY_PATH', plugin_dir_path(__FILE__));

// Inclure le fichier qui gère les menus admin
require_once SEFARPAY_PATH . 'admin/sefarpay-admin-menu.php';

//definir les urls des APIs en local
/*
define('SEFARPAY_API_URL_PAYMENT_DO', 'http://localhost/satimtest/do.php?');
define('SEFARPAY_API_URL_PAYMENT_CONFIRMATON', 'http://localhost/satimtest/confirmOrder.php?');
define('SEFARPAY_API_URL_CHECK_ACCOUNT_STATUS', 'http://localhost/sefarpay_management/wp-json/sefarpay_management/v1/account_status');
define('SEFARPAY_API_URL_NEW_PAYMENT', 'http://localhost/sefarpay_management/wp-json/sefarpay_management/v1/new-payment');
define('SEFARPAY_API_URL_REGISTER', 'http://localhost/sefarpay_management/wp-json/sefarpay_management/v1/register');
*/


define('SEFARPAY_API_URL_PAYMENT_DO', 'https://satimtest0.free.nf/do.php?');
define('SEFARPAY_API_URL_PAYMENT_CONFIRMATON', 'https://satimtest0.free.nf/confirmOrder.php?');
define('SEFARPAY_API_URL_CHECK_ACCOUNT_STATUS', 'https://sefarpaymanagement.free.nf/wp-json/sefarpay_management/v1/account_status');
define('SEFARPAY_API_URL_NEW_PAYMENT', 'https://sefarpaymanagement.free.nf/wp-json/sefarpay_management/v1/new-payment');
define('SEFARPAY_API_URL_REGISTER', 'https://sefarpaymanagement.free.nf/wp-json/sefarpay_management/v1/register');


// Notification à l’activation du plugin
function sefarpay_activation_notice()
{
    set_transient('sefarpay_activation_notice', true, 5);
}

// Affichage de la notification
function sefarpay_admin_notice()
{
    if (get_transient('sefarpay_activation_notice')) {
        echo '<div class="notice notice-success is-dismissible"><p>✅ Vous avez activé le plugin <strong>Sefarpay</strong>.</p></div>';
        delete_transient('sefarpay_activation_notice');
    }
}

function sefarpay_render_html_view($filename, $args = [])
{
    $path = plugin_dir_path(__FILE__) . 'views/' . $filename;

    if (file_exists($path)) {
        // Extraction des variables du tableau $args
        extract($args);

        include $path;
    } else {
        echo '<div class="notice notice-error"><p>Vue non trouvée : ' . esc_html($filename) . '</p></div>';
    }
}


add_action('admin_notices', 'sefarpay_admin_notice');

// Inclure le fichier d’installation
require_once SEFARPAY_PATH . 'admin/sefarpay-install.php';

// Lors de l'activation : créer la table + notifier
function sefarpay_on_activation()
{
    sefarpay_create_tables(); // crée la table enregistrements
    set_transient('sefarpay_activation_notice', true, 5);
}
register_activation_hook(__FILE__, 'sefarpay_on_activation');

// Traitement AJAX pour le formulaire d’enregistrement
require_once SEFARPAY_PATH . 'admin/sefarpay-form-handler.php';

require_once SEFARPAY_PATH . 'admin/sefarpay-config-handler.php';



// Inclure le fichier de gestion des shortcodes
require_once plugin_dir_path(__FILE__) . 'includes/sefarpay-shortcode.php';

add_action('wp_enqueue_scripts', function () {
    if (is_singular() && has_shortcode(get_post()->post_content, 'sefarpay')) {
        wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', [], null, true);
        wp_enqueue_style('sefarpay-style', plugins_url('assets/css/sefarpay.css', __FILE__));
    }
});

require_once plugin_dir_path(__FILE__) . 'includes/sefarpay-woocommerce.php';

//shortcode payment success 
function sefarpay_payment_success_shortcode()
{
    // Récupération de l'OrderId depuis l'URL
    $order_id = isset($_GET['orderId']) ? sanitize_text_field($_GET['orderId']) : '';

    if (empty($order_id)) {
        return '<p>Order ID manquant.</p>';
    }


    //recupere les  informations language, username, password  depuis la table sefarpay_configuration,
    global $wpdb;
    $table_name = $wpdb->prefix . 'sefarpay_configuration';
    $config = $wpdb->get_row("SELECT * FROM $table_name WHERE id = 1", ARRAY_A);
    $language  = $config['language'] ?? 'fr'; // Langue par défaut
    $userName  = $config['username'] ?? ''; // Nom d'utilisateur SATIM  
    $password  = $config['password'] ?? ''; // Mot de passe SATIM
    $return_url  = $config['return_url'] ?? '';
    $fail_url  = $config['fail_url'] ?? '';




    //recupere les  informations nom, prenom, client_id, email depuis la table sefarpay_enregistrements
    global $wpdb;
    $table_name = $wpdb->prefix . 'sefarpay_enregistrements';
    $enreg = $wpdb->get_row("SELECT * FROM $table_name WHERE id = 1", ARRAY_A);
    $name = $enreg['nom'] . ' ' . $enreg['prenom'];
    $email = $enreg['email'];
    $telephone = $enreg['telephone'];
    $client_id = $enreg['sefarpay_id'];

    // Construction de l'URL de confirmation SATIM
    $base_url = SEFARPAY_API_URL_PAYMENT_CONFIRMATON;
    $confirm_url = sprintf(
        $base_url . '?language=%s&orderId=%s&password=%s&userName=%s',
        urlencode($language),
        urlencode($order_id),
        urlencode($password),
        urlencode($userName)
    );

    // Appel HTTP GET vers SATIM
    $response = wp_remote_get($confirm_url);

    // Vérification d'erreur réseau
    if (is_wp_error($response)) {
        return '<p>Erreur lors de la requête de confirmation : ' . esc_html($response->get_error_message()) . '</p>';
    }

    // Récupération de la réponse
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Vérification JSON
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
        return '<p>Réponse invalide du serveur de paiement.</p>';
    }

    // Préparation des données à afficher
    $transaction_data = [
        'order_number'     => $data['OrderNumber'] ?? '',
        'order_status'     => $data['OrderStatus'] ?? null,
        'error_code'       => $data['ErrorCode'] ?? null,
        'action_code'       => $data['ActionCode'] ?? null,
        'error_message'    => $data['ErrorMessage'] ?? '',
        'amount'           => $data['Amount'] ?? '',
        'currency'         => $data['Currency'] ?? '',
        'approval_code'    => $data['ApprovalCode'] ?? '',
        'pan'              => $data['Pan'] ?? '',
        'cardholder_name'  => $data['CardholderName'] ?? '',
        'resp_message'     => $data['Params']['RespCodeDesc'] ?? '',
        'order_id' => $order_id,
        'date_of_transaction' =>  date('Y-m-d H:i:s')
    ];

    //insertion dans la table de paiemensts locale
    global $wpdb;

    $table_name = $wpdb->prefix . 'sefarpay_paiements';

    $inserted = $wpdb->insert($table_name, [
        'numero_commande'     => $transaction_data['order_number'],
        'montant'             => $transaction_data['amount'],
        'devise'              => $transaction_data['currency'],
        'nom_carte'           => $transaction_data['cardholder_name'],
        'pan'                 => $transaction_data['pan'],
        'code_acceptation'    => $transaction_data['approval_code'],
        'code_autorisation'   => $transaction_data['approval_code'],
        'code_action'         => $transaction_data['action_code'],
        'description_action'  => $transaction_data['resp_message'],
        'etat_paiement'       => $transaction_data['order_status'],
        'description'         => $transaction_data['error_message'],
        'created_at'          => current_time('mysql', 1),
        'transaction_id' => $order_id
    ]);

    /*
    if ($inserted === false) {
        echo '<pre>Erreur d\'insertion : ' . $wpdb->last_error . '</pre>';
    } else {
        echo '<pre>Insertion réussie. ID : ' . $wpdb->insert_id . '</pre>';
    }

    */

    //enregistrer les données dans l'API distante de sefarpay_management
    $api_url = SEFARPAY_API_URL_NEW_PAYMENT;

    // Construction des données à envoyer à l'API
    $payload = [
        'client_id'          => $client_id, // ID du client, à adapter selon votre logique
        'numero_commande'     => $transaction_data['order_number'],
        'montant'             => $transaction_data['amount'],
        'devise'              => $transaction_data['currency'],
        'nom_carte'           => $transaction_data['cardholder_name'],
        'pan'                 => $transaction_data['pan'],
        'code_acceptation'    => $transaction_data['approval_code'],
        'code_autorisation'   => $transaction_data['approval_code'],
        'code_action'         => $transaction_data['order_status'],
        'description_action'  => $transaction_data['resp_message'],
        'etat_paiement'       => $transaction_data['order_status'],
        'description'         => $transaction_data['error_message'],
        'transaction_satim_id'         => $order_id,
        'created_at'          => current_time('mysql', 1),
        'date_paiement'          => current_time('mysql', 1),
        'transaction_id'      => $order_id,
        'nom_client' => $name,
        'telephone_client' => $telephone,
        'email_client' => $email,
        'return_url' => $return_url,
        'fail_url' => $fail_url

    ];

    // Envoi de la requête POST à l’API distante
    $response = wp_remote_post($api_url, [
        'method'    => 'POST',
        'timeout'   => 15,
        'headers'   => [
            'Content-Type' => 'application/json',
        ],
        'body'      => json_encode($payload),
    ]);

    // Debug
    /*
    if (is_wp_error($response)) {
        echo '<p>Erreur de communication avec l’API distante : ' . $response->get_error_message() . '</p>';
    } else {
        $body = wp_remote_retrieve_body($response);
        echo '<p>Réponse API distante :</p><pre>' . esc_html($body) . '</pre>';
    }
    */


    // Inclusion de la vue
    ob_start();
    sefarpay_render_html_view('paiement-reussi.php', ['transaction_data' => $transaction_data]);
    return ob_get_clean();
}

add_shortcode('sefarpay_confirmation', 'sefarpay_payment_success_shortcode');


//shortcode payment declined
function sefarpay_payment_declined_shortcode()
{
    // Récupération de l'OrderId depuis l'URL
    $order_id = isset($_GET['orderId']) ? sanitize_text_field($_GET['orderId']) : '';

    if (empty($order_id)) {
        return '<p>Order ID manquant.</p>';
    }


    //recupere les  informations language, username et password  depuis la table sefarpay_configuration,
    global $wpdb;
    $table_name = $wpdb->prefix . 'sefarpay_configuration';
    $config = $wpdb->get_row("SELECT * FROM $table_name WHERE id = 1", ARRAY_A);
    $language  = $config['language'] ?? 'fr'; // Langue par défaut
    $userName  = $config['username'] ?? ''; // Nom d'utilisateur SATIM  
    $password  = $config['password'] ?? ''; // Mot de passe SATIM

    // Construction de l'URL de confirmation SATIM
    $base_url = SEFARPAY_API_URL_PAYMENT_CONFIRMATON;
    $confirm_url = sprintf(
        $base_url . '?language=%s&orderId=%s&password=%s&userName=%s',
        urlencode($language),
        urlencode($order_id),
        urlencode($password),
        urlencode($userName)
    );

    // Appel HTTP GET vers SATIM
    $response = wp_remote_get($confirm_url);

    // Vérification d'erreur réseau
    if (is_wp_error($response)) {
        return '<p>Erreur lors de la requête de confirmation : ' . esc_html($response->get_error_message()) . '</p>';
    }

    // Récupération de la réponse
    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body, true);

    // Vérification JSON
    if (json_last_error() !== JSON_ERROR_NONE || !is_array($data)) {
        return '<p>Réponse invalide du serveur de paiement.</p>';
    }

    // Préparation des données à afficher

    $transaction_data = [
        'order_number'     => $data['OrderNumber'] ?? '',
        'order_status'     => $data['OrderStatus'] ?? null,
        'error_code'       => $data['ErrorCode'] ?? null,
        'action_code'       => $data['ActionCode'] ?? null,
        'error_message'    => $data['ErrorMessage'] ?? '',
        'amount'           => $data['Amount'] ?? '',
        'currency'         => $data['Currency'] ?? '',
        'approval_code'    => $data['ApprovalCode'] ?? '',
        'pan'              => $data['Pan'] ?? '',
        'cardholder_name'  => $data['CardholderName'] ?? '',
        'resp_message'     => $data['Params']['RespCodeDesc'] ?? '',
        'order_id' => $order_id,
        'date_of_transaction' =>  date('Y-m-d H:i:s')
    ];

    //insertion dans la table de paiemensts locale
    global $wpdb;

    $table_name = $wpdb->prefix . 'sefarpay_paiements';

    $inserted = $wpdb->insert($table_name, [
        'numero_commande'     => $transaction_data['order_number'],
        'montant'             => $transaction_data['amount'],
        'devise'              => $transaction_data['currency'],
        'nom_carte'           => $transaction_data['cardholder_name'],
        'pan'                 => $transaction_data['pan'],
        'code_acceptation'    => $transaction_data['approval_code'],
        'code_autorisation'   => $transaction_data['approval_code'],
        'code_action'         => $transaction_data['action_code'],
        'description_action'  => $transaction_data['resp_message'],
        'etat_paiement'       => $transaction_data['order_status'],
        'description'         => $transaction_data['error_message'],
        'created_at'          => current_time('mysql', 1),
        'transaction_id' => $order_id
    ]);

    /*
    if ($inserted === false) {
        echo '<pre>Erreur d\'insertion : ' . $wpdb->last_error . '</pre>';
    } else {
        echo '<pre>Insertion réussie. ID : ' . $wpdb->insert_id . '</pre>';
    }
        */


    ob_start();
    sefarpay_render_html_view('paiement-echoue.php', ['transaction_data' => $transaction_data]);
    return ob_get_clean();
}

add_shortcode('sefarpay_declined', 'sefarpay_payment_declined_shortcode');

// traitement de la requête de paiement
/*
add_action('admin_post_nopriv_sefarpay_payment', 'sefarpay_handle_payment'); //utilisé pour les utilisateurs non connectés
add_action('admin_post_sefarpay_payment', 'sefarpay_handle_payment'); //utilisé pour les utilisateurs connecté
*/

require_once plugin_dir_path(__FILE__) . 'admin/sefarpay-payment-handler.php'; //include du fichier de traitement de la requête de paiement
