<?php
if (!defined('ABSPATH')) exit;

function sefarpay_create_tables()
{
    global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

    // 🔹 Table 1 : Enregistrements
    $table_register = $wpdb->prefix . 'sefarpay_enregistrements';
    $sql1 = "CREATE TABLE $table_register (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        civilite VARCHAR(10),
        nom VARCHAR(100),
        prenom VARCHAR(100),
        email VARCHAR(255),
        telephone VARCHAR(50),
        raison_sociale VARCHAR(255),
        adresse TEXT,
        site_web VARCHAR(255),
        wilaya VARCHAR(100),
        commune VARCHAR(100),
        date_debut_activite DATE,
        type_activite VARCHAR(100),
        forme_juridique VARCHAR(100),
        banque VARCHAR(100),
        email_societe VARCHAR(255),
        telephone_societe VARCHAR(50),
        numero_registre VARCHAR(100),
        registre_document_url TEXT,
        statut VARCHAR(50) DEFAULT 'en_attente',
        sefarpay_id VARCHAR(50) NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";
    dbDelta($sql1);

    // 🔹 Table 2 : Configuration
    $table_config = $wpdb->prefix . 'sefarpay_configuration';
    $sql2 = "CREATE TABLE $table_config (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        client_identifier VARCHAR(255),
        username VARCHAR(255),
        password VARCHAR(255),
        currency VARCHAR(10),
        language VARCHAR(10),
        return_url TEXT,
        fail_url TEXT,
        json_params TEXT,
        cgu_url TEXT,
        captcha_site_key VARCHAR(255),
        captcha_secret_key VARCHAR(255),
        base_url_api TEXT,
        button_text VARCHAR(100),
        button_color VARCHAR(20),
        button_size VARCHAR(20),
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";
    dbDelta($sql2);

    // 🔹 Table 3 : Paiements
    $table_paiements = $wpdb->prefix . 'sefarpay_paiements';
    $sql3 = "CREATE TABLE $table_paiements (
        id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        utilisateur_id BIGINT,
        nom_utilisateur VARCHAR(255),
        email_utilisateur VARCHAR(255),
        telephone_utilisateur VARCHAR(50),
        numero_commande VARCHAR(20),
        transaction_id VARCHAR(255),
        date_paiement DATETIME,
        description TEXT,
        montant DECIMAL(10,2),
        devise VARCHAR(10),
        moyen_paiement VARCHAR(50),
        nom_carte VARCHAR(255),
        pan VARCHAR(50),
        systeme_paiement VARCHAR(50),
        etat_paiement VARCHAR(50),
        score_fraude VARCHAR(50),
        ip_utilisateur VARCHAR(50),
        banque VARCHAR(100),
        pays_banque VARCHAR(100),
        code_action VARCHAR(20),
        description_action TEXT,
        code_acceptation VARCHAR(50),
        code_autorisation VARCHAR(50),
        code_reference VARCHAR(255),
        montant_depose DECIMAL(10,2),
        montant_rembourse DECIMAL(10,2),
        montant_approuve DECIMAL(10,2),
        terminal_id VARCHAR(100),
        type_authentification VARCHAR(100),
        return_url TEXT,
        fail_url TEXT,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    ) $charset_collate;";
    dbDelta($sql3);
}
