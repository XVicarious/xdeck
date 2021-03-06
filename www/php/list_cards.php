<?php
require "admin_functions.php";
require "SqlStatements.php";
$dbh = createPDO2();
$stmt = $dbh->prepare(SqlStatements::AUTOCOMPLETE_CARDS);
$query = '%' . $_GET["query"] . '%';
$stmt->bindParam(":query", $query, PDO::PARAM_STR);
$stmt->execute();
echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
