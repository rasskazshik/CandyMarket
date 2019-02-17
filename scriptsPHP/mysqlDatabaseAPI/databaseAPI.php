<?php
class DatabaseAPI{  
    //данные для подключения к базе данных
    private static $host = "localhost";
    private static $db_name = "alenka";
    private static $username = "root";
    private static $password = "mysql";
    public static $conn;    
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
            return $result;
        }
         catch(mysqli_sql_exception $exception)
        {
             throw $exception;            
        }       
    }
    //метод для передачи запроса добавления к бд с отслеживанием закрытия соединения и возвратом идентификатора добавленной записи
    private function Insert($queryString)
    {
        try
        {
            self::Connect();        
            $result = self::$conn->query($queryString);
            $id=-1;
            if($result)
            {
                $id = self::$conn->insert_id;
            }
            self::Disconnect();
            return $id;
        }
         catch(mysqli_sql_exception $exception)
        {
             throw $exception;            
        }       
    }
    //получение списка всех категорий
    public static function GetAllCat()
    {
        $query.="SELECT * FROM `category` WHERE `is_deleted`=0";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    //получение списка всех состояний заказа
    public static function GetAllOfferState()
    {
        $query.="SELECT * FROM `offer_state`";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    //получение списка всех телефонных номеров заказа
    public static function GetAllOffersPhone()
    {
        $query.="SELECT DISTINCT `phone` FROM `offer`";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    //получение списка всех телефонных номеров заказа
    public static function GetAllOffersId()
    {
        $query.="SELECT `id` FROM `offer`";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    //получение списка единиц измерения
    public static function GetAllMerchandiseMeasure()
    {
        $query.="SELECT * FROM `merchandise_measure` WHERE `is_deleted`=0";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    //получение списка товаров по категории или же ключевому слову названия/описания с указанием направления сортировки по цене
    //сортировка в обратном порядке - параметр $orderDirection == DESC
    //если фильтр не нужен - передать пустую строку
    public static function GetMerchByCat($catTitle,$searchText,$orderDirection)
    {
        $query="
SELECT `merchandise`.`id`, `merchandise`.`title`,`merchandise`.`description`,`merchandise`.`price`,`merchandise_measure`.`text`as'measure',`merchandise`.`image`,`category`.`title` as 'category'
FROM `merchandise`
LEFT JOIN `merchandise_measure` 
ON `merchandise`.`id_measure`=`merchandise_measure`.`id`
LEFT JOIN `category`
ON `merchandise`.`id_category`=`category`.`id`
WHERE `merchandise`.`is_deleted`=0 ";
        if($catTitle!="")
        {
          $query.="AND `category`.`title`='$catTitle' ";
          if($searchText!="")
          {
              $query.="AND (`merchandise`.`title` LIKE '%$searchText%' OR `merchandise`.`description` LIKE '%$searchText%') ";
          }
        }
        else
        {
            if($searchText!="")
          {
              $query.="AND (`merchandise`.`title` LIKE '%$searchText%' OR `merchandise`.`description` LIKE '%$searchText%') ";
          }
        }
        if($orderDirection=="DESC")
        {
            $query.="ORDER BY `merchandise`.`price` DESC";
        }
        else
        {
            $query.="ORDER BY `merchandise`.`price`";
        }
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    //получение списка заказов согласно статусу и сортировкой по дате заказа
    //сортировка в обратном порядке - параметр $orderDirection == DESC
    //если фильтр не нужен - передать пустую строку
    public static function GetOffersByStateAndPhoneAndOfferId($offerId,$stateId,$phone)
    {
        $query="
SELECT `offer`.`id` as 'offerId', `offer`.`date`, `offer`.`client_name`, `offer`.`address`, `offer`.`phone`, `offer_state`.`title` as 'offerState'
FROM `offer`
LEFT JOIN `offer_state`
ON `offer`.`id_state`=`offer_state`.`id`
";
        if ($stateId!="")
        {
            $query.="WHERE `offer_state`.`title`='$stateId' ";
            if($phone!="")
            {
                $query.="AND `offer`.`phone` LIKE '$phone' ";  
                if($offerId!="")
                {
                    $query.="AND `offer`.`id` =$offerId ";                
                }
            }
            else
            {
                if($offerId!="")
                {
                    $query.="AND `offer`.`id` =$offerId ";                
                }
            }
        }
        else
        {
            if($phone!="")
            {
                $query.="WHERE `offer`.`phone` LIKE '$phone' ";
                if($offerId!="")
                {
                    $query.="AND `offer`.`id` =$offerId ";                
                }
            }
            else
            {
                if($offerId!="")
                {
                    $query.="WHERE `offer`.`id` =$offerId ";                
                }
            }
        }
        $query.="ORDER BY `offer`.`date` ";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function GetOfferById($id)
    {
        $query="
SELECT `offer`.`id` as 'offerId', `offer`.`date`, `offer`.`client_name`, `offer`.`address`, `offer`.`phone`, `offer_state`.`title` as 'offerState'
FROM `offer`
LEFT JOIN `offer_state`
ON `offer`.`id_state`=`offer_state`.`id`
WHERE `offer`.`id`=$id 
";      
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    //получение списка товаров в заказе
    public static function GetOfferItems($offerId)
    {
        $query="SELECT `id`, `id_merchandise`, `count_in_offer` FROM `offer_item` WHERE `id_offer`=$offerId";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    //получение списка новостей
    public static function GetActions()
    {
        $query="SELECT * FROM `actions` ORDER BY `id` DESC";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function DeleteActionById($id)
    {
        $query="DELETE FROM `actions` WHERE `id`=$id";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function UpdateOfferById($id,$name,$address,$phone)
    {
        $query="UPDATE `offer` SET `client_name`='$name',`address`='$address',`phone`='$phone' WHERE `id`=$id";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function UpdateOfferState($offerId,$stateId)
    {
        $query="UPDATE `offer` SET `id_state`=$stateId  WHERE `id`=$offerId";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function GetPrice($id)
    {
        $query="SELECT `price` FROM `merchandise` WHERE `id`=$id";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function GetCatIdByTitle($title)
    {
        $query="SELECT `id` FROM `category` WHERE `title`='$title'";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function GetMeasureIdByText($text)
    {
        $query="SELECT `id` FROM `merchandise_measure` WHERE `text`='$text'";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function GetMerchInfo($id)
    {
        $query="
SELECT `merchandise`.`price`,`merchandise`.`title`, `merchandise_measure`.`text` AS 'measure'
FROM `merchandise` 
LEFT JOIN `merchandise_measure`
ON `merchandise`.`id_measure`=`merchandise_measure`.`id`
WHERE `merchandise`.`id`=$id";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function GetMerchInfoForOfferList($id)
    {
        $query="
SELECT `merchandise`.`id`,`merchandise`.`title`,`merchandise`.`description`,`merchandise`.`price`,`merchandise_measure`.`text`as'measure'
FROM `merchandise`
LEFT JOIN `merchandise_measure`
ON `merchandise`.`id_measure`=`merchandise_measure`.`id`
WHERE `merchandise`.`id`=$id";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function GetMerchInfoForOfferListByOfferId($idMerch,$idOffer)
    {
        $query="
SELECT `merchandise`.`id`,`merchandise`.`title`,`merchandise`.`description`,`merchandise`.`price`,`merchandise_measure`.`text`as'measure'
FROM `merchandise`
LEFT JOIN `merchandise_measure`
ON `merchandise`.`id_measure`=`merchandise_measure`.`id`
RIGHT JOIN `offer_item`
ON `merchandise`.`id`=`offer_item`.`id_merchandise`
WHERE `merchandise`.`id`=$idMerch AND `offer_item`.`id_offer`=$idOffer";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function UpdateMerchById($id,$idCat,$idMeasure,$title,$description,$price,$image)
    {
        $query="UPDATE `merchandise` SET `id_category`=$idCat,`id_measure`=$idMeasure,`title`='$title',`description`='$description',`price`=$price,`image`='$image' WHERE `id`=$id";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function DeleteMerchById($id)
    {
        $query="UPDATE `merchandise` SET `is_deleted`=1 WHERE `id`=$id";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function insertNewOffer($name,$address,$phone)
    {        
        $date = gmdate('Y-m-d H:i:s',time() + 3600*3);
        $query="INSERT INTO `offer`(`id_state`, `date`,`client_name`,`address`, `phone`) VALUES (1,'$date','$name','$address','$phone')";
        try
        {
            $PK = self::Insert($query);
            //ты возвращаешь id записи
            return $PK;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function insertNewMerch($idCat,$idMeasure,$title,$description,$price,$image)
    {   
        $query="INSERT INTO `merchandise`(`id_category`, `id_measure`, `title`, `description`, `price`, `image`) VALUES ($idCat,$idMeasure,'$title','$description',$price,'$image')";
        try
        {
            $PK = self::Insert($query);
            //ты возвращаешь id записи
            return $PK;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function insertNewAction($title,$description,$image)
    {   
        $query="INSERT INTO `actions`(`title`, `text`, `image`) VALUES ('$title','$description','$image')";
        try
        {
            $PK = self::Insert($query);
            //ты возвращаешь id записи
            return $PK;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    //добавление товара в заказ 
    public static function addOfferItem($idOffer,$idMerch,$count)
    {        
        $date = gmdate('Y-m-d H:i:s',time() + 3600*3);
        $query="INSERT INTO `offer_item`(`id_offer`, `id_merchandise`, `count_in_offer`) VALUES ($idOffer,$idMerch,$count)";
        try
        {
            $PK = self::Insert($query);
            //ты возвращаешь id записи
            return $PK;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function UpdateMeasureById($id,$text)
    {
        $query="UPDATE `merchandise_measure` SET `text`='$text' WHERE `id`=$id";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function UpdateActionById($id,$title,$description,$image)
    {
        $query="UPDATE `actions` SET `title`='$title',`text`='$description',`image`='$image' WHERE `id`=$id";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    //добавление товара в заказ 
    public static function InsertMeasure($text)
    {        
        $query="INSERT INTO `merchandise_measure`(`text`) VALUES ('$text')";
        try
        {
            $PK = self::Insert($query);
            //ты возвращаешь id записи
            return $PK;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function UpdateCatById($id,$text)
    {
        $query="UPDATE `category` SET `title`='$text' WHERE `id`=$id";
        try
        {
            $result = self::Query($query);
            //ты возвращаешь mysqli_result или null, если запрос навернулся
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    //добавление товара в заказ 
    public static function InsertCat($text)
    {        
        $query="INSERT INTO `category`(`title`) VALUES ('$text')";
        try
        {
            $PK = self::Insert($query);
            //ты возвращаешь id записи
            return $PK;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    public static function DeleteCatById($id)
    {        
        $query="UPDATE `category` SET `is_deleted`=1 WHERE `id`=$id";
        try
        {
            $PK = self::Insert($query);
            //ты возвращаешь id записи
            return $PK;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
    //добавление товара в заказ 
    public static function DeleteMeasureById($id)
    {        
        $query="UPDATE `merchandise_measure` SET `is_deleted`=1 WHERE `id`=$id";
        try
        {
            $PK = self::Insert($query);
            //ты возвращаешь id записи
            return $PK;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
}