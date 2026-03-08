<?php 
if (!isset($conn)) { 
    include 'db_connect.php'; 
}
include "header.php";

if(isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM invoice_list WHERE id = ".$_GET['id']);
    foreach($qry->fetch_array() as $k => $v){
        $$k = $v;
    }
}
?>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="" id="manage-invoice" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo isset($id) ? htmlspecialchars($id) : '' ?>">
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Left Fields -->
                        <div class="form-group">
                            <label for="" class="control-label">Nom de la facture</label>
                            <textarea class="form-control form-control-sm" name="name" rows="2"><?php echo isset($name) ? htmlspecialchars($name) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Nom du projet</label>
                            <textarea class="form-control form-control-sm" name="project_name" rows="2"><?php echo isset($project_name) ? htmlspecialchars($project_name) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Mission</label>
                            <select name="mission_id" id="mission" class="custom-select custom-select-sm select2" style="font-size: 18px;">
                                <option value="" disabled <?php echo !isset($mission_id) ? 'selected' : '' ?>>Veuillez sélectionner la mission ici</option>
                                <option value="1" <?php echo isset($mission_id) && $mission_id == 1 ? 'selected' : '' ?>>Mission de Contrôle</option>
                                <option value="2" <?php echo isset($mission_id) && $mission_id == 2 ? 'selected' : '' ?>>Mission d'Étude</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <!-- Right Fields -->
                        <div class="form-group">
                            <label for="" class="control-label">Description</label>
                            <textarea name="description" id="description" cols="30" rows="10" class="summernote form-control"><?php echo isset($description) ? htmlspecialchars($description) : '' ?></textarea>
                        </div>
                        <!-- File Upload Field -->
                        <div class="form-group">
                            <label for="file" class="control-label">Veuillez joindre la facture</label>
                            <div class="d-flex align-items-center">
                                <button type="button" class="btn btn-success btn-sm mr-2" id="upload-btn">
                                    <i class="fa fa-plus"></i> Associer un fichier
                                </button>
                                <input type="file" class="form-control form-control-sm" name="file" id="file" style="display: none;">
                            </div>
                            <?php if(!empty($file_name)): ?>
                                <small class="text-primary mt-2 d-block">Fichier actuel : <?php echo htmlspecialchars($file_name); ?></small>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-footer border-top border-info">
            <div class="d-flex w-100 justify-content-center align-items-center">
                <button class="btn btn-flat bg-gradient-primary mx-2" form="manage-invoice">Mettre à jour</button>
                <button class="btn btn-flat bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=invoice_list'">Annuler</button>
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

        removeDefaultOption('mission');

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

    $('#manage-invoice').submit(function(e){
        e.preventDefault();
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_invoice',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp){
                if (resp == 1){
                    alert_toast('Facture mise à jour avec succès.', 'success');
                    setTimeout(function(){
                        location.href = 'index.php?page=invoice_list';
                    }, 1500);
                }
            }
        });
    });
</script>
