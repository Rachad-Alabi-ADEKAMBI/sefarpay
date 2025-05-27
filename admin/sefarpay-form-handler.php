<?php
if (!defined('ABSPATH')) exit;

add_action('wp_ajax_sefarpay_save_registration', 'sefarpay_save_registration');

function sefarpay_save_registration()
{
    global $wpdb;

    // Empêcher plusieurs enregistrements
    $table = $wpdb->prefix . 'sefarpay_enregistrements';
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $table");
    if ($count > 0) {
        wp_send_json_error("Enregistrement déjà effectué.");
    }

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
        'email_societe',
        'telephone_societe',
        'numero_registre'
    ];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            wp_send_json_error("Champ manquant : $field");
        }
    }

    // Téléversement du document
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
        'email_societe' => sanitize_email($_POST['email_societe']),
        'telephone_societe' => sanitize_text_field($_POST['telephone_societe']),
        'numero_registre' => sanitize_text_field($_POST['numero_registre']),
        'registre_document_url' => esc_url_raw($document_url),
        'statut' => 'en_attente',
        'created_at' => current_time('mysql')
    ];

    // Insertion dans la BDD locale
    // Insertion dans la BDD locale
    $result = $wpdb->insert($table, $data);

    if ($result) {
        $insert_id = $wpdb->insert_id; // ID de la ligne insérée

        // Envoi vers API du fournisseur
        $response = wp_remote_post(SEFARPAY_API_URL_REGISTER, [
            'method'  => 'POST',
            'headers' => ['Content-Type' => 'application/json'],
            'body'    => wp_json_encode($data),
        ]);

        if (is_wp_error($response)) {
            wp_send_json_error("Erreur API fournisseur : " . $response->get_error_message());
        }

        $body = wp_remote_retrieve_body($response);
        $json = json_decode($body, true);

        if (isset($json['client_id'])) {
            // Mettre à jour la colonne sefarpay_id dans la ligne insérée
            $update_result = $wpdb->update(
                $table,
                ['sefarpay_id' => $json['client_id']],
                ['id' => $insert_id]  // suppose que la clé primaire s'appelle id
            );

            if ($update_result === false) {
                wp_send_json_error("Erreur lors de la mise à jour du client_id.");
            }
        } else {
            wp_send_json_error("client_id non reçu dans la réponse API.");
        }

        wp_send_json_success("Enregistrement réussi.");
    } else {
        wp_send_json_error("Erreur lors de l’enregistrement.");
    }
}

?>

<script>
    jQuery(document).ready(function($) {
        $('#sefarpay-form').on('submit', function(e) {
            e.preventDefault();

            let form = $(this)[0];
            let formData = new FormData(form);
            let submitBtn = $('#submit-button');
            submitBtn.prop('disabled', true);

            // Affiche un console.log lisible
            let logData = {};
            formData.forEach((value, key) => {
                logData[key] = value;
            });
            console.log("Requête envoyée :", logData);

            $.ajax({
                url: sefarpay_ajax.ajax_url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    if (response.success) {
                        alert(response.data);
                        form.reset(); // vider les champs
                    } else {
                        alert("Erreur : " + response.data);
                    }
                    submitBtn.prop('disabled', false);
                },
                error: function(err) {
                    console.log("Erreur AJAX", err);
                    alert("Erreur réseau.");
                    submitBtn.prop('disabled', false);
                }
            });
        });
    });
</script>