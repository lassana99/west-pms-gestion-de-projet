<?php
include 'db_connect.php';
$qry = $conn->query("SELECT * FROM contract WHERE contract_id = " . $_GET['id'])->fetch_array();
foreach ($qry as $k => $v) {
    $$k = $v;
}
include 'new_contract.php'; // Ensure this file contains the form for editing a contract
?>
