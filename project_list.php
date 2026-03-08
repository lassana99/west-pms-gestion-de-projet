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
    
<style>
    #list_wrapper .dataTables_length {
        margin-left: 12px;
    }
    #list_wrapper .dataTables_info {
        margin-left: 12px;
    }
</style>


<div class="col-lg-12 ">
    <div class="card card-outline card-success">
        <div class="card-header">
            <?php if ($_SESSION['login_type'] == 1): ?>
            <div class="card-tools">
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_project"><i class="fa fa-plus"></i> Ajouter un nouveau projet</a>
            </div>
            <?php endif; ?>
        </div>
        <div div class="card-body" style="padding-left: 0; margin-left: -10px;">
            <table class="table table-hover table-condensed" id="list">
                <colgroup>
                    <col width="3%">
                    <col width="25%">
                    <col width="20%">
                    <col width="20%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                    <col width="10%">
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Projet</th>
                        <th>Domaine</th>
                        <th>Mission</th>
                        <th>Date de démarrage</th>
                        <th>Date d’échéance</th>
                        <th class="text-center">Etat</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    // Tableau des statuts
                    $stat = array(
                        0 => "En attente",
                        3 => "En cours",
                        5 => "Fait"
                    );
                    $where = "";

                    // Condition pour les utilisateurs de type 8, 9 et 10
                    if (in_array($_SESSION['login_type'], [8, 9, 10])) {
                        $where = " WHERE (p.manager_id = {$_SESSION['login_id']} 
                                   OR CONCAT('[', REPLACE(p.user_id, ',', '],['), ']') LIKE '%[{$_SESSION['login_id']}]%')";
                    }

                    // Exécuter la requête principale
                    $qry = $conn->query("SELECT p.*, d.name as domain_name, m.name as mission_name 
                                         FROM project_list p 
                                         LEFT JOIN domain d ON p.domain_id = d.domain_id 
                                         LEFT JOIN mission m ON p.mission_id = m.mission_id 
                                         $where 
                                         ORDER BY p.name ASC");

                    // Vérification si la requête a réussi
                    if ($qry && $qry->num_rows > 0):
                        while ($row = $qry->fetch_assoc()):
                            $name = clean_text($row['name']);
                            $desc = clean_text($row['description']);
                            // Exécuter les requêtes pour calculer la progression
                            $tprog = $conn->query("SELECT * FROM activity_list WHERE project_id = {$row['id']}");
                    ?>
                    <tr>
                        <th class="text-center"><?php echo $i++ ?></th>
                        <td>
                            <p><b><?php echo $name ?></b></p>
                            <p class="truncate"><?php echo $desc ?></p>
                        </td>
                        <td><b><?php echo htmlspecialchars($row['domain_name'] ?? '') ?></b></td>
                        <td><b><?php echo htmlspecialchars($row['mission_name'] ?? '') ?></b></td>
                        <td><b><?php echo $row['start_date'] != '0000-00-00' ? date("d/m/Y", strtotime($row['start_date'])) : '' ?></b></td>
                        <td><b><?php echo $row['end_date'] != '0000-00-00' ? date("d/m/Y", strtotime($row['end_date'])) : '' ?></b></td>
                        <td class="text-center">
                            <?php
                            $status = intval($row['status']);
                            $status_label = isset($stat[$status]) ? $stat[$status] : "En attente";
                            switch ($status_label) {
                                case 'En attente':
                                    echo "<span class='badge badge-secondary'>$status_label</span>";
                                    break;
                                case 'En cours':
                                    echo "<span class='badge badge-info'>$status_label</span>";
                                    break;
                                case 'Fait':
                                    echo "<span class='badge badge-success'>$status_label</span>";
                                    break;
                                default:
                                    echo "<span class='badge badge-secondary'>En attente</span>";
                            }
                            ?>
                        </td>
                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <i class="fas fa-cog"></i> Options
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item view_project" href="./index.php?page=view_project&id=<?php echo $row['id'] ?>" data-id="<?php echo $row['id'] ?>"><i class="fas fa-eye text-primary"></i> Consulter</a>
                                    <div class="dropdown-divider"></div>
                                    <?php if ($_SESSION['login_type'] != 4): ?>
                                    <a class="dropdown-item" href="./index.php?page=edit_project&id=<?php echo $row['id'] ?>"><i class="fas fa-edit text-warning"></i> Modifier</a>
                                    <div class="dropdown-divider"></div>
                                    <a class="dropdown-item delete_project" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fas fa-trash-alt text-danger"></i> Supprimer</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                    <?php endwhile; else: ?>
                    <tr>
                        <td colspan="8" class="text-center">Aucun projet trouvé.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

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

        $('.delete_project').click(function(){
            _conf("Êtes-vous sûr de supprimer ce projet?", "delete_project", [$(this).attr('data-id')])
        });
    });

    function delete_project($id){
        start_load();
        $.ajax({
            url: 'ajax.php?action=delete_project',
            method: 'POST',
            data: {id: $id},
            success: function(resp){
                if (resp == 1) {
                    alert_toast("Données supprimées avec succès", 'success');
                    setTimeout(function(){
                        location.reload();
                    }, 1000);
                }
            }
        });
    }
</script>
