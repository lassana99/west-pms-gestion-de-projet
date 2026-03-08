<?php include 'db_connect.php' ?>

<?php
if (!function_exists('clean_text')) {
    function clean_text($text) {
        $text = (string) ($text ?? '');
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
            <?php if (in_array($_SESSION['login_type'], [1, 2, 3, 5, 6, 7, 8, 9, 10])): ?>
            <div class="card-tools">
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_iso_bureau"><i class="fa fa-plus"></i> Ajouter un nouveau document</a>
            </div>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <table class="table table-hover table-condensed" id="list">
                <colgroup>
                    <col width="5%">
                    <col width="20%">
                    <col width="15%">
                    <col width="25%">
                    <col width="15%">
                    <col width="15%">
                    <col width="5%">
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Nom de la fiche</th>
                        <th class="text-center">Propriétaire</th>
                        <th class="text-center">Description</th>
                        <th class="text-center">Date de création</th>
                        <th class="text-center">Date de mise à jour</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $qry = $conn->query("SELECT * FROM iso_bureau_list ORDER BY date_created DESC");

                    while ($row = $qry->fetch_assoc()):
                        $attached_name = clean_text($row['attached_name']);
                        $user_name = clean_text($row['user_name']);
                        $description = clean_text($row['description']);
                        $date_created = clean_text($row['date_created']);
                        $date_updated = clean_text($row['date_updated']);
                    ?>
                    <tr>
                        <th class="text-center"><?php echo $i++ ?></th>
                        <td><div class="text-truncate-5"><?php echo $attached_name ?></div></td>
                        <td><div class="text-truncate-5"><?php echo $user_name ?></div></td>
                        <td><div class="text-truncate-5"><?php echo $description ?></div></td>
                        <td><?php echo $date_created ?></td>
                        <td><?php echo $date_updated ?></td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <i class="fas fa-cog"></i> Options
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item view_iso_bureau" href="./index.php?page=view_iso_bureau&id=<?php echo $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><i class="fas fa-eye text-primary"></i> Consulter</a>
                                    <div class="dropdown-divider"></div>
                                    <?php if ($_SESSION['login_type'] != 11): ?>
                                    <a class="dropdown-item" href="./index.php?page=edit_iso_bureau&id=<?php echo $row['id'] ?>"><i class="fas fa-edit text-warning"></i> Modifier</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_iso_bureau" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fas fa-trash-alt text-danger"></i> Supprimer</a>
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
                "search": "Filtrer",
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

        $('.delete_iso_bureau').click(function(){
            _conf("Êtes-vous sûr de vouloir supprimer ce document?", "delete_iso_bureau", [$(this).attr('data-id')]);
        });
    });

    function delete_iso_bureau(id) {
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_iso_bureau',
            method: 'POST',
            data: { id: id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Document supprimé avec succès", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1500);
                } else {
                    alert_toast("Erreur lors de la suppression du document", 'error');
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
    word-wrap: break-word;
}
</style>
