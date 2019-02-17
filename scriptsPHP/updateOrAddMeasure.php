<?php
require_once 'mysqlDatabaseAPI/databaseAPI.php';
$newMeasureText = $_POST["newMeasureText"]; 
$oldMeasureText = $_POST["oldMeasureText"]; 
if($oldMeasureText==null)
{
    DatabaseAPI::InsertMeasure($newMeasureText);
    exit("true");
}
else
{
    $measureId=DatabaseAPI::GetMeasureIdByText($oldMeasureText);
    $id= mysqli_fetch_assoc($measureId);
    DatabaseAPI::UpdateMeasureById($id['id'],$newMeasureText);
    exit("true");
}
