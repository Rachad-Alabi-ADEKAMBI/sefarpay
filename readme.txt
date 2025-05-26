=== Sefarpay ===
Contributors: rachad-dev
Tags: paiement, paiement sécurisé, e-commerce, gateway, satim
Requires at least: 5.0
Tested up to: 6.5
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Sefarpay permet d'intégrer un système de paiement sécurisé basé sur la passerelle Satim dans votre site WordPress.

== Description ==

Sefarpay est une passerelle de paiement simple et rapide pour WordPress. Elle permet aux utilisateurs d’effectuer des paiements sécurisés via la plateforme Satim.

Fonctionnalités principales :
- Intégration facile avec la passerelle Satim
- Affichage des paiements effectués dans l'administration
- Validation des statuts de transaction
- Journalisation des paiements avec détails de transaction
- Compatible avec WooCommerce (optionnel à activer)

== Installation ==

1. Téléchargez le plugin depuis WordPress.org ou transférez le dossier dans `wp-content/plugins/sefarpay`.
2. Activez le plugin via le menu "Extensions" de WordPress.
3. Configurez les paramètres via le menu "Sefarpay" dans l'administration WordPress.
4. Ajoutez les identifiants fournis par Satim pour connecter votre boutique.

== Frequently Asked Questions ==

= Est-ce que ce plugin est compatible avec WooCommerce ? =
Oui, une intégration WooCommerce est en cours de développement ou peut être activée manuellement si nécessaire.

= Où puis-je voir les transactions ? =
Toutes les transactions sont visibles dans le menu d’administration “Sefarpay > Paiements”.

= Est-ce que les données de paiement sont sécurisées ? =
Oui. Aucune donnée sensible n’est stockée. Les paiements sont directement traités via la passerelle Satim.

== Screenshots ==

1. Liste des transactions dans l'administration
2. Détails d'une transaction
3. Interface de paiement (frontend)

== Changelog ==

= 1.0.0 =
* Première version stable
* Affichage des paiements
* Vérification du statut via l’API
* Interface simple de configuration

== Upgrade Notice ==

= 1.0.0 =
Première version officielle du plugin Sefarpay.

== License ==

Ce plugin est sous licence GPLv2 ou ultérieure.
