<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Liste des Paiements</title>
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    <script src="https://cdn.sheetjs.com/xlsx-latest/package/dist/xlsx.full.min.js"></script>

</head>

<body>





    <div class="container">
        <div class="page-header">
            <h1>Liste des Paiements {{ message }}</h1>
            <p>
                Consultez et gérez l'historique complet des transactions de paiement
                effectuées via l'API SATIM.
            </p>
        </div>

        <div id="app" class="page-content">
            <!-- Filtres et actions -->
            <div class="filters-section">
                <h2 class="section-title"><i class="fas fa-filter"></i>Filtres</h2>
                <div class="filters-row">
                    <div class="filter-group">
                        <label for="status-filter">État du paiement</label>
                        <select id="status-filter" class="filter-input" v-model="status">
                            <option value="">Tous les états</option>
                            <option value="success">Succès</option>
                            <option value="error">Échec</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label for="date-from">Date du paiement</label>
                        <input
                            type="date"
                            id="date-from"
                            class="filter-input"
                            v-model="date" />
                    </div>

                    <div class="filter-group">
                        <label for="amount-min">Montant minimum</label>
                        <input
                            type="number"
                            id="amount-min"
                            class="filter-input"
                            v-model.number="amount"
                            placeholder="0"
                            min="0" />
                    </div>
                </div>

                <div class="actions-row">
                    <div>
                        <button @click="exportCSV" class="export-btn">
                            <i class="fas fa-file-csv"></i> Exporter CSV
                        </button>
                        <button @click="exportExcel" class="export-btn">
                            <i class="fas fa-file-excel"></i> Exporter Excel
                        </button>
                    </div>
                </div>
            </div>

            <!-- Tableau -->
            <div class="table-container">
                <table class="payments-table">
                    <thead>
                        <tr>
                            <th><i class="fas fa-sort"></i> Numéro de commande</th>
                            <th><i class="fas fa-sort"></i> Utilisateur</th>
                            <th><i class="fas fa-sort"></i> ID Transaction</th>
                            <th><i class="fas fa-sort"></i> Date</th>
                            <th><i class="fas fa-sort"></i> Description</th>
                            <th><i class="fas fa-sort"></i> Montant</th>
                            <th><i class="fas fa-sort"></i> Moyen</th>
                            <th><i class="fas fa-sort"></i> État</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <template v-if="pagedPayments.length">
                            <tr
                                v-for="(paiement, index) in pagedPayments"
                                :key="paiement.id"
                                :data-id="index + 1">
                                <td>{{ paiement.numero_commande }}</td>
                                <td>
                                    <div class="tooltip">
                                        {{ paiement.nom_utilisateur }}
                                        <span class="tooltip-text">
                                            {{ paiement.email_utilisateur }}<br />
                                            {{ paiement.telephone_utilisateur }}
                                        </span>
                                    </div>
                                </td>
                                <td>{{ paiement.transaction_id }}</td>
                                <td>{{ paiement.date_paiement }}</td>
                                <td>{{ paiement.description }}</td>
                                <td>{{ formatAmount(paiement.montant, paiement.devise) }}</td>
                                <td>{{ paiement.moyen_paiement }}</td>
                                <td>
                                    <span
                                        :class="['status', 'status-' + paiement.etat_paiement.toLowerCase()]">
                                        {{ paiement.etat_paiement }}
                                    </span>
                                </td>
                                <td>
                                    <button class="toggle-details" data-id="{{paiement.id }}">
                                        <i class="fas fa-chevron-down"></i>
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="detailsVisible[index]" class="details-row">
                                <td colspan="9" class="p-0">
                                    <div class="transaction-details">
                                        <div class="details-grid">
                                            <div class="detail-item">
                                                <div class="detail-label">Nom du porteur</div>
                                                <div class="detail-value">{{ paiement.nom_carte }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Numéro de carte</div>
                                                <div class="detail-value">{{ paiement.pan }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Système de paiement</div>
                                                <div class="detail-value">{{ paiement.systeme_paiement }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Score de fraude</div>
                                                <div class="detail-value">{{ paiement.score_fraude }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Adresse IP</div>
                                                <div class="detail-value">{{ paiement.ip_utilisateur }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Banque du payeur</div>
                                                <div class="detail-value">{{ paiement.banque }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Pays de la banque</div>
                                                <div class="detail-value">{{ paiement.pays_banque }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Montant approuvé</div>
                                                <div class="detail-value">{{ formatAmount(paiement.montant_approuve, 'DZD') }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Montant déposé</div>
                                                <div class="detail-value">{{ formatAmount(paiement.montant_depose, 'DZD') }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Montant remboursé</div>
                                                <div class="detail-value">{{ formatAmount(paiement.montant_rembourse, 'DZD') }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">ID du terminal</div>
                                                <div class="detail-value">{{ paiement.terminal_id }}</div>
                                            </div>
                                            <div class="detail-item">
                                                <div class="detail-label">Type d'authentification</div>
                                                <div class="detail-value">{{ paiement.type_authentification }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </template>
                        <tr v-else>
                            <td colspan="9" class="empty-state">
                                <i class="fas fa-search"></i>
                                <h3>Aucun paiement trouvé</h3>
                                <p>Essayez de modifier vos filtres.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Loader -->
            <div id="loader" class="loader" v-show="loading">
                <i class="fas fa-spinner fa-spin"></i>
            </div>

            <!-- Pagination (statique) -->
            <div class="pagination">
                <button class="pagination-btn" @click="prevPage" :disabled="currentPage === 1">
                    <i class="fas fa-chevron-left"></i>
                </button>
                <button
                    class="pagination-btn"
                    :class="{ active: currentPage === 1 }"
                    @click="goToPage(1)">
                    1
                </button>
                <button
                    class="pagination-btn"
                    :class="{ active: currentPage === 2 }"
                    @click="goToPage(2)"
                    v-if="totalPages >= 2">
                    2
                </button>
                <button
                    class="pagination-btn"
                    :class="{ active: currentPage === 3 }"
                    @click="goToPage(3)"
                    v-if="totalPages >= 3">
                    3
                </button>
                <button
                    class="pagination-btn"
                    @click="nextPage"
                    :disabled="currentPage === totalPages">
                    <i class="fas fa-chevron-right"></i>
                </button>
            </div>
        </div>

        <script>
            const paiementsData = <?= json_encode($paiements, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP); ?>;

            const app = Vue.createApp({
                data() {
                    return {
                        paiements: paiementsData || [],
                        date: '',
                        amount: 0,
                        status: '',
                        currentPage: 1,
                        itemsPerPage: 10,
                        detailsVisible: {},
                        loading: false,
                    };
                },
                computed: {
                    filteredPayments() {
                        return this.paiements.filter((p) => {
                            const matchesStatus = !this.status || p.etat_paiement.toLowerCase() === this.status.toLowerCase();
                            const matchesDate = !this.date || p.date_paiement === this.date;
                            const matchesAmount = !this.amount || Number(p.montant) >= this.amount;
                            return matchesStatus && matchesDate && matchesAmount;
                        });
                    },
                    totalPages() {
                        return Math.ceil(this.filteredPayments.length / this.itemsPerPage) || 1;
                    },
                    pagedPayments() {
                        const start = (this.currentPage - 1) * this.itemsPerPage;
                        return this.filteredPayments.slice(start, start + this.itemsPerPage);
                    },
                },
                methods: {
                    formatAmount(value, devise) {
                        if (value == null) return '';
                        return Number(value).toLocaleString('fr-FR', {
                            style: 'currency',
                            currency: devise || 'EUR',
                        });
                    },
                    toggleDetails(index) {
                        this.detailsVisible = {
                            ...this.detailsVisible,
                            [index]: !this.detailsVisible[index],
                        };

                    },
                    prevPage() {
                        if (this.currentPage > 1) this.currentPage--;
                    },
                    nextPage() {
                        if (this.currentPage < this.totalPages) this.currentPage++;
                    },
                    goToPage(page) {
                        if (page >= 1 && page <= this.totalPages) {
                            this.currentPage = page;
                        }
                    },
                    exportCSV() {
                        if (!this.paiements.length) return;

                        // Colonnes à exporter
                        const headers = [
                            'Numéro de commande',
                            'Utilisateur',
                            'ID Transaction',
                            'Date',
                            'Description',
                            'Montant',
                            'Moyen',
                            'État'
                        ];

                        // Construire les lignes CSV
                        const rows = this.paiements.map(p => [
                            p.numero_commande,
                            p.nom_utilisateur,
                            p.transaction_id,
                            p.date_paiement,
                            p.description,
                            p.montant,
                            p.moyen_paiement,
                            p.etat_paiement
                        ]);

                        // Créer contenu CSV
                        const csvContent = [
                            headers.join(';'),
                            ...rows.map(r => r.map(field => `"${String(field).replace(/"/g, '""')}"`).join(';'))
                        ].join('\n');

                        // Télécharger le fichier
                        const blob = new Blob([csvContent], {
                            type: 'text/csv;charset=utf-8;'
                        });
                        const link = document.createElement('a');
                        link.href = URL.createObjectURL(blob);
                        link.setAttribute('download', 'paiements.csv');
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    },
                    exportExcel() {
                        if (!this.paiements.length) return;

                        const ws_data = [
                            ['Numéro de commande', 'Utilisateur', 'ID Transaction', 'Date', 'Description', 'Montant', 'Moyen', 'État']
                        ];

                        this.paiements.forEach(p => {
                            ws_data.push([
                                p.numero_commande,
                                p.nom_utilisateur,
                                p.transaction_id,
                                p.date_paiement,
                                p.description,
                                p.montant,
                                p.moyen_paiement,
                                p.etat_paiement
                            ]);
                        });

                        const wb = XLSX.utils.book_new();
                        const ws = XLSX.utils.aoa_to_sheet(ws_data);
                        XLSX.utils.book_append_sheet(wb, ws, 'Paiements');

                        XLSX.writeFile(wb, 'paiements.xlsx');
                    }

                },
            });

            app.mount('#app');
        </script>

    </div>





    <script>
        // Afficher/masquer les détails de transaction
        document.querySelectorAll(".toggle-details").forEach((button) => {
            button.addEventListener("click", function() {
                const id = this.getAttribute("data-id");
                const detailsDiv = document.getElementById(`details-${id}`);

                if (detailsDiv.style.display === "block") {
                    detailsDiv.style.display = "none";
                    this.classList.remove("open");
                } else {
                    // Fermer tous les autres détails ouverts
                    document.querySelectorAll(".transaction-details").forEach((div) => {
                        div.style.display = "none";
                    });
                    document.querySelectorAll(".toggle-details").forEach((btn) => {
                        btn.classList.remove("open");
                    });

                    // Ouvrir les détails actuels
                    detailsDiv.style.display = "block";
                    this.classList.add("open");
                }
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
            --warning-color: #f39c12;
            --info-color: #3498db;
            --white: #ffffff;
        }

        @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");

        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
            color: var(--dark-gray);
            line-height: 1.6;
            min-height: 100vh;
        }

        .container {
            margin: 15px;
            background-color: var(--white);
            border-radius: 20px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
        }

        .page-header {
            background: linear-gradient(135deg,
                    var(--primary-color) 0%,
                    var(--primary-light) 100%);
            padding: 40px 30px;
            text-align: center;
            position: relative;
        }

        .page-header::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 30px;
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

        .page-content {
            padding: 20px 30px 40px;
        }

        /* Filtres et actions */
        .filters-section {
            margin-bottom: 30px;
            padding: 25px;
            border-radius: 15px;
            background-color: var(--light-gray);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.03);
            transition: transform 0.3s, box-shadow 0.3s;
            position: relative;
        }

        .filters-section:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
        }

        .filters-section::before {
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

        .filters-row {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-bottom: 20px;
        }

        .filter-group {
            flex: 1 1 200px;
            position: relative;
        }

        .filter-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: var(--dark-gray);
            font-size: 0.95rem;
        }

        .filter-input {
            width: 100%;
            padding: 12px 15px 12px 40px;
            border: 2px solid #dee2e6;
            border-radius: 10px;
            font-size: 15px;
            transition: all 0.3s;
            background-color: var(--white);
        }

        .filter-input:focus {
            outline: none;
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 4px rgba(58, 167, 170, 0.15);
        }

        .filter-icon {
            position: absolute;
            left: 15px;
            top: 40px;
            color: #adb5bd;
            transition: color 0.3s;
        }

        .filter-group:focus-within .filter-icon {
            color: var(--primary-color);
        }

        .actions-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 25px;
        }

        .export-btn {
            display: inline-flex;
            align-items: center;
            padding: 12px 20px;
            background: linear-gradient(135deg,
                    var(--secondary-color),
                    var(--secondary-light));
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 10px rgba(58, 167, 170, 0.2);
            margin-right: 10px;
        }

        .export-btn i {
            margin-right: 8px;
        }

        .export-btn:hover {
            background: linear-gradient(135deg,
                    var(--secondary-dark),
                    var(--secondary-color));
            transform: translateY(-2px);
            box-shadow: 0 6px 15px rgba(58, 167, 170, 0.25);
        }

        .export-btn:active {
            transform: translateY(0);
        }

        .reset-btn {
            display: inline-flex;
            align-items: center;
            padding: 12px 20px;
            background-color: #f8f9fa;
            color: var(--dark-gray);
            border: 1px solid #dee2e6;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .reset-btn i {
            margin-right: 8px;
        }

        .reset-btn:hover {
            background-color: #e9ecef;
        }

        /* Tableau des paiements */
        .table-container {
            overflow-x: auto;
            border-radius: 15px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 30px;
        }

        .payments-table {
            width: 100%;
            border-collapse: collapse;
            background-color: var(--white);
            font-size: 0.95rem;
        }

        .payments-table th {
            background-color: var(--primary-color);
            color: var(--white);
            padding: 15px;
            text-align: left;
            position: relative;
            font-weight: 600;
            cursor: pointer;
            transition: background-color 0.3s;
            white-space: nowrap;
        }

        .payments-table th:hover {
            background-color: var(--primary-dark);
        }

        .payments-table th i {
            margin-left: 5px;
            font-size: 0.8rem;
        }

        .payments-table td {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            transition: background-color 0.3s;
        }

        .payments-table tbody tr {
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .payments-table tbody tr:hover {
            background-color: rgba(233, 236, 239, 0.5);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            position: relative;
            z-index: 1;
        }

        .payments-table tbody tr:last-child td {
            border-bottom: none;
        }

        /* États de paiement */
        .status {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 50px;
            font-weight: 500;
            font-size: 0.85rem;
        }

        .status i {
            margin-right: 5px;
            font-size: 0.8rem;
        }

        .status-success {
            background-color: rgba(46, 204, 113, 0.15);
            color: var(--success-color);
        }

        .status-error {
            background-color: rgba(231, 76, 60, 0.15);
            color: var(--error-color);
        }

        .status-pending {
            background-color: rgba(243, 156, 18, 0.15);
            color: var(--warning-color);
        }

        .status-info {
            background-color: rgba(52, 152, 219, 0.15);
            color: var(--info-color);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            margin-top: 30px;
        }

        .pagination-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            margin: 0 5px;
            border-radius: 50%;
            background-color: var(--white);
            color: var(--dark-gray);
            border: 1px solid #dee2e6;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s;
        }

        .pagination-btn:hover {
            background-color: var(--light-gray);
            border-color: #ced4da;
        }

        .pagination-btn.active {
            background: linear-gradient(135deg,
                    var(--primary-color),
                    var(--primary-light));
            color: var(--white);
            border: none;
            box-shadow: 0 4px 10px rgba(0, 84, 166, 0.2);
        }

        .pagination-btn.disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Détails de transaction */
        .transaction-details {
            display: none;
            background-color: var(--light-gray);
            padding: 20px;
            margin: 10px 0;
            border-radius: 10px;
            box-shadow: inset 0 0 10px rgba(0, 0, 0, 0.05);
            animation: fadeIn 0.3s;
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

        .details-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
        }

        .detail-item {
            margin-bottom: 15px;
        }

        .detail-label {
            font-weight: 600;
            color: var(--primary-color);
            margin-bottom: 5px;
            font-size: 0.9rem;
        }

        .detail-value {
            font-size: 0.95rem;
        }

        .toggle-details {
            background: none;
            border: none;
            color: var(--primary-color);
            cursor: pointer;
            font-size: 1.1rem;
            transition: transform 0.3s;
            display: flex;
            align-items: center;
            padding: 5px;
        }

        .toggle-details:hover {
            color: var(--primary-dark);
        }

        .toggle-details i {
            transition: transform 0.3s;
        }

        .toggle-details.open i {
            transform: rotate(180deg);
        }

        /* Responsive */
        @media (max-width: 1200px) {
            .payments-table {
                font-size: 0.85rem;
            }

            .payments-table th,
            .payments-table td {
                padding: 12px 10px;
            }
        }

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

            .page-content {
                padding: 15px 20px 30px;
            }

            .filters-section {
                padding: 20px;
            }

            .filters-row {
                flex-direction: column;
                gap: 15px;
            }

            .actions-row {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
            }

            .export-btn,
            .reset-btn {
                margin-right: 0;
                justify-content: center;
            }

            .pagination-btn {
                width: 35px;
                height: 35px;
                margin: 0 3px;
            }
        }

        /* Tooltip */
        .tooltip {
            position: relative;
            display: inline-block;
        }

        .tooltip .tooltip-text {
            visibility: hidden;
            width: 200px;
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

        /* Loader */
        .loader {
            display: none;
            text-align: center;
            padding: 30px;
        }

        .loader i {
            font-size: 2rem;
            color: var(--primary-color);
            animation: spin 1s infinite linear;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }

        /* Empty state */
        .empty-state {
            display: none;
            text-align: center;
            padding: 50px 20px;
            color: #6c757d;
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: 20px;
            color: #adb5bd;
        }

        .empty-state h3 {
            margin-bottom: 10px;
            font-weight: 600;
        }

        .empty-state p {
            max-width: 500px;
            margin: 0 auto;
        }
    </style>

</html>