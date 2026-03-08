<?php 
if (!isset($conn)) { 
    include 'db_connect.php'; 
} 
include "header.php";
?>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="" id="manage-contract" enctype="multipart/form-data">
                <input type="hidden" name="contract_id" value="<?php echo isset($contract_id) ? htmlspecialchars($contract_id) : '' ?>">
                <div class="row">
                    <div class="col-md-6">
                        <!-- Left Fields -->
                        <div class="form-group">
                            <label for="" class="control-label">Nom du contrat</label>
                            <textarea class="form-control form-control-sm" name="name" rows="2"><?php echo isset($name) ? htmlspecialchars($name) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Client</label>
                            <textarea class="form-control form-control-sm" name="lessor" rows="2"><?php echo isset($lessor) ? htmlspecialchars($lessor) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Mission</label>
                            <select name="mission_id" id="mission" class="custom-select custom-select-sm select2" style="font-size: 18px;">
                                <option value="" disabled selected>Veuillez sélectionner la mission ici</option>
                                <option value="1" <?php echo isset($mission_id) && $mission_id == 1 ? 'selected' : '' ?>>Mission de Contrôle</option>
                                <option value="2" <?php echo isset($mission_id) && $mission_id == 2 ? 'selected' : '' ?>>Mission d'Etude</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!-- Right Fields -->
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
                            <label for="" class="control-label">Destinateur</label>
                            <select class="form-control form-control-sm select2" name="sender_id">
                                <option></option>
                                <?php 
                                $managers = $conn->query("SELECT *, CONCAT(firstname,' ',lastname) as name FROM users WHERE type IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10) ORDER BY CONCAT(firstname,' ',lastname) ASC");
                                while ($row = $managers->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($sender_id) && $sender_id == $row['id'] ? "selected" : '' ?>><?php echo htmlspecialchars($row['name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Destinataire</label>
                            <select class="form-control form-control-sm select2" name="recipient_id[]" multiple>
                                <option></option>
                                <?php 
                                $managers = $conn->query("SELECT *, CONCAT(firstname,' ',lastname) as name FROM users WHERE type IN (1, 2, 3, 4, 5, 6, 7, 8, 9, 10) ORDER BY CONCAT(firstname,' ',lastname) ASC");
                                while ($row = $managers->fetch_assoc()): 
                                ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo isset($recipient_id) && in_array($row['id'], explode(',', $recipient_id)) ? "selected" : '' ?>><?php echo htmlspecialchars($row['name']) ?></option>
                                <?php endwhile; ?>
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
                <!-- File Upload Field -->
                <div class="form-group">
                    <label for="file" class="control-label">Veuillez joindre le contract complémentaire</label>
                    <div class="d-flex align-items-center">
                        <button type="button" class="btn btn-success btn-sm mr-2" id="upload-btn">
                            <i class="fa fa-plus"></i> Associer un fichier
                        </button>
                        <input type="file" class="form-control form-control-sm" name="file" id="file" style="display: none;">
                    </div>
                </div>
            </form>
        </div>
        <div class="card-footer border-top border-info">
            <div class="d-flex w-100 justify-content-center align-items-center">
                <button class="btn btn-flat bg-gradient-primary mx-2" form="manage-contract">Sauvegarder</button>
                <button class="btn btn-flat bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=contract_list'">Annuler</button>
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

        // Toggle file input visibility
        $('#upload-btn').click(function() {
            $('#file').click();
        });

        $('#file').change(function() {
            if (this.files.length > 0) {
                $('#upload-btn').text(this.files[0].name);
            } else {
                $('#upload-btn').html('<i class="fa fa-plus"></i> Associer un fichier');
            }
        });
    });

    $('#manage-contract').submit(function(e){
        e.preventDefault();
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_contract',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp){
                if (resp == 1){
                    alert_toast('Data successfully saved.', 'success');
                    setTimeout(function(){
                        location.href = 'index.php?page=contract_list';
                    }, 1500);
                }
            }
        });
    });
</script>
