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

// Fonction pour tronquer le texte après un certain nombre de mots
if (!function_exists('truncate_words')) {
    function truncate_words($text, $limit) {
        $words = explode(' ', $text);
        if (count($words) > $limit) {
            return implode(' ', array_slice($words, 0, $limit)) . '...';
        }
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

<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div div class="card-body" style="padding-left: 0; margin-left: -5px;">
            <table class="table table-hover table-condensed" id="list">
                <colgroup>
                    <col width="3%">
                    <col width="25%">
                    <col width="25%">
                    <col width="15%">
                    <col width="15%">
                    <col width="15%">
                    <col width="8%">
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Activité</th>
                        <th class="text-center">Projet</th>
                        <th class="text-center">Domaine</th>
                        <th class="text-center">Date de démarrage</th>
                        <th class="text-center">Date d’achèvement</th>
                        <th class="text-center">Etat du Projet</th>
                        <th class="text-center">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $where = "";

                    if (in_array($_SESSION['login_type'], [8, 9, 10])) {
                        $where = " WHERE p.manager_id = '{$_SESSION['login_id']}' 
                                    OR concat('[', REPLACE(p.user_id, ',', ']['), ']') LIKE '%[{$_SESSION['login_id']}]%' ";
                    }

                    $qry = $conn->query("SELECT t.*, p.name as pname, p.start_date, p.status as pstatus, p.end_date, p.id as pid, d.name as domain_name 
                                         FROM activity_list t 
                                         INNER JOIN project_list p ON p.id = t.project_id 
                                         INNER JOIN domain d ON d.domain_id = p.domain_id 
                                         $where 
                                         ORDER BY p.name ASC");
                    while ($row = $qry->fetch_assoc()):
                        $description = clean_text($row['description']);
                        $truncated_description = truncate_words($description, 15);
                        
                        $status_label = "";
                        switch ($row['pstatus']) {
                            case 0:
                                $status_label = "En attente";
                                $badge_class = "badge-secondary";
                                break;
                            case 3:
                                $status_label = "En cours";
                                $badge_class = "badge-info";
                                break;
                            case 5:
                                $status_label = "Fait";
                                $badge_class = "badge-success";
                                break;
                            default:
                                $status_label = "Inconnu";
                                $badge_class = "badge-secondary";
                                break;
                        }
                    ?>
                    <tr>
                        <td class="text-center"><?php echo $i++ ?></td>
                        <td>
                            <p><b><?php echo htmlspecialchars(ucwords($row['activity']), ENT_QUOTES) ?></b></p>
                            <p class="truncate"><?php echo nl2br($truncated_description) ?></p>
                        </td>
                        <td>
                            <p><b><?php echo htmlspecialchars(ucfirst(strtolower($row['pname'])), ENT_QUOTES) ?></b></p>
                        </td>
                        <td>
                            <p><b><?php echo htmlspecialchars(ucfirst(strtolower($row['domain_name'])), ENT_QUOTES) ?></b></p>
                        </td>
                        <td><b><?php echo $row['start_date'] != '0000-00-00' ? date("d/m/Y", strtotime($row['start_date'])) : '' ?></b></td>
                        <td><b><?php echo $row['end_date'] != '0000-00-00' ? date("d/m/Y", strtotime($row['end_date'])) : '' ?></b></td>
                        <td class="text-center">
                            <span class='badge <?php echo $badge_class; ?>'><?php echo $status_label; ?></span>
                        </td>
                        <td class="text-center">
                            <div class="btn-group dropleft">
                                <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                    <i class="fa fa-cogs"></i> Option
                                </button>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item view_project" href="./index.php?page=view_project&id=<?php echo $row['pid'] ?>" data-id="<?php echo $row['pid'] ?>"><i class="fa fa-eye text-primary"></i> Consulter</a>
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

<script>
    $(document).ready(function() {
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
    });

    function delete_project($id) {
        start_load()
        $.ajax({
            url: 'ajax.php?action=delete_project',
            method: 'POST',
            data: { id: $id },
            success: function(resp) {
                if (resp == 1) {
                    alert_toast("Données supprimées avec succès", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 1500)
                }
            }
        })
    }
</script>
