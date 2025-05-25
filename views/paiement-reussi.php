<div class="sefarpay-confirmation" style="max-width: 600px; margin: 0 auto; padding: 20px; font-family: sans-serif;">
    <?php if (!empty($transaction_data)) : ?>
        <?php if ($transaction_data['order_status'] == 2 && ($transaction_data['error_code'] == 0 || $transaction_data['error_code'] == 2)) : ?>
            <h2 style="color: green;">✅ Paiement réussi</h2>
            <p>Votre transaction a été traitée avec succès.</p>
            <ul style="list-style: none; padding-left: 0;">
                <li><strong>Commande :</strong> <?= esc_html($transaction_data['order_number']) ?></li>
                <li><strong>Montant :</strong> <?= esc_html($transaction_data['amount']) ?> <?= esc_html($transaction_data['currency']) ?></li>
                <li><strong>Carte :</strong> <?= esc_html($transaction_data['pan']) ?> (<?= esc_html($transaction_data['expiration']) ?>)</li>
                <li><strong>Porteur :</strong> <?= esc_html($transaction_data['cardholder_name']) ?></li>
                <li><strong>Code d’approbation :</strong> <?= esc_html($transaction_data['approval_code']) ?></li>
                <li><strong>Message :</strong> <?= esc_html($transaction_data['resp_message']) ?></li>
            </ul>
        <?php else : ?>
            <h2 style="color: red;">❌ Paiement échoué</h2>
            <p>Une erreur est survenue lors du traitement de votre paiement.</p>
            <ul style="list-style: none; padding-left: 0;">
                <li><strong>Code erreur :</strong> <?= esc_html($transaction_data['error_code']) ?></li>
                <li><strong>Message :</strong> <?= esc_html($transaction_data['error_message']) ?></li>
                <li><strong>Détail :</strong> <?= esc_html($transaction_data['resp_message']) ?></li>
            </ul>
        <?php endif; ?>
    <?php else : ?>
        <p>Aucune information de transaction disponible.</p>
    <?php endif; ?>
</div>