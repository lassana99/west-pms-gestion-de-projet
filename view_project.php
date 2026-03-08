<?php
include 'db_connect.php';

$stat = array("En attente", "En cours", "Fait");

// Récupération des informations sur le projet
$qry = $conn->query("
    SELECT pl.*, c.name AS country_name 
    FROM project_list pl 
    LEFT JOIN country c ON pl.country_id = c.country_id 
    WHERE pl.id = " . intval($_GET['id'])
);


if (!$qry) {
    echo "Erreur dans la requête : " . $conn->error;
    exit;
}

$qry = $qry->fetch_array();
foreach ($qry as $k => $v) {
    $$k = $v;
}

// Récupération des informations sur le gestionnaire
$manager = $conn->query("SELECT *, CONCAT(firstname,' ',lastname) AS name FROM users WHERE id = $manager_id");
$manager = $manager->num_rows > 0 ? $manager->fetch_array() : array();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
</head>
</html>
<div class="col-lg-12">
<div class="col-lg-12">
    <div class="row">
        <div class="col-md-12">
            <div class="callout callout-info project-info">
                <dl>
                    <dt><b>Nom de la mission :</b></dt>
                    <dd><?php echo ucwords($name) ?></dd>
                    <div class="inline">
                        <dt><b>Pays :</b></dt>
                        <dd><?php echo htmlspecialchars($country_name); ?></dd>
                        <dd class="separator">|</dd>
                        <dt><b>Lieu :</b></dt>
                        <dd><?php echo htmlspecialchars($place); ?></dd>
                    </div>
                    <dt><b>Nom de l’Autorité contractante (Bailleur) :</b></dt>
                    <dd><?php echo preg_replace('/(.*?):/', '<b>$1:</b>', htmlspecialchars($lessor)) ?></dd>
                    <dt><b>Adresse :</b></dt>
                    <dd><?php echo preg_replace('/(.*?):/', '<b>$1:</b>', htmlspecialchars($address)) ?></dd>
                    <div class="inline">
                        <dt><b>Date de démarrage :</b></dt>
                        <dd><?php echo ($start_date == '0000-00-00') ? '' : date("F d, Y", strtotime($start_date)); ?></dd>
                    </div>
                    <div class="inline">
                        <dt><b>Date d’achèvement :</b></dt>
                        <dd><?php echo ($end_date == '0000-00-00') ? '' : date("F d, Y", strtotime($end_date)); ?></dd>
                    </div>
                    <dt><b>Noms des consultants associés/partenaires éventuels :</b></dt>
                    <dd class="margin-top"><?php echo preg_replace('/(.*?):/', '<b>$1:</b>', htmlspecialchars($consultant_partner)) ?></dd>
                </dl>
                <dl>
                    <div class="flex-container">
                    <div class="inline">
                        <dt><b>Etat :</b></dt>
                        <dd>
                            <?php
                            $status_label = '';
                            switch ($status) {
                                case 0:
                                    $status_label = "<span class='badge badge-secondary'>En attente</span>";
                                    break;
                                case 3:
                                    $status_label = "<span class='badge badge-info'>En cours</span>";
                                    break;
                                case 5:
                                    $status_label = "<span class='badge badge-success'>Fait</span>";
                                    break;
                                default:
                                    $status_label = "<span class='badge badge-secondary'>Inconnu</span>";
                                    break;
                            }
                            echo $status_label;
                            ?>
                        </dd>
                    </div>


                        <div class="inline">
                            <dt><b>Chef de mission :</b></dt>
                            <dd class="text-center">
                                <?php if (isset($manager['id'])) : ?>
                                    <div class="mt-1">
                                        <b><?php echo ucwords($manager['firstname'] . ' ' . $manager['lastname']) ?></b>
                                    </div>
                                <?php else: ?>
                                    <small><i>Gestionnaire supprimé de la base de données</i></small>
                                <?php endif; ?>
                            </dd>
                        </div>

                    </div>
                    <dt><b>Valeur approximative du contrat :</b></dt>
                    <dd><?php echo preg_replace('/(.*?):/', '<b>$1:</b>', htmlspecialchars($contract_value)) ?></dd>
                    <dt><b>Durée de la mission (mois) :</b></dt>
                    <dd><?php echo preg_replace('/(.*?):/', '<b>$1:</b>', htmlspecialchars($duration)) ?></dd>
                    <dt><b>Nombre total d’employés/mois ayant participé à la Mission :</b></dt>
                    <dd><?php echo preg_replace('/(.*?):/', '<b>$1:</b>', htmlspecialchars($employee_total_number)) ?></dd>
                    <dt><b>Valeur approximative des services offerts par notre société :</b></dt>
                    <dd><?php echo preg_replace('/(.*?):/', '<b>$1:</b>', htmlspecialchars($company_value)) ?></dd>
                    <dt><b>Nombre d’employés/mois fournis par les consultants associés :</b></dt>
                    <dd><?php echo preg_replace('/(.*?):/', '<b>$1:</b>', htmlspecialchars($consultant_employee_number)) ?></dd>
                    <dt><b>Nom et fonctions des responsables :</b></dt>
                    <dd><?php echo nl2br(preg_replace('/(.*?):/', '<b>$1:</b>', htmlspecialchars($responsible))) ?></dd>
                </dl>
                <dl class="full-width">
                    <dt><b>Descriptif du Projet :</b></dt>
                    <dd class="highlighted"><?php echo html_entity_decode($description) ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>
    
<div class="row">
            <div class="col-md-12">
            <div class="card card-outline card-primary">
    <div class="card-header">
        <span><b>Membre(s) de l'équipe:</b></span>
    </div>
    <div class="card-body">
        <?php 
        if (!empty($user_id)) :
            $members = $conn->query("SELECT *, concat(firstname,' ',lastname) as name FROM users WHERE id IN ($user_id) ORDER BY concat(firstname,' ',lastname) ASC");
            $names = [];
            while ($row = $members->fetch_assoc()) :
                $names[] = "<b>" . ucwords($row['name']) . "</b>";
            endwhile;
            echo implode(' &nbsp;&nbsp;&nbsp; | &nbsp;&nbsp;&nbsp; ', $names);
        endif;
        ?>
    </div>
</div>

<div class="col-md-12">
    <div class="card card-outline card-primary">
        <div class="card-header">
            <span class="large-text"><b>Liste des activités des consultants:</b></span>
            <div class="card-tools">
                <button class="btn btn-primary bg-gradient-primary btn-sm" type="button" id="new_activity">
                    <i class="fa fa-plus"></i> Nouvelle Activité
                </button>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="activity-list">
                <?php 
                $activitys = $conn->query("
                    SELECT 
                        activity_list.*, 
                        concat(users.firstname, ' ', users.lastname) as creator_name 
                    FROM 
                        activity_list 
                    LEFT JOIN 
                        users ON activity_list.user_id = users.id 
                    WHERE 
                        activity_list.project_id = {$id} 
                    ORDER BY 
                        activity_list.activity ASC
                ");
                while ($row = $activitys->fetch_assoc()) :
                    $desc = nl2br(html_entity_decode($row['description']));
                ?>
                    <div class="activity-item" id="activity_<?php echo $row['id']; ?>">
                        <div class="activity-details">
                            <!-- Champ du créateur avec marge inférieure -->
                            <div class="activity-creator">
                                <strong style="font-size: 1.2rem;">
                                    <?php 
                                    $date_created = date('d/m/Y H:i', strtotime($row['date_created'])); 
                                    ?>
                                    Cette activité a été Créée le <?php echo $date_created; ?> par : 
                                    <span class="creator-name"><?php echo ucwords($row['creator_name']); ?></span>
                                </strong>
                            </div>
                            
                            <!-- Champ de l'objet (activity) -->
                            <div class="activity-title">
                                <strong style="font-size: 1.2rem;">Objet :</strong>
                                <p><?php echo ucwords($row['activity']); ?></p>
                            </div>

                            <!-- Champ de la description -->
                            <div class="activity-description">
                                <strong style="font-size: 1.2rem;">Description :</strong>
                                <p><?php echo $desc; ?></p>
                            </div>
                            
                            <!-- Conteneur pour les champs de fichier -->
                            <div class="activity-file-container d-flex justify-content-between mt-3">
                                <!-- Nom du fichier à gauche -->
                                <div class="activity-file">
                                    <strong style="font-size: 1.2rem;">Nom du fichier :</strong>
                                    <p><?php echo isset($row['activity_file']) ? htmlspecialchars($row['activity_file'], ENT_QUOTES) : 'Aucun fichier associé'; ?></p>
                                </div>
                                
                                <!-- Actions pour modifier ou télécharger le fichier à droite -->
                                <div class="activity-file-actions">
                                    <strong style="font-size: 1.2rem;">Veuillez modifier ou télécharger le fichier :</strong>
                                    <div class="d-flex justify-content-end mt-2">
                                        <!-- Icône de modification -->
                                        <div class="text-center mr-3">
                                            <a class="edit_activity" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>" data-activity="<?php echo $row['activity'] ?>">
                                                <i class="fa fa-edit text-warning" style="font-size: 2.5rem;"></i>
                                            </a>
                                            <span style="display: block; font-size: 0.9rem;">Modifier</span>
                                        </div>
                                        <!-- Icône de téléchargement -->
                                        <div class="text-center">
                                            <a href="assets/activity_file/<?php echo htmlspecialchars($row['activity_file'], ENT_QUOTES); ?>" download>
                                                <i class="fa fa-download" style="font-size: 2.5rem;"></i>
                                            </a>
                                            <span style="display: block; font-size: 0.9rem;">Télécharger</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="activity-options">
                            <div class='btn-group dropleft'>
                                <button type='button' class='btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle' data-toggle='dropdown' aria-haspopup='true' aria-expanded='false'>
                                    <i class='fa fa-cog'></i> Options
                                </button>
                                <div class='dropdown-menu'>
                                    <?php if($_SESSION['login_type'] != 4): ?>
                                        <a class='dropdown-item edit_activity' href='javascript:void(0)' data-id='<?php echo $row['id'] ?>' data-activity='<?php echo $row['activity'] ?>'><i class='fa fa-edit text-warning'></i> Modifier</a>
                                        <a class='dropdown-item delete_activity' href='javascript:void(0)' data-id='<?php echo $row['id'] ?>'><i class='fa fa-trash text-danger'></i> Supprimer</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>





</div>

</div>
<style>

/* Conteneur de la carte */
.card {
    margin: 20px;
    border-radius: 5px;
}

/* Titres et contenu des tâches */
.activity-details {
    padding: 15px;
    border-bottom: 1px solid #ddd;
}

/* Style du champ du créateur */
.activity-creator strong {
    display: block;
    margin-bottom: 10px;
}

/* Champ de l'objet */
.activity-title strong {
    display: block;
    margin-bottom: 5px;
}

/* Champ de la description */
.activity-description {
    margin-top: 15px;
}

.activity-description strong {
    display: block;
    margin-bottom: 5px;
}

/* Conteneur pour les champs de fichier */
.activity-file-container {
    width: 100%;
    display: flex;
    justify-content: space-between;
    margin-top: 20px;
}

/* Champ pour le nom du fichier */
.activity-file {
    width: 45%;
}

/* Champ pour les actions sur le fichier */
.activity-file-actions {
    width: 45%;
    text-align: right;
}

/* Ajustement du texte dans les actions */
.activity-file-actions strong {
    display: block;
    margin-bottom: 5px;
}

/* Alignement des icônes */
.activity-file-actions .d-flex {
    justify-content: flex-end;
}

/* Options pour les tâches */
.activity-options {
    padding: 10px;
    text-align: right;
}

.dropdown-menu a {
    padding: 5px 15px;
    display: flex;
    align-items: center;
    font-size: 14px;
}

.dropdown-menu i {
    margin-right: 10px;
}




.activity-file-container {
    border: 1px solid #ddd; /* Bordure légère pour les conteneurs */
    padding: 10px; /* Espacement interne */
    border-radius: 5px; /* Coins arrondis */
    background-color: #f9f9f9; /* Couleur de fond légère */
}

.activity-file {
    margin-bottom: 15px; /* Espacement inférieur entre les sections */
}

.activity-file-actions {
    display: flex;
    flex-direction: column; /* Empile les éléments verticalement */
}

.activity-file-actions a {
    margin-bottom: 10px; /* Espacement entre les icônes */
}


/* Classe CSS pour augmenter la taille du texte */
.large-text {
    font-size: 20px; /* Ajustez la taille selon vos besoins */
}

.activity-creator .creator-name {
    margin-bottom: 30px; /* Espacement de 20px entre le créateur et les détails */
    color: blue;
    font-weight: bold;

}

.activity-creator {
    margin-bottom: 30px; /* Espacement de 20px entre le créateur et les détails */
    color: blue;
    font-weight: bold;


}




.activity-list {
    margin: 0;
    padding: 0;
    list-style: none;
}

.activity-item {
    display: flex;
    justify-content: space-between;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.activity-details {
    display: flex;
    flex-direction: column;
    max-width: 80%;
}

.activity-title {
    font-weight: bold;
}

.activity-description {
    margin-top: 5px;
}

.activity-options {
    display: flex;
    align-items: center;
    justify-content: flex-end;
    flex-shrink: 0;
}


.users-list>li img {
border-radius: 50%;
height: 67px;
width: 67px;
object-fit: cover;
}
.users-list>li {
width: 33.33% !important;
}
.truncate {
-webkit-line-clamp:1 !important;
}
.picture-placeholder {
display: flex;
justify-content: center;
align-items: center;
width: 67px;
height: 67px;
background-color: #007bff;
color: #ffffff;
font-size: 24px;
font-weight: bold;
border-radius: 50%;
}
.dropdown-menu .dropdown-item i.fa {
margin-right: 10px;
}
.dropdown-menu .dropdown-item.view_activity i {
color: #007bff;
}
.dropdown-menu .dropdown-item.edit_activity i {
color: #ffc107;
}
.dropdown-menu .dropdown-item.delete_activity i {
color: #dc3545;
}
.dropdown-menu .dropdown-item.manage_progress i {
color: #ffc107;
}
.dropdown-menu .dropdown-item.delete_progress i {
color: #dc3545;
}
</style>
<style>
        .print-button {
            position: absolute;
            top: 10px;
            right: 10px;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
            display: inline-flex;
            align-items: center;
            padding: 10px 20px;
            font-size: 16px;
            border-radius: 5px;
            text-decoration: none;
            transition: background-color 0.3s, box-shadow 0.3s;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
        }
        .btn-primary i {
            margin-right: 10px;
        }
    </style>
    <style>
    .project-info {
        display: flex;
        flex-wrap: wrap;
        border: 2px solid blue;
        padding: 10px;
        background-color: #b6b3d1; /* Couleur de fond mise à jour */
    }
    .project-info dl {
        width: 50%;
        margin: 0;
        padding: 10px;
        border: 1px solid blue;
    }
    .project-info dl.full-width {
        width: 100%;
    }
    .project-info dt {
        font-weight: bold;
        margin-bottom: 5px;
    }
    .project-info dd {
        margin-left: 20px;
        margin-bottom: 10px;
    }
    .project-info .inline dt,
    .project-info .inline dd {
        display: inline;
        margin: 0;
    }
    .project-info .inline dd {
        margin-left: 5px;
    }
    .project-info .flex-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .project-info .badge {
        padding: 5px;
        border-radius: 3px;
    }
    .highlighted {
        font-size: 20px; /* Augmentez la taille du texte ici */
    }
    .separator {
        margin: 0 10px;
        display: inline-block;
    }
    .margin-top {
        margin-top: 15px; /* Ajustez la valeur selon vos besoins */
    }

</style>

<script>
$(document).ready(function(){
    $('#new_activity').click(function(){
        uni_modal('Nouvelle Activité','manage_activity.php?pid=<?php echo $id ?>','mid-large');
    });

    $('.view_activity').click(function(){
        uni_modal('Détail de la Tâche','view_activity.php?id='+$(this).attr('data-id'),'mid-large');
    });

    $('.edit_activity').click(function(){
        uni_modal("Modifier l'activité",'manage_activity.php?pid=<?php echo $id ?>&id='+$(this).attr('data-id'),'mid-large');
    });

    });

    // Suppression de tâche avec SweetAlert2
    $('.delete_activity').click(function() {
        var activity_id = $(this).data('id');
        
        Swal.fire({
            title: 'Êtes-vous sûr de vouloir supprimer cette activité ?',
            text: "Cette action est irréversible !",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Poursuivre',
            cancelButtonText: 'Annuler'
        }).then((result) => {
            if (result.isConfirmed) {
                // Effectuer la suppression via AJAX
                $.ajax({
                    url: 'ajax.php?action=delete_activity',
                    method: 'POST',
                    data: { id: activity_id },
                    success: function(resp) {
                        if (resp == 1) {
                            Swal.fire(
                                'Supprimé !',
                                "L'activité a été supprimée avec succès.",
                                'success'
                            );
                            // Supprimer l'élément de la liste après suppression
                            $('#activity_' + activity_id).remove();
                        } else {
                            Swal.fire(
                                'Erreur !',
                                "Échec de la suppression de l'activité.",
                                'error'
                            );
                        }
                    }
                });
            }
        });
    });
</script>

