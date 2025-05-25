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

    //recupere les  informations language, username et password  depuis la table sefarpay_configuration,
    global $wpdb;
    $table_name = $wpdb->prefix . 'sefarpay_configuration';
    $config = $wpdb->get_row("SELECT * FROM $table_name WHERE id = 1", ARRAY_A);
    $language  = $config['language'] ?? 'fr'; // Langue par défaut
    $userName  = $config['username'] ?? ''; // Nom d'utilisateur SATIM  
    $password  = $config['password'] ?? ''; // Mot de passe SATIM

    // Construction de l'URL de confirmation SATIM
    $confirm_url = sprintf(
        'http://localhost/satimtest/confirmOrder.php?language=%s&orderId=%s&password=%s&userName=%s',
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
        'error_message'    => $data['ErrorMessage'] ?? '',
        'amount'           => $data['Amount'] ?? '',
        'currency'         => $data['Currency'] ?? '',
        'approval_code'    => $data['ApprovalCode'] ?? '',
        'pan'              => $data['Pan'] ?? '',
        'expiration'       => $data['Expiration'] ?? '',
        'cardholder_name'  => $data['CardholderName'] ?? '',
        'resp_message'     => $data['Params']['RespCodeDesc'] ?? '',
    ];

    // Inclusion de la vue
    ob_start();
    include plugin_dir_path(__FILE__) . 'views/paiement_reussi.php';
    return ob_get_clean();
}

add_shortcode('sefarpay_confirmation', 'sefarpay_payment_success_shortcode');


//shortcode payment declined
function sefarpay_payment_declined_shortcode()
{
    // Supposons que SATIM renvoie les infos via GET
    $order_id        = isset($_GET['order_id']) ? sanitize_text_field($_GET['order_id']) : '';
    $transaction_id  = isset($_GET['transaction_id']) ? sanitize_text_field($_GET['transaction_id']) : '';
    $auth_code       = isset($_GET['auth_code']) ? sanitize_text_field($_GET['auth_code']) : '';
    $date            = isset($_GET['date']) ? sanitize_text_field($_GET['date']) : '';
    $amount          = isset($_GET['amount']) ? floatval($_GET['amount']) : 0;
    $payment_method  = isset($_GET['payment_method']) ? sanitize_text_field($_GET['payment_method']) : '';

    // TODO: ici tu peux ajouter une vérification de signature ou validation supplémentaire

    ob_start();
?>
    <!DOCTYPE html>
    <html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Échec de Paiement</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
        <style>
            :root {
                --primary-color: #0054A6;
                --primary-light: #1a6bc2;
                --primary-dark: #00407d;
                --secondary-color: #3AA7AA;
                --secondary-light: #4dbdc0;
                --secondary-dark: #2a8a8d;
                --light-gray: #f8f9fa;
                --medium-gray: #e9ecef;
                --dark-gray: #343a40;
                --error-color: #e74c3c;
                --error-light: #f5b7b1;
                --white: #ffffff;
            }

            * {
                box-sizing: border-box;
                margin: 0;
                padding: 0;
                font-family: 'Poppins', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            }

            @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

            body {
                background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
                color: var(--dark-gray);
                line-height: 1.6;
                min-height: 100vh;
                padding: 40px 20px;
            }

            .container {
                max-width: 800px;
                margin: 0 auto;
                background-color: var(--white);
                border-radius: 20px;
                box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
                overflow: hidden;
                position: relative;
            }

            .page-header {
                background: linear-gradient(135deg, var(--error-color) 0%, #c0392b 100%);
                padding: 40px 30px;
                text-align: center;
                position: relative;
            }

            .page-header::after {
                content: '';
                position: absolute;
                bottom: 0;
                left: 0;
                right: 0;
                height: 30px;
                background: var(--white);
                border-radius: 50% 50% 0 0;
            }

            .page-header h1 {
                color: var(--white);
                margin-bottom: 10px;
                font-weight: 600;
                font-size: 2.5rem;
                text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            }

            .page-header p {
                color: rgba(255, 255, 255, 0.9);
                font-size: 1.1rem;
                max-width: 800px;
                margin: 0 auto;
            }

            .error-icon {
                width: 80px;
                height: 80px;
                background-color: var(--white);
                border-radius: 50%;
                display: flex;
                align-items: center;
                justify-content: center;
                margin: 0 auto 20px;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
            }

            .error-icon i {
                font-size: 40px;
                color: var(--error-color);
            }

            .page-content {
                padding: 30px;
            }

            .error-section {
                margin-bottom: 30px;
                padding: 25px;
                border-radius: 15px;
                background-color: var(--light-gray);
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
                transition: transform 0.3s, box-shadow 0.3s;
                position: relative;
            }

            .error-section:hover {
                transform: translateY(-5px);
                box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
            }

            .error-section::before {
                content: '';
                position: absolute;
                top: 0;
                left: 0;
                width: 5px;
                height: 100%;
                background: linear-gradient(to bottom, var(--error-color), var(--primary-color));
                border-radius: 5px 0 0 5px;
            }

            .section-title {
                color: var(--error-color);
                margin-bottom: 20px;
                font-weight: 600;
                font-size: 1.5rem;
                display: flex;
                align-items: center;
            }

            .section-title i {
                margin-right: 12px;
                font-size: 1.3rem;
            }

            .error-message {
                background-color: var(--error-light);
                padding: 20px;
                border-radius: 10px;
                margin-bottom: 20px;
                color: var(--dark-gray);
                font-size: 1.1rem;
                border-left: 5px solid var(--error-color);
            }

            .error-code {
                font-family: monospace;
                background-color: #f1f3f5;
                padding: 5px 10px;
                border-radius: 5px;
                margin: 0 5px;
            }

            .action-buttons {
                display: flex;
                flex-wrap: wrap;
                gap: 15px;
                margin-top: 30px;
            }

            .action-btn {
                display: flex;
                align-items: center;
                justify-content: center;
                padding: 14px 25px;
                border-radius: 10px;
                font-size: 1rem;
                font-weight: 500;
                cursor: pointer;
                transition: all 0.3s;
                text-decoration: none;
                flex: 1 1 200px;
            }

            .action-btn i {
                margin-right: 10px;
                font-size: 1.1rem;
            }

            .retry-btn {
                background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
                color: var(--white);
                border: none;
                box-shadow: 0 4px 10px rgba(0, 84, 166, 0.2);
            }

            .retry-btn:hover {
                background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
                transform: translateY(-2px);
                box-shadow: 0 6px 15px rgba(0, 84, 166, 0.25);
            }

            .contact-btn {
                background-color: var(--white);
                color: var(--dark-gray);
                border: 1px solid #dee2e6;
            }

            .contact-btn:hover {
                background-color: #f1f3f5;
                transform: translateY(-2px);
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
            }

            .back-btn {
                background: linear-gradient(135deg, var(--secondary-color), var(--secondary-light));
                color: var(--white);
                border: none;
                box-shadow: 0 4px 10px rgba(58, 167, 170, 0.2);
            }

            .back-btn:hover {
                background: linear-gradient(135deg, var(--secondary-dark), var(--secondary-color));
                transform: translateY(-2px);
                box-shadow: 0 6px 15px rgba(58, 167, 170, 0.25);
            }

            .satim-support {
                display: flex;
                align-items: center;
                padding: 20px;
                background-color: var(--white);
                border-radius: 10px;
                box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                margin-top: 30px;
            }

            .satim-logo {
                width: 100px;
                margin-right: 20px;
            }

            .satim-text {
                flex: 1;
            }

            .satim-text p {
                margin-bottom: 5px;
                font-size: 1rem;
            }

            .satim-text .support-number {
                font-weight: 600;
                color: var(--primary-color);
                font-size: 1.2rem;
            }

            /* Animation */
            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(-10px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }

            .fade-in {
                animation: fadeIn 0.5s ease-out;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .container {
                    border-radius: 15px;
                }

                .page-header {
                    padding: 30px 20px;
                }

                .page-header h1 {
                    font-size: 2rem;
                }

                .error-icon {
                    width: 60px;
                    height: 60px;
                }

                .error-icon i {
                    font-size: 30px;
                }

                .page-content {
                    padding: 20px;
                }

                .error-section {
                    padding: 20px;
                }

                .action-buttons {
                    flex-direction: column;
                }

                .action-btn {
                    width: 100%;
                }

                .satim-support {
                    flex-direction: column;
                    text-align: center;
                }

                .satim-logo {
                    margin-right: 0;
                    margin-bottom: 15px;
                }
            }
        </style>
    </head>

    <body>
        <div class="container">
            <div class="page-header">
                <div class="error-icon">
                    <i class="fas fa-times"></i>
                </div>
                <h1>Échec du Paiement</h1>
                <p>Nous n'avons pas pu traiter votre paiement. Veuillez vérifier les détails ci-dessous.</p>
            </div>

            <div class="page-content">
                <div class="error-section fade-in">
                    <h2 class="section-title"><i class="fas fa-exclamation-triangle"></i>Raison de l'échec</h2>

                    <div class="error-message">
                        Votre transaction a été refusée par votre banque avec le code d'erreur <span class="error-code">ERR-5023</span>: Fonds insuffisants sur le compte.
                    </div>

                    <p>Voici quelques raisons possibles pour lesquelles votre paiement a échoué :</p>
                    <ul>
                        <li>Solde insuffisant sur votre compte bancaire</li>
                        <li>Limite de paiement quotidienne atteinte</li>
                        <li>Carte expirée ou informations incorrectes</li>
                        <li>Transaction bloquée par votre banque pour des raisons de sécurité</li>
                    </ul>

                    <div class="action-buttons">
                        <a href="#" class="action-btn retry-btn">
                            <i class="fas fa-redo"></i>
                            Réessayer le paiement
                        </a>

                        <a href="#" class="action-btn contact-btn">
                            <i class="fas fa-headset"></i>
                            Contacter le support
                        </a>

                        <a href="#" class="action-btn back-btn">
                            <i class="fas fa-arrow-left"></i>
                            Retour à la boutique
                        </a>
                    </div>

                    <div class="satim-support">
                        <img src="https://via.placeholder.com/100x50" alt="Logo SATIM" class="satim-logo">
                        <div class="satim-text">
                            <p>En cas de problème de paiement, veuillez contacter le numéro vert de la SATIM</p>
                            <p class="support-number">3020</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            // Simuler un nouvel essai de paiement
            document.querySelector('.retry-btn').addEventListener('click', function(e) {
                e.preventDefault();
                if (confirm('Voulez-vous réessayer le paiement avec une autre carte ?')) {
                    // Redirection vers la page de paiement
                    window.location.href = 'page-produit.html';
                }
            });

            // Simuler le contact du support
            document.querySelector('.contact-btn').addEventListener('click', function(e) {
                e.preventDefault();
                alert('Vous allez être redirigé vers notre page de support client.');
                // Redirection vers la page de support
                // window.location.href = 'support.html';
            });

            // Retour à la boutique
            document.querySelector('.back-btn').addEventListener('click', function(e) {
                e.preventDefault();
                // Redirection vers la page d'accueil
                window.location.href = 'index.html';
            });
        </script>
    </body>

    </html>
<?php
    return ob_get_clean();
}

add_shortcode('sefarpay_declined', 'sefarpay_payment_declined_shortcode');

// traitement de la requête de paiemen
add_action('admin_post_nopriv_sefarpay_payment', 'sefarpay_handle_payment'); //utilisé pour les utilisateurs non connectés
add_action('admin_post_sefarpay_payment', 'sefarpay_handle_payment'); //utilisé pour les utilisateurs connecté

require_once plugin_dir_path(__FILE__) . 'admin/sefarpay-payment-handler.php'; //include du fichier de traitement de la requête de paiement
