
<?php
session_start(); // Démarre la session

// Redirige vers login si non connecté
if (!isset($_SESSION['login_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Accueil | WEST Ingénierie Projects Management System</title>
    <!-- Font Awesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
    <style>
        :root {
            --company-strong-green: rgb(11, 100, 11);
        }
  body {
    background-color: #0B640B; /* Vert très fort et professionnel */
    color: #f0f0f0; /* Texte clair par défaut */
    font-family: 'Source Sans Pro', sans-serif;
    margin: 0;
}
        header {
            background-color: #f5f5f5;
            color: #333;
            padding: 15px 30px;
            display: flex;
            align-items: center;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            z-index: 1000;
        }
        header img.logo {
            height: 90px;
            margin-right: 30px;
        }
        header div.title-container {
            margin-left: 0;
        }
        @media (min-width: 601px) {
            header div.title-container {
                margin-left: 50px;
            }
        }
        header h2 {
            margin: 0;
            color: var(--company-strong-green);
        }
        header .user-info {
            margin-left: 120px; /* Ajuste la valeur selon le rendu souhaité */
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--company-strong-green);
        }
       main {
    max-width: 1200px;
    margin: 40px auto;
    padding: 120px 20px 70px 20px; /* espace pour header et footer */
    
    background-color: #f7faf7; /* Gris très clair avec une pointe de vert */
    color: #333; /* Texte sombre pour contraste */
    
    border-radius: 12px;
    box-shadow: 0 8px 20px rgba(11, 100, 11, 0.1);
}
        h1 {
            color: var(--company-strong-green);
            font-size: 2.5rem;
            margin-bottom: 10px;
        }
        p.instruction {
            font-size: 2.3rem;
            font-weight: 600;
            margin-bottom: 30px;
            color: var(--company-strong-green);
            text-align: center;
        }
        /* Grille de boutons */
        .button-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .custom-btn {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 160px;
            height: 80px;
            border-radius: 8px;
            color: white;
            font-weight: 600;
            font-size: 1rem;
            text-decoration: none;
            box-shadow: 0 4px 6px rgba(0,0,0,0.10);
            transition: transform 0.2s, box-shadow 0.2s, filter 0.2s;
            user-select: none;
            border: none;
            outline: none;
            text-align: center;
        }
        .custom-btn i {
            font-size: 1.5em;
            margin-bottom: 7px;
        }
        .custom-btn:hover {
            transform: translateY(-4px) scale(1.05);
            box-shadow: 0 8px 14px rgba(0,0,0,0.18);
            text-decoration: none;
            filter: brightness(1.1);
            color: white;
        }
        /* Couleurs des boutons */
        .btn-blue         { background: #2196f3; }
        .btn-green        { background: rgb(11, 100, 11); }
        .btn-lightgreen   { background: #8bc34a; }
        .btn-orange       { background: #ff9800; }
        .btn-red          { background: #e53935; }
        .btn-pink         { background: #e91e63; }
        .btn-gray         { background: #757575; }
        .btn-lightblue    { background: #64b5f6; }
        .btn-black        { background: #222; }
        .btn-white        { background: #f5f5f5; color: #333; border: 1px solid #ccc; }
        .btn-teal         { background: #00897b; }
        .btn-brown        { background: #8d6e63; }
        .btn-purple       { background: #7c4dff; }
        .btn-amber        { background: #ffb300; color: #333; }
        .btn-cyan         { background: #00bcd4; }
        .btn-lime         { background: #cddc39; color: #333; }
        /* Responsive */
        @media (max-width: 1000px) {
            .custom-btn { width: 45vw; min-width: 140px; }
        }
        @media (max-width: 600px) {
            .button-grid {
                gap: 12px;
            }
            .custom-btn {
                width: 90vw;
                min-width: unset;
                height: 70px;
                font-size: 0.95em;
            }
            .custom-btn i {
                font-size: 1.2em;
            }
            .dashboard-btn-container {
                margin-bottom: 18px !important;
            }
        }
        /* Style spécifique pour le bouton Tableau de bord */
        .dashboard-btn-container {
            display: flex;
            justify-content: center;
            margin-bottom: 30px;
        }
        /* Footer */
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background: #f5f5f5;
            color: #333;
            text-align: center;
            padding: 12px 0;
            font-size: 1rem;
            box-shadow: 0 -2px 5px rgba(0,0,0,0.08);
            z-index: 999;
        }
.bg-light-gray {
    background-color: #e8e8e8; /* Gris clair légèrement chaud */
    color: #333;               /* Couleur du texte sombre pour le contraste */
    box-shadow: 0 2px 5px rgba(0,0,0,0.1); /* Optionnel : légère ombre */
}
    </style>
</head>
<body>
    <header class="bg-light-gray">
        <img src="assets/uploads/logo.png" alt="WEST Ingénierie Logo" class="logo" />
        <div class="title-container">
            <h2>La plateforme de Suivi et de Gestion des projets de WEST Ingénierie</h2>
        </div>
        <div class="user-info">
            Bienvenue: <?php echo htmlspecialchars(ucwords($_SESSION['login_firstname'] . ' ' . $_SESSION['login_lastname'])); ?>!
        </div>
    </header>
    <main>
        <p class="instruction">Merci de choisir la tâche que vous voulez effectuer</p>
        <!-- Bouton Tableau de bord au-dessus -->
        <div class="dashboard-btn-container">
            <a href="index.php?page=home" class="custom-btn btn-blue">
                <i class="fas fa-tachometer-alt"></i>
                Tableau de bord
            </a>
        </div>
        <div class="button-grid">
            <!-- Tous les autres boutons ici, sauf Tableau de bord -->
            <a href="index.php?page=new_contract" class="custom-btn btn-green">
                <i class="fas fa-file-signature"></i>
                Ajouter un contrat
            </a>
            <a href="index.php?page=contract_list" class="custom-btn btn-lightgreen">
                <i class="fas fa-list-alt"></i>
                Liste des contrats
            </a>
            <a href="index.php?page=new_project" class="custom-btn btn-orange">
                <i class="fas fa-plus-square"></i>
                Ajouter un projet
            </a>
            <a href="index.php?page=project_list" class="custom-btn btn-red">
                <i class="fas fa-tasks"></i>
                Liste des projets
            </a>
            <a href="index.php?page=activity_list" class="custom-btn btn-pink">
                <i class="fas fa-chart-line"></i>
                Activités sur projets
            </a>
            <a href="index.php?page=new_invoice" class="custom-btn btn-gray">
                <i class="fas fa-file-invoice-dollar"></i>
                Ajouter une  facture
            </a>
            <a href="index.php?page=invoice_list" class="custom-btn btn-lightblue">
                <i class="fas fa-file-invoice"></i>
                Liste des factures
            </a>
            <a href="index.php?page=new_attachment" class="custom-btn btn-amber">
                <i class="fas fa-paperclip"></i>
                Ajouter un attachement
            </a>
            <a href="index.php?page=attachment_list" class="custom-btn btn-cyan">
                <i class="fas fa-paperclip"></i>
                Liste des attachements
            </a>
            <a href="index.php?page=new_statement" class="custom-btn btn-brown">
                <i class="fas fa-file-contract"></i>
                Ajouter un décompte
            </a>
            <a href="index.php?page=statement_list" class="custom-btn btn-purple">
                <i class="fas fa-file-contract"></i>
                Liste des décomptes
            </a>
            <a href="index.php?page=project_archive_list" class="custom-btn btn-black">
                <i class="fas fa-archive"></i>
                Projets archivés
            </a>
            <a href="index.php?page=new_report" class="custom-btn btn-green">
                <i class="fas fa-plus-circle"></i>
                Ajouter un rapport
            </a>
            <a href="index.php?page=report_list" class="custom-btn btn-blue">
                <i class="fas fa-file-alt"></i>
                Liste des livrables
            </a>
            <a href="index.php?page=archive_list" class="custom-btn btn-gray">
                <i class="fas fa-archive"></i>
                Livrables archivés
            </a>
            <a href="index.php?page=new_iso_terrain" class="custom-btn btn-lightgreen">
                <i class="fas fa-file-medical"></i>
                Ajouter un doc ISO terrain
            </a>
            <a href="index.php?page=iso_terrain_list" class="custom-btn btn-orange">
                <i class="fas fa-file-medical-alt"></i>
                Liste des docs ISO terrain
            </a>
            <a href="index.php?page=new_iso_bureau" class="custom-btn btn-lightblue">
                <i class="fas fa-file-medical"></i>
                Ajouter un doc ISO bureau
            </a>
            <a href="index.php?page=iso_bureau_list" class="custom-btn btn-pink">
                <i class="fas fa-file-medical-alt"></i>
                Liste des docs ISO bureau
            </a>
            <a href="index.php?page=new_cv_batiment" class="custom-btn btn-green">
                <i class="fas fa-user-plus"></i>
                Ajouter un CV bâtiment
            </a>
            <a href="index.php?page=cv_batiment_list" class="custom-btn btn-blue">
                <i class="fas fa-users"></i>
                Liste des CV bâtiment
            </a>
            <a href="index.php?page=new_cv_route" class="custom-btn btn-orange">
                <i class="fas fa-user-plus"></i>
                Ajouter un CV route
            </a>
            <a href="index.php?page=cv_route_list" class="custom-btn btn-red">
                <i class="fas fa-users"></i>
                Liste des CV route
            </a>
            <a href="index.php?page=new_cv_amenagement" class="custom-btn btn-lightgreen">
                <i class="fas fa-user-plus"></i>
                Ajouter un CV aménagement
            </a>
            <a href="index.php?page=cv_amenagement_list" class="custom-btn btn-cyan">
                <i class="fas fa-users"></i>
                Liste des CV aménagement
            </a>
            <a href="index.php?page=new_cv_aep" class="custom-btn btn-brown">
                <i class="fas fa-user-plus"></i>
                Ajouter un CV AEP
            </a>
            <a href="index.php?page=cv_aep_list" class="custom-btn btn-purple">
                <i class="fas fa-users"></i>
                Liste des CV AEP
            </a>
            <a href="index.php?page=domain" class="custom-btn btn-lightblue">
                <i class="fas fa-sitemap"></i>
                Projets par domaine
            </a>
            <a href="index.php?page=mission" class="custom-btn btn-amber">
                <i class="fas fa-bullseye"></i>
                Projets par mission
            </a>
            <a href="index.php?page=country" class="custom-btn btn-lime">
                <i class="fas fa-globe-africa"></i>
                Projets par pays
            </a>
        </div>
    </main>
    <footer class="bg-light-gray">
        &copy; <?php echo date('Y'); ?> WEST Ingénierie. Tous droits réservés.
    </footer>
</body>
</html>
