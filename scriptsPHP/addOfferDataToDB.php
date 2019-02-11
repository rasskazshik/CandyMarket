<?php
require_once 'mysqlDatabaseAPI/databaseAPI.php';
$goods = $_POST["goods"];
$name= $_POST["name"];
$address = $_POST["address"];
$phone = $_POST["phone"];
DatabaseAPI::AddOffer();
$offerId;
foreach ($goods as $value) 
{
    $price = mysqli_fetch_assoc(DatabaseAPI::GetMerchInfoForOfferList($value['id']));
    $merchId=$value['id'];
    $merchNum=$value['num'];
    DatabaseAPI::AddOfferItem($offerId,$merchId,$merchNum);
}
exit("true");
