<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM report_archive WHERE report_id = " . $_GET['id'])->fetch_array();
foreach ($qry as $k => $v) {
    $$k = $v;
}
include 'new_report.php'; // Ensure this file contains the form for editing a contract
?>
