<?php
/**
 * Created by PhpStorm.
 * User: sebastiangustavsson
 * Date: 2016-09-23
 * Time: 09:46
 */

namespace model;

require_once('UserDAL.php');

class Authorization
{
    private $userDAL;
    private static $USER_DO_NOT_EXIST_OR_WRONG_PASSWORD = "Wrong name or password";

    public function __construct(\model\Credentials $credentials) {
        $this->userDAL = new \model\UserDAL($credentials);
        $this->authFlow();
    }

    private function authFlow() {
        if(!$this->userDAL->userExists()) {
            throw new \Exception(self::$USER_DO_NOT_EXIST_OR_WRONG_PASSWORD);
        }
        if(!$this->userDAL->isMatchingPasswordForUser()){
            throw new \Exception(self::$USER_DO_NOT_EXIST_OR_WRONG_PASSWORD);
        }
    }

    private function checkIfUserExists(){

    }
}