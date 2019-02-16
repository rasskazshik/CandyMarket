<?php
    require_once '../scriptsPHP/mysqlDatabaseAPI/databaseAPI.php';
    $keywords=$_POST["keywords"];
    if($keywords==null)
    {
        $keywords="";
    }
    $state=$_POST["state"];
    if($state==null)
    {
        $state="";
    }
    $sort=$_POST["sort"];
    if($sort!="DESK")
    {
        $state="";
    }
    $offers = DatabaseAPI::GetOffersByStateAndKeywords($state,$keywords,$sort);
    if($offers->num_rows>0)
    {
        while($offer=mysqli_fetch_assoc($offers))
        {
            $name=$offer["client_name"];
            $address=$offer["address"];
            $phone=$offer["phone"];
            $totalprice=0;
print<<<END
<div class="offerList col-12 container">
    <p>Заказ</p>
    <input type="text" class="name" value="$name" required placeholder="Имя клиента">
    <input type="text" class="address" value="$address" required placeholder="Адрес для доставки заказа">
    <input type="text" class="phone" value="$phone" required placeholder="Телефон для уточнения деталей заказа">
    <h3>Список покупок:</h3>
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
    <li>$title ($measure $price) x <input type="number" offerItemId="$offerItemId" value="$count"> - $totalMerchPrice</li>
END;
            }
print<<<END
    </ol>
    <p>Итого: $totalprice руб.</p>    
</div>
END;
        }
    }
    else
    {
print<<<END
    <div class="col-12">
        <p>Заказы отсутствуют</p>
    </div>
END;
}