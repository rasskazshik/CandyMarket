<?php
    require_once '../scriptsPHP/mysqlDatabaseAPI/databaseAPI.php';
    $goods = $_POST["goods"];
    $report = $_POST["report"];
    $totalPrice=0;
    
if($goods==null)
{
    if($report==null)
    {
print<<<END
<div class="offerList col container">
    Список покупок пуст.
</div>
END;
    }
    else
    {
print<<<END
<div class="offerList col container">
    $report
</div>
END;
    }
}
else {
print<<<END
<div class="offerList col container">
    <h3>Список покупок:</h3>
    <ol>
END;
    foreach ($goods as $value) 
    {
       $price = mysqli_fetch_assoc(DatabaseAPI::GetMerchInfoForOfferList($value['id']));
       $id=$value['id'];
       $totalMerchPrice = ($price['price']*$value['num'])."руб.";
       $totalprice+=$price['price']*$value['num'];
       $num=$value['num'];
       $title=$price['title'];
       $measure=$price['measure'];
       $description=$price['description'];
       $price=($price['price'])."руб.";
   
print<<<END
        <li>$title ($measure $price) x $num - $totalMerchPrice <span addId="$id">+</span> / <span subId="$id">-</span></li>
END;
}
print<<<END
    </ol>
    <p>Итого: $totalprice руб.</p>
    <form class="sendOfferData">
        <input type="text" class="name" required placeholder="Как к Вам обращаться">
        <input type="text" class="address" required placeholder="Адрес для доставки заказа">
        <input type="text" class="phone" required placeholder="Телефон для уточнения деталей заказа">
        <p>Отправляя заявку Вы соглашаетесь на хранение и обработку Ваших персональных данных. Они будут использованы исключительно для связи с Вами и осуществления доставки товара.</p>
        <input class="offerListSubmit" type="submit" value="Отправить заявку">
    </form>
</div>
END;
}
?>