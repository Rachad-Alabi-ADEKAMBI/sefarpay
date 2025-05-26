<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Configuration</title>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

</head>

<body>
  <div class="container">
    <div class="form-header">
      <h1>Configuration</h1>
      <p>
        Configurez les paramètres globaux pour l'intégration de l'API SATIM.
      </p>
    </div>

    <div class="form-content">
      <form id="configForm">
        <!-- Identification et Authentification -->
        <div class="form-section">
          <h2 class="section-title">
            <i class="fas fa-id-card"></i>Identification et Authentification
          </h2>
          <div class="form-row">
            <div class="form-group">
              <label for="source_identifier" class="required">Source Identifier</label>
              <div class="input-wrapper">
                <i class="fas fa-fingerprint"></i>
                <input
                  type="text"
                  id="source_identifier"
                  name="source_identifier"
                  placeholder="Identifiant unique du client"
                  required />
              </div>
              <div class="tooltip">
                <i class="fas fa-info-circle"></i>
                <span class="tooltip-text">Identifiant unique fourni par SATIM pour votre
                  compte.</span>
              </div>
            </div>
            <div class="form-group">
              <label for="username" class="required">UserName</label>
              <div class="input-wrapper">
                <i class="fas fa-user"></i>
                <input
                  type="text"
                  id="username"
                  name="username"
                  placeholder="Identifiant de connexion à l'API SATIM"
                  required />
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="password" class="required">Password</label>
              <div class="input-wrapper">
                <i class="fas fa-lock"></i>
                <input
                  type="password"
                  id="password"
                  name="password"
                  placeholder="Mot de passe d'authentification API SATIM"
                  required />
              </div>
            </div>
            <div class="form-group">
              <label for="base_url" class="required">Base URL API</label>
              <div class="input-wrapper">
                <i class="fas fa-link"></i>
                <input
                  type="url"
                  id="base_url"
                  name="base_url"
                  placeholder="https://api.satim.dz"
                  required />
              </div>
              <div class="tooltip">
                <i class="fas fa-info-circle"></i>
                <span class="tooltip-text">Adresse de base de l'API SATIM pour les appels.</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Paramètres de Transaction -->
        <div class="form-section">
          <h2 class="section-title">
            <i class="fas fa-cogs"></i>Paramètres de Transaction
          </h2>
          <div class="form-row">
            <div class="form-group">
              <label for="currency" class="required">Currency</label>
              <div class="input-wrapper">
                <i class="fas fa-money-bill-wave"></i>
                <select id="currency" name="currency" required>
                  <option value="">Sélectionnez</option>
                  <option value="DZD">DZD</option>
                  <option value="EUR">EUR</option>
                  <option value="USD">USD</option>
                </select>
              </div>
              <div class="tooltip">
                <i class="fas fa-info-circle"></i>
                <span class="tooltip-text">Code ISO de la devise utilisée pour les transactions.</span>
              </div>
            </div>
            <div class="form-group">
              <label for="language" class="required">Language</label>
              <div class="input-wrapper">
                <i class="fas fa-language"></i>
                <select id="language" name="language" required>
                  <option value="">Sélectionnez</option>
                  <option value="fr">Français (fr)</option>
                  <option value="en">Anglais (en)</option>
                </select>
              </div>
              <div class="tooltip">
                <i class="fas fa-info-circle"></i>
                <span class="tooltip-text">Code langue utilisé dans l'interface de paiement.</span>
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="json_params" class="optional">JsonParams</label>
              <div class="input-wrapper textarea-wrapper">
                <i class="fas fa-code"></i>
                <textarea
                  id="json_params"
                  name="json_params"
                  placeholder='{"param1": "value1", "param2": "value2"}'></textarea>
              </div>
              <div class="tooltip">
                <i class="fas fa-info-circle"></i>
                <span class="tooltip-text">Paramètres additionnels en format JSON pour personnaliser
                  les transactions.</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Redirection et Callbacks -->
        <div class="form-section">
          <h2 class="section-title">
            <i class="fas fa-exchange-alt"></i>Redirection et Callbacks
          </h2>
          <div class="form-row">
            <div class="form-group">
              <label for="return_url" class="required">ReturnUrl</label>
              <div class="input-wrapper">
                <i class="fas fa-check-circle"></i>
                <input
                  type="url"
                  id="return_url"
                  name="return_url"
                  placeholder="https://votre-site.com/success"
                  required />
              </div>
              <div class="tooltip">
                <i class="fas fa-info-circle"></i>
                <span class="tooltip-text">URL de redirection après une transaction réussie.</span>
              </div>
            </div>
            <div class="form-group">
              <label for="fail_url" class="required">FailUrl</label>
              <div class="input-wrapper">
                <i class="fas fa-times-circle"></i>
                <input
                  type="url"
                  id="fail_url"
                  name="fail_url"
                  placeholder="https://votre-site.com/fail"
                  required />
              </div>
              <div class="tooltip">
                <i class="fas fa-info-circle"></i>
                <span class="tooltip-text">URL de redirection après une transaction échouée.</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Sécurité et Validation -->
        <div class="form-section">
          <h2 class="section-title">
            <i class="fas fa-shield-alt"></i>Sécurité et Validation
          </h2>
          <div class="form-row">
            <div class="form-group">
              <label for="captcha_site_key" class="required">Captcha - Site Key</label>
              <div class="input-wrapper">
                <i class="fas fa-robot"></i>
                <input
                  type="text"
                  id="captcha_site_key"
                  name="captcha_site_key"
                  placeholder="Clé publique pour reCAPTCHA"
                  required />
              </div>
              <div class="tooltip">
                <i class="fas fa-info-circle"></i>
                <span class="tooltip-text">Clé publique pour l'intégration de Google reCAPTCHA.</span>
              </div>
            </div>
            <div class="form-group">
              <label for="captcha_secret_key" class="required">Captcha - Secret Key</label>
              <div class="input-wrapper">
                <i class="fas fa-key"></i>
                <input
                  type="password"
                  id="captcha_secret_key"
                  name="captcha_secret_key"
                  placeholder="Clé privée pour reCAPTCHA"
                  required />
              </div>
              <div class="tooltip">
                <i class="fas fa-info-circle"></i>
                <span class="tooltip-text">Clé privée pour la validation côté serveur de Google
                  reCAPTCHA.</span>
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="terms_url" class="optional">Conditions d'utilisation</label>
              <div class="input-wrapper">
                <i class="fas fa-file-contract"></i>
                <input
                  type="url"
                  id="terms_url"
                  name="terms_url"
                  placeholder="https://votre-site.com/conditions" />
              </div>
              <div class="tooltip">
                <i class="fas fa-info-circle"></i>
                <span class="tooltip-text">Lien vers les conditions générales d'utilisation du service
                  de paiement.</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Personnalisation -->
        <div class="form-section">
          <h2 class="section-title">
            <i class="fas fa-paint-brush"></i>Personnalisation
          </h2>
          <div class="form-row">
            <div class="form-group">
              <label for="button_color" class="required">Couleur du bouton</label>
              <div
                class="input-wrapper"
                style="display: flex; align-items: center">
                <input
                  type="color"
                  id="button_color"
                  name="button_color"
                  value="#0054A6"
                  required />
                <span
                  id="color_preview"
                  class="color-preview"
                  style="background-color: #0054a6"></span>
              </div>
              <div class="tooltip">
                <i class="fas fa-info-circle"></i>
                <span class="tooltip-text">Couleur par défaut pour le bouton de paiement.</span>
              </div>
            </div>
            <div class="form-group">
              <label for="button_size" class="required">Taille du bouton</label>
              <div class="input-wrapper">
                <i class="fas fa-text-height"></i>
                <select id="button_size" name="button_size" required>
                  <option value="">Sélectionnez</option>
                  <option value="small">Petit</option>
                  <option value="medium" selected>Moyen</option>
                  <option value="large">Grand</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="button_text" class="required">Texte du bouton</label>
              <div class="input-wrapper">
                <i class="fas fa-font"></i>
                <input
                  type="text"
                  id="button_text"
                  name="button_text"
                  placeholder="Payer maintenant"
                  value="Payer maintenant"
                  required />
              </div>
            </div>
          </div>
        </div>

        <div class="submit-container">
          <button type="submit" class="submit-btn">
            <i class="fas fa-save"></i> Enregistrer les paramètres
          </button>
          <div id="message" class="message"></div>
        </div>

        <input type="hidden" name="action" value="sefarpay_save_config" />
        <script>
          document
            .getElementById("configForm")
            .addEventListener("submit", async function(e) {
              e.preventDefault();
              const form = document.getElementById("configForm");
              const formData = new FormData(form);
              const messageBox = document.getElementById("message");

              messageBox.innerHTML = "⏳ Enregistrement en cours...";

              const response = await fetch(ajaxurl, {
                method: "POST",
                body: formData,
              });

              const result = await response.json();

              if (result.success) {
                messageBox.innerHTML = `<p style="color:green">✅ ${result.data}</p>`;
              } else {
                messageBox.innerHTML = `<p style="color:red">❌ ${result.data}</p>`;
              }
            });
        </script>
      </form>
    </div>
  </div>

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

    @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");


    .container {
      max-width: 1100px;
      margin: 30px;
      background-color: var(--white);
      border-radius: 20px;
      box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
      overflow: hidden;
      position: relative;
    }

    .form-header {
      background: linear-gradient(135deg,
          var(--primary-color) 0%,
          var(--primary-light) 100%);
      padding: 40px 30px;
      text-align: center;
      position: relative;
    }

    .form-header::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 30px;
      background: var(--white);
      border-radius: 50% 50% 0 0;
    }

    .form-header h1 {
      color: var(--white);
      margin-bottom: 10px;
      font-weight: 600;
      font-size: 2.5rem;
      text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .form-header p {
      color: rgba(255, 255, 255, 0.9);
      font-size: 1.1rem;
      max-width: 600px;
      margin: 0 auto;
    }

    .form-content {
      padding: 20px 30px 40px;
    }

    .form-section {
      margin-bottom: 35px;
      padding: 25px;
      border-radius: 15px;
      background-color: var(--light-gray);
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
      transition: transform 0.3s, box-shadow 0.3s;
      position: relative;
      overflow: hidden;
    }

    .form-section:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
    }

    .form-section::before {
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

    .form-row {
      display: flex;
      flex-wrap: wrap;
      margin: 0 -15px 20px;
    }

    .form-group {
      flex: 1 1 300px;
      margin: 0 15px 20px;
      position: relative;
    }

    label {
      display: block;
      margin-bottom: 10px;
      font-weight: 500;
      color: var(--dark-gray);
      font-size: 0.95rem;
      transition: color 0.3s;
    }

    .form-group:focus-within label {
      color: var(--primary-color);
    }

    .input-wrapper {
      position: relative;
    }

    .input-wrapper i {
      position: absolute;
      left: 15px;
      top: 50%;
      transform: translateY(-50%);
      color: #adb5bd;
      transition: color 0.3s;
    }

    .form-group:focus-within .input-wrapper i {
      color: var(--primary-color);
    }

    input,
    select,
    textarea {
      width: 100%;
      padding: 14px 15px 14px 45px;
      border: 2px solid #dee2e6;
      border-radius: 10px;
      font-size: 16px;
      transition: all 0.3s;
      background-color: var(--white);
    }

    input[type="color"] {
      padding: 5px;
      height: 50px;
      cursor: pointer;
    }

    input:focus,
    select:focus,
    textarea:focus {
      outline: none;
      border-color: var(--secondary-color);
      box-shadow: 0 0 0 4px rgba(58, 167, 170, 0.15);
    }

    textarea {
      min-height: 120px;
      resize: vertical;
      padding-left: 45px;
    }

    .textarea-wrapper i {
      top: 20px;
      transform: none;
    }

    .required::after {
      content: " *";
      color: var(--error-color);
      font-weight: bold;
    }

    .optional::after {
      content: " (optionnel)";
      color: #6c757d;
      font-size: 0.85rem;
      font-weight: normal;
    }

    .submit-container {
      text-align: center;
      margin-top: 40px;
      position: relative;
    }

    .submit-btn {
      display: inline-block;
      padding: 16px 40px;
      background: linear-gradient(135deg,
          var(--primary-color),
          var(--primary-light));
      color: white;
      border: none;
      border-radius: 50px;
      font-size: 1.1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s;
      box-shadow: 0 6px 15px rgba(0, 84, 166, 0.25);
      position: relative;
      overflow: hidden;
    }

    .submit-btn::before {
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

    .submit-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 8px 20px rgba(0, 84, 166, 0.3);
    }

    .submit-btn:hover::before {
      left: 100%;
    }

    .submit-btn:active {
      transform: translateY(-1px);
    }

    .message {
      margin-top: 25px;
      padding: 15px 20px;
      border-radius: 10px;
      text-align: center;
      display: none;
      font-weight: 500;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
      animation: fadeIn 0.5s;
    }

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

    .success {
      background-color: rgba(46, 204, 113, 0.15);
      color: var(--success-color);
      border: 1px solid rgba(46, 204, 113, 0.3);
      display: block;
    }

    .error {
      background-color: rgba(231, 76, 60, 0.15);
      color: var(--error-color);
      border: 1px solid rgba(231, 76, 60, 0.3);
      display: block;
    }

    /* Animations pour les champs */
    @keyframes shake {

      0%,
      100% {
        transform: translateX(0);
      }

      20%,
      60% {
        transform: translateX(-5px);
      }

      40%,
      80% {
        transform: translateX(5px);
      }
    }

    .shake {
      animation: shake 0.5s;
    }

    /* Tooltip pour les informations supplémentaires */
    .tooltip {
      position: relative;
      display: inline-block;
      margin-left: 8px;
      color: var(--primary-color);
      cursor: pointer;
    }

    .tooltip i {
      font-size: 0.9rem;
    }

    .tooltip .tooltip-text {
      visibility: hidden;
      width: 250px;
      background-color: var(--dark-gray);
      color: var(--white);
      text-align: center;
      border-radius: 6px;
      padding: 8px;
      position: absolute;
      z-index: 1;
      bottom: 125%;
      left: 50%;
      transform: translateX(-50%);
      opacity: 0;
      transition: opacity 0.3s;
      font-size: 0.85rem;
      font-weight: normal;
      box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .tooltip .tooltip-text::after {
      content: "";
      position: absolute;
      top: 100%;
      left: 50%;
      margin-left: -5px;
      border-width: 5px;
      border-style: solid;
      border-color: var(--dark-gray) transparent transparent transparent;
    }

    .tooltip:hover .tooltip-text {
      visibility: visible;
      opacity: 1;
    }

    /* Responsive */
    @media (max-width: 768px) {
      .container {
        border-radius: 15px;
      }

      .form-header {
        padding: 30px 20px;
      }

      .form-header h1 {
        font-size: 2rem;
      }

      .form-content {
        padding: 15px 20px 30px;
      }

      .form-section {
        padding: 20px;
      }

      .form-row {
        margin: 0 -10px 15px;
      }

      .form-group {
        margin: 0 10px 15px;
        flex: 1 1 100%;
      }

      .submit-btn {
        width: 100%;
        padding: 15px 20px;
      }
    }

    /* Effet de focus sur les sections */
    .form-section:focus-within {
      border-left: 5px solid var(--secondary-color);
      padding-left: 22px;
    }

    /* Effet de hover sur les inputs */
    input:hover,
    select:hover,
    textarea:hover {
      border-color: #b8c2cc;
    }

    /* Style pour les options des selects */
    select option {
      padding: 10px;
    }

    /* Effet de transition pour les placeholders */
    input::placeholder,
    textarea::placeholder {
      transition: opacity 0.3s;
    }

    input:focus::placeholder,
    textarea:focus::placeholder {
      opacity: 0.5;
    }

    /* Effet de pulsation pour le bouton */
    @keyframes pulse {
      0% {
        box-shadow: 0 0 0 0 rgba(0, 84, 166, 0.4);
      }

      70% {
        box-shadow: 0 0 0 10px rgba(0, 84, 166, 0);
      }

      100% {
        box-shadow: 0 0 0 0 rgba(0, 84, 166, 0);
      }
    }

    .submit-btn:focus {
      animation: pulse 1.5s infinite;
    }

    /* Style pour le sélecteur de couleur */
    .color-preview {
      display: inline-block;
      width: 30px;
      height: 30px;
      border-radius: 50%;
      margin-left: 10px;
      border: 2px solid #dee2e6;
      vertical-align: middle;
    }
  </style>

  <script>
    // Mise à jour de la prévisualisation de couleur
    document
      .getElementById("button_color")
      .addEventListener("input", function(e) {
        document.getElementById("color_preview").style.backgroundColor =
          e.target.value;
      });

    // Animation pour les champs invalides
    function animateInvalidField(field) {
      field.classList.add("shake");
      field.style.borderColor = "var(--error-color)";
      setTimeout(() => {
        field.classList.remove("shake");
      }, 500);
    }

    // Validation du formulaire
    document
      .getElementById("configForm")
      .addEventListener("submit", function(e) {
        e.preventDefault();

        // Validation côté client
        const form = this;
        const requiredFields = form.querySelectorAll("[required]");
        let isValid = true;

        requiredFields.forEach((field) => {
          if (!field.value.trim()) {
            animateInvalidField(field);
            isValid = false;
          } else {
            field.style.borderColor = "#dee2e6";
          }

          // Validation spécifique pour les URLs
          if (field.type === "url" && field.value) {
            try {
              new URL(field.value);
            } catch (e) {
              animateInvalidField(field);
              isValid = false;
            }
          }

          // Validation du JSON
          if (field.id === "json_params" && field.value.trim()) {
            try {
              JSON.parse(field.value);
            } catch (e) {
              animateInvalidField(field);
              isValid = false;
            }
          }
        });

        const messageDiv = document.getElementById("message");

        if (!isValid) {
          messageDiv.textContent =
            "Veuillez remplir correctement tous les champs obligatoires.";
          messageDiv.className = "message error";
          messageDiv.style.display = "block";

          // Scroll to first invalid field
          const firstInvalidField = form.querySelector(
            '[style*="border-color: var(--error-color)"]'
          );
          if (firstInvalidField) {
            firstInvalidField.scrollIntoView({
              behavior: "smooth",
              block: "center",
            });
          }

          return;
        }

        // Validation du JSON si rempli
        const jsonParams = document
          .getElementById("json_params")
          .value.trim();
        if (jsonParams) {
          try {
            JSON.parse(jsonParams);
          } catch (e) {
            document.getElementById("json_params").style.borderColor =
              "var(--error-color)";
            messageDiv.textContent =
              "Le format JSON des paramètres additionnels est invalide.";
            messageDiv.className = "message error";
            messageDiv.style.display = "block";
            document
              .getElementById("json_params")
              .scrollIntoView({
                behavior: "smooth",
                block: "center"
              });
            return;
          }
        }

        // Simulation d'envoi de formulaire (à remplacer par votre logique d'envoi réelle)
        const submitBtn = form.querySelector(".submit-btn");
        const originalBtnText = submitBtn.innerHTML;

        submitBtn.innerHTML =
          '<i class="fas fa-spinner fa-spin"></i> Enregistrement en cours...';
        submitBtn.disabled = true;

        // Simuler un délai de traitement
        setTimeout(() => {
          messageDiv.textContent = "Configuration enregistrée avec succès!";
          messageDiv.className = "message success";
          messageDiv.style.display = "block";

          submitBtn.innerHTML =
            '<i class="fas fa-check"></i> Paramètres enregistrés!';

          // Réinitialiser le bouton après 3 secondes
          setTimeout(() => {
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
          }, 3000);

          // Scroll to message
          messageDiv.scrollIntoView({
            behavior: "smooth",
            block: "center"
          });

          // Dans un environnement réel, vous utiliseriez AJAX pour envoyer les données au serveur
        }, 1500);
      });

    // Effet de focus sur les sections
    const formSections = document.querySelectorAll(".form-section");
    formSections.forEach((section) => {
      const inputs = section.querySelectorAll("input, select, textarea");
      inputs.forEach((input) => {
        input.addEventListener("focus", () => {
          formSections.forEach((s) => (s.style.opacity = "0.8"));
          section.style.opacity = "1";
        });

        input.addEventListener("blur", () => {
          formSections.forEach((s) => (s.style.opacity = "1"));
        });
      });
    });

    // Validation du JSON en temps réel
    document
      .getElementById("json_params")
      .addEventListener("blur", function() {
        const jsonValue = this.value.trim();
        if (jsonValue) {
          try {
            JSON.parse(jsonValue);
            this.style.borderColor = "#dee2e6";
          } catch (e) {
            this.style.borderColor = "var(--error-color)";
          }
        }
      });

    // Pré-remplir avec des valeurs par défaut (à adapter selon vos besoins)
    document.addEventListener("DOMContentLoaded", function() {
      // Vous pouvez pré-remplir les champs avec des valeurs par défaut ou sauvegardées
      // Par exemple:
      // document.getElementById('source_identifier').value = 'VOTRE_ID_PAR_DEFAUT';
    });
  </script>
</body>

</html>