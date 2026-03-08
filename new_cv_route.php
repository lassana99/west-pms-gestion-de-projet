<?php 
if (!isset($conn)) { 
    include 'db_connect.php'; 
} 
include "header.php";
?>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="" id="manage-cv-route" enctype="multipart/form-data">
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
                                <option value="Ing. Ouvrage d'art" <?php echo (isset($speciality) && $speciality == "Ing. Ouvrage d'art") ? 'selected' : '' ?>>Ing. Ouvrage d'art</option>
                                <option value="Ing. Routier" <?php echo (isset($speciality) && $speciality == 'Ing. Routier') ? 'selected' : '' ?>>Ing. Routier</option>
                                <option value="Ing. Géotechnicien" <?php echo (isset($speciality) && $speciality == 'Ing. Géotechnicien') ? 'selected' : '' ?>>Ing. Géotechnicien</option>
                                <option value="Ing. Topographe" <?php echo (isset($speciality) && $speciality == 'Ing. Topographe') ? 'selected' : '' ?>>Ing. Topographe</option>
                                <option value="Ing. Environnementaliste" <?php echo (isset($speciality) && $speciality == 'Ing. Environnementaliste') ? 'selected' : '' ?>>Ing. Environnementaliste</option>
                                <option value="Spécialiste Sécurité Routière" <?php echo (isset($speciality) && $speciality == 'Spécialiste Sécurité Routière') ? 'selected' : '' ?>>Spécialiste Sécurité Routière</option>
                                <option value="Hydrolicien" <?php echo (isset($speciality) && $speciality == 'Hydrolicien') ? 'selected' : '' ?>>Hydrolicien</option>
                                <option value="Socio économiste" <?php echo (isset($speciality) && $speciality == 'Socio économiste') ? 'selected' : '' ?>>Socio économiste</option>
                                <option value="Ing. Qualité" <?php echo (isset($speciality) && $speciality == 'Ing. Qualité') ? 'selected' : '' ?>>Ing. Qualité</option>
                                <option value="Economiste de transport" <?php echo (isset($speciality) && $speciality == 'Economiste de transport') ? 'selected' : '' ?>>Economiste de transport</option>
                                <option value="Autre" <?php echo (isset($speciality) && !in_array($speciality, [
                                    "Ing. Ouvrage d'art", "Ing. Routier", "Ing. Géotechnicien", "Ing. Topographe", "Ing. Environnementaliste",
                                    "Spécialiste Sécurité Routière", "Hydrolicien", "Socio économiste", "Ing. Qualité", "Economiste de transport"
                                ])) ? 'selected' : '' ?>>Autre</option>
                            </select>

                            <input type="text" class="form-control form-control-sm mt-2" name="speciality_other" id="speciality-other" placeholder="Veuillez préciser votre spécialité" value="<?php echo (isset($speciality) && !in_array($speciality, [
                                    "Ing. Ouvrage d'art", "Ing. Routier", "Ing. Géotechnicien", "Ing. Topographe", "Ing. Environnementaliste",
                                    "Spécialiste Sécurité Routière", "Hydrolicien", "Socio économiste", "Ing. Qualité", "Economiste de transport"
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
                <button class="btn btn-flat bg-gradient-primary mx-2" form="manage-cv-route">Sauvegarder</button>
                <button class="btn btn-flat bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=cv_route_list'">Annuler</button>
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

    $('#manage-cv-route').submit(function(e){
        e.preventDefault();
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_cv_route',
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
                        location.href = 'index.php?page=cv_route_list';
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
