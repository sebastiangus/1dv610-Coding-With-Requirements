<?php
/**
 * Created by PhpStorm.
 * User: sebastiangustavsson
 * Date: 2016-09-23
 * Time: 10:03
 */

namespace model;

use settings\Settings;

require_once('Settings.php.default');

abstract class DatabaseConnection
{
    protected $mysqli;

    public function __construct() {
        $this->establishConnection();
        $this->prepareTable();
    }

    private function establishConnection() {
        //http://php.net/manual/en/function.mysql-connect.php
        $this->mysqli = new \mysqli(Settings::$LOCALHOST, Settings::$DB_USER, Settings::$DB_PASSWORD, Settings::$DB_NAME);

        if ($this->mysqli->connect_errno)
        {
            echo 'Failed to connect to MySQL: (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error;
        }
    }
}