<?php
/**
 * Created by PhpStorm.
 * User: Blackbeard
 * Date: 18/05/2018
 * Time: 08:43
 */
namespace Bbase\DB;
class Sql {
    
    const HOSTNAME = HOSTNAME;
    const USERNAME = USERNAME;
    const PASSWORD = PASSWORD;
    const DBNAME = DBNAME;

    private $conn;
    
    public function __construct($dsn=null)
    {
        if(is_null($dsn)):
            $this->conn = new \PDO("mysql:dbname=".Sql::DBNAME.";host=".Sql::HOSTNAME,
            Sql::USERNAME,
            Sql::PASSWORD);
        else:
            $this->conn = new \PDO($dsn);
        endif; 

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
