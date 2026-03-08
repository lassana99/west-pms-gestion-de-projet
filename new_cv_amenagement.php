<?php 
if (!isset($conn)) { 
    include 'db_connect.php'; 
} 
include "header.php";
?>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="" id="manage-cv-amenagement" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo isset($id) ? htmlspecialchars($id) : '' ?>">

                <div class="row">
                    <div class="col-md-6">
                        <!-- Left Fields -->
                        <div class="form-group">
                            <label for="" class="control-label">Nom de l'expert / technicien</label>
                            <textarea class="form-control form-control-sm" name="user_name" rows="2"><?php echo isset($user_name) ? htmlspecialchars($user_name) : '' ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="" class="control-label">Spécialité</label>
                            <select class="form-control form-control-sm" name="speciality" id="speciality-select">
                                <option value="">Sélectionnez une spécialité</option>
                                <option value="Ing. Genie Rural" <?php echo (isset($speciality) && $speciality == "Ing. Genie Rural") ? 'selected' : '' ?>>Ing. Genie Rural</option>
                                <option value="Ing. Genie Civil" <?php echo (isset($speciality) && $speciality == 'Ing. Genie Civil') ? 'selected' : '' ?>>Ing. Genie Civil</option>
                                <option value="Géotechnicien" <?php echo (isset($speciality) && $speciality == 'Géotechnicien') ? 'selected' : '' ?>>Géotechnicien</option>
                                <option value="Topographe" <?php echo (isset($speciality) && $speciality == 'Topographe') ? 'selected' : '' ?>>Topographe</option>
                                <option value="Environnementaliste" <?php echo (isset($speciality) && $speciality == 'Environnementaliste') ? 'selected' : '' ?>>Environnementaliste</option>
                                <option value="Agro-économiste" <?php echo (isset($speciality) && $speciality == 'Agro-économiste') ? 'selected' : '' ?>>Agro-économiste</option>
                                <option value="Machinisme Agricole" <?php echo (isset($speciality) && $speciality == 'Machinisme Agricole') ? 'selected' : '' ?>>Machinisme Agricole</option>
                                <option value="Hydrologue" <?php echo (isset($speciality) && $speciality == 'Hydrologue') ? 'selected' : '' ?>>Hydrologue</option>
                                <option value="Hydrogeologue" <?php echo (isset($speciality) && $speciality == 'Hydrogeologue') ? 'selected' : '' ?>>Hydrogeologue</option>
                                <option value="Superviseur / Contrôleur" <?php echo (isset($speciality) && $speciality == 'Superviseur / Contrôleur') ? 'selected' : '' ?>>Superviseur / Contrôleur</option>
                                <option value="Pédologue" <?php echo (isset($speciality) && $speciality == 'Pédologue') ? 'selected' : '' ?>>Pédologue</option>
                                <option value="Expert Foncier" <?php echo (isset($speciality) && $speciality == 'Expert Foncier') ? 'selected' : '' ?>>Expert Foncier</option>
                                <option value="Autre" <?php echo (isset($speciality) && !in_array($speciality, [
                                    "Ing. Genie Rural", "Ing. Genie Civil", "Ing. Géotechnicien", "Géotechnicien", "Topographe",
                                    "Environnementaliste", "Agro-économiste", "Machinisme Agricole", "Hydrologue", "Hydrogeologue", "Superviseur / Contrôleur", "Pédologue", "Expert Foncier" 
                                ])) ? 'selected' : '' ?>>Autre</option>
                            </select>

                            <input type="text" class="form-control form-control-sm mt-2" name="speciality_other" id="speciality-other" placeholder="Veuillez préciser votre spécialité" value="<?php echo (isset($speciality) && !in_array($speciality, [
                                    "Ing. Genie Rural", "Ing. Genie Civil", "Ing. Géotechnicien", "Géotechnicien", "Topographe",
                                    "Environnementaliste", "Agro-économiste", "Machinisme Agricole", "Hydrologue", "Hydrogeologue", "Superviseur / Contrôleur", "Pédologue", "Expert Foncier"
                                ])) ? htmlspecialchars($speciality) : '' ?>" style="display: none;">
                        </div>

                        <div class="form-group">
                            <label for="" class="control-label">Années d'expérience</label>
                            <textarea class="form-control form-control-sm" name="year_experience" rows="2"><?php echo isset($year_experience) ? htmlspecialchars($year_experience) : '' ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Right Fields -->
                        <div class="form-group">
                            <label for="" class="control-label">Description</label>
                            <textarea name="description" id="description" cols="30" rows="10" class="summernote form-control"><?php echo isset($description) ? htmlspecialchars($description) : '' ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="file" class="control-label">Veuillez joindre le CV</label>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-success btn-sm mr-2" id="upload-btn">
                                    <i class="fa fa-plus"></i> Associer un fichier
                                </button>
                                <input type="file" class="form-control form-control-sm" name="file" id="file" style="display: none;">
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-footer border-top border-info">
            <div class="d-flex w-100 justify-content-center align-items-center">
                <button class="btn btn-flat bg-gradient-primary mx-2" form="manage-cv-amenagement">Sauvegarder</button>
                <button class="btn btn-flat bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=cv_amenagement_list'">Annuler</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
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

    $('#manage-cv-amenagement').submit(function(e){
        e.preventDefault();
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_cv_amenagement',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp){
                if (resp == 1){
                    alert_toast('CV enregistré avec succès.', 'success');
                    setTimeout(function(){
                        location.href = 'index.php?page=cv_amenagement_list';
                    }, 1500);
                }
            }
        });
    });

    $(document).ready(function() {
    function toggleSpecialityOther() {
        if ($('#speciality-select').val() === 'Autre') {
            $('#speciality-other').show();
        } else {
            $('#speciality-other').hide();
            $('#speciality-other').val('');
        }
    }

    // Lors du chargement
    toggleSpecialityOther();

    // Lors d'un changement de sélection
    $('#speciality-select').change(function() {
        toggleSpecialityOther();
    });
});

</script>
