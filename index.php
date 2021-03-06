<?php 
session_start();
?>
<!DOCTYPE html>
<html lang = "ru">
<head>
    <meta charset="utf-8" />
    <title>Аленка - магазин сладостей</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" media="screen" href="styles/bootstrap.min.css" />
    <link defer rel="stylesheet" type="text/css" media="screen" href="styles/main.css" />
    <!--фреймворк jQuery-->
    <script defer src="scriptsJS/jquery-3.3.1.min.js"></script>
    <!--плагин jQuery для работы с куки-->
    <script defer src="scriptsJS/jquery.cookie.js"></script>
    <!--плагин для всплывающих подсказок (нужен для Bootstrap4)-->
    <script defer src="scriptsJS/popper.min.js"></script>
    <!--Bootstrap4-->
    <script defer src="scriptsJS/bootstrap.min.js"></script>
    <!--собственные скрипты-->
    <script defer src="scriptsJS/main.js"></script>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="row">
                <div class="logo col-lg-8">
                    <div>Магазин Аленка</div>
                </div>
                <div class="shopingCart col-lg-4 row align-items-center">
                    <div class="col-3">
                    <img class="img-fluid" src="images/cart.png">
                    </div>
                    <div class="offerPrice col-9">Стоимость товаров в корзине: 0 руб.</div>
                </div>
            </div>
            <!--навигация-->
            <div class="row navigation no-gutters">
                <a class="col-md container navToCatalog active"><div>Каталог</div></a>
                <a class="col-md container navToActions"><div>Новости</div></a>
                <a class="col-md container navToContacts"><div>Контактные данные</div></a>
            </div>  
        </div>
        <!--контейнер для выгрузки контента-->
        <div class="row no-gutters content contentRow">

        </div>
    </div> 
    
    <!---->
    
    <div class="addMerchLayout row align-items-center">
    <div class="col container ">
        <form class="setCount">
            <input type="text" class="countMerch" placeholder="Количество товара">
            <input type="submit" value="Принять">
            <input type="button" class="Cancel" value="Отмена">
        </form>
    </div>
    </div>
</body>
</html>