<?php 
session_start();
?>
<!DOCTYPE html>
<html lang = "ru">
<head>
    <meta charset="utf-8" />
    <title>Конфетка - магазин сладостей</title>
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
                <div class="logo col-md-8">
                    <div>Магазинушко Аленка</div>
                </div>
                <div class="shopingCart col-md-4 row align-items-center">
                    <div class="col-3">
                    <img class="img-fluid" src="images/cart.png">
                    </div>
                    <div class="col-9">Стоимость товаров в корзине: 100500 руб.</div>
                </div>
            </div>
            <!--навигация-->
            <div class="row navigation no-gutters">
                <a class="col-md container navToCatalog active"><div>Каталог</div></a>
                <a class="col-md container navToActions"><div>Акции</div></a>
                <a class="col-md container navToContacts"><div>Контактные данные</div></a>
            </div>  
        </div>
        <!--контейнер для выгрузки контента-->
        <div class="row no-gutters content contentRow">

        </div>
        <!--футер-->
        <div class="row footer no-gutters">
            <span  class="container"><div>Благодарим Вас за то, что Вы с нами!</div></span>
        </div>
    </div>   
</body>
</html>