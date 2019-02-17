<?php
require_once 'mysqlDatabaseAPI/databaseAPI.php';
$offerId = $_POST["offerId"];
$stateId = $_POST["stateId"];
DatabaseAPI::UpdateOfferState($offerId,$stateId);
exit("true");