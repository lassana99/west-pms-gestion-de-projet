<?php include 'db_connect.php' ?>

<?php
// Définition de la fonction clean_text si elle n'existe pas encore
if (!function_exists('clean_text')) {
    function clean_text($text) {
        if ($text === null) {
            return ''; // Si le texte est null, renvoyer une chaîne vide
        }
        $text = strip_tags($text);
        $text = html_entity_decode($text, ENT_QUOTES, 'UTF-8');
        $text = htmlspecialchars_decode($text, ENT_QUOTES);
        return $text;
    }
}

?>

<div class="col-lg-12">
    <div class="card card-outline card-success">
                <div class="card-body">
            <table class="table table-hover table-condensed" id="list">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="15%">
                    <col width="10%">
                    <col width="12%">
                    <col width="10%">
                    <col width="10%">
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
                    $user_id = $_SESSION['login_id']; // Assurez-vous que cette variable contient l'ID de l'utilisateur connecté
                    $login_type = $_SESSION['login_type']; // Type d'utilisateur
                    
                    if (in_array($login_type, [1, 2, 3, 4, 5, 6, 7])) {
                        // Afficher tous les rapports pour les utilisateurs autorisés
                        $qry = $conn->query("SELECT d.*, CONCAT(u.firstname, ' ', u.lastname) as sender_name FROM report_archive d LEFT JOIN users u ON d.sender_id = u.id ORDER BY d.date_validation DESC, d.name ASC");
                    } elseif ($login_type == 9) {
                        // Afficher uniquement les rapports associés au Destinateur ou Destinataire
                        $qry = $conn->query("SELECT d.*, CONCAT(u.firstname, ' ', u.lastname) as sender_name FROM report_archive d LEFT JOIN users u ON d.sender_id = u.id WHERE d.sender_id = '$user_id' OR FIND_IN_SET('$user_id', d.recipient_id) > 0 ORDER BY d.date_validation DESC, d.name ASC");
                    } else {
                        // Aucun rapport pour les autres types
                        $qry = [];
                    }
                    

                    while ($row = $qry->fetch_assoc()):
                        $name = clean_text($row['name']);
                        $sender_name = clean_text($row['sender_name']);
                        $date_created = clean_text($row['date_created']);
                        $date_updated = clean_text($row['date_updated']);
                        $date_validation = clean_text($row['date_validation']); // Nouvelle variable pour la date de validation
                    ?>
                    <tr>
                        <th class="text-center"><?php echo $i++ ?></th>
                        <td><span class="truncate-text"><?php echo $name ?></span></td>
                        <td><?php echo $sender_name ?></td>
                        <td><?php echo $date_created ?></td>
                        <td><?php echo $date_updated ?></td>
                        <td><?php echo $date_validation ?></td> <!-- Affiche la date de validation -->
                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <i class="fas fa-cog"></i> Options
                                </button>
                                <div class="dropdown-menu" style="">
                                    <a class="dropdown-item view_report" href="./index.php?page=view_report_archive&id=<?php echo $row['report_id'] ?>" data-id="<?php echo $row['report_id'] ?>"><i class="fas fa-eye text-primary"></i> Consulter</a>
                                    <div class="dropdown-divider"></div>
                                    <?php if ($_SESSION['login_type'] != 11): ?>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_report_archive" href="javascript:void(0)" data-id="<?php echo $row['report_id'] ?>"><i class="fas fa-trash-alt text-danger"></i> Supprimer</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>    
                    <?php endwhile; ?>
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
    
        $('.delete_report_archive').click(function(){
            _conf("Êtes-vous sûr de vouloir supprimer ce rapport archivé?", "delete_report_archive", [$(this).attr('data-id')]);
        });
    });

    function delete_report_archive(id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_report_archive',
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
