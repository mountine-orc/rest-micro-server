<?php 
namespace Core
{
    use PDO;

    class Db
    {
        private static $_db = FALSE;
        
        function connect()
        {
            include("db-conf.php");

            try {  
                if (Db::$_db) 
                    return Db::$_db;
                  
                $opt = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ];
                $db = new PDO('mysql:host=localhost;dbname='.$dbConfig["dbname"], $dbConfig["user"], $dbConfig["password"], $opt); //TODO: move to config

                Db::$_db = $db;
                
                return $db;
            }  
            catch(PDOException $e) {  
                echo $e->getMessage();  
            }
        }
        

    }

}