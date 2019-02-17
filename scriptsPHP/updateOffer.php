<?php
require_once 'mysqlDatabaseAPI/databaseAPI.php';
$id = $_POST["id"]; 
$name = $_POST["name"];
$address = $_POST["address"];
$phone= $_POST["phone"];     
DatabaseAPI::UpdateOfferById($id, $name, $address, $phone);
exit("true");
