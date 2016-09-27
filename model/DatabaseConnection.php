<?php
/**
 * Created by PhpStorm.
 * User: sebastiangustavsson
 * Date: 2016-09-23
 * Time: 10:03
 */

namespace model;

class DatabaseConnection
{
    private $mysqli;


    public function __constructor() {
        //http://php.net/manual/en/function.mysql-connect.php
        $this->mysqli = new mysqli('127.0.0.1', '1dv610', '1dv610', '1dv610');

        if ($this->mysqli->connect_errno)
        {
            echo 'Failed to connect to MySQL: (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error;
        }
    }


    public function getCount() {
        echo $this->mysqli->query("SELECT COUNT(*) FROM 1dv610");
    }


    public function getConnectionInformation() {
        echo 'Test';
        $info = $this->mysqli->host_info . '\n';
        var_dump($info);
        echo $info;
    }

}
