<?php
require_once 'mysqlDatabaseAPI/databaseAPI.php';
$title= $_POST["actionAdmTitle"];
$description= $_POST["actionAdmText"];
$imageName=time().$_FILES["actionImage"]["name"];
$imageTmpName=$_FILES["actionImage"]["tmp_name"];
if(!move_uploaded_file($imageTmpName,"../images/actions/".$imageName))
{
    exit("Ошибка записи файла изображения на сервер.");
} 
DatabaseAPI::insertNewAction($title, $description, $imageName);
exit("true");
