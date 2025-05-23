<?php
if (!defined('ABSPATH')) exit;

add_action('wp_ajax_sefarpay_save_registration', 'sefarpay_save_registration');

function sefarpay_save_registration()
{
    global $wpdb;

    // Vérifier la présence des champs nécessaires
    $required_fields = [
        'civilite',
        'nom',
        'prenom',
        'email',
        'telephone',
        'raison_sociale',
        'adresse',
        'domaine',
        'wilaya',
        'commune',
        'date_debut',
        'type_activite',
        'forme_juridique',
        'banque',
        'email_entite',
        'telephone_entite',
        'numero_registre'
    ];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            wp_send_json_error("Champ manquant : $field");
        }
    }

    // Gestion du fichier téléversé
    if (!empty($_FILES['document']['tmp_name'])) {
        $upload = wp_handle_upload($_FILES['document'], ['test_form' => false]);
        if (isset($upload['url'])) {
            $document_url = $upload['url'];
        } else {
            wp_send_json_error("Échec du téléchargement du fichier.");
        }
    } else {
        wp_send_json_error("Document requis.");
    }

    // Préparation des données
    $data = [
        'civilite' => sanitize_text_field($_POST['civilite']),
        'nom' => sanitize_text_field($_POST['nom']),
        'prenom' => sanitize_text_field($_POST['prenom']),
        'email' => sanitize_email($_POST['email']),
        'telephone' => sanitize_text_field($_POST['telephone']),
        'raison_sociale' => sanitize_text_field($_POST['raison_sociale']),
        'adresse' => sanitize_textarea_field($_POST['adresse']),
        'site_web' => sanitize_text_field($_POST['domaine']),
        'wilaya' => sanitize_text_field($_POST['wilaya']),
        'commune' => sanitize_text_field($_POST['commune']),
        'date_debut_activite' => sanitize_text_field($_POST['date_debut']),
        'type_activite' => sanitize_text_field($_POST['type_activite']),
        'forme_juridique' => sanitize_text_field($_POST['forme_juridique']),
        'banque' => sanitize_text_field($_POST['banque']),
        'email_societe' => sanitize_email($_POST['email_entite']),
        'telephone_societe' => sanitize_text_field($_POST['telephone_entite']),
        'numero_registre' => sanitize_text_field($_POST['numero_registre']),
        'registre_document_url' => esc_url_raw($document_url),
        'statut' => 'en_attente',
        'created_at' => current_time('mysql')
    ];

    // Insertion
    $table = $wpdb->prefix . 'sefarpay_enregistrements';
    $result = $wpdb->insert($table, $data);

    if ($result) {
        wp_send_json_success("Enregistrement réussi.");
    } else {
        wp_send_json_error("Erreur lors de l’enregistrement.");
    }
}
