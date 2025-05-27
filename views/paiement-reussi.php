  <!DOCTYPE html>
  <html lang="fr">

  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Confirmation de Paiement</title>
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
      <!-- jQuery -->
      <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

  </head>

  <body>
      <div class="container">
          <div class="page-header">
              <div class="success-icon">
                  <i class="fas fa-check"></i>
              </div>
              <h1>Paiement Confirmé</h1>
              <p>Votre paiement a été traité avec succès. Merci pour votre achat!</p>
          </div>

          <div class="page-content">
              <!-- Détails de la transaction -->
              <div class="confirmation-section fade-in">
                  <h2 class="section-title"><i class="fas fa-info-circle"></i>Détails de la transaction</h2>

                  <div class="transaction-details">
                      <div class="detail-item">
                          <div class="detail-label">Numéro de commande</div>
                          <div class="detail-value">
                              <?= esc_html($transaction_data['order_number']) ?>
                          </div>
                      </div>

                      <div class="detail-item">
                          <div class="detail-label">ID de transaction SATIM</div>
                          <div class="detail-value">
                              <?= esc_html($transaction_data['order_id']) ?>
                          </div>
                      </div>

                      <div class="detail-item">
                          <div class="detail-label">Code d'autorisation</div>
                          <div class="detail-value">
                              <?= esc_html($transaction_data['approval_code']) ?>
                          </div>
                      </div>

                      <div class="detail-item">
                          <div class="detail-label">Date et heure</div>
                          <div class="detail-value">
                              <?= esc_html($transaction_data['date_of_transaction']) ?>
                          </div>
                      </div>

                      <div class="detail-item">
                          <div class="detail-label">Montant payé</div>
                          <div class="detail-value">
                              <?= esc_html($transaction_data['amount']) ?>
                              <?= esc_html($transaction_data['currency']) ?>
                          </div>
                      </div>

                      <div class="detail-item">
                          <div class="detail-label">Moyen de paiement</div>
                          <div class="detail-value">CIB</div>
                      </div>
                  </div>
              </div>

              <!-- Actions -->
              <div class="actions-section fade-in" style="animation-delay: 0.2s;">
                  <h2 class="section-title"><i class="fas fa-file-invoice"></i>Votre facture</h2>

                  <div class="action-buttons">
                      <button class="action-btn download-btn" id="download" onclick="downloadFacturePDF()">
                          <i class="fas fa-download"></i>
                          Télécharger la facture
                      </button>

                      <button class="action-btn print-btn" onclick="printFacture()">
                          <i class="fas fa-print"></i>
                          Imprimer la facture
                      </button>

                      <buttton class="action-btn email-btn" onclick="sendFactureEmail()">
                          <i class="fas fa-envelope"></i>
                          Envoyer par email
                          </button>
                  </div>

                  <div class="satim-support">
                      <img src="https://via.placeholder.com/100x50" alt="Logo SATIM" class="satim-logo">
                      <div class="satim-text">
                          <p>En cas de problème de paiement, veuillez contacter le numéro vert de la SATIM</p>
                          <p class="support-number">3020</p>
                      </div>
                  </div>
              </div>

              <a href="#" class="continue-shopping">Retourner à la boutique</a>
          </div>
      </div>

      <script>
          function printFacture() {
              const contenu = document.getElementById("facture").outerHTML;
              const original = document.body.innerHTML;

              document.body.innerHTML = contenu;
              window.print();
              document.body.innerHTML = original;
          }

          function sendFactureEmail(email) {
              const factureText = document.getElementById('facture').innerText.trim();
              const subject = encodeURIComponent('Votre facture');
              const body = encodeURIComponent(factureText);

              const mailtoUrl = `mailto:${email}?subject=${subject}&body=${body}`;
              window.location.href = mailtoUrl;
          }



          async function downloadFacturePDF() {
              const element = document.getElementById("facture");
              if (!element) {
                  alert("Facture introuvable !");
                  return;
              }

              const canvas = await html2canvas(element);
              const imgData = canvas.toDataURL("image/png");

              const {
                  jsPDF
              } = window.jspdf;
              const pdf = new jsPDF("p", "mm", "a4");

              const pageWidth = pdf.internal.pageSize.getWidth();
              const pageHeight = pdf.internal.pageSize.getHeight();
              const imgWidth = pageWidth;
              const imgHeight = canvas.height * imgWidth / canvas.width;

              pdf.addImage(imgData, "PNG", 0, 0, imgWidth, imgHeight);
              pdf.save("facture.pdf");
          }
      </script>

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
              --success-color: #2ecc71;
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
              max-width: 1000px;
              margin: 0 auto;
              background-color: var(--white);
              border-radius: 20px;
              box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
              overflow: hidden;
              position: relative;
          }

          .page-header {
              background: linear-gradient(135deg, var(--success-color) 0%, #27ae60 100%);
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

          .success-icon {
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

          .success-icon i {
              font-size: 40px;
              color: var(--success-color);
          }

          .page-content {
              padding: 20px 30px 40px;
          }

          .confirmation-section {
              margin-bottom: 30px;
              padding: 25px;
              border-radius: 15px;
              background-color: var(--light-gray);
              box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
              transition: transform 0.3s, box-shadow 0.3s;
              position: relative;
          }

          .confirmation-section:hover {
              transform: translateY(-5px);
              box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
          }

          .confirmation-section::before {
              content: '';
              position: absolute;
              top: 0;
              left: 0;
              width: 5px;
              height: 100%;
              background: linear-gradient(to bottom, var(--success-color), var(--secondary-color));
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
              background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
              -webkit-background-clip: text;
              -webkit-text-fill-color: transparent;
          }

          .transaction-details {
              display: grid;
              grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
              gap: 20px;
          }

          .detail-item {
              margin-bottom: 15px;
          }

          .detail-label {
              font-weight: 500;
              color: #6c757d;
              margin-bottom: 5px;
              font-size: 0.9rem;
          }

          .detail-value {
              font-weight: 600;
              font-size: 1.1rem;
              color: var(--dark-gray);
          }

          .actions-section {
              margin-bottom: 30px;
              padding: 25px;
              border-radius: 15px;
              background-color: var(--light-gray);
              box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
              transition: transform 0.3s, box-shadow 0.3s;
              position: relative;
          }

          .actions-section:hover {
              transform: translateY(-5px);
              box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
          }

          .actions-section::before {
              content: '';
              position: absolute;
              top: 0;
              left: 0;
              width: 5px;
              height: 100%;
              background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
              border-radius: 5px 0 0 5px;
          }

          .action-buttons {
              display: flex;
              flex-wrap: wrap;
              gap: 15px;
              margin-top: 20px;
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

          .download-btn {
              background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
              color: var(--white);
              border: none;
              box-shadow: 0 4px 10px rgba(0, 84, 166, 0.2);
          }

          .download-btn:hover {
              background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
              transform: translateY(-2px);
              box-shadow: 0 6px 15px rgba(0, 84, 166, 0.25);
          }

          .print-btn {
              background-color: var(--white);
              color: var(--dark-gray);
              border: 1px solid #dee2e6;
          }

          .print-btn:hover {
              background-color: #f1f3f5;
              transform: translateY(-2px);
              box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
          }

          .email-btn {
              background: linear-gradient(135deg, var(--secondary-color), var(--secondary-light));
              color: var(--white);
              border: none;
              box-shadow: 0 4px 10px rgba(58, 167, 170, 0.2);
          }

          .email-btn:hover {
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

          .continue-shopping {
              display: block;
              text-align: center;
              margin-top: 30px;
              color: var(--primary-color);
              text-decoration: none;
              font-weight: 500;
              transition: color 0.3s;
          }

          .continue-shopping:hover {
              color: var(--primary-dark);
              text-decoration: underline;
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

              .success-icon {
                  width: 60px;
                  height: 60px;
              }

              .success-icon i {
                  font-size: 30px;
              }

              .page-content {
                  padding: 15px 20px 30px;
              }

              .confirmation-section,
              .actions-section {
                  padding: 20px;
              }

              .transaction-details {
                  grid-template-columns: 1fr;
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


  </body>


  <div class="facture" id="facture">
      <div class="invoice-container">
          <div class="invoice-header">
              <div class="company-info">
                  <img src="https://via.placeholder.com/150x80" alt="Logo de l'entreprise" class="company-logo">
                  <div class="company-details">
                      <h2><?php echo get_bloginfo('name'); ?></h2>
                      <p>
                          <?php
                            echo esc_html(get_option('woocommerce_store_address')) . ', ';
                            echo esc_html(get_option('woocommerce_store_address_2')) . '<br>';
                            echo esc_html(get_option('woocommerce_store_city')) . ', ';
                            echo esc_html(get_option('woocommerce_default_country'));
                            ?>
                      </p>
                      <p><?php echo esc_html(get_option('woocommerce_store_email')); ?></p>
                      <p><?php echo esc_html(get_option('woocommerce_store_phone')); ?></p>
                  </div>

              </div>
              <div class="invoice-title">
                  <h1>FACTURE</h1>
                  <p> <?= esc_html($transaction_data['order_number']) ?></p>
                  <p>Date: <?= esc_html($transaction_data['date_of_transaction']) ?></p>
              </div>
          </div>

          <div class="invoice-body">
              <?php if (is_user_logged_in()) :
                    $user = wp_get_current_user();
                    $user_id = $user->ID;
                    $billing_phone = get_user_meta($user_id, 'billing_phone', true);
                    $billing_address = wc()->customer->get_billing_address_1();
                    $billing_city = wc()->customer->get_billing_city();
                    $billing_postcode = wc()->customer->get_billing_postcode();
                    $billing_country = wc()->customer->get_billing_country();
                ?>

                  <div class="client-info">
                      <div class="info-title">Informations client</div>

                      <div class="info-item">
                          <span class="info-label">Nom:</span>
                          <?php echo esc_html($user->display_name); ?>
                      </div>

                      <div class="info-item">
                          <span class="info-label">Email:</span>
                          <?php echo esc_html($user->user_email); ?>
                      </div>

                      <div class="info-item">
                          <span class="info-label">Téléphone:</span>
                          <?php echo esc_html($billing_phone); ?>
                      </div>

                      <div class="info-item">
                          <span class="info-label">Adresse:</span>
                          <?php
                            echo esc_html($billing_address) . ', ' .
                                esc_html($billing_city) . ' ' .
                                esc_html($billing_postcode) . ', ' .
                                esc_html($billing_country);
                            ?>
                      </div>
                  </div>

              <?php else : ?>

                  <div class="client-info">
                      <div class="info-title">Informations client</div>
                      <p>Client non connecté</p>
                  </div>

              <?php endif; ?>

              <?php
                $cart = WC()->cart;
                if (! $cart->is_empty()) :
                ?>
                  <table class="invoice-table">
                      <thead>
                          <tr>
                              <th>Produit</th>
                              <th>Quantité</th>
                              <th>Prix unitaire</th>
                              <th>Total</th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($cart->get_cart() as $cart_item) :
                                $product = $cart_item['data'];
                            ?>
                              <tr>
                                  <td>
                                      <div class="item-name"><?php echo esc_html($product->get_name()); ?></div>
                                      <div class="item-description"><?php echo esc_html($product->get_short_description()); ?></div>
                                  </td>
                                  <td><?php echo esc_html($cart_item['quantity']); ?></td>
                                  <td><?php echo wc_price($product->get_price()); ?></td>
                                  <td><?php echo wc_price($cart_item['line_total']); ?></td>
                              </tr>
                          <?php endforeach; ?>
                      </tbody>
                  </table>

                  <div class="invoice-totals">
                      <div class="total-row">
                          <span>Sous-total</span>
                          <span><?php echo wc_price($cart->get_subtotal()); ?></span>
                      </div>
                      <div class="total-row">
                          <span>Livraison</span>
                          <span><?php echo wc_price($cart->get_shipping_total()); ?></span>
                      </div>
                      <div class="total-row">
                          <span>TVA</span>
                          <span><?php echo wc_price($cart->get_taxes_total()); ?></span>
                      </div>
                      <div class="total-row">
                          <span>Total TTC</span>
                          <span><?php echo wc_price($cart->get_total()); ?></span>
                      </div>
                  </div>
              <?php endif; ?>


              <div class="invoice-notes">
                  <p>Notes:</p>
                  <p>Cette facture sert de preuve d'achat et de garantie pour les produits achetés.</p>
                  <p>Tous les produits sont garantis selon les conditions générales de vente disponibles sur notre site web.</p>
              </div>
          </div>

          <div class="invoice-footer">
              <div class="support-info">
                  <img src="https://via.placeholder.com/100x50" alt="Logo SATIM">
                  <div class="support-text">
                      <p>En cas de problème de paiement, veuillez contacter le numéro vert de la SATIM</p>
                      <p class="support-number">3020</p>
                  </div>
              </div>

              <p class="thank-you">Merci pour votre achat!</p>
          </div>
      </div>

      <style>
          :root {
              --primary-color: #0054A6;
              --primary-light: #1a6bc2;
              --secondary-color: #3AA7AA;
              --light-gray: #f8f9fa;
              --medium-gray: #e9ecef;
              --dark-gray: #343a40;
              --white: #ffffff;
          }

          * {
              box-sizing: border-box;
              margin: 0;
              padding: 0;
              font-family: 'Arial', sans-serif;
          }

          body {
              background-color: var(--light-gray);
              color: var(--dark-gray);
              line-height: 1.6;
              padding: 20px;
          }

          .invoice-container {
              margin: 0 auto;
              background-color: var(--white);
              border-radius: 10px;
              box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
              overflow: hidden;
          }

          .invoice-header {
              padding: 30px;
              border-bottom: 1px solid var(--medium-gray);
              display: flex;
              justify-content: space-between;
              align-items: center;
          }

          .company-info {
              display: flex;
              align-items: center;
          }

          .company-logo {
              width: 150px;
              height: auto;
              margin-right: 20px;
          }

          .company-details h2 {
              color: var(--primary-color);
              margin-bottom: 5px;
              font-size: 1.5rem;
          }

          .company-details p {
              color: #6c757d;
              font-size: 0.9rem;
              margin-bottom: 3px;
          }

          .invoice-title {
              text-align: right;
          }

          .invoice-title h1 {
              color: var(--primary-color);
              font-size: 2rem;
              margin-bottom: 5px;
          }

          .invoice-title p {
              color: #6c757d;
              font-size: 1rem;
          }

          .invoice-body {
              padding: 30px;
          }

          .invoice-details {
              display: flex;
              justify-content: space-between;
              margin-bottom: 30px;
          }

          .client-info,
          .payment-info {
              flex: 1;
          }

          .info-title {
              color: var(--primary-color);
              font-weight: bold;
              margin-bottom: 10px;
              font-size: 1.1rem;
              border-bottom: 2px solid var(--secondary-color);
              padding-bottom: 5px;
              display: inline-block;
          }

          .info-item {
              margin-bottom: 5px;
          }

          .info-label {
              font-weight: bold;
              color: #495057;
          }

          .invoice-table {
              width: 100%;
              border-collapse: collapse;
              margin-bottom: 30px;
          }

          .invoice-table th {
              background-color: var(--primary-color);
              color: var(--white);
              padding: 12px 15px;
              text-align: left;
          }

          .invoice-table td {
              padding: 12px 15px;
              border-bottom: 1px solid var(--medium-gray);
          }

          .invoice-table tr:last-child td {
              border-bottom: none;
          }

          .invoice-table .item-name {
              font-weight: bold;
          }

          .invoice-table .item-description {
              color: #6c757d;
              font-size: 0.9rem;
          }

          .invoice-totals {
              width: 350px;
              margin-left: auto;
          }

          .total-row {
              display: flex;
              justify-content: space-between;
              padding: 8px 0;
              border-bottom: 1px solid var(--medium-gray);
          }

          .total-row:last-child {
              border-bottom: none;
              font-weight: bold;
              font-size: 1.1rem;
              color: var(--primary-color);
              padding-top: 12px;
          }

          .invoice-footer {
              padding: 20px 30px;
              background-color: var(--light-gray);
              border-top: 1px solid var(--medium-gray);
              text-align: center;
          }

          .support-info {
              display: flex;
              align-items: center;
              justify-content: center;
              margin-bottom: 15px;
          }

          .support-info img {
              width: 100px;
              margin-right: 15px;
          }

          .support-text {
              text-align: left;
          }

          .support-text p {
              margin-bottom: 5px;
          }

          .support-number {
              font-weight: bold;
              color: var(--primary-color);
          }

          .thank-you {
              margin-top: 15px;
              font-weight: bold;
              color: var(--primary-color);
          }

          .invoice-notes {
              margin-top: 30px;
              padding-top: 20px;
              border-top: 1px dashed var(--medium-gray);
              font-size: 0.9rem;
              color: #6c757d;
          }

          @media print {
              body {
                  background-color: var(--white);
                  padding: 0;
              }

              .invoice-container {
                  box-shadow: none;
                  border-radius: 0;
              }

              .no-print {
                  display: none;
              }
          }

          @media (max-width: 768px) {
              .invoice-header {
                  flex-direction: column;
                  text-align: center;
              }

              .company-info {
                  flex-direction: column;
                  margin-bottom: 20px;
              }

              .company-logo {
                  margin-right: 0;
                  margin-bottom: 15px;
              }

              .invoice-title {
                  text-align: center;
              }

              .invoice-details {
                  flex-direction: column;
              }

              .client-info,
              .payment-info {
                  margin-bottom: 20px;
              }

              .invoice-totals {
                  width: 100%;
              }

              .support-info {
                  flex-direction: column;
              }

              .support-info img {
                  margin-right: 0;
                  margin-bottom: 10px;
              }

              .support-text {
                  text-align: center;
              }
          }
      </style>
  </div>

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
          --success-color: #2ecc71;
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
          max-width: 1000px;
          margin: 0 auto;
          background-color: var(--white);
          border-radius: 20px;
          box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
          overflow: hidden;
          position: relative;
      }

      .page-header {
          background: linear-gradient(135deg, var(--success-color) 0%, #27ae60 100%);
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

      .success-icon {
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

      .success-icon i {
          font-size: 40px;
          color: var(--success-color);
      }

      .page-content {
          padding: 20px 30px 40px;
      }

      .confirmation-section {
          margin-bottom: 30px;
          padding: 25px;
          border-radius: 15px;
          background-color: var(--light-gray);
          box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
          transition: transform 0.3s, box-shadow 0.3s;
          position: relative;
      }

      .confirmation-section:hover {
          transform: translateY(-5px);
          box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
      }

      .confirmation-section::before {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          width: 5px;
          height: 100%;
          background: linear-gradient(to bottom, var(--success-color), var(--secondary-color));
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
          background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
          -webkit-background-clip: text;
          -webkit-text-fill-color: transparent;
      }

      .transaction-details {
          display: grid;
          grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
          gap: 20px;
      }

      .detail-item {
          margin-bottom: 15px;
      }

      .detail-label {
          font-weight: 500;
          color: #6c757d;
          margin-bottom: 5px;
          font-size: 0.9rem;
      }

      .detail-value {
          font-weight: 600;
          font-size: 1.1rem;
          color: var(--dark-gray);
      }

      .actions-section {
          margin-bottom: 30px;
          padding: 25px;
          border-radius: 15px;
          background-color: var(--light-gray);
          box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
          transition: transform 0.3s, box-shadow 0.3s;
          position: relative;
      }

      .actions-section:hover {
          transform: translateY(-5px);
          box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
      }

      .actions-section::before {
          content: '';
          position: absolute;
          top: 0;
          left: 0;
          width: 5px;
          height: 100%;
          background: linear-gradient(to bottom, var(--primary-color), var(--secondary-color));
          border-radius: 5px 0 0 5px;
      }

      .action-buttons {
          display: flex;
          flex-wrap: wrap;
          gap: 15px;
          margin-top: 20px;
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

      .download-btn {
          background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
          color: var(--white);
          border: none;
          box-shadow: 0 4px 10px rgba(0, 84, 166, 0.2);
      }

      .download-btn:hover {
          background: linear-gradient(135deg, var(--primary-dark), var(--primary-color));
          transform: translateY(-2px);
          box-shadow: 0 6px 15px rgba(0, 84, 166, 0.25);
      }

      .print-btn {
          background-color: var(--white);
          color: var(--dark-gray);
          border: 1px solid #dee2e6;
      }

      .print-btn:hover {
          background-color: #f1f3f5;
          transform: translateY(-2px);
          box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      }

      .email-btn {
          background: linear-gradient(135deg, var(--secondary-color), var(--secondary-light));
          color: var(--white);
          border: none;
          box-shadow: 0 4px 10px rgba(58, 167, 170, 0.2);
      }

      .email-btn:hover {
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

      .continue-shopping {
          display: block;
          text-align: center;
          margin-top: 30px;
          color: var(--primary-color);
          text-decoration: none;
          font-weight: 500;
          transition: color 0.3s;
      }

      .continue-shopping:hover {
          color: var(--primary-dark);
          text-decoration: underline;
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

          .success-icon {
              width: 60px;
              height: 60px;
          }

          .success-icon i {
              font-size: 30px;
          }

          .page-content {
              padding: 15px 20px 30px;
          }

          .confirmation-section,
          .actions-section {
              padding: 20px;
          }

          .transaction-details {
              grid-template-columns: 1fr;
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

  </html>