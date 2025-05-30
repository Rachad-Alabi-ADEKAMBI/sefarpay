<?php if (!defined('ABSPATH')) exit; ?>

<div class="page-content">
    <!-- Résumé de la commande -->
    <div class="order-summary fade-in">
        <h2 class="section-title">
            <i class="fas fa-shopping-cart"></i>Résumé de votre commande
        </h2>

        <div class="satim">
            <?php
            global $wpdb;
            $table_name = $wpdb->prefix . 'sefarpay_configuration';

            // Récupération de la première ligne de configuration
            $config_row = $wpdb->get_row("SELECT * FROM $table_name LIMIT 1", ARRAY_A);

            if (empty($config_row)) {
                echo '<p>Aucune configuration trouvée. Veuillez configurer SefarPay.</p>';
                return;
            }

            // Extraction des valeurs pour le formulaire
            $currency   = esc_attr($config_row['currency'] ?? '');
            $language   = esc_attr($config_row['language'] ?? '');
            $userName   = esc_attr($config_row['username'] ?? '');
            $password   = esc_attr($config_row['password'] ?? '');
            $returnUrl  = esc_url($config_row['return_url'] ?? '');
            $failUrl    = esc_url($config_row['fail_url'] ?? '');
            $jsonParams = esc_textarea($config_row['json_params'] ?? '');
            $cgu = esc_textarea($config_row['cgu_url'] ?? '');

            //$order_number

            $order = wc_create_order();

            // Ajouter des produits du panier à la commande
            foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
                $order->add_product($cart_item['data'], $cart_item['quantity']);
            }

            // Ajouter les totaux
            $order->set_total(WC()->cart->get_total('edit'));
            $order->save();

            // Récupération des infos
            $order_id = $order->get_id();
            $amount = $order->get_total(); // Cela retourne un float ou string comme "124.00"
            $new_amount = $amount * 100;
            $orderNumber = $order->get_order_number();

            // echo "Commande #$orderNumber (ID : $order_id) – Montant : $amount";


            // Données pour personnalisation bouton
            $buttonColor = esc_attr($config_row['button_color']);
            $buttonSize  = esc_attr($config_row['button_size']);
            $buttonText  = esc_html($config_row['button_text']);
            ?>

            <div class="product-list">
                <?php foreach ($cart_data['items'] as $item): ?>
                    <div class="product-item">
                        <?php echo $item['image']; ?>
                        <div class="product-details">
                            <div class="product-name"><?php echo esc_html($item['name']); ?></div>
                            <div class="product-description"><?php echo esc_html($item['description']); ?></div>
                            <div class="product-quantity">Quantité : <?php echo intval($item['quantity']); ?></div>
                            <div class="product-price"><?php echo $item['price']; ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="order-totals">
                <div class="total-row"><span>Sous-total</span><span><?php echo $cart_data['subtotal']; ?></span></div>
                <div class="total-row"><span>Livraison</span><span><?php echo $cart_data['shipping']; ?></span></div>
                <div class="total-row"><span>TVA</span><span><?php echo $cart_data['tax']; ?></span></div>
                <div class="total-row grand-total"><span>Total</span><span><?php echo $amount ?></span></div>
            </div>
        </div>

        <!-- Section de paiement -->
        <div class="payment-section fade-in" style="animation-delay: 0.2s">
            <h2 class="section-title">
                <i class="fas fa-credit-card"></i>Paiement
            </h2>

            <?php $url_do = SEFARPAY_API_URL_PAYMENT_DO;  ?>

            <form method="get" action="<?= $url_do ?>" id="payment-form">
                <input type="hidden" name="action" value="sefarpay_payment">

                <input type="text" id="currency" name="currency" style="display: none;" value="<?php echo $currency; ?>" />

                <input type="text" id="language" name="language" style="display: none;" value="<?php echo $language; ?>" />

                <input type="text" id="userName" name="userName" style="display: none;" value="<?php echo $userName; ?>" />

                <input type="password" id="password" name="password" style="display: none;" value="<?php echo $password; ?>" />

                <input type="url" id="returnUrl" name="returnUrl" style="display: none;" value="<?php echo $returnUrl; ?>" />

                <input type="url" id="failUrl" name="failUrl" style="display: none;" value="<?php echo $failUrl; ?>" />

                <textarea id="jsonParams" name="jsonParams" style="display: none;" rows="4"><?php echo $jsonParams; ?></textarea>

                <input type="text" id="amount" name="amount" style="display: none;" value="<?php echo $new_amount; ?>" />

                <input type="text" id="OrderNumber" name="orderNumber" style="display: none;" value="<?php echo $orderNumber ?>" />
                <!-- verifier que le client est autorisé à utiliser le plugin sefarpay  -->
                <?php
                global $wpdb;

                $validation_url = SEFARPAY_API_URL_CHECK_ACCOUNT_STATUS;

                // Récupérer le sefarpay_id depuis la table sefarpay_enregistrements
                $table_enregistrements = $wpdb->prefix . 'sefarpay_enregistrements';
                $sefarpay_id = $wpdb->get_var("SELECT sefarpay_id FROM $table_enregistrements LIMIT 1");

                $status = 'Non configuré';

                if ($sefarpay_id) {
                    // Construire l’URL avec paramètre GET
                    $request_url = add_query_arg('sefarpay_id', urlencode($sefarpay_id), $validation_url);

                    // Requête GET
                    $response = wp_remote_get($request_url);

                    if (!is_wp_error($response)) {
                        $body = wp_remote_retrieve_body($response);
                        $status = trim($body); // Suppression des espaces ou retours à la ligne
                    } else {
                        $status = 'Erreur de récupération du statut';
                    }
                }
                $status = trim($status, " \t\n\r\0\x0B\"'"); ?>

                <?php if ($status == 'Actif') { ?>
                    <div class="payment-options">
                        <?php
                        switch ($buttonSize) {
                            case 'small':
                                $buttonStyle = 'width: 100px; height: 30px;';
                                break;
                            case 'medium':
                                $buttonStyle = 'width: 150px; height: 40px;';
                                break;
                            case 'large':
                                $buttonStyle = 'width: 200px; height: 50px;';
                                break;
                            default:
                                $buttonStyle = '';
                        }
                        ?>

                        <button type="submit"
                            id="pay-button"
                            class="payment-btn"
                            style="background-color: <?php echo $buttonColor; ?>; <?php echo $buttonStyle; ?>">
                            <?php echo $buttonText; ?>
                        </button>


                        <div class="terms-captcha">
                            <p>
                                En cliquant sur "Payer", vous acceptez
                                <a href="<?php echo $cgu; ?>" class="terms-link">nos conditions générales d'utilisation
                                </a>
                            </p>



                            <div class="captcha-container">
                                <div class="captcha-header">
                                    <span class="captcha-title">Veuillez saisir le code ci-dessous</span>
                                    <button class="captcha-refresh" title="Actualiser le captcha">
                                        <i class="fas fa-sync-alt"></i>
                                    </button>
                                </div>
                                <div class="captcha-image">XD94K7</div>
                                <input type="text" class="captcha-input" placeholder="Entrez le code" />
                            </div>
                        </div>
                    </div>
                <?php  } else if ($status == 'Inactif') { ?>
                    <p>
                        Plugin Inactif, merci de contacter SefarPay
                    </p>
                <?php } else { ?>
                    <p>
                        Veuillez configurer le plugin Sefarpay
                    </p>
                <?php } ?>
            </form>

        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('payment-form');

        form.addEventListener('submit', function(e) {
            e.preventDefault(); // Empêche la soumission normale

            const params = new URLSearchParams(new FormData(form)).toString();

            const SEFARPAY_API_URL_PAYMENT = "<?php echo SEFARPAY_API_URL_PAYMENT_DO; ?>";


            fetch(SEFARPAY_API_URL_PAYMENT + '?' + params, {
                    method: 'GET',
                    credentials: 'same-origin'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.ErrorCode === 0) {
                        // Redirige vers l'URL FormUrl

                        alert('Vous serez redirigé vers le portail de la Satim pour le paiement sécurisé');
                        window.location.href = data.FormUrl;
                    } else {
                        // Affiche le message d'erreur
                        alert('Erreur : ' + data.ErrorMessage);
                    }
                })
                .catch(error => {
                    console.error('Erreur AJAX:', error);
                    alert('Erreur réseau ou serveur');
                });
        });
    });
</script>


<style>
    :root {
        --primary-color: #0054a6;
        --primary-light: #1a6bc2;
        --primary-dark: #00407d;
        --secondary-color: #3aa7aa;
        --secondary-light: #4dbdc0;
        --secondary-dark: #2a8a8d;
        --light-gray: #f8f9fa;
        --medium-gray: #e9ecef;
        --dark-gray: #343a40;
        --error-color: #e74c3c;
        --success-color: #2ecc71;
        --white: #ffffff;
    }

    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
        font-family: "Poppins", "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
    }

    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");

    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
        color: var(--dark-gray);
        line-height: 1.6;
        min-height: 100vh;
        padding: 40px 20px;
    }

    .container {
        max-width: 1100px;
        margin: 0 auto;
        background-color: var(--white);
        border-radius: 20px;
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        position: relative;
    }

    .page-header {
        background: linear-gradient(135deg,
                var(--primary-color) 0%,
                var(--primary-light) 100%);
        padding: 40px 30px;
        text-align: center;
        position: relative;
    }

    .page-header::after {
        content: "";
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

    .page-content {
        padding: 20px 30px 40px;
    }

    .order-summary {
        margin-bottom: 30px;
        padding: 25px;
        border-radius: 15px;
        background-color: var(--light-gray);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
    }

    .order-summary:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
    }

    .order-summary::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(to bottom,
                var(--primary-color),
                var(--secondary-color));
        border-radius: 5px 0 0 5px;
    }

    .section-title {
        color: var(--primary-color);
        margin-bottom: 25px;
        font-weight: 600;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        position: relative;
    }

    .section-title i {
        margin-right: 12px;
        font-size: 1.3rem;
        background: linear-gradient(135deg,
                var(--primary-color),
                var(--secondary-color));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .product-list {
        margin-bottom: 20px;
    }

    .product-item {
        display: flex;
        align-items: center;
        padding: 15px;
        border-bottom: 1px solid #dee2e6;
        transition: background-color 0.3s;
    }

    .product-item:last-child {
        border-bottom: none;
    }

    .product-item:hover {
        background-color: rgba(233, 236, 239, 0.5);
    }

    .product-image {
        width: 80px;
        height: 80px;
        border-radius: 10px;
        object-fit: cover;
        margin-right: 20px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .product-details {
        flex: 1;
    }

    .product-name {
        font-weight: 600;
        font-size: 1.1rem;
        margin-bottom: 5px;
        color: var(--dark-gray);
    }

    .product-description {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 5px;
    }

    .product-price {
        font-weight: 600;
        color: var(--primary-color);
        font-size: 1.1rem;
    }

    .order-totals {
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px dashed #dee2e6;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        font-size: 1rem;
    }

    .total-row.grand-total {
        font-size: 1.2rem;
        font-weight: 600;
        color: var(--primary-color);
        padding-top: 10px;
        border-top: 1px solid #dee2e6;
        margin-top: 10px;
    }

    .payment-section {
        margin-bottom: 30px;
        padding: 25px;
        border-radius: 15px;
        background-color: var(--light-gray);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
        transition: transform 0.3s, box-shadow 0.3s;
        position: relative;
    }

    .payment-section:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
    }

    .payment-section::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        width: 5px;
        height: 100%;
        background: linear-gradient(to bottom,
                var(--primary-color),
                var(--secondary-color));
        border-radius: 5px 0 0 5px;
    }

    .payment-options {
        margin-top: 20px;
    }

    .payment-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 16px 20px;
        background: linear-gradient(135deg,
                var(--primary-color),
                var(--primary-light));
        color: white;
        border: none;
        border-radius: 10px;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        box-shadow: 0 6px 15px rgba(0, 84, 166, 0.25);
        position: relative;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .payment-btn::before {
        content: "";
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg,
                transparent,
                rgba(255, 255, 255, 0.2),
                transparent);
        transition: left 0.7s;
    }

    .payment-btn:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 20px rgba(0, 84, 166, 0.3);
    }

    .payment-btn:hover::before {
        left: 100%;
    }

    .payment-btn:active {
        transform: translateY(-1px);
    }

    .payment-btn img {
        height: 30px;
        margin-right: 15px;
    }

    .terms-captcha {
        margin-top: 20px;
        text-align: center;
    }

    .terms-link {
        display: inline-block;
        margin-bottom: 15px;
        color: var(--primary-color);
        text-decoration: none;
        font-size: 0.95rem;
        transition: color 0.3s;
    }

    .terms-link:hover {
        color: var(--primary-dark);
        text-decoration: underline;
    }

    .captcha-container {
        background-color: var(--white);
        border-radius: 10px;
        padding: 15px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        margin-top: 15px;
    }

    .captcha-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .captcha-title {
        font-size: 0.9rem;
        color: #6c757d;
        font-weight: 500;
    }

    .captcha-refresh {
        background: none;
        border: none;
        color: var(--secondary-color);
        cursor: pointer;
        font-size: 1rem;
        transition: color 0.3s;
    }

    .captcha-refresh:hover {
        color: var(--secondary-dark);
    }

    .captcha-image {
        width: 100%;
        height: 80px;
        background-color: #f1f3f5;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        font-family: "Courier New", monospace;
        font-size: 1.8rem;
        font-weight: bold;
        color: #495057;
        letter-spacing: 5px;
        text-decoration: line-through;
        position: relative;
        overflow: hidden;
    }

    .captcha-image::before {
        content: "";
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: repeating-linear-gradient(45deg,
                transparent,
                transparent 10px,
                rgba(0, 0, 0, 0.05) 10px,
                rgba(0, 0, 0, 0.05) 20px);
    }

    .captcha-input {
        width: 100%;
        padding: 12px 15px;
        border: 2px solid #dee2e6;
        border-radius: 10px;
        font-size: 1rem;
        transition: all 0.3s;
    }

    .captcha-input:focus {
        outline: none;
        border-color: var(--secondary-color);
        box-shadow: 0 0 0 4px rgba(58, 167, 170, 0.15);
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

        .page-content {
            padding: 15px 20px 30px;
        }

        .order-summary,
        .payment-section {
            padding: 20px;
        }

        .product-item {
            flex-direction: column;
            align-items: flex-start;
        }

        .product-image {
            margin-bottom: 15px;
            margin-right: 0;
        }

        .payment-btn {
            padding: 14px 16px;
            font-size: 1rem;
        }

        .payment-btn img {
            height: 24px;
            margin-right: 10px;
        }
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
</style>