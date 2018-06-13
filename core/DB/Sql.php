<?php
/**
 * Created by PhpStorm.
 * User: Blackbeard
 * Date: 18/05/2018
 * Time: 08:43
 */
namespace Bbase\DB;

class Sql {

    const HOSTNAME = '127.0.0.1';
    const USERNAME = 'root';
    const PASSWORD = '';
    const DBNAME = 'sistema';

    private $conn;

    public function __construct($dsn=null)
    {
        if(is_null($dsn)):
           $dsn = "mysql:dbname=" . Sql::DBNAME . ";host=". Sql::HOSTNAME .','. Sql::USERNAME . ',' . Sql::PASSWORD;
            
        endif;    
        $this->conn = new \PDO($dsn);
    }

    private function setParams($statement, $parameters=array())
    {
        foreach($parameters as $key => $value):
            $this->bindParam($statement, $key, $value);
        endforeach;
    }

    private function bindParam($statement, $key, $value)
    {
        $statement->bindParam($key, $value);
    }

    public function query($rawQuery, $params = array())
    {
        $stmt = $this->conn->prepare($rawQuery);
        $this->setParams($stmt, $params);
        $stmt->execute();
    }

    public function select($rawQuery, $params = array())
    {
        $stmt = $this->conn->prepare($rawQuery);
        $this->setParams($stmt, $params);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}