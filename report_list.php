<?php include 'db_connect.php' ?>

<?php
// Définition de la fonction clean_text si elle n'existe pas encore
if (!function_exists('clean_text')) {
    function clean_text($text) {
        // Vérifie si $text est nul ou vide
        if (empty($text)) {
            return ''; // Retourne une chaîne vide si $text est nul ou vide
        }

        $text = strip_tags($text); // Applique strip_tags uniquement si $text est valide
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = htmlspecialchars_decode($text, ENT_QUOTES);
        return $text;
    }
}

?>

<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <?php if ($_SESSION['login_type'] == 1): ?>
            <div class="card-tools">
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_report"><i class="fa fa-plus"></i> Ajouter un nouveau rapport</a>
            </div>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <table class="table table-hover table-condensed" id="list">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="15%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="5%">
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Nom du rapport</th>
                        <th class="text-center">Destinateur</th>
                        <th class="text-center">Date de création</th>
                        <th class="text-center">Date de mise à jour</th>
                        <th class="text-center">Date de validation par la D.O</th> <!-- Nouvelle colonne -->
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                <?php
                    $i = 1;
                    $user_id = $_SESSION['login_id'];
                    $login_type = $_SESSION['login_type'];

                    // Requête SQL en fonction du type de l'utilisateur
                    if (in_array($login_type, [1, 2, 3, 4, 5, 6, 7])) {
                        $qry = $conn->query("
                            SELECT d.*, CONCAT(u.firstname, ' ', u.lastname) as sender_name 
                            FROM report d 
                            LEFT JOIN users u ON d.sender_id = u.id 
                            ORDER BY d.name ASC
                        ");
                    } elseif ($login_type == 9) {
                        $qry = $conn->query("
                            SELECT d.*, CONCAT(u.firstname, ' ', u.lastname) as sender_name 
                            FROM report d 
                            LEFT JOIN users u ON d.sender_id = u.id 
                            WHERE d.sender_id = '$user_id' OR FIND_IN_SET('$user_id', d.recipient_id) > 0 
                            ORDER BY d.name ASC
                        ");
                    } else {
                        $qry = false;
                    }

                    if ($qry):
                        while ($row = $qry->fetch_assoc()):
                            $name = clean_text($row['name']);
                            $sender_name = clean_text($row['sender_name']);
                            $date_created = clean_text($row['date_created']);
                            $date_updated = clean_text($row['date_updated']);
                            $date_validation = clean_text($row['date_validation']);
                ?>
                <tr>
                    <th class="text-center"><?php echo $i++ ?></th>
                    <td><span class="truncate-text"><?php echo $name ?></span></td>
                    <td><?php echo $sender_name ?></td>
                    <td><?php echo $date_created ?></td>
                    <td><?php echo $date_updated ?></td>
                    <td><?php echo $date_validation ?></td>
                    <td class="text-center">
                        <div class="dropdown">
                            <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                <i class="fas fa-cog"></i> Options
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item view_report" href="./index.php?page=view_report&id=<?php echo $row['report_id'] ?>" data-id="<?php echo $row['report_id'] ?>">
                                    <i class="fas fa-eye text-primary"></i> Consulter
                                </a>
                                <?php if ($_SESSION['login_type'] != 11): ?>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="./index.php?page=edit_report&id=<?php echo $row['report_id'] ?>">
                                    <i class="fas fa-edit text-warning"></i> Modifier
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item delete_report" href="javascript:void(0)" data-id="<?php echo $row['report_id'] ?>">
                                    <i class="fas fa-trash-alt text-danger"></i> Supprimer
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php 
                        endwhile; 
                    else: 
                ?>
                <tr><td colspan="7" class="text-center">Aucun rapport trouvé.</td></tr>
            <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>


<!-- Styles pour tronquer le texte -->
<style>
    .truncate-text {
        display: -webkit-box;
        -webkit-line-clamp: 5; /* Limite le texte à 5 lignes */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: normal; /* Pour s'assurer que le texte est sur plusieurs lignes */
    }
</style>

<!-- JavaScript pour DataTables -->
<script>
    $(document).ready(function(){
        $('#list').dataTable({
            "language": {
                "search": "Filtrer", // Remplace "Search" par "Filtrer"
                "lengthMenu": "Afficher _MENU_ entrées",
                "zeroRecords": "Aucun enregistrement correspondant trouvé",
                "info": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
                "infoEmpty": "Affichage de 0 à 0 sur 0 entrées",
                "infoFiltered": "(filtré à partir de _MAX_ entrées au total)",
                "paginate": {
                    "first": "Premier",
                    "last": "Dernier",
                    "next": "Suivant",
                    "previous": "Précédent"
                }
            }
        });
    
        $('.delete_report').click(function(){
            _conf("Êtes-vous sûr de vouloir supprimer ce rapport?", "delete_report", [$(this).attr('data-id')]);
        });
    });

    function delete_report(id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_report',
            method: 'POST',
            data: { id: id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("rapport supprimé avec succès", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                } else {
                    alert_toast("Erreur lors de la suppression du rapport", 'error');
                    end_load();
                }
            },
            error: function() {
                alert_toast("Une erreur s'est produite", 'error');
                end_load();
            }
        });
    }
</script>
