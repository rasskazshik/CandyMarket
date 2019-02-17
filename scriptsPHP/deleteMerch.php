<?php
require_once 'mysqlDatabaseAPI/databaseAPI.php';
$id = $_POST["id"]; 
$image = $_POST["imageName"];
if(file_exists("../images/merches/$image"))
{
    unlink("../images/merches/$image");    
}    
DatabaseAPI::DeleteMerchById($id);
exit("true");
