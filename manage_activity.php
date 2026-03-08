<?php 
include 'db_connect.php';
session_start(); // Ensure session is started

if (isset($_GET['id'])) {
    $qry = $conn->query("SELECT * FROM activity_list WHERE id = " . $_GET['id'])->fetch_array();
    foreach ($qry as $k => $v) {
        $$k = $v;
    }
}

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : ''; // Ensure user_id is set
?>
<div class="container-fluid">
    <form action="ajax.php?action=save_activity" id="manage-activity" class="manage-activity-form" enctype="multipart/form-data"> <!-- Ajout de enctype pour permettre le téléchargement de fichier -->
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="project_id" value="<?php echo isset($_GET['pid']) ? $_GET['pid'] : '' ?>">
        <input type="hidden" name="user_id" value="<?php echo htmlspecialchars($user_id, ENT_QUOTES); ?>"> <!-- Hidden field for user_id -->
        
        <div class="form-group">
            <label for="">Objet</label>
            <input type="text" class="form-control form-control-sm" name="activity" value="<?php echo isset($activity) ? htmlspecialchars($activity, ENT_QUOTES) : '' ?>" required>
        </div>
        
        <div class="form-group">
            <label for="">Description</label>
            <textarea name="description" id="description" cols="30" rows="10" class="summernote form-control"><?php echo isset($description) ? htmlspecialchars($description, ENT_QUOTES) : '' ?></textarea>
        </div>
        
        <!-- Nouveau champ pour uploader un fichier -->
        <div class="form-group">
            <label for="activity_file" class="control-label">Veuillez joindre un fichier associé à l'activité</label>
            <div class="d-flex align-items-center">
                <button type="button" class="btn btn-success btn-sm mr-2" id="upload-btn">
                    <i class="fa fa-plus"></i> Associer un fichier
                </button>
                <input type="file" class="form-control form-control-sm" name="activity_file" id="activity_file" style="display: none;">
            </div>
        </div>
    </form>
</div>

<script>
    $(document).ready(function(){
        $('.summernote').summernote({
            height: 200,
            toolbar: [
                [ 'style', [ 'style' ] ],
                [ 'font', [ 'bold', 'italic', 'underline', 'strikethrough', 'superscript', 'subscript', 'clear'] ],
                [ 'fontname', [ 'fontname' ] ],
                [ 'fontsize', [ 'fontsize' ] ],
                [ 'color', [ 'color' ] ],
                [ 'para', [ 'ol', 'ul', 'paragraph', 'height' ] ],
                [ 'table', [ 'table' ] ],
                [ 'view', [ 'undo', 'redo', 'fullscreen', 'codeview', 'help' ] ]
            ]
        });

        $('#manage-activity').submit(function(e){
            e.preventDefault();
            start_load();
            $.ajax({
                url: $(this).attr('action'),
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                success:function(resp){
                    if (resp == 1) {
                        alert_toast('Données enregistrées avec succès', "success");
                        setTimeout(function(){
                            location.reload();
                        }, 1500);
                    }
                }
            });
        });
    });

	// Script pour déclencher l'upload du fichier lorsque le bouton est cliqué
    document.getElementById('upload-btn').addEventListener('click', function() {
        document.getElementById('activity_file').click();
    });
</script>
