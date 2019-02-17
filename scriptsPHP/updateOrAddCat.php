<?php
require_once 'mysqlDatabaseAPI/databaseAPI.php';
$newCatText = $_POST["newCatText"]; 
$oldCatText = $_POST["oldCatText"]; 
if($oldCatText==null)
{
    DatabaseAPI::InsertCat($newCatText);
    exit("true");
}
else
{
    $catId=DatabaseAPI::GetCatIdByTitle($oldCatText);
    $id= mysqli_fetch_assoc($catId);
    DatabaseAPI::UpdateCatById($id['id'],$newCatText);
    exit("true");
}
