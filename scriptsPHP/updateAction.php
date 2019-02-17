<?php
require_once 'mysqlDatabaseAPI/databaseAPI.php';
$id = $_POST["actionId"]; 
$oldImage = $_POST["actionOldImage"];

$title= $_POST["actionAdmTitle"];
$description= $_POST["actionAdmText"];
$imageName=time().$_FILES["actionImage"]["name"];
$imageTmpName=$_FILES["actionImage"]["tmp_name"];
if($_FILES["actionImage"]["name"]=="")
{
    $imageName=$oldImage;
}
else
{    
    if(file_exists("../images/actions/$oldImage"))
    {
        unlink("../images/actions/$oldImage");    
    }
    $imageName=time().$_FILES["actionImage"]["name"];
    $imageTmpName=$_FILES["actionImage"]["tmp_name"];
    if(!move_uploaded_file($imageTmpName,"../images/actions/".$imageName))
    {
        exit("Ошибка записи файла изображения на сервер.");
    }
}    
DatabaseAPI::UpdateActionById($id,$title, $description, $imageName);
exit("true");
