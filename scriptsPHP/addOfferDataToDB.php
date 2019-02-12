<?php
require_once 'mysqlDatabaseAPI/databaseAPI.php';
$goods = $_POST["goods"];
$name= $_POST["name"];
$address = $_POST["address"];
$phone = $_POST["phone"];
$offerId = DatabaseAPI::insertNewOffer($name, $address, $phone);
if($offerId==-1)
{
    exit("false");
}
foreach ($goods as $value) 
{
    $price = mysqli_fetch_assoc(DatabaseAPI::GetMerchInfoForOfferList($value['id']));
    $merchId=$value['id'];
    $merchCount=$value['num'];    
    DatabaseAPI::addOfferItem($offerId, $merchId, $merchCount);
}
exit("true");
