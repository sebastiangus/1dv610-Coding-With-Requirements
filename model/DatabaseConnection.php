<?php
/**
 * Created by PhpStorm.
 * User: sebastiangustavsson
 * Date: 2016-09-23
 * Time: 10:03
 */

namespace model;

abstract class DatabaseConnection
{
    protected $mysqli;

    private $DATABASE_NAME = "1dv610";


    public function __construct() {
        $this->establishConnection();
        $this->prepareTable();
    }

    private function establishConnection() {
        //http://php.net/manual/en/function.mysql-connect.php
        $this->mysqli = new \mysqli('127.0.0.1', '1dv610', '1dv610', $this->DATABASE_NAME);

        if ($this->mysqli->connect_errno)
        {
            echo 'Failed to connect to MySQL: (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error;
        }
    }
}
