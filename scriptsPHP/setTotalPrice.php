<?php
require_once 'mysqlDatabaseAPI/databaseAPI.php';
$goods = $_POST["goods"];
$totalPrice=0;
if($goods!=null)
{
    foreach ($goods as $value) 
    {
       $price = mysqli_fetch_assoc(DatabaseAPI::GetPrice($value['id']));
       $totalPrice+= (int)$price['price']*(int)$value['num'];
    }
}
echo "Стоимость товаров в корзине: $totalPrice руб.";