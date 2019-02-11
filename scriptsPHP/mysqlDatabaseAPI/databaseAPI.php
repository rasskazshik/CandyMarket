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
            self::$conn->query($queryString);   
            $id = self::$conn->insert_id;
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
        $query.="SELECT * FROM `category`";
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
    //получение списка единиц измерения
    public static function GetAllMerchandiseMeasure()
    {
        $query.="SELECT * FROM `merchandise_measure`";
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
    public static function GetMerchByCat($catId,$searchText,$orderDirection)
    {
        $query="
SELECT `merchandise`.`id`, `merchandise`.`title`,`merchandise`.`description`,`merchandise`.`price`,`merchandise_measure`.`text`as'measure',`merchandise`.`image`
FROM `merchandise` LEFT JOIN `merchandise_measure` ON `merchandise`.`id_measure`=`merchandise_measure`.`id` ";
        if($catId!="")
        {
          $query.="WHERE `merchandise`.`id_category`=$catId ";
          if($searchText!="")
          {
              $query.="AND (`merchandise`.`title` LIKE '%$searchText%' OR `merchandise`.`description` LIKE '%$searchText%') ";
          }
        }
        else
        {
            if($searchText!="")
          {
              $query.="WHERE (`merchandise`.`title` LIKE '%$searchText%' OR `merchandise`.`description` LIKE '%$searchText%') ";
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
    public static function GetOffersByState($stateId,$orderDirection)
    {
        $query="
SELECT `offer`.`id`,`offer`.`date`,`offer`.`client_name`,`offer`.`email`,`offer`.`phone`,`offer`.`address`,`offer_state`.`title`as'state'
FROM `offer` LEFT JOIN `offer_state` ON `offer`.`id_state`=`offer_state`.`id` ";
        if($stateId!="")
        {
          $query.="WHERE `offer`.`id_state`=$stateId ";
        }
        if($orderDirection=="DESC")
        {
            $query.="ORDER BY `offer`.`date` DESC";
        }
        else
        {
            $query.="ORDER BY `offer`.`date`";
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
    //получение списка товаров в заказе с калькуляцией стоимости наименования по id заказа
    public static function GetOfferItemsByOfferId($offerId)
    {
        $query="
SELECT `offer_item`.`id`AS'offer_item_id',`merchandise`.`price`,`offer_item`.`count_in_offer`,(`offer_item`.`count_in_offer`*`merchandise`.`price`)AS'total_price',`merchandise`.`id`AS'merch_id',`merchandise`.`title`
FROM `offer_item` LEFT JOIN `merchandise` ON `offer_item`.`id_merchandise`=`merchandise`.`id`
WHERE `offer_item`.`id_offer`=$offerId";
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
    //получение списка акций
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
    //получение списка товаров участвующих в акции
    public static function GetActionItems($actionId)
    {
        $query="
SELECT `action_item`.`id`,`merchandise`.`title`,`action_item`.`discount_precent`
FROM `action_item` LEFT JOIN `merchandise` ON `action_item`.`id_merchandise`=`merchandise`.`id`
WHERE `action_item`.`id_action`=$actionId";
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
    
    public static function insertNewOffer($name,$address,$phone)
    {
        $date = date('Y-m-d H:i:s');
        $query="INSERT INTO `offer`(`id_state`, `date`,`client_name`,`address`, `phone`) VALUES (0,$date,$name,$address,$phone)";
        try
        {
            $result = self::Insert($query);
            //ты возвращаешь xbckj - id записи
            return $result;
        }
        catch(mysqli_sql_exception $exception)
        {
            die("Ошибка. Код: ".$exception->code.". Описание ошибки: ".$exception->errorMessage().".");
        }
    }
    
}