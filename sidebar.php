<aside class="main-sidebar elevation-4">
    <?php 
    include "header.php";
    ?>
    <!-- Ajout du logo et personnalisation de la taille -->
    <div>
        <img class="logo" src="assets/uploads/logo.png" alt="Logo">
    </div>

    <div class="menu-sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
                <li class="nav-item dropdown">
                    <a href="./" class="nav-link nav-home" style="color: white; font-size: 1.2em;">
                        <i class="nav-icon fas fa-tachometer-alt" style="font-size: 1.2em; margin-right: 20px;"></i>
                        <p>
                            Tableau de bord
                        </p>
                    </a>
                </li>  
                <!-- Ajout de doc -->
                <?php if (!in_array($_SESSION['login_type'], [8, 9, 10])): ?>
                <li class="nav-item">
                    <a href="#" class="nav-link nav-is-tree nav-edit_contract nav-view_contract" style="color: white; font-size: 1.2em;">
                        <i class="nav-icon fas fa-file-signature" style="font-size: 1.2em; margin-right: 15px;"></i>
                        <p>
                            Contrats
                            <i  style="font-size: 1.2em;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                    <?php if ($_SESSION['login_type'] == 1 || $_SESSION['login_type'] == 2): ?>
                            <li class="nav-item">
                                <a href="./index.php?page=new_contract" class="nav-link nav-new_contract tree-item" style="color: white; font-size: 1.2em;">
                                    <i class="fas fa-angle-right nav-icon" style="font-size: 1.6em; margin-right: 10px;"></i>
                                    <p>Ajouter un Nouveau</p>
                                </a>
                            </li>
                        <?php endif; ?>    
                        <li class="nav-item">
                            <a href="./index.php?page=contract_list" class="nav-link nav-contract_list tree-item" style="color: white; font-size: 1.2em;">
                                <i class="fas fa-angle-right nav-icon" style="font-size: 1.6em; margin-right: 10px;"></i>
                                <p>Liste</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
                <li class="nav-item">
                    <a href="#" class="nav-link nav-edit_project nav-view_project" style="color: white; font-size: 1.2em;">
                        <i class="nav-icon fas fa-hard-hat" style="font-size: 1.2em; margin-right: 15px;"></i>
                        <p>
                            Projets
                            <i style="font-size: 1.2em;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                    <?php if(in_array($_SESSION['login_type'], [1, 3, 5, 6, 7])): ?>
                            <li class="nav-item">
                                <a href="./index.php?page=new_project" class="nav-link nav-new_project tree-item" style="color: white; font-size: 1.2em;">
                                    <i class="fas fa-angle-right nav-icon" style="font-size: 1.6em; margin-right: 10px;"></i>
                                    <p>Ajouter un Nouveau</p>
                                </a>
                            </li>
                        <?php endif; ?>
                        <li class="nav-item">
                            <a href="./index.php?page=project_list" class="nav-link nav-project_list tree-item" style="color: white; font-size: 1.2em;">
                                <i class="fas fa-angle-right nav-icon" style="font-size: 1.6em; margin-right: 10px;"></i>
                                <p>Liste</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="nav-item">
                    <a href="./index.php?page=activity_list" class="nav-link nav-activity_list" style="color: white; font-size: 1.2em;">
                        <i class="fas fa-chart-line nav-icon" style="font-size: 1.2em; margin-right: 15px;"></i>
                        <p>Activités de projets</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link nav-edit_project nav-view_project" style="color: white; font-size: 1.2em;">
                        <i class="nav-icon fas fa-folder-open" style="font-size: 1.2em; margin-right: 15px;"></i>
                        <p>
                            Docs de projets
                            <i  style="font-size: 1.2em;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                    <?php if(in_array($_SESSION['login_type'], [1, 3, 5, 6, 7])): ?>
                        <li class="nav-item">
                            <a href="./index.php?page=invoice_list" class="nav-link nav-invoice_list tree-item" style="color: white; font-size: 1.2em;">
                                <i class="fas fa-file-invoice-dollar nav-icon" style="font-size: 1.4em; margin-right: 10px;"></i>
                                <p>Factures</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index.php?page=attachment_list" class="nav-link nav-attachment_list tree-item" style="color: white; font-size: 1.2em;">
                                <i class="fas fa-paperclip nav-icon" style="font-size: 1.4em; margin-right: 10px;"></i>
                                <p>Attachements</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index.php?page=statement_list" class="nav-link nav-statement_list tree-item" style="color: white; font-size: 1.2em;">
                                <i class="nav-icon fas fa-calculator" style="font-size: 1.4em; margin-right: 10px;"></i>
                                <p>Décomptes</p>
                            </a>
                        </li>
                    <?php endif; ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link nav-edit_project_archive nav-view_project_archive" style="color: white; font-size: 1.2em;">
                        <i class="nav-icon fas fa-toolbox" style="font-size: 1.2em; margin-right: 15px;"></i>
                        <p>
                            Archive des projets
                            <i style="font-size: 1.2em;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./index.php?page=project_archive_list" class="nav-link nav-project_archive_list tree-item" style="color: white; font-size: 1.2em;">
                                <i class="fas fa-angle-right nav-icon" style="font-size: 1.6em; margin-right: 10px;"></i>
                                <p>Liste</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- Ajout de doc -->
                <?php if (!in_array($_SESSION['login_type'], [9, 11])): ?>
                <li class="nav-item <?= (isset($_GET['page']) && in_array($_GET['page'], ['new_report', 'report_list'])) ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (isset($_GET['page']) && in_array($_GET['page'], ['new_report', 'report_list'])) ? 'active' : '' ?>" style="color: white; font-size: 1.2em;">
                        <i class="nav-icon fas fa-file-alt" style="font-size: 1.2em; margin-right: 5px;"></i>
                        <p>
                            Suivi des livrables
                            <i  style="font-size: 1.2em;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                    <?php if (in_array($_SESSION['login_type'], [1, 3, 5, 6, 7, 10])): ?>
                        <li class="nav-item">
                            <a href="./index.php?page=new_report" class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'new_report') ? 'active' : '' ?>" style="color: white; font-size: 1.2em;">
                                <i class="fas fa-angle-right nav-icon" style="font-size: 1.6em; margin-right: 10px;"></i>
                                <p>Ajouter un Nouveau</p>
                            </a>
                        </li>
                    <?php endif; ?>
                        <li class="nav-item">
                            <a href="./index.php?page=report_list" class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'report_list') ? 'active' : '' ?>" style="color: white; font-size: 1.2em;">
                                <i class="fas fa-angle-right nav-icon" style="font-size: 1.6em; margin-right: 10px;"></i>
                                <p>Liste</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <!-- Ajout de doc -->
                <?php if (!in_array($_SESSION['login_type'], [7, 8, 9, 10])): ?>
                <li class="nav-item <?= (isset($_GET['page']) && $_GET['page'] == 'archive_list') ? 'menu-open' : '' ?>">
                    <a href="#" class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'archive_list') ? 'active' : '' ?>" style="color: white; font-size: 1.2em;">
                        <i class="nav-icon fas fa-archive" style="font-size: 1.2em; margin-right: 5px;"></i>
                        <p>
                            Archive des livrables
                            <i  style="font-size: 1.2em;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./index.php?page=archive_list" class="nav-link <?= (isset($_GET['page']) && $_GET['page'] == 'archive_list') ? 'active' : '' ?>" style="color: white; font-size: 1.2em;">
                                <i class="fas fa-angle-right nav-icon" style="font-size: 1.6em; margin-right: 10px;"></i>
                                <p>Liste</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <?php if(in_array($_SESSION['login_type'], [1, 2, 3, 5, 6, 7, 8, 9, 10])): ?>
                <li class="nav-item">
                    <a href="#" class="nav-link nav-edit_project nav-view_project" style="color: white; font-size: 1.2em;">
                        <i class="nav-icon fas fa-certificate" style="font-size: 1.2em; margin-right: 15px;"></i>
                        <p>
                            Docs ISO9001:2015
                            <i style="font-size: 1.2em;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./index.php?page=iso_terrain_list" class="nav-link nav-iso_terrain_list tree-item" style="color: white; font-size: 1.2em;">
                                <i class="fas fa-map nav-icon" style="font-size: 1.4em; margin-right: 10px;"></i>
                                <p>Docs du Terrain</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index.php?page=iso_bureau_list" class="nav-link nav-iso_bureau_list tree-item" style="color: white; font-size: 1.2em;">
                                <i class="fas fa-desktop nav-icon" style="font-size: 1.4em; margin-right: 10px;"></i>
                                <p>Docs du Bureau</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>

                <li class="nav-item">
                    <a href="#" class="nav-link nav-edit_project nav-view_project" style="color: white; font-size: 1.2em;">
                        <i class="nav-icon fas fa-id-card" style="font-size: 1.2em; margin-right: 15px;"></i>
                        <p>
                            CVs Experts
                            <i style="font-size: 1.2em;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                    <?php if(in_array($_SESSION['login_type'], [1, 3, 5, 6, 7, 8])): ?>
                        <li class="nav-item">
                            <a href="./index.php?page=cv_batiment_list" class="nav-link nav-cv_batiment_list tree-item" style="color: white; font-size: 1.2em;">
                                <i class="fas fa-building nav-icon" style="font-size: 1.4em; margin-right: 10px;"></i>
                                <p>Bâtiment</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index.php?page=cv_route_list" class="nav-link nav-cv_route_list tree-item" style="color: white; font-size: 1.2em;">
                                <i class="fas fa-road nav-icon" style="font-size: 1.4em; margin-right: 10px;"></i>
                                <p>Route & Ouvrage</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index.php?page=cv_amenagement_list" class="nav-link nav-cv_amenagement_list tree-item" style="color: white; font-size: 1.2em;">
                                <i class="fas fa-water nav-icon" style="font-size: 1.4em; margin-right: 10px;"></i>
                                <p>Am.Hydro-Agricole</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index.php?page=cv_aep_list" class="nav-link nav-cv_aep_list tree-item" style="color: white; font-size: 1.2em;">
                                <i class="fas fa-tint nav-icon" style="font-size: 1.4em; margin-right: 10px;"></i>
                                <p>A E P</p>
                            </a>
                        </li>
                    <?php endif; ?>

                    </ul>
                </li>

                <!-- Ajout du rapport de domaine -->
                <?php if (!in_array($_SESSION['login_type'], [7, 8, 9, 10])): ?>
                <li class="nav-item">
                    <a href="./index.php?page=domain" class="nav-link nav-domain" style="color: white; font-size: 1.2em;">
                        <i class="fas fa-sitemap nav-icon" style="font-size: 1.2em; margin-right: 20px;"></i>
                        <p>Domaine</p>
                    </a>
                </li>
                <?php endif; ?>

                <!-- Ajout du rapport de mission -->
                <?php if (!in_array($_SESSION['login_type'], [7, 8, 9, 10])): ?>
                    <li class="nav-item">
                        <a href="./index.php?page=mission" class="nav-link nav-mission" style="color: white; font-size: 1.2em;">
                            <i class="fas fa-bullseye nav-icon" style="font-size: 1.2em; margin-right: 15px;"></i>
                            <p>Mission</p>
                        </a>
                    </li>
                    <?php endif; ?>
                <!-- Ajout du rapport de pays -->
                <?php if (!in_array($_SESSION['login_type'], [7, 8, 9, 10])): ?>
                    <li class="nav-item">
                        <a href="./index.php?page=country" class="nav-link nav-country" style="color: white; font-size: 1.2em;">
                            <i class="fas fa-globe-africa nav-icon" style="font-size: 1.2em; margin-right: 15px;"></i>
                            <p>Pays</p>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- Masquer le champ Utilisateurs sauf pour les types spécifiques -->
                <?php if(in_array($_SESSION['login_type'], [1, 3, 5, 6, 7])): ?>
                <li class="nav-item">
                    <a href="#" class="nav-link nav-edit_user" style="color: white; font-size: 1.2em;">
                        <i class="nav-icon fas fa-users" style="font-size: 1.2em; margin-right: 10px;"></i>
                        <p>
                            Utilisateurs
                            <i style="font-size: 1.2em;"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="./index.php?page=new_user" class="nav-link nav-new_user tree-item" style="color: white; font-size: 1.2em;">
                                <i class="fas fa-angle-right nav-icon" style="font-size: 1.6em; margin-right: 10px;"></i>
                                <p>Ajouter un Nouveau</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./index.php?page=user_list" class="nav-link nav-user_list tree-item" style="color: white; font-size: 1.2em;">
                                <i class="fas fa-angle-right nav-icon" style="font-size: 1.6em; margin-right: 10px;"></i>
                                <p>Liste</p>
                            </a>
                        </li>
                    </ul>
                </li>
                <?php endif; ?>
            </ul>
        </nav>
    </div>
</aside>
<style>
.main-sidebar {
    position: fixed;
    top: 0;
    bottom: 0;
    width: 250px; /* Ajuster la largeur selon vos besoins */
    overflow-y: auto;
    z-index: 1000; /* Pour s'assurer qu'elle reste au-dessus du contenu */
}
.logo {
    display: block;
    margin: 0 auto;
    max-width: 100%; /* Limite la largeur du logo à celle de la barre latérale */
    height: auto; /* Maintient les proportions du logo */
}
.menu-sidebar {
    padding: 20px; /* Ajuster le padding selon vos besoins */
}
.nav-link {
    display: flex;
    align-items: center;
    text-decoration: none;
    color: white;
    font-size: 1em;
}
.nav-link i {
    margin-right: 15px; /* Ajuster l'espacement entre l'icône et le texte */
}
.nav-treeview {
    padding-left: 20px; /* Ajuster l'indentation des sous-menus */
}
</style>
<script>
    $(document).ready(function(){
        var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
        var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
        if(s!='')
            page = page+'_'+s;
        if($('.nav-link.nav-'+page).length > 0){
            $('.nav-link.nav-'+page).addClass('active')
            if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
                $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
                $('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
            }
            if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
                $('.nav-link.nav-'+page).parent().addClass('menu-open')
            }
        }
    })
</script>