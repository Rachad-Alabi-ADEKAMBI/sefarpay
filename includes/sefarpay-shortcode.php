<?php
if (!defined('ABSPATH')) exit;

function sefarpay_shortcode()
{
    // Vérifier que WooCommerce est actif
    if (!function_exists('WC')) {
        return '<p>WooCommerce doit être activé pour utiliser ce shortcode.</p>';
    }

    // masquer l'en-tête et le récapitulatif de la commande
    echo '<style>
        #order_review_heading, #order_review {
            display: none !important;
        }</style>';


    // Récupérer les données du panier
    $cart = WC()->cart;
    if (!$cart) {
        return '<p>Le panier est vide ou non disponible.</p>';
    }

    $items = [];
    foreach ($cart->get_cart() as $cart_item) {
        $product = $cart_item['data'];
        $items[] = [
            'name' => $product->get_name(),
            'description' => $product->get_short_description(),
            'quantity' => $cart_item['quantity'],
            'price' => wc_price($product->get_price()),
            'image' => $product->get_image()
        ];
    }

    // Si le panier est vide, retourner un message
    if (empty($items)) {
        return '<p>Le panier est vide.</p>';
    } else {
        $cart = WC()->cart;

        $cart_data = [
            'items' => $items,
            'subtotal' => wc_price($cart->get_subtotal()),
            'shipping' => wc_price($cart->get_shipping_total()),
            'tax' => wc_price($cart->get_taxes_total()),
            'total' => wc_price($cart->get_total())
        ];
    }



    //var_dump($cart_data); // Pour débogage, à supprimer en production

    global $wpdb;

    // Valeurs par défaut
    $default_config = [
        'button_color' => '#007bff',
        'button_size'  => '16px',
        'button_text'  => 'Payer maintenant',
    ];

    // Tenter de récupérer la configuration depuis la table personnalisée
    $table_name = $wpdb->prefix . 'sefarpay_configuration';
    $config_row = $wpdb->get_row("SELECT * FROM $table_name LIMIT 1", ARRAY_A);

    // Si aucune configuration trouvée, utiliser les valeurs par défaut
    $config = is_array($config_row) ? array_merge($default_config, $config_row) : $default_config;

    // Inclure la vue
    ob_start();
    include plugin_dir_path(__FILE__) . '../views/sefarpay.php';
    return ob_get_clean();
}

add_shortcode('sefarpay', 'sefarpay_shortcode');
