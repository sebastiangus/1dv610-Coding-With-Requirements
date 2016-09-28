<?php
/**
 * Created by PhpStorm.
 * User: sebastiangustavsson
 * Date: 2016-09-28
 * Time: 07:39
 */


namespace model;

require_once('DatabaseConnection.php');

class UserDAL extends DatabaseConnection
{
    private $DATABASE_TABLE = "users";


    protected function prepareTable(){
        //http://dev.mysql.com/doc/refman/5.7/en/create-table.html
        $this->mysqli->query("
        CREATE TABLE IF NOT EXISTS $this->DATABASE_TABLE (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100),
        password VARCHAR(120))
        ");
    }

    public function addData() {
        $this->mysqli->query("INSERT INTO $this->DATABASE_TABLE (username, password) VALUES ('Admin', 'Password')");
        if ($this->mysqli->connect_errno)
        {
            echo 'Failed to connect to MySQL: (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error;
        }
    }
}