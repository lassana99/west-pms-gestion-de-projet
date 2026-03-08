<?php include 'db_connect.php' ?>

<div class="col-lg-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <div class="card-tools">
                <a class="btn btn-block btn-sm btn-default btn-flat border-primary" href="./index.php?page=new_user">
                    <i class="fa fa-plus"></i> Ajouter un Nouvel Utilisateur
                </a>
            </div>
        </div>
        <div class="card-body">
            <table class="table table-hover table-bordered" id="list">
                <colgroup>
                    <col width="5%">
                    <col width="20%">
                    <col width="15%">
                    <col width="20%">
                    <col width="10%">
                    <col width="25%">
                    <col width="5%">
                </colgroup>
                <thead>
                    <tr>
                        <th class="text-center">#</th>
                        <th>Nom</th>
                        <th>Téléphone</th>
                        <th>Email</th>
                        <th>Rôle</th>
                        <th>Domaine</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $type = array('', "Administrateur", "Assistante de Direction", "Coordonnateur Technique", "Direction des Finances", "Directeur Technique", "Directeur des Opérations", "Département Opérations", "Département Recherche & Développement", "Expert Technique", "Chef de Mission", "Consultant");
                    // Récupérer le type d'utilisateur connecté
                    $connected_user_type = $_SESSION['login_type'];
                    $allowed_roles = array(); // Initialiser un tableau vide pour les rôles autorisés
                    
                    // Déterminer les rôles autorisés en fonction du type d'utilisateur connecté
                    if ($connected_user_type == 1) {
                        // Si l'utilisateur connecté est un administrateur, tous les rôles sont autorisés
                        $allowed_roles = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11);
                    } elseif (in_array($connected_user_type, [3, 5, 6, 7, 8]))
                    {
                        // Si l'utilisateur connecté n'est pas un administrateur, tous les rôles lui sont autorisés sauf celui de l'administrateur
                        $allowed_roles = array(2, 3, 4, 5, 6, 7, 8, 9, 10, 11);
                    }
                    
                    // Récupérer et afficher uniquement les utilisateurs dont le rôle est autorisé pour l'utilisateur connecté
                    $qry = $conn->query("SELECT u.*, CONCAT(u.firstname, ' ', u.lastname) as name, u.telephone, d.name as domain_name FROM users u LEFT JOIN domain d ON u.domain_id = d.domain_id WHERE u.type IN (".implode(',', $allowed_roles).") ORDER BY CONCAT(u.firstname, ' ', u.lastname) ASC");
                    while ($row = $qry->fetch_assoc()):
                    ?>
                    <tr>
                        <th class="text-center"><?php echo $i++ ?></th>
                        <td><b><?php echo ucwords($row['name']) ?></b></td>
                        <td><b><?php echo $row['telephone'] ?></b></td> <!-- Affichage du numéro de téléphone -->
                        <td><b><?php echo $row['email'] ?></b></td>
                        <td><b><?php echo $type[$row['type']] ?></b></td>
                        <td><b><?php echo $row['domain_name'] ?></b></td> <!-- Colonnes pour le nom de domaine -->
                        <td class="text-center">
                            <div class="dropdown">
                                <button type="button" class="btn btn-default btn-sm btn-flat border-info wave-effect text-info dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                                  <i class="fas fa-cog"></i> Options
                                </button>
                                <div class="dropdown-menu" style="">
                                  <a class="dropdown-item view_user" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fas fa-eye text-primary"></i> Consulter</a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item" href="./index.php?page=edit_user&id=<?php echo $row['id'] ?>"><i class="fas fa-edit text-success"></i> Modifier</a>
                                  <div class="dropdown-divider"></div>
                                  <a class="dropdown-item delete_user" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>"><i class="fas fa-trash-alt text-danger"></i> Supprimer</a>
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
    $(document).ready(function(){
        $('#list').dataTable({
            "language": {
                "search": "Filtrer",
                "info": "Affichage de _START_ à _END_ sur _TOTAL_ entrées",
                "infoEmpty": "Affichage de 0 à 0 sur 0 entrées",
                "infoFiltered": "(filtré à partir de _MAX_ entrées au total)",
                "lengthMenu": "Afficher _MENU_ entrées", // Remplacement de "Show" par "Afficher" et "entrées" par "entrées"
                "paginate": {
                    "previous": "Précédent",
                    "next": "Suivant"
                },
                "zeroRecords": "Aucun enregistrement correspondant trouvé",
                "emptyTable": "Aucune donnée disponible dans le tableau",
                "loadingRecords": "Chargement...",
                "processing": "Traitement...",
                "thousands": " ",
                "aria": {
                    "sortAscending": ": activer pour trier la colonne par ordre croissant",
                    "sortDescending": ": activer pour trier la colonne par ordre décroissant"
                }
            }
        });
        
        $('.view_user').click(function(){
            uni_modal("<i class='fa fa-id-card'></i> Détails de l'utilisateur","view_user.php?id="+$(this).attr('data-id'))
        });
        
        $('.delete_user').click(function(){
            _conf("Êtes-vous sûr de vouloir supprimer cet utilisateur?","delete_user",[$(this).attr('data-id')])
        });
    });
    
    function delete_user($id){
        start_load();
        $.ajax({
            url:'ajax.php?action=delete_user',
            method:'POST',
            data:{id:$id},
            success:function(resp){
                if(resp == 1){
                    alert_toast("Données supprimées avec succès",'success');
                    setTimeout(function(){
                        location.reload();
                    },1500);
                }
            }
        });
    }
</script>
