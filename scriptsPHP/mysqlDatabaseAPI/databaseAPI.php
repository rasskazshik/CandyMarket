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
}