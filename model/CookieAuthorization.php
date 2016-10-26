<?php
/**
 * Created by PhpStorm.
 * User: sebastiangustavsson
 * Date: 2016-10-25
 * Time: 18:52
 */

namespace model;


class CookieAuthorization
{
    private $userDAL;
    private static $USER_DO_NOT_EXIST_OR_WRONG_PASSWORD = "Wrong name or password";
    private static $isAuthorized = FALSE;


    public function __construct(\model\Credentials $credentials) {
        $this->userDAL = new \model\UserDAL($credentials);
        $this->authFlow();
    }


    private function authFlow() {
        if(!$this->userDAL->userExists()) {
            throw new \Exception(self::$USER_DO_NOT_EXIST_OR_WRONG_PASSWORD);
        }
        if(!$this->userDAL->isMatchingMetahashForUser()){
            throw new \Exception(self::$USER_DO_NOT_EXIST_OR_WRONG_PASSWORD);
        } else {
            self::$isAuthorized = TRUE;
        }
    }


    public function isAuthorized() {
        return self::$isAuthorized;
    }
}