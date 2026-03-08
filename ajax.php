<?php
ob_start();
date_default_timezone_set("Africa/Conakry");
include 'admin_class.php';

if (isset($_GET['action']) && !empty($_GET['action'])) {
    $action = $_GET['action'];
    $crud = new Action();
    $response = '';

    switch ($action) {
        case 'login':
            $response = $crud->login();
            break;
        case 'logout':
            $response = $crud->logout();
            break;
        case 'signup':
            $response = $crud->signup();
            break;
        case 'save_user':
            $response = $crud->save_user();
            break;
        case 'update_user':
            $response = $crud->update_user();
            break;
        case 'delete_user':
            $response = $crud->delete_user();
            break;
        case 'save_project':
            $response = $crud->save_project();
            break;
        case 'delete_project':
            $response = $crud->delete_project();
            break;

        case 'save_project_archive':
            $response = $crud->save_project_archive();
            break;
        case 'delete_project_archive':
            $response = $crud->delete_project_archive();
            break;

        case 'save_contract':
            $response = $crud->save_contract();
            break;
        case 'delete_contract':
            $response = $crud->delete_contract();
            break;

        case 'save_invoice':
            $response = $crud->save_invoice();
            break;
        case 'delete_invoice':
            $response = $crud->delete_invoice();
            break;

        case 'save_attachment':
            $response = $crud->save_attachment();
            break;
        case 'delete_attachment':
            $response = $crud->delete_attachment();
            break;

        case 'save_statement':
            $response = $crud->save_statement();
            break;
        case 'delete_statement':
            $response = $crud->delete_statement();
            break;

        case 'save_iso_terrain':
            $response = $crud->save_iso_terrain();
            break;
        case 'delete_iso_terrain':
            $response = $crud->delete_iso_terrain();
            break;

        case 'save_iso_bureau':
            $response = $crud->save_iso_bureau();
            break;
        case 'delete_iso_bureau':
            $response = $crud->delete_iso_bureau();
            break;

        case 'save_cv_batiment':
            $response = $crud->save_cv_batiment();
            break;
        case 'delete_cv_batiment':
            $response = $crud->delete_cv_batiment();
            break;

        case 'save_cv_route':
            $response = $crud->save_cv_route();
            break;
        case 'delete_cv_route':
            $response = $crud->delete_cv_route();
            break;

        case 'save_cv_amenagement':
            $response = $crud->save_cv_amenagement();
            break;
        case 'delete_cv_amenagement':
            $response = $crud->delete_cv_amenagement();
            break;

        case 'save_cv_aep':
            $response = $crud->save_cv_aep();
            break;
        case 'delete_cv_aep':
            $response = $crud->delete_cv_aep();
            break;

        case 'save_report':
            $response = $crud->save_report();
            break;
        case 'delete_report':
            $response = $crud->delete_report();
            break;
       
        case 'save_report_archive':
            $response = $crud->save_report_archive();
            break;
        case 'delete_report_archive':
            $response = $crud->delete_report_archive();
            break;
        case 'save_activity':
            $response = $crud->save_activity();
            break;
        case 'delete_activity':
            $response = $crud->delete_activity();
            break;
        case 'get_report':
            $response = $crud->get_report();
            break;
        default:
            $response = 0;
            break;
    }

    echo $response ? $response : 0;
} else {
    echo 0;
}

ob_end_flush();
?>
