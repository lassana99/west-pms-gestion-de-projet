<?php
include 'db_connect.php';

// Fetch CV amenagement details
$id = intval($_GET['id']);
$qry = $conn->query("
    SELECT *
    FROM cv_amenagement_list
    WHERE id = $id
");

if (!$qry) {
    echo "Erreur dans la requête : " . $conn->error;
    exit;
}

$qry = $qry->fetch_assoc();

if (!$qry) {
    echo "Aucun CV amenagement trouvé.";
    exit;
}

foreach ($qry as $k => $v) {
    $$k = $v;
}

// Initialize variables safely
$user_name = isset($user_name) ? $user_name : '';
$speciality = isset($speciality) ? $speciality : '';
$year_experience = isset($year_experience) ? $year_experience : '';
$description = isset($description) ? $description : '';
$file_name = isset($file_name) ? $file_name : '';
$date_created = isset($date_created) ? $date_created : '';
$date_updated = isset($date_updated) ? $date_updated : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CV amenagement</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Style similaire à view_contract.php adapté */
        #cv-amenagement-view {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        #cv-amenagement-view .cv-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        #cv-amenagement-view .cv-content {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 20px;
            background-color: #ffffff;
        }
        #cv-amenagement-view .left-section, #cv-amenagement-view .right-section {
            width: 48%;
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        #cv-amenagement-view .vertical-separator {
            border-left: 2px solid #007bff;
            height: 100%;
            margin: 0 20px;
        }
        #cv-amenagement-view .description-section {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            height: 300px;
            overflow-y: auto;
        }
        #cv-amenagement-view .description-section h3 {
            margin-top: 0;
            font-size: 1.2em;
            font-weight: bold;
        }
        #cv-amenagement-view .description-section p {
            font-size: 1em;
        }
        #cv-amenagement-view .file-name-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        #cv-amenagement-view .file-name-section,
        #cv-amenagement-view .file-actions-section {
            flex: 1;
            padding: 10px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        #cv-amenagement-view .file-actions-section {
            margin-right: 0;
        }
        #cv-amenagement-view .file-name-section label,
        #cv-amenagement-view .file-actions-section label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            font-size: 1.2em;
        }
        #cv-amenagement-view .file-name,
        #cv-amenagement-view .icons {
            font-size: 1em;
        }
        #cv-amenagement-view .icons {
            display: flex;
            gap: 20px;
        }
        #cv-amenagement-view .icons a {
            text-decoration: none;
            font-size: 2em;
        }
        #cv-amenagement-view .icons a i.fas.fa-edit {
            color: #007bff;
        }
        #cv-amenagement-view .icons a i.fas.fa-download {
            color: #28a745;
        }
        #cv-amenagement-view .icons a:hover i {
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div id="cv-amenagement-view">
    <div class="cv-container">
        <div class="cv-content">
            <div class="left-section">
                <dl>
                    <dt><b>Nom de l'expert / Technicien :</b></dt>
                    <dd><?php echo htmlspecialchars($user_name ?? 'Non défini'); ?></dd>
                    <dt><b>Spécialité :</b></dt>
                    <dd><?php echo htmlspecialchars($speciality ?? 'Non défini'); ?></dd>
                    <dt><b>Années d'expérience :</b></dt>
                    <dd><?php echo htmlspecialchars($year_experience ?? 'Non défini'); ?></dd>
                </dl>
            </div>
            <div class="vertical-separator"></div>
            <div class="right-section">
                <dl>
                    <dt><b>Date de création :</b></dt>
                    <dd><?php echo htmlspecialchars($date_created); ?></dd>
                    <dt><b>Date de la mise à jour :</b></dt>
                    <dd><?php echo htmlspecialchars($date_updated); ?></dd>
                </dl>
            </div>
        </div>

        <hr class="separator">

        <div class="description-section">
            <h3>Description</h3>
            <p><?php echo nl2br(htmlspecialchars($description)); ?></p>
        </div>

        <!-- File Name and Actions -->
        <div class="file-name-container">
            <!-- File Name Section -->
            <div class="file-name-section">
                <label>Nom du fichier joint</label>
                <div class="file-name"><?php echo htmlspecialchars($file_name ?? 'Non défini'); ?></div>
            </div>

            <!-- File Actions Section -->
            <div class="file-actions-section">
                <label>Veuillez mettre à jour ou télécharger le fichier</label>
                <div class="icons d-flex justify-content-start">
                    <div class="text-center mr-3">
                        <a href="./index.php?page=edit_cv_amenagement&id=<?php echo $id; ?>" title="Modifier le CV amenagement">
                            <i class="fas fa-edit"></i>
                        </a>
                        <span style="display: block; font-size: 0.9rem; margin-top: 0.2rem;">Modifier</span>
                    </div>

                    <div class="text-center">
                        <a href="assets/cv_amenagement/<?php echo htmlspecialchars($file_name ?? ''); ?>" download title="Télécharger le fichier">
                            <i class="fas fa-download"></i>
                        </a>
                        <span style="display: block; font-size: 0.9rem; margin-top: 0.2rem;">Télécharger</span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

</body>
</html>
