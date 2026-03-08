<?php include('db_connect.php'); ?>

<?php
$twhere = "";
if (!in_array($_SESSION['login_type'], [1, 3, 5, 6, 7])) {
    $twhere = "  ";
}
?>

<!-- Gestion de chatbot -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const toggleBtn = document.getElementById('chatbotToggle');
    const chatbotContainer = document.getElementById('chatbotContainer');
    const chatForm = document.getElementById('chatForm');
    const chatbox = document.getElementById('chatbox');
    const chatInput = document.getElementById('chatInput');

    // Cacher le chatbot au départ
    chatbotContainer.style.display = 'none';

    // Affichage / masquage du chatbot
    toggleBtn.addEventListener('click', function () {
        if (chatbotContainer.style.display === 'none' || chatbotContainer.style.display === '') {
            chatbotContainer.style.display = 'flex';
            chatInput.focus();
        } else {
            chatbotContainer.style.display = 'none';
        }
    });

    // Soumission du message
    chatForm.addEventListener('submit', async function (e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (!message) return;

        // Afficher le message utilisateur
        chatbox.innerHTML += `<div><strong>Vous:</strong> ${message}</div>`;
        chatInput.value = '';

        try {
            const response = await fetch('chatbot_api.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ prompt: message })
            });

            const text = await response.text(); // lire la réponse brute
            console.log("Réponse brute :", text); // pour le débogage

            const data = JSON.parse(text); // tenter de parser
            if (data && data.reply) {
                chatbox.innerHTML += `<div><strong>Bot:</strong> ${data.reply}</div>`;
            } else {
                chatbox.innerHTML += `<div style="color:red;"><strong>Bot:</strong> Réponse inattendue.</div>`;
            }
        } catch (error) {
            console.error("Erreur chatbot:", error);
            chatbox.innerHTML += `<div style="color:red;"><strong>Bot:</strong> Une erreur s'est produite.</div>`;
        }

        chatbox.scrollTop = chatbox.scrollHeight;
    });
});
</script>



<!-- Info boxes -->
<div class="col-12">
    <div class="card">
        <div class="card-body">
            Bienvenue <?php echo htmlspecialchars($_SESSION['login_name']); ?>!
            
           <!-- Chatbot zone déplacée -->
            <div style="margin-top: 20px;">
                <button id="chatbotToggle" style="background: #4A90E2; color: white; border: none; padding: 6px 12px; border-radius: 20px; font-size: 0.9rem; margin-left: auto; display: block;">
                    💬 Lancer le Chatbot
                </button>

                <div id="chatbotContainer" style="display: none; margin-top: 10px; width: 100%; max-width: 400px; height: 300px; background: #fff; border: 1px solid #ccc; border-radius: 8px; box-shadow: 0 0 10px rgba(0,0,0,0.1); overflow: hidden; flex-direction: column; margin-left: auto;">
                    <div style="background: #4A90E2; color: white; padding: 8px; font-weight: bold; font-size: 0.95rem;">Assistant Virtuel</div>
                    <div id="chatbox" style="flex: 1; padding: 8px; overflow-y: auto; font-size: 0.85rem;"></div>
                    <form id="chatForm" style="display: flex; border-top: 1px solid #ccc;">
                        <input type="text" id="chatInput" placeholder="Message..." style="flex: 1; padding: 8px; border: none; font-size: 0.85rem;">
                        <button type="submit" style="padding: 8px 12px; border: none; background: #4A90E2; color: white; font-size: 0.85rem;">Envoyer</button>
                    </form>
                </div>
            </div>



        </div>
    </div>
</div>
<hr>


<?php 
$where = "";
$where2 = "";
if (isset($_SESSION['login_type'])) {
    if (in_array($_SESSION['login_type'], [1, 3, 5, 6, 7])) {
        // Tous les projets
        $where = "";
        $where2 = "";
    } elseif (in_array($_SESSION['login_type'], [8, 9, 10])) {
        // Projets associés à l'utilisateur en tant que chef de mission ou membre de l'équipe
        $login_id = $_SESSION['login_id'];
        $where = " WHERE (p.manager_id = '$login_id' OR FIND_IN_SET('$login_id', p.user_id)) ";
        $where2 = " WHERE (p.manager_id = '$login_id' OR FIND_IN_SET('$login_id', p.user_id)) ";
    }
}
?>
<div class="row">
    <div class="col-md-9">
        <div class="card card-outline card-success">
            <div class="card-header">
                <b>Projets</b>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table m-0 table-hover">
                        <colgroup>
                            <col width="5%">
                            <col width="25%">
                            <col width="20%">
                            <col width="10%">
                            <col width="10%">
                        </colgroup>
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Projet</th>
                                <th>Domaine</th>
                                <th>Statut</th>
                                <th>Détails</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $stat = array(0 => "En attente", 3 => "En cours", 5 => "Fait");
                            $qry = $conn->query("SELECT p.*, d.name as domain_name FROM project_list p LEFT JOIN domain d ON p.domain_id = d.domain_id $where ORDER BY p.name ASC");
                            if ($qry) {
                                while ($row = $qry->fetch_assoc()):
                                    $status_label = '';
                                    switch ($row['status']) {
                                        case 0:
                                            $status_label = "<span class='badge badge-secondary'>En attente</span>";
                                            break;
                                        case 3:
                                            $status_label = "<span class='badge badge-info'>En cours</span>";
                                            break;
                                        case 5:
                                            $status_label = "<span class='badge badge-success'>Fait</span>";
                                            break;
                                        default:
                                            $status_label = "<span class='badge badge-light'>Inconnu</span>";
                                            break;
                                    }
                                    ?>
                                    <tr>
                                        <td><?php echo $i++ ?></td>
                                        <td>
                                            <a><?php echo htmlspecialchars($row['name']) ?></a>
                                        </td>
                                        <td><?php echo isset($row['domain_name']) ? htmlspecialchars($row['domain_name']) : '' ?></td>
                                        <td class="project-state">
                                            <?php echo $status_label; ?>
                                        </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm" href="./index.php?page=view_project&id=<?php echo $row['id'] ?>">
                                                <i class="fas fa-folder"></i>
                                                Consulter
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; 
                            } else {
                                echo "<tr><td colspan='5'>Aucun projet trouvé.</td></tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
    <div class="row">
        <!-- Bloc pour afficher le nombre total de domaines associés aux projets -->
        <div class="col-12 col-sm-6 col-md-12">
            <div class="small-box bg-light shadow-sm border">
                <div class="inner">
                    <?php
                    $domain_count_query = "SELECT COUNT(DISTINCT d.domain_id) as domain_count FROM project_list p LEFT JOIN domain d ON p.domain_id = d.domain_id $where";
                    $domain_count_result = $conn->query($domain_count_query);
                    $domain_count = $domain_count_result ? $domain_count_result->fetch_assoc()['domain_count'] : 0;
                    ?>
                    <h3 style="font-size: 3.5rem; margin-bottom: 20px;"><?php echo $domain_count; ?></h3>
                    <p style="font-size: 1.8rem;">Domaine(s) au total</p>
                </div>
                <div class="icon">
                    <i class="fas fa-sitemap" style="font-size: 4.8rem;"></i>
                </div>
            </div>
        </div>
        <!-- Bloc pour afficher le nombre total de projets -->
        <div class="col-12 col-sm-6 col-md-12">
            <div class="small-box bg-light shadow-sm border">
                <div class="inner">
                    <?php
                    if (isset($_SESSION['login_type'])) {
                        $project_count_query = "SELECT COUNT(*) as project_count FROM project_list p $where";
                        $project_count_result = $conn->query($project_count_query);
                        if ($project_count_result) {
                            $project_count = $project_count_result->fetch_assoc()['project_count'];
                        } else {
                            $project_count = 0;
                        }
                    } else {
                        $project_count = 0;
                    }
                    ?>
                    <h3 style="font-size: 3.5rem; margin-bottom: 20px;"><?php echo $project_count; ?></h3>
                    <p style="font-size: 1.8rem;">Projet(s) au total</p>
                </div>
                <div class="icon">
                    <i class="nav-icon fas fa-hard-hat" style="font-size: 5rem;"></i>
                </div>
            </div>
        </div>
        <!-- Fin du bloc pour afficher le nombre total de projets -->
         
        <?php if (isset($_SESSION['login_type'])): ?>
            <?php
            $login_type = $_SESSION['login_type'];
            $login_id = $_SESSION['login_id'];
            
            if (!in_array($login_type, [8, 10])): // Cacher pour les types 8 et 10
                if (in_array($login_type, [1, 2, 3, 4, 5, 6, 7])) {
                    // Compter tous les livrables
                    $report_count_query = "SELECT COUNT(*) as report_count FROM report";
                } elseif ($login_type == 9) {
                    // Compter les livrables où l'utilisateur est sender_id ou recipient_id
                    $report_count_query = "SELECT COUNT(*) as report_count FROM report 
                                            WHERE sender_id = '$login_id' OR FIND_IN_SET('$login_id', recipient_id)";
                } else {
                    $report_count_query = ""; // Aucun livrable pour les autres types
                }

                if (!empty($report_count_query)) {
                    $report_count_result = $conn->query($report_count_query);
                    if ($report_count_result) {
                        $report_count = $report_count_result->fetch_assoc()['report_count'];
                    } else {
                        $report_count = 0;
                    }
                } else {
                    $report_count = 0;
                }
            ?>
                <div class="col-12 col-sm-6 col-md-12">
                    <div class="small-box bg-light shadow-sm border">
                        <div class="inner">
                            <h3 style="font-size: 3.5rem; margin-bottom: 20px;"><?php echo $report_count; ?></h3>
                            <p style="font-size: 1.8rem;">Livrable(s) au total</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-file-alt" style="font-size: 5rem;"></i>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Fin du bloc pour afficher le nombre total des activités -->
    </div>
</div>
</div>
