<?php include 'db_connect.php'; ?>
<div class="col-lg-12 col-md-12">
    <div class="card card-outline card-success">
        <div class="card-header">
            <b>Projets par Domaine</b>
            <div class="card-tools">
                <button class="btn btn-flat btn-sm bg-gradient-success btn-success" id="print"><i class="fa fa-print"></i> Imprimer</button>
            </div>
        </div>
        <div class="card-body p-0">
            <!-- Personnalisation des contenus des cellules -->
            <style>
                .large-font {
                    font-size: 18px; /* Augmenter la taille de la police */
                }
                .project-item {
                    display: block;
                    margin-bottom: 30px; /* Espace entre les projets */
                }
                .domain-column {
                    text-align: center; /* Centrer horizontalement */
                    vertical-align: middle; /* Centrer verticalement */
                    font-weight: bold;  /* Mettre le texte en gras */
                }
            </style>
            <div class="table-responsive" id="printable">
                <table class="table m-0 table-bordered">
                    <thead>
                    <tr>
                        <th style="width: 10%; text-align: center; vertical-align: middle;">#</th>
                        <th style="width: 20%; text-align: center; vertical-align: middle;">Domaine</th>
                        <th style="width: 60%; text-align: center; vertical-align: middle;">Projets</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    $i = 1;
                    $qry = $conn->query("SELECT c.domain_id, c.name, GROUP_CONCAT(CONCAT(p.id, '|', p.name) SEPARATOR '||') as projects
                                        FROM domain c 
                                        LEFT JOIN project_list p ON p.domain_id = c.domain_id
                                        GROUP BY c.domain_id");
                    while ($row = $qry->fetch_assoc()):
                    ?>
                        <tr>
                            <td style="width: 10%; vertical-align: middle;"><span class="large-font"><?php echo $i++ ?></span></td>
                            <td class="domain-column" style="width: 20%; vertical-align: middle;"><span class="large-font"><?php echo ucwords($row['name'] ?? '') ?></span></td>
                            <td style="width: 60%; vertical-align: middle;">
                                <span class="large-font">
                                    <?php
                                    if (!empty($row['projects'])) {
                                        $projects = explode('||', $row['projects']);
                                        foreach ($projects as $project) {
                                            list($project_id, $project_name) = explode('|', $project, 2); // Divise une seule fois
                                            echo "<a href='./index.php?page=view_project&id=" . htmlspecialchars($project_id) . "' class='project-item'>" . ucwords(htmlspecialchars($project_name)) . "</a> ";
                                        }
                                    }
                                    ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    $('#print').click(function(){
        start_load();
        var _h = $('head').clone();
        var _p = $('#printable').clone();
        var _d = "<p class='text-center'><b>Rapport des projets par pays en date du (<?php echo date('F d, Y') ?>)</b></p>";
        _p.prepend(_d);
        _p.prepend(_h);
        var nw = window.open('', '', 'width=900,height=600');
        nw.document.write(_p.html());
        nw.document.close();
        nw.print();
        setTimeout(function(){
            nw.close();
            end_load();
        }, 750);
    });
</script>
