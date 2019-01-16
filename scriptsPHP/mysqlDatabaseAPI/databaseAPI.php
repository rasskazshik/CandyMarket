<?php
class DatabaseAPI{  
    //данные для подключения к базе данных
    private static $host = "localhost";
    private static $db_name = "5ka_feedback";
    private static $username = "root";
    private static $password = "mysql";
    public static $conn;  
    //private static $host = "81.90.180.128";
    //private static $db_name = "5ka_feedback";
    //private static $username = "5ka";
    //private static $password = "5kamysql";
    //public static $conn;  
    //метод для получения дескриптора подключения к базе данных
    private function Connect(){  
        self::$conn = null;  
        try{
            self::$conn = new mysqli(self::$host, self::$username, self::$password, self::$db_name);
            self::$conn->set_charset("utf8");
        }
        catch(mysqli_sql_exception $exception){
            throw $exception;
        }  
        return self::$conn;
    }
    //метод для разрыва соединения с базой данных
    private function Disconnect(){
        self::$conn->close();
    }
    //метод для передачи запроса к бд с отслеживанием закрытия соединения
    private function Query($queryString)
    {
        try
        {
            self::Connect();        
            $result=self::$conn->query($queryString);        
            self::Disconnect();
            return $result->fetch_assoc();
        }
         catch(mysqli_sql_exception $exception)
        {
             throw $exception;            
        }        
    }
    //метод для передачи запроса к бд с отслеживанием закрытия соединения
    private function ScalarQuery($queryString)
    {
        try
        {
            self::Connect();        
            $result=self::$conn->query($queryString);        
            self::Disconnect();
            return $result;
        }
         catch(mysqli_sql_exception $exception)
        {
             throw $exception;            
        }       
    }
    //получение id существующего пользователя по ссылке на профиль или добаление нового    
    private function GetUserId($name,$lastname,$photo,$profile)
    {
        try
        {
            $users=self::Query("SELECT * FROM `user` WHERE `profile`='$profile'");
            if($users!=NULL)
            {
                return $users['id'];
            }
            else
            {
                self::ScalarQuery("INSERT INTO `user`(`name`, `lastname`, `photo`, `profile`) VALUES ('$name','$lastname','$photo','$profile')");
                $users=self::Query("SELECT * FROM `user` WHERE `profile`='$profile'");
                return $users['id'];
            }
        }
        catch(mysqli_sql_exception $exception)
        {
            throw $exception;
        }
    }
    //получение id торговой точки  или добаление новой   
    private function GetMarketId($marketAddress)
    {
        try
        {
            $market=self::Query("SELECT * FROM `market` WHERE `address`='$marketAddress'");
            if($market!=NULL)
            {
                return $market['id'];
            }
            else
            {
                self::ScalarQuery("INSERT INTO `market`(`address`) VALUES ('$marketAddress')");
                $market=self::Query("SELECT * FROM `market` WHERE `address`='$marketAddress'");
                return $market['id'];
            }
        }
        catch(mysqli_sql_exception $exception)
        {
            throw $exception;
        }
    }
    //получение из трех таблиц данных о отзыве и пользователе конкретной торговой точки
    public static function GetFeedbacks($marketAddress)
    {
        try
        {
            $result = self::ScalarQuery("
select `feedback`.`id`as'feedbackId',`feedback`.`date`as'date',`feedback`.`feedback_text`as'feedbackText',`feedback`.`comment`as'feedbackComment', `user`.`id`as'userId',`user`.`name`as'userName',`user`.`lastname`as'userLastname',`user`.`photo`as'userPhoto',`user`.`profile`as'userProfile'
from `feedback`
left join `user` on `feedback`.`id_user`=`user`.`id`
left join `market` on `feedback`.`id_market`=`market`.`id`
where `market`.`address`='$marketAddress' and `feedback`.`deleted_by_user`=false
order by `feedback`.`date` desc
");
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка при получении отзывов. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    //добавление отзыва
    public static function AddFeedback($name,$lastname,$photo,$profile,$marketAddress,$feedbackText)
    {
        try
        {
            $marketId=self::GetMarketId($marketAddress);
            $userId=self::GetUserId($name,$lastname,$photo,$profile);
            $date=gmdate('Y-m-d H:i:s', time() + 3*3600);
            self::ScalarQuery("INSERT INTO `feedback`(`date`, `id_market`, `id_user`, `feedback_text`, `comment`) VALUES ('$date','$marketId','$userId','$feedbackText',NULL)");
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка при добавлении отзыва. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    } 
    //удаление отзыва
    public static function DeleteFeedback($feedbackId)
    {
        try
        {
            self::ScalarQuery("DELETE FROM `feedback` WHERE `id`=$feedbackId");
            self::KillFantoms();
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка при удалении отзыва. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    //обновление комментария
    public static function UpdateComment($feedbackId,$comment)
    {
        try
        {
            self::ScalarQuery("UPDATE `feedback` SET `comment`='$comment' WHERE `id`=$feedbackId");
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка при обновлении комментария. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }  
    //отметка о пользовательском удалении комментария
    public static function MarkDeletedByUser($feedbackId)
    {
        try
        {
            self::ScalarQuery("UPDATE `feedback` SET `deleted_by_user`=true WHERE `id`=$feedbackId");
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка при обновлении комментария. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    //получение данных о отзыве и пользователе без комментария представителя
    public static function CheckAdminPassword($password)
    {
        try
        {
            $result = self::Query("select * from `adminpanel` where `password`='$password'");
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            if($result==NULL)
            {
                return false;
            }
            else 
            {
                return true;
            }
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка при получении отзывов. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    //получение данных о отзыве и пользователе без комментария представителя
    public static function GetFeedbacksWithoutComment($marketAddress)
    {
        try
        {
            $result = self::ScalarQuery("
select `market`.`address`as'marketAdress',`feedback`.`id`as'feedbackId',`feedback`.`date`as'date',`feedback`.`feedback_text`as'feedbackText', `user`.`id`as'userId',`user`.`name`as'userName',`user`.`lastname`as'userLastname',`user`.`photo`as'userPhoto',`user`.`profile`as'userProfile'
from `feedback`
left join `user` on `feedback`.`id_user`=`user`.`id`
left join `market` on `feedback`.`id_market`=`market`.`id`
where `feedback`.`comment` is null and `feedback`.`deleted_by_user`=false
order by `feedback`.`date`
");
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка при получении отзывов. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    //получение имен пользователей
    public static function GetAllUsersNames()
    {
        try
        {
            $result = self::ScalarQuery("SELECT `id`,`name`,`lastname` FROM `user`");
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка при получении отзывов. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    //получение имен пользователей
    public static function GetAllMarketsAddresses()
    {
        try
        {
            $result = self::ScalarQuery("SELECT `id`, `address` FROM `market`");
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка при получении отзывов. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    //получение из трех таблиц данных о отзыве и пользователе конкретной торговой точки
    public static function GetSearchedFeedbacks($userId,$marketId)
    {
        $query="
select `feedback`.`deleted_by_user`as'deletedByUser',`market`.`address`as'marketAdress',`feedback`.`id`as'feedbackId',`feedback`.`date`as'date',`feedback`.`feedback_text`as'feedbackText',`feedback`.`comment`as'feedbackComment', `user`.`id`as'userId',`user`.`name`as'userName',`user`.`lastname`as'userLastname',`user`.`photo`as'userPhoto',`user`.`profile`as'userProfile'
from `feedback`
left join `user` on `feedback`.`id_user`=`user`.`id`
left join `market` on `feedback`.`id_market`=`market`.`id` ";
        if($userId!="")
        {
          $query.="where `user`.`id`=$userId ";
          if($marketId!="")
          {
              $query.="and `market`.`id`=$marketId ";
          }
        }
        else
        {
            if($marketId!="")
          {
              $query.="where `market`.`id`=$marketId ";
          }
        }
        $query.="order by `feedback`.`date` desc";
        try
        {
            $result = self::ScalarQuery($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка при получении отзывов. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    //удаление неиспользуемых в отзывах данных из таблиц пользователей и торговых точек
    private static function KillFantoms()
    {
        try
        {
            self::ScalarQuery("delete from `user` where `user`.`id` not in (select `feedback`.`id_user` from `feedback`)");
            self::ScalarQuery("delete from `market` where `market`.`id` not in (select `feedback`.`id_market` from `feedback`)");
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка при удалении отзыва. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    //обновление всего отзыва
    public static function UpdateFeedback($feedbackId,$feedback,$comment)
    {
        try
        {
            self::ScalarQuery("UPDATE `feedback` SET `comment`='$comment',`feedback_text`='$feedback' WHERE `id`=$feedbackId");
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка при обновлении комментария. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    } 
}