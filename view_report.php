<?php
include 'db_connect.php';

// Fetch report details
$id = intval($_GET['id']);
$qry = $conn->query("
    SELECT d.*, 
           c.name AS country_name, 
           m.name AS mission_name, 
           dom.name AS domain_name, 
           u1.firstname AS sender_firstname, 
           u1.lastname AS sender_lastname, 
           GROUP_CONCAT(CONCAT(u2.firstname, ' ', u2.lastname) SEPARATOR ', ') AS recipients
    FROM report d
    LEFT JOIN country c ON d.country_id = c.country_id
    LEFT JOIN mission m ON d.mission_id = m.mission_id
    LEFT JOIN domain dom ON d.domain_id = dom.domain_id
    LEFT JOIN users u1 ON d.sender_id = u1.id
    LEFT JOIN users u2 ON FIND_IN_SET(u2.id, d.recipient_id)
    WHERE d.report_id = $id
    GROUP BY d.report_id
");

if (!$qry) {
    echo "Erreur dans la requête : " . $conn->error;
    exit;
}

$qry = $qry->fetch_assoc(); // Use fetch_assoc() to get an associative array

// Check if $qry is not empty
if (!$qry) {
    echo "Aucun rapport trouvé.";
    exit;
}

foreach ($qry as $k => $v) {
    $$k = $v;
}

// Date fields (no formatting applied)
$date_created = isset($date_created) ? $date_created : '';
$date_updated = isset($date_updated) ? $date_updated : '';
$description = isset($description) ? $description : '';
$file_name = isset($file_name) ? $file_name : ''; // Fetch the file name
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        #contract-view {
            background-color: #f0f0f0; /* Light gray background for the whole page */
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        #report-view .report-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #ffffff; /* White background for the content area */
            border-radius: 8px;
            border: 1px solid #ddd;
        }
        #report-view .report-content {
            display: flex;
            justify-content: space-between;
            border-bottom: 2px solid #007bff; /* Blue color for the horizontal separator */
            padding-bottom: 20px;
            margin-bottom: 20px;
            background-color: #ffffff;
        }
        #report-view .left-section, #report-view .right-section {
            width: 48%;
            background-color: #f9f9f9;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        #report-view .vertical-separator {
            border-left: 2px solid #007bff; /* Blue color for the vertical separator */
            height: 100%;
            margin: 0 20px;
        }
        #report-view .description-section {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 20px;
            height: 300px; /* Adjust height as needed */
            overflow-y: auto;
        }
        #report-view .description-section h3 {
            margin-top: 0;
            font-size: 1.2em; /* Matching font size for headings */
            font-weight: bold; /* Ensure consistency with other headings */
        }
        #report-view .description-section p {
            font-size: 1em; /* Match font size with other paragraph texts */
            white-space: pre-line; /* Preserve whitespace */
        }
        #report-view .file-name-container {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
        #report-view .file-name-section {
            flex: 1;
            padding: 10px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-right: 10px;
        }
        #report-view .file-actions-section {
            flex: 1;
            padding: 10px;
            background-color: #ffffff;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        #report-view .file-name-section label,
        #report-view .file-actions-section label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
            font-size: 1.2em; /* Increase font size */
        }
        #report-view .file-name-section .file-name,
        #report-view .file-actions-section .icons {
            font-size: 1em; /* Ensure consistent font size with other sections */
        }
        #report-view .file-actions-section .icons {
            display: flex;
            gap: 20px; /* Increase the gap between icons */
        }
        #report-view .file-actions-section .icons a {
            text-decoration: none;
            font-size: 2em; /* Increase icon size */
        }
        #report-view .file-actions-section .icons a i.fas.fa-edit {
            color: #007bff; /* Blue color for the edit icon */
        }
        #report-view .file-actions-section .icons a i.fas.fa-download {
            color: #28a745; /* Green color for the download icon */
        }
        #report-view .file-actions-section .icons a:hover i {
            opacity: 0.8; /* Slightly change the opacity on hover for better UX */
        }
    </style>
</head>
<body>

    <div id="report-view">
        <div class="report-container">
            <div class="report-content">
                <div class="left-section">
                    <dl>
                        <dt><b>Nom du rapport :</b></dt>
                        <dd><?php echo htmlspecialchars($name ?? 'Non défini'); ?></dd>
                        <dt><b>Pays :</b></dt>
                        <dd><?php echo htmlspecialchars($country_name ?? 'Non défini'); ?></dd>
                        <dt><b>Mission :</b></dt>
                        <dd><?php echo htmlspecialchars($mission_name ?? 'Non défini'); ?></dd>
                        <dt><b>Domaine :</b></dt>
                        <dd><?php echo htmlspecialchars($domain_name ?? 'Non défini'); ?></dd>
                    </dl>
                </div>

                <div class="vertical-separator"></div>

                <div class="right-section">
                    <dl>
                        <dt><b>Destinateur :</b></dt>
                        <dd><?php echo ucwords(($sender_firstname ?? '') . ' ' . ($sender_lastname ?? '')); ?></dd>
                        <dt><b>Destinataire(s) :</b></dt>
                        <dd><?php echo htmlspecialchars($recipients ?? 'Non défini'); ?></dd>
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
                    <label for="file-name">Nom du rapport :</label>
                    <div class="file-name"><?php echo htmlspecialchars($report_file ?? 'Non défini'); ?></div>
                </div>

                <!-- File Actions Section -->
                <div class="file-actions-section">
                    <label>Veuillez mettre à jour ou télécharger le rapport :</label>
                    <div class="icons d-flex justify-content-start">
                        <!-- Icône de modification avec étiquette -->
                        <div class="text-center mr-3">
                            <a href="./index.php?page=edit_report&id=<?php echo $id; ?>" title="Modifier le rapport">
                                <i class="fas fa-edit" style="font-size: 2rem;"></i>
                            </a>
                            <span style="display: block; font-size: 0.9rem;">Modifier</span>
                        </div>
                        <!-- Icône de téléchargement avec étiquette -->
                        <div class="text-center">
                            <a href="assets/report_file/<?php echo htmlspecialchars($file_name ?? ''); ?>" download title="Télécharger le rapport">
                                <i class="fas fa-download" style="font-size: 2rem;"></i>
                            </a>
                            <span style="display: block; font-size: 0.9rem;">Télécharger</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>
