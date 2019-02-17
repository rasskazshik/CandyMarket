<!DOCTYPE html>
<html lang = "ru">
<head>
    <meta charset="utf-8" />
    <title>Аленка - магазин сладостей</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" media="screen" href="../styles/bootstrap.min.css" />
   
    <!--фреймворк jQuery-->
    <script defer src="../scriptsJS/jquery-3.3.1.min.js"></script>
    <!--собственные скрипты-->
    <script defer src="../scriptsJS/print.js"></script>
</head>
<body>
    <div class="container">
    <div class="row no-gutters content contentRow">
<?php
    require_once '../scriptsPHP/mysqlDatabaseAPI/databaseAPI.php';
    $offerId=$_POST["offerId"];
    $offers = DatabaseAPI::GetOfferById($offerId);
    if($offers->num_rows>0)
    {
        while($offer=mysqli_fetch_assoc($offers))
        {
            $name=$offer["client_name"];
            $address=$offer["address"];
            $phone=$offer["phone"];
            $offerState=$offer["offerState"];
            $date=$offer["date"];
            $offerId=$offer["offerId"];
            $totalprice=0;
print<<<END
<div class="offerList col-12 container">
    <h3>Магазин Аленка. Заказ №$offerId от $date</h3>
    <p>Имя клиента: $name</p>
    <p>Адрес доставки: $address</p>
    <p>Контактный телефон: $phone</p>
    <h3>Список товаров:</h3>
    <ol>
END;
            $offerItems=DatabaseAPI::GetOfferItems($offer["offerId"]);
            //перебираем все товары
            while($offerItem=mysqli_fetch_assoc($offerItems))
            {   
                $merchId = $offerItem["id_merchandise"];
                $offerItemId=$offer["id"];
                $merchInfo = mysqli_fetch_assoc(DatabaseAPI::GetMerchInfoForOfferListByOfferId($merchId,$offer["offerId"]));
                $count = $offerItem["count_in_offer"];
                $totalMerchPrice = ($merchInfo['price']*$count)."руб.";
                $totalprice+=$merchInfo['price']*$count;
                $title=$merchInfo['title'];
                $measure=$merchInfo['measure'];
                $description=$merchInfo['description'];
                $price=($merchInfo['price'])."руб.";     

print<<<END
    <li>$title ($measure $price) x $count - $totalMerchPrice</li>
END;
            }
print<<<END
    </ol>
    <p>Итого: $totalprice руб.</p>
    <br>
    <h3>Заказ получен:</h3>
    <hr>
        
</div>
END;
        }
    }
    else
    {
print<<<END
    <div class="col-12">
        <p>Заказа с указанным идентификатором не существует</p>
    </div>
END;
}
?>
        </div>
    </div>   
</body>
</html>