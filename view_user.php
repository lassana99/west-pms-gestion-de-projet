<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
    $type_arr = array('', "Administrateur", "Assistante de Direction", "Coordonnateur Technique", "Direction des Finances", "Directeur Technique", "Directeur des Opérations", "Equipe des Opérations", "Expert Technique", "Chef de Mission", "Consultant" , "Technicien(ne)");
    $qry = $conn->query("SELECT u.*, concat(u.firstname,' ',u.lastname) as name, d.name as domain_name 
                         FROM users u 
                         LEFT JOIN domain d ON u.domain_id = d.domain_id 
                         WHERE u.id = ".$_GET['id'])->fetch_array();
    foreach($qry as $k => $v){
        $$k = $v;
    }
}
?>
<div class="container-fluid">
    <div class="card card-widget widget-user shadow">
        <div class="widget-user-header bg-dark">
            <h3 class="widget-user-username"><?php echo ucwords($name) ?></h3>
            <h5 class="widget-user-desc"><?php echo $email ?></h5>
        </div>
        <div class="widget-user-image">
            <?php if(empty($picture) || (!empty($picture) && !is_file('assets/uploads/'.$picture))): ?>
                <span class="brand-image img-circle elevation-2 d-flex justify-content-center align-items-center bg-primary text-white font-weight-500" style="width: 90px;height:90px"><h4><?php echo strtoupper(substr($firstname, 0,1).substr($lastname, 0,1)) ?></h4></span>
            <?php else: ?>
                <img class="img-circle elevation-2" src="assets/uploads/<?php echo $picture ?>" alt="Photos"  style="width: 90px;height:90px;object-fit: cover">
            <?php endif ?>
        </div>
        <div class="card-footer">
            <div class="container-fluid">
                <dl>
                    <dt>Rôle</dt>
                    <dd><?php echo $type_arr[$type] ?></dd>
                    <dt>Domaine</dt>
                    <dd><?php echo isset($domain_name) ? $domain_name : 'Non spécifié' ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer display p-0 m-0">
    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
</div>
<style>
    #uni_modal .modal-footer{
        display: none
    }
    #uni_modal .modal-footer.display{
        display: flex
    }
</style>
