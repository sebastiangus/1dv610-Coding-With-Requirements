<?php

namespace model;

require_once('UserDAL.php');
require_once('./model/CustomExceptions/WrongUsernameOrPasswordException.php');

class Authorization
{
    private $userDAL;
    private static $isAuthorized = FALSE;


    public function __construct(\model\Credentials $credentials) {
        $this->userDAL = new \model\UserDAL($credentials);
        $this->authFlow();
    }


    private function authFlow() {
        if(!$this->userDAL->userExists()) {
            throw new WrongUsernameOrPasswordException();
        }
        if(!$this->userDAL->isMatchingPasswordForUser()){
            throw new WrongUsernameOrPasswordException();
        } else {
            self::$isAuthorized = TRUE;
        }
    }


    public function isAuthorized() {
        return self::$isAuthorized;
    }

    public function getMetaHash(){
        return $this->userDAL->getMetaHashForUser();
    }
}