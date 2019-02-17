<?php
require_once 'mysqlDatabaseAPI/databaseAPI.php';
$id = $_POST["id"]; 
$image = $_POST["image"];
if(file_exists("../images/actions/$image"))
{
    unlink("../images/actions/$image");    
}    
DatabaseAPI::DeleteActionById($id);
exit("true");
