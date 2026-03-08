<?php
include 'db_connect.php';

// Fetch invoice details
$id = intval($_GET['id']);
$qry = $conn->query("
    SELECT i.*, 
           m.name AS mission_name
    FROM invoice_list i
    LEFT JOIN mission m ON i.mission_id = m.mission_id
    WHERE i.id = $id
");

if (!$qry) {
    echo "Erreur dans la requête : " . $conn->error;
    exit;
}

$qry = $qry->fetch_assoc(); // Fetch as associative array

// Check if $qry is not empty
if (!$qry) {
    echo "Aucune facture trouvée.";
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
    <title>Facture</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        /* Le même style que view_contract.php adapté */
        #invoice-view {
            background-color: #f0f0f0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        #invoice-view .invoice-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        #invoice-view .invoice-content {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
            margin-bottom: 20px;
            background-color: #ffffff;
        }
        #invoice-view .left-section, #invoice-view .right-section {
            width: 48%;
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        #invoice-view .vertical-separator {
            border-left: 2px solid #007bff;
            height: 100%;
            margin: 0 20px;
        }
        #invoice-view .description-section {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            height: 300px;
            overflow-y: auto;
        }
        #invoice-view .description-section h3 {
            margin-top: 0;
            font-size: 1.2em;
            font-weight: bold;
        }
        #invoice-view .description-section p {
            font-size: 1em;
        }
        #invoice-view .file-name-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        #invoice-view .file-name-section,
        #invoice-view .file-actions-section {
            flex: 1;
            padding: 10px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        #invoice-view .file-actions-section {
            margin-right: 0;
        }
        #invoice-view .file-name-section label,
        #invoice-view .file-actions-section label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            font-size: 1.2em;
        }
        #invoice-view .file-name,
        #invoice-view .icons {
            font-size: 1em;
        }
        #invoice-view .icons {
            display: flex;
            gap: 20px;
        }
        #invoice-view .icons a {
            text-decoration: none;
            font-size: 2em;
        }
        #invoice-view .icons a i.fas.fa-edit {
            color: #007bff;
        }
        #invoice-view .icons a i.fas.fa-download {
            color: #28a745;
        }
        #invoice-view .icons a:hover i {
            opacity: 0.8;
        }
    </style>
</head>
<body>

<div id="invoice-view">
    <div class="invoice-container">
        <div class="invoice-content">
            <div class="left-section">
                <dl>
                    <dt><b>Nom de la facture :</b></dt>
                    <dd><?php echo htmlspecialchars($name ?? 'Non défini'); ?></dd>
                    <dt><b>Projet :</b></dt>
                    <dd><?php echo htmlspecialchars($project_name ?? 'Non défini'); ?></dd>
                    <dt><b>Mission :</b></dt>
                    <dd><?php echo htmlspecialchars($mission_name ?? 'Non défini'); ?></dd>
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
                <label>Veuillez mettre à jour ou télécharger la facture</label>
                <div class="icons d-flex justify-content-start">
                    <div class="text-center mr-3">
                        <a href="./index.php?page=edit_invoice&id=<?php echo $id; ?>" title="Modifier la facture">
                            <i class="fas fa-edit"></i>
                        </a>
                        <span style="display: block; font-size: 0.9rem; margin-top: 0.2rem;">Modifier</span>
                    </div>

                    <div class="text-center">
                        <a href="assets/factures/<?php echo htmlspecialchars($file_name ?? ''); ?>" download title="Télécharger la facture">
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
