<?php
/**
 * Created by PhpStorm.
 * User: sebastiangustavsson
 * Date: 2016-09-28
 * Time: 07:39
 */


namespace model;

require_once('DatabaseConnection.php');
require_once('Settings.php');

class UserDAL extends DatabaseConnection
{
    private $credentials;
    private $userList;

    public function __construct(\model\Credentials $c)
    {
        parent::__construct();
        $this->credentials = $c;
        $this->prepareTable();
        $this->getUsers();
        //TODO: REMOVE THIS LINE! Temporary ADD USER FUNCTIONALITY
        //$this->addUser($c);
    }

    protected function prepareTable(){
        //http://dev.mysql.com/doc/refman/5.7/en/create-table.html
        //Password field needs to be at least 255 characters long to be future proof acording to http://php.net/manual/en/function.password-hash.php
        $this->mysqli->query("
        CREATE TABLE IF NOT EXISTS" . Settings::$DB_TABLE ." (
        id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
        username VARCHAR(100),
        password VARCHAR(255))
        ");
    }

    //TODO: Rebuild this function to be able to create users. Watch out for sql-injections.
    public function addUser(\model\Credentials $credentials) {
        if(!$this->userExists()) {
            $username = $credentials->getUsername();
            $hashedPassword = $credentials->getHashedPassword();
            $stmt = $this->mysqli->prepare("INSERT INTO $this->DATABASE_TABLE (username, password) VALUES (?, ?)");
            $stmt->bind_param('ss', $username, $hashedPassword);
            $stmt->execute();
            if ($this->mysqli->connect_errno) {
                echo 'Failed to connect to MySQL: (' . $this->mysqli->connect_errno . ') ' . $this->mysqli->connect_error;
            }
        }
    }

    public function userExists() {
        $checkForThisUsername = $this->credentials->getUsername();

        foreach ($this->userList as $user) {
            if(strcmp($checkForThisUsername, $user[0]) === 0){
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    //http://php.net/manual/en/mysqli.prepare.php
    public function isMatchingPasswordForUser(){
        $password = "";
        $user = $this->credentials->getUsername();
        $stmt = $this->mysqli->prepare('SELECT password FROM users WHERE username=?');
        $stmt->bind_param("s", $user);
        $stmt->execute();
        $stmt->bind_result($dbPassword);
        if($stmt->fetch()){
               $password = $dbPassword;
        };
        $stmt->close();
        //http://php.net/manual/en/function.password-verify.php
        if(password_verify($this->credentials->getPassword(), $password)){
            return TRUE;
        } else {
            return FALSE;
        };

    }

    //http://us2.php.net/manual/en/function.mysql-fetch-array.php
    //http://php.net/manual/en/mysqli-result.fetch-array.php
    private function getUsers(){
        $this->userList = $this->mysqli->query("SELECT username from " . Settings::$DB_TABLE);
        $this->userList = mysqli_fetch_all($this->userList, MYSQLI_NUM);
    }

}