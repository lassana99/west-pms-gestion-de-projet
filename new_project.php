<?php 
if (!isset($conn)) { 
    include 'db_connect.php'; 
} 
include "header.php";
?>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="" id="manage-project">
                <input type="hidden" name="id" value="<?php echo isset($id) ? htmlspecialchars($id) : '' ?>">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Champs à gauche -->
                        <div class="form-group">
                            <label for="" class="control-label">Nom du projet</label>
                            <textarea class="form-control form-control-sm" name="name" rows="2"><?php echo isset($name) ? htmlspecialchars($name) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Mission</label>
                            <select name="mission_id" id="mission" class="custom-select custom-select-sm select2" style="font-size: 18px;">
                                <option value="" disabled selected>Veuillez sélectionner la mission ici</option>
                                <option value="1" <?php echo isset($mission_id) && $mission_id == 1 ? 'selected' : '' ?>>Mission de Contrôle</option>
                                <option value="2" <?php echo isset($mission_id) && $mission_id == 2 ? 'selected' : '' ?>>Mission d'Etude</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Domaine</label>
                            <select name="domain_id" id="domain" class="custom-select custom-select-sm select2" style="font-size: 18px;">
                                <option value="" disabled selected>Veuillez sélectionner le domaine ici</option>
                                <option value="1" <?php echo isset($domain_id) && $domain_id == 1 ? 'selected' : '' ?>>Infrastructures de transport et génie civil</option>
                                <option value="2" <?php echo isset($domain_id) && $domain_id == 2 ? 'selected' : '' ?>>Habitat et aménagements urbains</option>
                                <option value="3" <?php echo isset($domain_id) && $domain_id == 3 ? 'selected' : '' ?>>Approvisionnement en eau et assainissement</option>
                                <option value="4" <?php echo isset($domain_id) && $domain_id == 4 ? 'selected' : '' ?>>Développement agricole et rural</option>
                                <option value="5" <?php echo isset($domain_id) && $domain_id == 5 ? 'selected' : '' ?>>Énergie et Electrification rurale</option>
                                <option value="6" <?php echo isset($domain_id) && $domain_id == 6 ? 'selected' : '' ?>>Environnement et Aménagement du territoire</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Pays</label>
                            <select name="country_id" id="country" class="custom-select custom-select-sm select2" style="font-size: 18px;">
                                <option value="" disabled selected>Veuillez sélectionner le pays ici</option>
                                <?php 
                                $countries = $conn->query("SELECT * FROM country");
                                while ($row = $countries->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $row['country_id'] ?>" <?php echo isset($country_id) && $country_id == $row['country_id'] ? "selected" : '' ?>><?php echo htmlspecialchars($row['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Lieu</label>
                            <textarea class="form-control form-control-sm" name="place" rows="2"><?php echo isset($place) ? htmlspecialchars($place) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Nom de l’Autorité contractante (Bailleur)</label>
                            <textarea class="form-control form-control-sm" name="lessor" rows="2"><?php echo isset($lessor) ? htmlspecialchars($lessor) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Adresse</label>
                            <textarea class="form-control form-control-sm" name="address" rows="2"><?php echo isset($address) ? htmlspecialchars($address) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Date de début</label>
                            <input type="date" class="form-control form-control-sm" autocomplete="off" name="start_date" value="<?php echo isset($start_date) ? date("Y-m-d", strtotime($start_date)) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Date de fin</label>
                            <input type="date" class="form-control form-control-sm" autocomplete="off" name="end_date" value="<?php echo isset($end_date) ? date("Y-m-d", strtotime($end_date)) : '' ?>">
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Noms des consultants associés/partenaires éventuels</label>
                            <textarea class="form-control form-control-sm" name="consultant_partner" rows="2"><?php echo isset($consultant_partner) ? htmlspecialchars($consultant_partner) : '' ?></textarea>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Champs à droite -->
                        <div class="form-group">
                            <label for="">Etat</label>
                            <select name="status" class="custom-select">
                                <option value="0" <?= isset($status) && $status == 0 ? 'selected' : '' ?>>En attente</option>
                                <option value="3" <?= isset($status) && $status == 3 ? 'selected' : '' ?>>En cours</option>
                                <option value="5" <?= isset($status) && $status == 5 ? 'selected' : '' ?>>Fait</option>
                            </select>

                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Valeur approximative du contrat</label>
                            <textarea class="form-control form-control-sm" name="contract_value" rows="2"><?php echo isset($contract_value) ? htmlspecialchars($contract_value) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Durée de la mission (mois)</label>
                            <textarea class="form-control form-control-sm" name="duration" rows="2"><?php echo isset($duration) ? htmlspecialchars($duration) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Nombre total d’employés/mois ayant participé à la Mission</label>
                            <textarea class="form-control form-control-sm" name="employee_total_number" rows="2"><?php echo isset($employee_total_number) ? htmlspecialchars($employee_total_number) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Valeur approximative des services offerts par notre société</label>
                            <textarea class="form-control form-control-sm" name="company_value" rows="2"><?php echo isset($company_value) ? htmlspecialchars($company_value) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Nombre d’employés/mois fournis par les consultants associés</label>
                            <textarea class="form-control form-control-sm" name="consultant_employee_number" rows="2"><?php echo isset($consultant_employee_number) ? htmlspecialchars($consultant_employee_number) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Nom et fonctions des responsables</label>
                            <textarea class="form-control form-control-sm" name="responsible" rows="2"><?php echo isset($responsible) ? htmlspecialchars($responsible) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Chef de mission</label>
                            <select class="form-control form-control-sm select2" name="manager_id">
                                <option></option>
                                <?php 
                                $managers = $conn->query("SELECT *, CONCAT(firstname,' ',lastname) as name FROM users WHERE type = 9 ORDER BY CONCAT(firstname,' ',lastname) ASC ");
                                while ($row = $managers->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($manager_id) && $manager_id == $row['id'] ? "selected" : '' ?>><?php echo htmlspecialchars($row['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Membres de l'équipe du projet</label>
                            <select class="form-control form-control-sm select2" multiple="multiple" name="user_id[]">
                                <option></option>
                                <?php 
                                $employees = $conn->query("SELECT *, CONCAT(firstname,' ',lastname) as name FROM users WHERE type IN (7, 8, 10) ORDER BY CONCAT(firstname,' ',lastname) ASC ");
                                while ($row = $employees->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($user_id) && in_array($row['id'], explode(',', $user_id)) ? "selected" : '' ?>><?php
                                    echo htmlspecialchars($row['name']) ?></option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-10">
                            <div class="form-group">
                                <label for="" class="control-label">Description</label>
                                <textarea name="description" id="description" cols="30" rows="10" class="summernote form-control"><?php echo isset($description) ? htmlspecialchars($description) : '' ?></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-footer border-top border-info">
                <div class="d-flex w-100 justify-content-center align-items-center">
                    <button class="btn btn-flat bg-gradient-primary mx-2" form="manage-project">Sauvegarder</button>
                    <button class="btn btn-flat bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=project_list'">Annuler</button>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    $(document).ready(function() {
        $('.select2').select2({
            placeholder: 'Select an option',
            allowClear: true,
            theme: 'bootstrap4'
        });

        function removeDefaultOption(selectId) {
            var selectElement = document.getElementById(selectId);
            selectElement.addEventListener('click', function() {
                var defaultOption = this.querySelector('option[disabled][selected]');
                if (defaultOption) {
                    defaultOption.style.display = 'none';
                }
            });

            selectElement.addEventListener('change', function() {
                var defaultOption = this.querySelector('option[disabled][selected]');
                if (defaultOption) {
                    this.removeChild(defaultOption);
                }
            });
        }

        removeDefaultOption('domain');
        removeDefaultOption('country');
    });

    $('#manage-project').submit(function(e) {
        e.preventDefault();
        start_load();

        // Récupérer la valeur du champ "Etat"
        var status = $('#status').val(); // Remplacez '#status' par l'ID ou le sélecteur exact du champ "Etat"

        // Déterminer l'action en fonction de la valeur de "Etat"
        var action = (status == 5) ? 'save_project_archive' : 'save_project';
        var redirectPage = (status == 5) ? 'project_archive_list' : 'project_list';

        $.ajax({
            url: 'ajax.php?action=' + action,
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                if (resp == 1) {
                    alert_toast('Données enregistrées avec succès', "success");
                    setTimeout(function() {
                        location.href = 'index.php?page=' + redirectPage;
                    }, 2000);
                } else {
                    alert_toast('Erreur lors de l\'enregistrement', "danger");
                }
            }
        });
    });
</script>


    <?php include "footer.php"; ?>
    