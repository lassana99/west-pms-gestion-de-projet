<?php
include 'db_connect.php';

// Fetch ISO Terrain details
$id = intval($_GET['id']);
$qry = $conn->query("
    SELECT it.*, 
           m.name AS mission_name
    FROM iso_terrain_list it
    LEFT JOIN mission m ON it.mission_id = m.mission_id
    WHERE it.id = $id
");

if (!$qry) {
    echo "Erreur dans la requête : " . $conn->error;
    exit;
}

$qry = $qry->fetch_assoc();

if (!$qry) {
    echo "Aucun ISO Terrain trouvé.";
    exit;
}

foreach ($qry as $k => $v) {
    $$k = $v;
}

// Initialize variables safely
$date_created = isset($date_created) ? $date_created : '';
$date_updated = isset($date_updated) ? $date_updated : '';
$description = isset($description) ? $description : '';
$file_name = isset($file_name) ? $file_name : '';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ISO Terrain</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Style similaire à view_contract.php adapté */
        #iso-terrain-view {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        #iso-terrain-view .terrain-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        #iso-terrain-view .terrain-content {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 20px;
            background-color: #ffffff;
        }
        #iso-terrain-view .left-section, #iso-terrain-view .right-section {
            width: 48%;
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        #iso-terrain-view .vertical-separator {
            border-left: 2px solid #007bff;
            height: 100%;
            margin: 0 20px;
        }
        #iso-terrain-view .description-section {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            height: 300px;
            overflow-y: auto;
        }
        #iso-terrain-view .description-section h3 {
            margin-top: 0;
            font-size: 1.2em;
            font-weight: bold;
        }
        #iso-terrain-view .description-section p {
            font-size: 1em;
        }
        #iso-terrain-view .file-name-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        #iso-terrain-view .file-name-section,
        #iso-terrain-view .file-actions-section {
            flex: 1;
            padding: 10px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        #iso-terrain-view .file-actions-section {
            margin-right: 0;
        }
        #iso-terrain-view .file-name-section label,
        #iso-terrain-view .file-actions-section label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            font-size: 1.2em;
        }
        #iso-terrain-view .file-name,
        #iso-terrain-view .icons {
            font-size: 1em;
        }
        #iso-terrain-view .icons {
            display: flex;
            gap: 20px;
        }
        #iso-terrain-view .icons a {
            text-decoration: none;
            font-size: 2em;
        }
        #iso-terrain-view .icons a i.fas.fa-edit {
            color: #007bff;
        }
        #iso-terrain-view .icons a i.fas.fa-download {
            color: #28a745;
        }
        #iso-terrain-view .icons a:hover i {
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div id="iso-terrain-view">
    <div class="terrain-container">
        <div class="terrain-content">
            <div class="left-section">
                <dl>
                    <dt><b>Nom du projet :</b></dt>
                    <dd><?php echo htmlspecialchars($project_name ?? 'Non défini'); ?></dd>
                    <dt><b>Mission :</b></dt>
                    <dd><?php echo htmlspecialchars($mission_name ?? 'Non défini'); ?></dd>
                    <dt><b>Nom du Chef de mission :</b></dt>
                    <dd><?php echo htmlspecialchars($user_name ?? 'Non défini'); ?></dd>
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
                        <a href="./index.php?page=edit_iso_terrain&id=<?php echo $id; ?>" title="Modifier l'ISO Terrain">
                            <i class="fas fa-edit"></i>
                        </a>
                        <span style="display: block; font-size: 0.9rem; margin-top: 0.2rem;">Modifier</span>
                    </div>

                    <div class="text-center">
                        <a href="assets/iso_terrain/<?php echo htmlspecialchars($file_name ?? ''); ?>" download title="Télécharger le fichier">
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
