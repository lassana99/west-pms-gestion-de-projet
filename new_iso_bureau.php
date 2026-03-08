<?php 
if (!isset($conn)) { 
    include 'db_connect.php'; 
} 
include "header.php";
?>

<div class="col-lg-12">
    <div class="card card-outline card-primary">
        <div class="card-body">
            <form action="" id="manage-iso-bureau" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?php echo isset($id) ? htmlspecialchars($id) : '' ?>">
                
                <div class="row">
                    <div class="col-md-6">
                        <!-- Left Fields -->
                        <div class="form-group">
                            <label for="" class="control-label">Nom de la fiche</label>
                            <textarea class="form-control form-control-sm" name="attached_name" rows="2"><?php echo isset($attached_name) ? htmlspecialchars($attached_name) : '' ?></textarea>
                        </div>
                        <div class="form-group">
                            <label for="" class="control-label">Nom du propriétaire</label>
                            <textarea class="form-control form-control-sm" name="user_name" rows="2"><?php echo isset($user_name) ? htmlspecialchars($user_name) : '' ?></textarea>
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
                            <label for="file" class="control-label">Veuillez joindre le document</label>
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
                <button class="btn btn-flat bg-gradient-primary mx-2" form="manage-iso-bureau">Sauvegarder</button>
                <button class="btn btn-flat bg-gradient-secondary mx-2" type="button" onclick="location.href='index.php?page=iso_bureau_list'">Annuler</button>
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

    $('#manage-iso-bureau').submit(function(e){
        e.preventDefault();
        start_load();
        $.ajax({
            url: 'ajax.php?action=save_iso_bureau',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp){
                if (resp == 1){
                    alert_toast('Document ISO Bureau enregistré avec succès.', 'success');
                    setTimeout(function(){
                        location.href = 'index.php?page=iso_bureau_list';
                    }, 1500);
                }
            }
        });
    });
</script>
