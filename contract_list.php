<?php include 'db_connect.php' ?>

<?php
// Définition de la fonction clean_text si elle n'existe pas encore
if (!function_exists('clean_text')) {
    function clean_text($text) {
        $text = strip_tags($text);
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
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_contract"><i class="fa fa-plus"></i> Ajouter un nouveau contract</a>
            </div>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <table class="table table-hover table-condensed" id="list">
                <colgroup>
                    <col width="5%">
                    <col width="15%">
                    <col width="25%">
                    <col width="15%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="5%">
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Nom du contrat</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Destinateur</th>
                        <th class="text-center">Date de création</th>
                        <th class="text-center">Date de mise à jour</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    if ($_SESSION['login_type'] == 1) {
                        // Afficher tous les contrats pour les administrateurs
                        $qry = $conn->query("SELECT d.*, CONCAT(u.firstname, ' ', u.lastname) as sender_name FROM contract d LEFT JOIN users u ON d.sender_id = u.id ORDER BY d.name ASC");
                    } else {
                        // Afficher uniquement les contrats associés au Destinateur ou Destinataire pour les autres types
                        $user_id = $_SESSION['login_id']; // Assurez-vous que cette variable contient l'ID de l'utilisateur connecté
                        $qry = $conn->query("SELECT d.*, CONCAT(u.firstname, ' ', u.lastname) as sender_name FROM contract d LEFT JOIN users u ON d.sender_id = u.id WHERE d.sender_id = '$user_id' OR FIND_IN_SET('$user_id', d.recipient_id) > 0 ORDER BY d.name ASC");
                    }

                    while ($row = $qry->fetch_assoc()):
                        $name = clean_text($row['name']);
                        $description = clean_text($row['description']);
                        $sender_name = clean_text($row['sender_name']);
                        $date_created = clean_text($row['date_created']);
                        $date_updated = clean_text($row['date_updated']);
                    ?>
                    <tr>
                        <th class="text-center"><?php echo $i++ ?></th>
                        <td><div class="text-truncate-5"><?php echo $name ?></div></td>
                        <td><div class="text-truncate-5"><?php echo $description ?></div></td>
                        <td><?php echo $sender_name ?></td>
                        <td><?php echo $date_created ?></td>
                        <td><?php echo $date_updated ?></td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <i class="fas fa-cog"></i> Options
                                </button>
                                <div class="dropdown-menu" style="">
                                    <a class="dropdown-item view_contract" href="./index.php?page=view_contract&id=<?php echo $row['contract_id'] ?>" data-id="<?php echo $row['contract_id'] ?>"><i class="fas fa-eye text-primary"></i> Consulter</a>
                                    <div class="dropdown-divider"></div>
                                    <?php if ($_SESSION['login_type'] != 11): ?>
                                    <a class="dropdown-item" href="./index.php?page=edit_contract&id=<?php echo $row['contract_id'] ?>"><i class="fas fa-edit text-warning"></i> Modifier</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_contract" href="javascript:void(0)" data-id="<?php echo $row['contract_id'] ?>"><i class="fas fa-trash-alt text-danger"></i> Supprimer</a>
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
    
        $('.delete_contract').click(function(){
            _conf("Êtes-vous sûr de vouloir supprimer ce contract?", "delete_contract", [$(this).attr('data-id')]);
        });
    });

    function delete_contract(id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_contract',
            method: 'POST',
            data: { id: id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("contract supprimé avec succès", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                } else {
                    alert_toast("Erreur lors de la suppression du contract", 'error');
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

<!-- CSS pour tronquer le texte après 5 lignes -->
<style>
.text-truncate-5 {
    display: -webkit-box;
    -webkit-line-clamp: 5;
    -webkit-box-orient: vertical;  
    overflow: hidden;
    text-overflow: ellipsis;
    word-wrap: break-word; /* Ensure long words are wrapped */
}
</style>
