<?php

namespace model;

require_once('./model/CustomExceptions/CookieLoginException.php');


class CookieAuthorization
{
    private $userDAL;
    private static $isAuthorized = FALSE;


    public function __construct(\model\Credentials $credentials) {
        $this->userDAL = new \model\UserDAL($credentials);
        $this->authFlow();
    }


    private function authFlow() {
        if(!$this->userDAL->userExists()) {
            throw new CookieLoginException();
        }
        if(!$this->userDAL->isMatchingMetahashForUser()){
            throw new CookieLoginException();
        } else {
            self::$isAuthorized = TRUE;
        }
    }


    public function isAuthorized() {
        return self::$isAuthorized;
    }
}