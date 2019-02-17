<?php
require_once 'mysqlDatabaseAPI/databaseAPI.php';
$oldCatText = $_POST["oldCatText"]; 
$catId=DatabaseAPI::GetCatIdByTitle($oldCatText);
$id= mysqli_fetch_assoc($catId);
DatabaseAPI::DeleteCatById($id['id']);
exit("true");
