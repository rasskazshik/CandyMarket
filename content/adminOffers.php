<?php
    require_once '../scriptsPHP/mysqlDatabaseAPI/databaseAPI.php';
    $phone=$_POST["phone"];
    if($phone==null)
    {
        $phone="";
    }
    $state=$_POST["state"];
    if($state==null)
    {
        $state="";
    }
    $searchOfferId=$_POST["searchOfferId"];
    if($searchOfferId==null)
    {
        $searchOfferId="";
    }
print<<<END
<div class="search col-md-12">
    <input class="stateId" list="stateId" value="$state" autocomplete="off" placeholder="Укажите искомый статус заказа">
    <datalist id="stateId">
END;
    $states = DatabaseAPI::GetAllOfferState();
    //перебираем все товары из результата
    while($statesToSearch=mysqli_fetch_assoc($states))
    {
        $text=$statesToSearch['title'];
        echo "<option value='$text'>";
    }
print<<<END
    </datalist>
    <br>
</div>
<div class="search col-md-12">
    <input class="phone" list="phone" value="$phone" autocomplete="off" placeholder="Укажите искомый номер телефона">
    <datalist id="phone">
END;
    $phones = DatabaseAPI::GetAllOffersPhone();
    //перебираем все товары из результата
    while($phoneToSearch=mysqli_fetch_assoc($phones))
    {
        $text=$phoneToSearch['phone'];
        echo "<option value='$text'>";
    }
print<<<END
    </datalist>
    <input type="button" id='findOffersAdm' value="Фильтрация заказов">
</div>
<div class="search col-md-12">
    <input class="searchOfferId" list="searchOfferId" autocomplete="off" value="$searchOfferId" placeholder="Укажите искомый номер заказа">
    <datalist id="searchOfferId">
END;
    $searchOfferIds = DatabaseAPI::GetAllOffersId();
    //перебираем все товары из результата
    while($searchOfferIdInList=mysqli_fetch_assoc($searchOfferIds))
    {
        $text=$searchOfferIdInList['id'];
        echo "<option value='$text'>";
    }
print<<<END
    </datalist>
    <br>
</div> 
END;
    $offers = DatabaseAPI::GetOffersByStateAndPhoneAndOfferId($searchOfferId,$state,$phone);
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
    <form class="offerForm">
        <p>Заказ №$offerId от $date</p>
        <p>Статус: $offerState</p>
        <input type="text" value="$offerId" name="id" hidden>
        <input type="text" class="name" name="name" value="$name" required placeholder="Имя клиента">
        <input type="text" class="address" name="address" value="$address" required placeholder="Адрес для доставки заказа">
        <input type="text" class="phone" name="phone" value="$phone" required placeholder="Телефон для уточнения деталей заказа">
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
    <li>$title ($measure $price) x $count - $totalMerchPrice</li>
END;
            }
print<<<END
    </ol>
    <p>Итого: $totalprice руб.</p>
END;
if($offerState!="Завершено"&&$offerState!="Заказ был отменен")
{
    echo "<input type= 'submit' value='Изменить заказ'>";
}

if($offerState=="Не обработано")
{
    echo "<input class='updateOfferState' offerId='$offerId' state='2' type='button' value='Принять заказ'>";
}
else
{
    if($offerState=="Выполняется")
    {
        echo "<input class='updateOfferState' offerId='$offerId' state='3' type='button' value='Завершить заказ'>";
    }
}
if($offerState!="Завершено"&&$offerState!="Заказ был отменен")
{
    echo "<input class='updateOfferState' offerId='$offerId' state='4' type='button' value='Отменить заказ'>";
}
print<<<END
        <input offerId="$offerId" class="printOffer" type="button" value="Печать информационного листа">
    </form>
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