<?php
/**
 * Created by PhpStorm.
 * User: sebastiangustavsson
 * Date: 2016-09-22
 * Time: 11:27
 */

namespace controller;

require_once('./model/Authorization.php');

class Controller
{
    private $loginView;
    private $userDB;
    private $auth;

    public function __construct(\view\LoginView $view)
    {
        $this->loginView = $view;
    }

    public function init(){
        if($this->loginView->userNameOrPasswordIsset()) {
            $this->login();
        }

        if($this->isLoggedIn()) {
            $this->loginView->setToLoggedInView();
        }
    }


    public function login() {
        //GET LOGIN CREDENTIALS FROM VIEW
        $username = $this->loginView->getRequestUserName();
        $password = $this->loginView->getRequestPassword();

        $validator = new \model\CredentialValidator($username, $password);

        try {
            $validator->isValidInput();
            $credentials = $validator->getCredentials();
            $this->authorize($credentials);
        } catch (\Exception $exception) {
            //SET CURRENCT USERNAME IN VIEW, MAKES IT POSSIBLE TO KEEP SAME INPUT IN USERNAME FIELD AT NEXT RENDERING.
            $this->loginView->setUsername();
            $this->loginView->setResponseMessage($exception->getMessage());
        }
    }


    private function authorize(\model\Credentials $credentials){
        $this->auth = new \model\Authorization($credentials);
    }


    public function isLoggedIn(){
        if($this->auth !== null){
            return $this->auth->isAuthorized();
        } else {
            return FALSE;
        }
    }

    private function getSessionId(){
        if(isset($_COOKIE['PHPSESSID'])){
            return $_COOKIE['PHPSESSID'];
        }
    }
}