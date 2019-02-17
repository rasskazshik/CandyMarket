<?php 
    session_start();
    if($_SESSION["isAuth"]!=true)
    {
        header("Location: index.php");
    }
?>
<!DOCTYPE html>
<html lang = "ru">
<head>
    <meta charset="utf-8" />
    <title>Аленка - магазин сладостей</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="../favicon.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" media="screen" href="../styles/bootstrap.min.css" />
    <link defer rel="stylesheet" type="text/css" media="screen" href="../styles/main.css" />
    <!--фреймворк jQuery-->
    <script defer src="../scriptsJS/jquery-3.3.1.min.js"></script>
    <!--плагин jQuery для работы с куки-->
    <script defer src="../scriptsJS/jquery.cookie.js"></script>
    <!--плагин для всплывающих подсказок (нужен для Bootstrap4)-->
    <script defer src="../scriptsJS/popper.min.js"></script>
    <!--Bootstrap4-->
    <script defer src="../scriptsJS/bootstrap.min.js"></script>
    <script defer src="../scriptsJS/jquery.redirect.js" type="text/javascript"></script>
    <!--собственные скрипты-->
    <script defer src="../scriptsJS/main.js"></script>
</head>
<body>
    <div class="container">
        <div class="headerAdm">
            <div class="row">
                <div class="logo col-12">
                    <div>Магазин Аленка - заказы</div>
                </div>             
            </div>
            <!--навигация-->
            <div class="row navigation no-gutters">
                <a href="catalog.php" class="col-md container"><div>Товары</div></a>
                <a href="offers.php" class="col-md container"><div>Заказы</div></a>
                <a href="actions.php" class="col-md container"><div>Новости</div></a>
            </div>  
        </div>
        <!--контейнер для выгрузки контента-->
        <div class="row no-gutters content contentRow">
    <?php
        require_once '../content/adminOffers.php';
    ?>
        </div>
    </div>   
</body>
</html>