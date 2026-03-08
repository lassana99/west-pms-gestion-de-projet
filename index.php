<!DOCTYPE html>
<html lang="en">
<?php session_start() ?>
<?php 
	if(!isset($_SESSION['login_id']))
	    header('location:login.php');
    include 'db_connect.php';
    ob_start();
    ob_end_flush();

	include 'header.php' 
?>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div style="background-color: #178d38;" class="wrapper">
  <?php include 'topbar.php' ?>
  <?php include 'sidebar.php' ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
  	 <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
	    <div class="toast-body text-white"></div>
	  </div>
    <div id="toastsContainerTopRight" class="toasts-top-right fixed"></div>

    <!-- Content Header (Page header) here -->
    <div class="content-header">
      <div class="container-fluid">
        <!-- Définition du nom des sous-titres -->
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0" style="white-space: nowrap;">
              <?php
                $page = isset($_GET['page']) ? $_GET['page'] : 'accueil';
                switch($page){
                  case 'new_project':
                    echo "Nouveau projet";
                    break;
                  case 'project_list':
                    echo "Liste des projets";
                    break;
                  case 'activity_list':
                    echo "Liste des activités menées par les consultants";
                    break;
                  case 'mission':
                    echo "La répartition des projets en fonction de mission";
                    break;
                  case 'new_user':
                    echo "Nouvel utilisateur";
                    break;
                  case 'user_list':
                    echo "Liste des utilisateurs";
                    break;
                  case 'view_project':
                    echo "FICHE DE PROJET";
                    break;
                  case 'country':
                    echo "Le rapport des différents pays en terme de projet";
                    break;
                  case 'domain':
                    echo "Le rapport des différents domaines";
                    break;
                  case 'edit_project':
                    echo "La mise à jour du projet";
                    break;
                  case 'edit_user':
                    echo "La mise à jour de l'utilisateur";
                    break;
                  case 'new_contract':
                    echo "Nouveau  contrat";
                    break;
                  case 'contract_list':
                    echo "Liste des contrats";
                    break;
                  case 'edit_contract':
                    echo "Mise à jour du  contrat";
                    break;
                  case 'view_contract':
                    echo "Détails du  contrat";
                    break;
                  case 'report_list':
                    echo "Liste des livrables soumis";
                    break;
                  case 'view_report':
                    echo "Détails du rapport soumis";
                    break;
                  case 'new_report':
                    echo "Nouveau rapport";
                    break;
                  case 'edit_report':
                    echo "Mise à jour du rapport";
                    break;
                  case 'archive_list':
                    echo "La liste des livrables archivés";
                    break;
                  case 'view_report_archive':
                    echo "Détails des livrables archivés";
                    break;
                  case 'project_archive_list':
                    echo "La liste des projets archivés";
                    break;
                  case 'view_projec_archive':
                    echo "Détails des projets archivés";
                    break;
                  case 'new_invoice':
                    echo "Nouvelle facture";
                    break;
                  case 'invoice_list':
                    echo "Liste des factures";
                    break;
                  case 'edit_invoice':
                    echo "Mise à jour de la facture";
                    break;
                  case 'view_invoice':
                    echo "Détails de la facture";
                    break;
                  case 'new_iso_terrain':
                    echo "Nouveau document ISO sur le terrain";
                    break;
                  case 'iso_terrain_list':
                    echo "Liste des documents ISO sur le terrain";
                    break;
                  case 'edit_iso_terrain':
                    echo "Mise à jour du document ISO sur le terrain";
                    break;
                  case 'view_iso_terrain':
                    echo "Détails du document ISO sur le terrain";
                    break;
                  case 'new_iso_bureau':
                    echo "Nouveau document ISO du bureau";
                    break;
                  case 'iso_bureau_list':
                    echo "Liste des documents du bureau";
                    break;
                  case 'edit_iso_bureau':
                    echo "Mise à jour du document ISO du bureau";
                    break;
                  case 'view_iso_bureau':
                    echo "Détails du document ISO du bureau";
                    break;
                  case 'new_cv_batiment':
                    echo "Nouveau CV dans le domaine des bâtiments";
                    break;
                  case 'cv_batiment_list':
                    echo "Liste des CV dans le domaine des bâtiments";
                    break;
                  case 'edit_cv_batiment':
                    echo "Mise à jour du CV dans le domaine des bâtiments";
                    break;
                  case 'view_cv_batiment':
                    echo "Détails du CV dans le domaine des bâtiments";
                    break;
                  case 'new_cv_route':
                    echo "Nouveau CV dans le domaine des Routes et Ouvrages";
                    break;
                  case 'cv_route_list':
                    echo "Liste des CV dans le domaine des Routes et Ouvrages";
                    break;
                  case 'edit_cv_route':
                    echo "Mise à jour du CV dans le domaine des Routes et Ouvrages";
                    break;
                  case 'view_cv_route':
                    echo "Détails du CV dans le domaine des Routes et Ouvrages";
                    break;
                  case 'new_cv_amenagement':
                    echo "Nouveau CV dans le domaine des Aménagements Hydro-Agricoles";
                    break;
                  case 'cv_amenagement_list':
                    echo "Liste des CV dans le domaine des Aménagements Hydro-Agricoles";
                    break;
                  case 'edit_cv_amenagement':
                    echo "Mise à jour du CV dans le domaine des Aménagements Hydro-Agricoles";
                    break;
                  case 'view_cv_amenagement':
                    echo "Détails du CV dans le domaine des Aménagements Hydro-Agricoles";
                    break;
                  case 'new_cv_aep':
                    echo "Nouveau CV dans le domaine d'Adduction en Eau Potable";
                    break;
                  case 'cv_aep_list':
                    echo "Liste des CV dans le domaine d'Adduction en Eau Potable";
                    break;
                  case 'edit_cv_aep':
                    echo "Mise à jour du CV dans le domaine d'Adduction en Eau Potable";
                    break;
                  case 'view_cv_aep':
                    echo "Détails du CV dans le domaine d'Adduction en Eau Potable";
                    break;
                  case 'new_attachment':
                    echo "Nouvel attachement";
                    break;
                  case 'attachment_list':
                    echo "Liste des attachements";
                    break;
                  case 'edit_attachment':
                    echo "Mise à jour de l'attachement";
                    break;
                  case 'view_attachment':
                    echo "Détails de l'attachement";
                    break;
                  case 'new_statement':
                    echo "Nouveau décompte";
                    break;
                  case 'statement_list':
                    echo "Liste des décomptes";
                    break;
                  case 'edit_statement':
                    echo "Mise à jour du décompte";
                    break;
                  case 'view_statement':
                    echo "Détails du décompte";
                    break;
                  default:
                    echo "Accueil";
                    break;
                }
              ?>
            </h1>
          </div><!-- /.col -->
        </div><!-- /.row -->
        <hr class="border-primary">
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
         <?php 
            $page = isset($_GET['page']) ? $_GET['page'] : 'accueil';
            if(!file_exists($page.".php")){
                include '404.html';
            } else {
                include $page.'.php';
            }
          ?>
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->

    <!-- Modals -->
    <div class="modal fade" id="confirm_modal" role='dialog'>
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Confirmation</h5>
          </div>
          <div class="modal-body">
            <div id="delete_content"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id='confirm' onclick="">Poursuivre</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="uni_modal" role='dialog'>
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
          </div>
          <div class="modal-body"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Sauvegarder</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="uni_modal_right" role='dialog'>
      <div class="modal-dialog modal-full-height modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span class="fa fa-arrow-right"></span>
            </button>
          </div>
          <div class="modal-body"></div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="viewer_modal" role='dialog'>
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <button type="button" class="btn-close" data-dismiss="modal">
            <span class="fa fa-times"></span>
          </button>
          <img src="" alt="">
        </div>
      </div>
    </div>
  </div>
  <!-- End content-wrapper -->

  <!-- Main Footer -->
  <footer class="main-footer">
    <div class="scroll-title">
      <marquee behavior="scroll" direction="left">
        <strong>
          <span style="color: green; font-size: larger;">
            Tout le monde au même niveau d'information pour plus de productivité chez WEST Ingénierie !!!
          </span>
        </strong>
      </marquee>
    </div>

    <!-- Définition du nom du système en pied de page -->
    <div class="float-right d-none d-sm-inline-block">
      <h1 class="footer-title"><b>WEST Ingénierie Projects Management System</b></h1>
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->
<?php include 'footer.php' ?>
</body>
</html>
