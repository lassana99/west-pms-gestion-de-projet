<?php 
if (!isset($conn)) { 
    include 'db_connect.php'; 
}
include "header.php";

if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM cv_aep_list WHERE id = " . $_GET['id']);
    foreach ($qry->fetch_array() as $k => $v) {
        $$k = $v;
    }
}
?>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="" id="manage-cv-aep" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo isset($id) ? htmlspecialchars($id) : '' ?>">

                <div class="row">
                    <div class="col-md-6">
                        <!-- Left fields -->
                        <div class="form-group">
                            <label class="control-label">Nom de l'expert / Technicien</label>
                            <textarea class="form-control form-control-sm" name="user_name" rows="2"><?php echo isset($user_name) ? htmlspecialchars($user_name) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Spécialité</label>
                            <select class="form-control form-control-sm" name="speciality" id="speciality-select">
                                <option value="">Sélectionnez une spécialité</option>
                                <option value="Hydro-géologue" <?php echo (isset($speciality) && $speciality == 'Hydro-géologue') ? 'selected' : '' ?>>Hydro-géologue</option>
                                <option value="Hydrolicien" <?php echo (isset($speciality) && $speciality == 'Hydrolicien') ? 'selected' : '' ?>>Hydrolicien</option>
                                <option value="Ing. Génie Civil" <?php echo (isset($speciality) && $speciality == 'Ing. Génie Civil') ? 'selected' : '' ?>>Ing. Génie Civil</option>
                                <option value="Topographe" <?php echo (isset($speciality) && $speciality == 'Topographe') ? 'selected' : '' ?>>Topographe</option>
                                <option value="Expert SIG" <?php echo (isset($speciality) && $speciality == 'Expert SIG') ? 'selected' : '' ?>>Expert SIG</option>
                                <option value="Environnementaliste" <?php echo (isset($speciality) && $speciality == 'Environnementaliste') ? 'selected' : '' ?>>Environnementaliste</option>
                                <option value="Electromecanicien" <?php echo (isset($speciality) && $speciality == 'Electromecanicien') ? 'selected' : '' ?>>Electromecanicien</option>
                                <option value="Géotechnicien" <?php echo (isset($speciality) && $speciality == 'Géotechnicien') ? 'selected' : '' ?>>Géotechnicien</option>
                                <option value="Ing. Hydrologue" <?php echo (isset($speciality) && $speciality == 'Ing. Hydrologue') ? 'selected' : '' ?>>Ing. Hydrologue</option>
                                <option value="Autre" <?php echo (isset($speciality) && !in_array($speciality, [
                                    'Hydro-géologue', 'Hydrolicien', 'Ing. Génie Civil', 'Topographe', 'Expert SIG',
                                    'Environnementaliste', 'Electromecanicien', 'Géotechnicien', 'Ing. Hydrologue'
                                ])) ? 'selected' : '' ?>>Autre</option>
                            </select>

                            <input type="text" class="form-control form-control-sm mt-2" name="speciality_other" id="speciality-other"
                                placeholder="Veuillez préciser votre spécialité"
                                value="<?php echo (isset($speciality) && !in_array($speciality, [
                                    'Hydro-géologue', 'Hydrolicien', 'Ing. Génie Civil', 'Topographe', 'Expert SIG',
                                    'Environnementaliste', 'Electromecanicien', 'Géotechnicien', 'Ing. Hydrologue'
                                ])) ? htmlspecialchars($speciality) : '' ?>"
                                style="display: none;">
                        </div>
                        <div class="form-group">
                            <label class="control-label">Années d'expérience</label>
                            <textarea class="form-control form-control-sm" name="year_experience" rows="2"><?php echo isset($year_experience) ? htmlspecialchars($year_experience) : '' ?></textarea>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Right fields -->
                        <div class="form-group">
                            <label class="control-label">Description</label>
                            <textarea name="description" id="description" cols="30" rows="10" class="summernote form-control"><?php echo isset($description) ? htmlspecialchars($description) : '' ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="file" class="control-label">Veuillez joindre un fichier</label>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-success btn-sm mr-2" id="upload-btn">
                                    <i class="fa fa-plus"></i> Associer un fichier
                                </button>
                                <input type="file" class="form-control form-control-sm" name="file" id="file" style="display: none;">
                            </div>
                            <?php if (!empty($file_name)): ?>
                                <small class="text-primary mt-2 d-block">Fichier actuel : <?php echo htmlspecialchars($file_name); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

            </form>
        </div>

        <div class="card-footer border-top border-info">
            <div class="d-flex w-100 justify-content-center align-items-center">
                <button class="btn btn-flat bg-gradient-primary mx-2" form="manage-cv-aep">Mettre à jour</button>
                <button class="btn btn-flat bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=cv_aep_list'">Annuler</button>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.select2').select2({
        placeholder: 'Sélectionner une option',
        allowClear: true,
        theme: 'bootstrap4'
    });

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

$('#manage-cv-aep').submit(function(e){
    e.preventDefault();
    start_load();
    $.ajax({
        url: 'ajax.php?action=save_cv_aep',
        data: new FormData($(this)[0]),
        cache: false,
        contentType: false,
        processData: false,
        method: 'POST',
        type: 'POST',
        success: function(resp){
            if (resp == 1){
                alert_toast('CV mis à jour avec succès.', 'success');
                setTimeout(function(){
                    location.href = 'index.php?page=cv_aep_list';
                }, 1500);
            }
        }
    });
});

$(document).ready(function () {
    function toggleSpecialityOther() {
        if ($('#speciality-select').val() === 'Autre') {
            $('#speciality-other').show();
        } else {
            $('#speciality-other').hide().val('');
        }
    }

    toggleSpecialityOther();

    $('#speciality-select').on('change', toggleSpecialityOther);
});

</script>
