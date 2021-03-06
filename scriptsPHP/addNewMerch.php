<?php
require_once 'mysqlDatabaseAPI/databaseAPI.php';
$catTitle= $_POST["merchAdmCat"];
$merchTitle= $_POST["merchAdmTitle"];
$price= $_POST["merchAdmPrice"];
$measureText= $_POST["merchAdmMeasure"];
$description= $_POST["merchAdmDescription"];
$imageName=time().$_FILES["merchAdmImage"]["name"];
$imageTmpName=$_FILES["merchAdmImage"]["tmp_name"];
if(!move_uploaded_file($imageTmpName,"../images/merches/".$imageName))
{
    exit("Ошибка записи файла изображения на сервер.");
}
$cats = DatabaseAPI::GetCatIdByTitle($catTitle);
if($cats->num_rows<1)
{
    exit("Вы указали не существующую категорию товаров.");
}
$idCat=mysqli_fetch_assoc($cats);
$measures = DatabaseAPI::GetMeasureIdByText($measureText);
if($measures->num_rows<1)
{
    exit("Вы указали не существующую единицу измерения товаров.");
}
$idMeasure=mysqli_fetch_assoc($measures);      
DatabaseAPI::insertNewMerch($idCat["id"], $idMeasure["id"], $merchTitle, $description, $price, $imageName);
exit("true");
