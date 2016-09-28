<?php
/**
 * Created by PhpStorm.
 * User: sebastiangustavsson
 * Date: 2016-09-23
 * Time: 10:03
 */

namespace model;

require_once('Settings.php');

abstract class DatabaseConnection
{
    protected $mysqli;

    public function __construct() {
        $this->establishConnection();
        $this->prepareTable();
    }

    private function establishConnection() {
        //http://php.net/manual/en/function.mysql-connect.php
        $this->mysqli = new \mysqli(\settings\Settings::$LOCALHOST, \settings\Settings::$DB_USER, \settings\Settings::$DB_PASSWORD, \settings\Settings::$DB_NAME);

        if ($this->mysqli->connect_errno)
        {
            echo 'Failed to connect to MySQL: (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error;
        }
    }
}