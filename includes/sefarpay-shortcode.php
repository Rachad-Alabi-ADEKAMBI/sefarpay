<?php
if (!defined('ABSPATH')) exit;

// Définir le shortcode [sefarpay]
function sefarpay_shortcode($atts = [])
{
    $options = get_option('sefarpay_configuration', []);
    $atts = shortcode_atts([
        'montant'         => '',
        'description'     => '',
        'texte'           => $options['button_text'] ?? 'Payer maintenant',
        'couleur'         => $options['button_color'] ?? '#0054A6',
        'taille'          => $options['button_size'] ?? 'medium',
        'terms_url'       => $options['terms_url'] ?? 'http://127.0.0.1/wordpress/terms',
        'captcha_site_key' => $options['captcha_site_key'] ?? 'xxx',
    ], $atts);

    ob_start();
?>
    <div class="sefarpay-container">
        <form id="sefarpay-form">
            <button type="submit"
                class="sefarpay-button <?= esc_attr($atts['taille']) ?>"
                style="background-color: <?= esc_attr($atts['couleur']) ?>;">
                <img src="<?= plugins_url('assets/img/cib-edahabia.svg', __FILE__) ?>" alt="CIB/Edahabia" class="card-icon" />
                <?= esc_html($atts['texte']) ?>
            </button>

            <?php if (!empty($atts['terms_url'])): ?>
                <p class="sefarpay-cgu">
                    En cliquant, vous acceptez nos
                    <a href="<?= esc_url($atts['terms_url']) ?>" target="_blank" rel="noopener">Conditions Générales d’Utilisation</a>.
                </p>
            <?php endif; ?>

            <?php if (!empty($atts['captcha_site_key'])): ?>
                <div class="sefarpay-captcha">
                    <div class="g-recaptcha" data-sitekey="<?= esc_attr($atts['captcha_site_key']) ?>"></div>
                </div>
            <?php endif; ?>
        </form>
    </div>
<?php
    return ob_get_clean();
}
add_shortcode('sefarpay', 'sefarpay_shortcode');
