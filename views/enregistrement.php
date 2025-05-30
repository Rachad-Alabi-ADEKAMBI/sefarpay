<!DOCTYPE html>
<html lang="fr">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Formulaire d'Enregistrement</title>
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />

</head>

<body>
  <div class="container">
    <div class="form-header">
      <h1>Formulaire d'Enregistrement</h1>
      <p>
        Complétez le formulaire ci-dessous pour vous enregistrer. Tous les
        champs marqués d'un astérisque (*) sont obligatoires.
      </p>
    </div>

    <div class="form-content">
      <form id="registrationForm" enctype="multipart/form-data">
        <!-- Informations personnelles -->
        <div class="form-section">
          <h2 class="section-title">
            <i class="fas fa-user"></i>Informations personnelles
          </h2>
          <div class="form-row">
            <div class="form-group">
              <label for="civilite" class="required">Civilité</label>
              <div class="input-wrapper">
                <select id="civilite" name="civilite" required>
                  <option value="">Sélectionnez</option>
                  <option value="Mr">Mr</option>
                  <option value="Mme">Mme</option>
                  <option value="Mlle">Mlle</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="nom" class="required">Nom</label>
              <div class="input-wrapper">
                <input
                  type="text"
                  id="nom"
                  name="nom"
                  placeholder="Votre nom"
                  value="<?php echo esc_attr($enregistrement['nom'] ?? ''); ?>"
                  required />
              </div>
            </div>
            <div class="form-group">
              <label for="prenom" class="required">Prénom</label>
              <div class="input-wrapper">
                <input
                  type="text"
                  id="prenom"
                  name="prenom"
                  placeholder="Votre prénom"
                  value="<?php echo esc_attr($enregistrement['prenom'] ?? ''); ?>"
                  required />
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="email" class="required">Email personnel</label>
              <div class="input-wrapper">
                <input
                  type="email"
                  id="email"
                  name="email"
                  placeholder="votre.email@exemple.com"
                  value="<?php echo esc_attr($enregistrement['email'] ?? ''); ?>"
                  required />
              </div>
            </div>
            <div class="form-group">
              <label for="telephone" class="required">Numéro de téléphone</label>
              <div class="input-wrapper">
                <input
                  type="tel"
                  id="telephone"
                  name="telephone"
                  placeholder="Ex: 0123456789"
                  value="<?php echo esc_attr($enregistrement['telephone'] ?? ''); ?>"
                  required />
              </div>
            </div>
          </div>
        </div>

        <!-- Entité juridique -->
        <div class="form-section">
          <h2 class="section-title">
            <i class="fas fa-building"></i>Entité juridique
          </h2>
          <div class="form-row">
            <div class="form-group">
              <label for="raison_sociale" class="required">Raison sociale</label>
              <div class="input-wrapper">
                <input
                  type="text"
                  id="raison_sociale"
                  name="raison_sociale"
                  placeholder="Nom de votre entreprise"
                  value="<?php echo esc_attr($enregistrement['raison_sociale'] ?? ''); ?>"
                  required />
              </div>
            </div>
            <div class="form-group">
              <label for="domaine" class="required">Domaine du site web</label>
              <div class="input-wrapper">
                <input
                  type="text"
                  id="domaine"
                  name="domaine"
                  placeholder="exemple.com"
                  value="<?php echo esc_attr($enregistrement['domaine'] ?? ''); ?>"
                  required />
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="adresse" class="required">Adresse complète</label>
              <div class="input-wrapper textarea-wrapper">
                <textarea
                  id="adresse"
                  name="adresse"
                  placeholder="Adresse complète de votre entreprise"
                  value="<?php echo esc_attr($enregistrement['adresse'] ?? ''); ?>"
                  required></textarea>
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="wilaya" class="required">Wilaya</label>
              <div class="input-wrapper">
                <select id="wilaya" name="wilaya" required>
                  <option value="">Sélectionnez</option>
                  <option value="Alger">Alger</option>
                  <option value="Oran">Oran</option>
                  <option value="Constantine">Constantine</option>
                  <option value="Annaba">Annaba</option>
                  <option value="Blida">Blida</option>
                  <option value="Batna">Batna</option>
                  <option value="Setif">Setif</option>
                  <option value="Tlemcen">Tlemcen</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="commune" class="required">Commune</label>
              <div class="input-wrapper">
                <select id="commune" name="commune" required>
                  <option value="">Sélectionnez d'abord une wilaya</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="date_debut" class="required">Date de début d'activité</label>
              <div class="input-wrapper">
                <input
                  type="date"
                  id="date_debut"
                  name="date_debut"
                  required />
              </div>
            </div>
          </div>
        </div>

        <!-- Activité -->
        <div class="form-section">
          <h2 class="section-title">
            <i class="fas fa-briefcase"></i>Activité
          </h2>
          <div class="form-row">
            <div class="form-group">
              <label for="type_activite" class="required">Type d'activité</label>
              <div class="input-wrapper">
                <select id="type_activite" name="type_activite" required>
                  <option value="">Sélectionnez</option>
                  <option value="Vente de biens">Vente de biens</option>
                  <option value="Prestation de services">
                    Prestation de services
                  </option>
                  <option value="Service public">Service public</option>
                  <option value="Informatique">Informatique</option>
                  <option value="Organisme socio-professionnel">
                    Organisme socio-professionnel
                  </option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label for="forme_juridique" class="required">Forme juridique</label>
              <div class="input-wrapper">
                <select id="forme_juridique" name="forme_juridique" required>
                  <option value="">Sélectionnez</option>
                  <option value="Société Anonyme">Société Anonyme</option>
                  <option value="SPA">SPA</option>
                  <option value="SCS">SCS</option>
                  <option value="SCA">SCA</option>
                  <option value="SARL">SARL</option>
                  <option value="SNC">SNC</option>
                  <option value="Entreprise individuelle">
                    Entreprise individuelle
                  </option>
                  <option value="EURL">EURL</option>
                  <option value="START-UP">START-UP</option>
                  <option value="EPIC">EPIC</option>
                  <option value="Autres">Autres</option>
                </select>
              </div>
            </div>
          </div>
        </div>

        <!-- Coordonnées bancaires -->
        <div class="form-section">
          <h2 class="section-title">
            <i class="fas fa-university"></i>Coordonnées bancaires
          </h2>
          <div class="form-row">
            <div class="form-group">
              <label for="banque" class="required">Banque domiciliataire</label>
              <div class="input-wrapper">
                <select id="banque" name="banque" required>
                  <option value="">Sélectionnez</option>
                  <option value="BNA">BNA</option>
                  <option value="BEA">BEA</option>
                  <option value="CPA">CPA</option>
                  <option value="BDL">BDL</option>
                  <option value="BADR">BADR</option>
                  <option value="CNEP">CNEP</option>
                  <option value="Al Baraka">Al Baraka</option>
                  <option value="Société Générale">Société Générale</option>
                </select>
              </div>
            </div>
          </div>
          <div class="form-row">
            <div class="form-group">
              <label for="email_societe" class="required">Email de l'entité</label>
              <div class="input-wrapper">
                <input
                  type="email"
                  id="email_societe"
                  name="email_societe"
                  placeholder="contact@votre-entreprise.com"
                  value="<?php echo esc_attr($enregistrement['email_societe'] ?? ''); ?>"
                  required />
              </div>
            </div>
            <div class="form-group">
              <label for="telephone_societe" class="required">Téléphone de l'entité</label>
              <div class="input-wrapper">
                <input
                  type="tel"
                  id="telephone_societe"
                  name="telephone_societe"
                  placeholder="Ex: 0123456789"
                  value="<?php echo esc_attr($enregistrement['telephone_societe'] ?? ''); ?>"
                  required />
              </div>
            </div>
          </div>
        </div>

        <!-- Registre de commerce -->
        <div class="form-section">
          <h2 class="section-title">
            <i class="fas fa-file-contract"></i>Registre de commerce
          </h2>
          <div class="form-row">
            <div class="form-group">
              <label for="numero_registre" class="required">Numéro du registre</label>
              <div class="input-wrapper">
                <input
                  type="text"
                  id="numero_registre"
                  name="numero_registre"
                  placeholder="Numéro du registre de commerce"
                  value="<?php echo esc_attr($enregistrement['numero_registre'] ?? ''); ?>"
                  required />
              </div>
            </div>
            <div class="form-group">
              <label for="document" class="required">Document à téléverser</label>
              <div class="file-upload">
                <label for="document" class="file-upload-label">
                  <i class="fas fa-cloud-upload-alt"></i>
                  Choisir un fichier
                </label>
                <input
                  type="file"
                  id="document"
                  name="document"
                  accept=".pdf,.png,.jpg,.jpeg"
                  required />
              </div>
              <div id="file-name" class="file-name">
                <i class="fas fa-file-alt"></i>
                <span>Aucun fichier sélectionné</span>
              </div>
            </div>
          </div>
        </div>

        <div class="submit-container">
          <button type="submit" class="submit-btn">
            <i class="fas fa-paper-plane"></i> Soumettre
          </button>
          <div id="message" class="message"></div>
        </div>

        <input
          type="hidden"
          name="action"
          value="sefarpay_save_registration" />

        <script>
          document
            .getElementById("registrationForm")
            .addEventListener("submit", async function(e) {
              e.preventDefault();

              const form = document.getElementById("registrationForm");
              const formData = new FormData(form);
              const messageBox = document.getElementById("message");

              messageBox.innerHTML = "⏳ Envoi en cours...";

              const response = await fetch(ajaxurl, {
                method: "POST",
                body: formData,
              });

              const result = await response.json();

              if (result.success) {
                messageBox.innerHTML = `<p style="color:green">✅ ${result.data}</p>`;
                form.reset();
              } else {
                messageBox.innerHTML = `<p style="color:red">❌ ${result.data}</p>`;
              }
            });
        </script>
      </form>
    </div>
  </div>

  <script>
    // Afficher le nom du fichier sélectionné
    document
      .getElementById("document")
      .addEventListener("change", function(e) {
        const fileNameSpan = document.querySelector("#file-name span");
        const fileIcon = document.querySelector("#file-name i");

        if (e.target.files[0]) {
          const fileName = e.target.files[0].name;
          fileNameSpan.textContent = fileName;

          // Changer l'icône en fonction du type de fichier
          const fileExt = fileName.split(".").pop().toLowerCase();
          if (fileExt === "pdf") {
            fileIcon.className = "fas fa-file-pdf";
          } else if (["jpg", "jpeg", "png"].includes(fileExt)) {
            fileIcon.className = "fas fa-file-image";
          } else {
            fileIcon.className = "fas fa-file-alt";
          }
        } else {
          fileNameSpan.textContent = "Aucun fichier sélectionné";
          fileIcon.className = "fas fa-file-alt";
        }
      });

    // Simuler les communes en fonction de la wilaya sélectionnée
    document.getElementById("wilaya").addEventListener("change", function() {
      const wilaya = this.value;
      const communeSelect = document.getElementById("commune");

      // Vider le select des communes
      communeSelect.innerHTML = "";

      if (!wilaya) {
        communeSelect.innerHTML =
          '<option value="">Sélectionnez d\'abord une wilaya</option>';
        return;
      }

      // Simuler les communes pour chaque wilaya
      let communes = [];

      if (wilaya === "Alger") {
        communes = [
          "Alger Centre",
          "Bab El Oued",
          "Bir Mourad Raïs",
          "Hussein Dey",
          "El Biar",
          "Kouba",
          "Bouzareah",
          "Chéraga",
        ];
      } else if (wilaya === "Oran") {
        communes = [
          "Oran",
          "Bir El Djir",
          "Es Senia",
          "Arzew",
          "Bethioua",
          "Mers El Kébir",
          "Aïn El Turk",
        ];
      } else if (wilaya === "Constantine") {
        communes = [
          "Constantine",
          "El Khroub",
          "Hamma Bouziane",
          "Didouche Mourad",
          "Aïn Smara",
          "Zighoud Youcef",
        ];
      } else if (wilaya === "Annaba") {
        communes = [
          "Annaba",
          "El Bouni",
          "El Hadjar",
          "Sidi Amar",
          "Berrahal",
        ];
      } else if (wilaya === "Blida") {
        communes = ["Blida", "Boufarik", "Bougara", "Mouzaia", "Ouled Yaich"];
      } else if (wilaya === "Batna") {
        communes = ["Batna", "Tazoult", "Barika", "Aïn Touta", "Arris"];
      } else if (wilaya === "Setif") {
        communes = [
          "Sétif",
          "El Eulma",
          "Aïn Oulmene",
          "Bougaa",
          "Aïn Arnat",
        ];
      } else if (wilaya === "Tlemcen") {
        communes = ["Tlemcen", "Mansourah", "Chetouane", "Maghnia", "Remchi"];
      }

      // Ajouter les options de communes
      communeSelect.innerHTML =
        '<option value="">Sélectionnez une commune</option>';
      communes.forEach((commune) => {
        const option = document.createElement("option");
        option.value = commune;
        option.textContent = commune;
        communeSelect.appendChild(option);
      });
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
      .getElementById("registrationForm")
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

          // Validation spécifique pour l'email
          if (field.type === "email" && field.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(field.value)) {
              animateInvalidField(field);
              isValid = false;
            }
          }

          // Validation spécifique pour le téléphone
          if (field.id === "telephone" || field.id === "telephone_societe") {
            const phoneRegex = /^[0-9+\s()-]{8,15}$/;
            if (!phoneRegex.test(field.value)) {
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

        // Simulation d'envoi de formulaire (à remplacer par votre logique d'envoi réelle)
        const submitBtn = form.querySelector(".submit-btn");
        const originalBtnText = submitBtn.innerHTML;

        submitBtn.innerHTML =
          '<i class="fas fa-spinner fa-spin"></i> Traitement en cours...';
        submitBtn.disabled = true;

        // Simuler un délai de traitement
        setTimeout(() => {
          messageDiv.textContent =
            "Formulaire soumis avec succès! Nous traiterons votre demande dans les plus brefs délais.";
          messageDiv.className = "message success";
          messageDiv.style.display = "block";

          submitBtn.innerHTML =
            '<i class="fas fa-check"></i> Envoyé avec succès!';

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
      padding-left: 15px;
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

    .file-upload {
      position: relative;
      display: inline-block;
      width: 100%;
    }

    .file-upload-label {
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 15px;
      background: linear-gradient(135deg,
          var(--secondary-color),
          var(--secondary-light));
      color: white;
      text-align: center;
      border-radius: 10px;
      cursor: pointer;
      transition: all 0.3s;
      font-weight: 500;
      box-shadow: 0 4px 10px rgba(58, 167, 170, 0.2);
    }

    .file-upload-label i {
      margin-right: 10px;
      font-size: 1.2rem;
    }

    .file-upload-label:hover {
      background: linear-gradient(135deg,
          var(--secondary-dark),
          var(--secondary-color));
      transform: translateY(-2px);
      box-shadow: 0 6px 15px rgba(58, 167, 170, 0.25);
    }

    .file-upload input[type="file"] {
      position: absolute;
      left: 0;
      top: 0;
      opacity: 0;
      width: 100%;
      height: 100%;
      cursor: pointer;
    }

    .file-name {
      margin-top: 10px;
      font-size: 0.9rem;
      color: var(--dark-gray);
      background-color: var(--white);
      padding: 8px 12px;
      border-radius: 6px;
      border: 1px solid #dee2e6;
      display: flex;
      align-items: center;
    }

    .file-name i {
      margin-right: 8px;
      color: var(--secondary-color);
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
  </style>
</body>

</html>