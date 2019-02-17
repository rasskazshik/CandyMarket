<?php
require_once 'mysqlDatabaseAPI/databaseAPI.php';
$oldMeasureText = $_POST["oldMeasureText"]; 
$measureId=DatabaseAPI::GetMeasureIdByText($oldMeasureText);
$id= mysqli_fetch_assoc($measureId);
DatabaseAPI::DeleteMeasureById($id['id']);
exit("true");