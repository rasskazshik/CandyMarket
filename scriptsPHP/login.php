<?php
$password=$_POST["password"];
if($password=="alenkaadmin")
{
    session_start();
    $_SESSION["isAuth"]=true;
    exit("true");
}
else
{
    exit("Неверный пароль");
}