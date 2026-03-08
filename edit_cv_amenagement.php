<?php 
if (!isset($conn)) { 
    include 'db_connect.php'; 
}
include "header.php";

if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM cv_amenagement_list WHERE id = " . $_GET['id']);
    foreach ($qry->fetch_array() as $k => $v) {
        $$k = $v;
    }
}
?>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="" id="manage-cv-amenagement" enctype="multipart/form-data">
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
                                <?php 
                                $options = [
                                    "Ing. Genie Rural", "Ing. Genie Civil", "Geotechnicien", "Topographe", "Environnementaliste", "Agro-économiste",
                                    "Machinisme Agricole", "Hydrologue", "Hydrogeologue", "Superviseur / Contrôleur", "Pedologue", "Expert Foncier"
                                ];
                                foreach ($options as $opt) {
                                    echo '<option value="' . $opt . '"' . 
                                        ((isset($speciality) && $speciality == $opt) ? ' selected' : '') . '>' . $opt . '</option>';
                                }
                                ?>
                                <option value="Autre" <?php echo (isset($speciality) && !in_array($speciality, $options)) ? 'selected' : '' ?>>Autre</option>
                            </select>

                            <input type="text" class="form-control form-control-sm mt-2" name="speciality_other" id="speciality-other" placeholder="Veuillez préciser votre spécialité" value="<?php echo (isset($speciality) && !in_array($speciality, $options)) ? htmlspecialchars($speciality) : '' ?>" style="display: none;">
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

        function toggleSpecialityOther() {
            if ($('#speciality-select').val() === 'Autre') {
                $('#speciality-other').show();
            } else {
                $('#speciality-other').hide();
                $('#speciality-other').val('');
            }
        }

        toggleSpecialityOther();

        $('#speciality-select').change(function() {
            toggleSpecialityOther();
        });

        $('#manage-cv-amenagement').submit(function(e){
            e.preventDefault();
            start_load();
            var formData = new FormData(this);

            // Replace speciality if 'Autre' is selected
            if ($('#speciality-select').val() === 'Autre') {
                formData.set('speciality', $('#speciality-other').val());
            }

            $.ajax({
                url: 'ajax.php?action=save_cv_amenagement',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success: function(resp){
                    if (resp == 1){
                        alert_toast('CV mis à jour avec succès.', 'success');
                        setTimeout(function(){
                            location.href = 'index.php?page=cv_amenagement_list';
                        }, 1500);
                    }
                }
            });
        });
    });
</script>
