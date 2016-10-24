<?php
/**
 * Created by PhpStorm.
 * User: sebastiangustavsson
 * Date: 2016-09-22
 * Time: 11:27
 */

namespace controller;

use model\CredentialValidator;
use model\SessionTracker;
use MongoDB\Driver\Server;

require_once('./model/Authorization.php');
require_once('./model/SessionTracker.php');
require_once('RequestHandler.php');

class Controller
{
    private $loginView;
    private $layoutView;
    private $userDB;
    private $auth;
    private $sessionTracker;
    private $requestHandler;

    public function __construct(\view\LoginView $view, $lv)
    {
        session_start();
        $this->loginView = $view;
        $this->layoutView = $lv;
        $this->sessionTracker = new SessionTracker();
        $this->requestHandler = new RequestHandler($this);

    }

    public function init(){
        $this->requestHandler->checkForRequestAttribute();

        if($this->loginView->userNameOrPasswordIsset()) {
            $this->login();
        }

        if($this->isLoggedIn()) {
            $this->loginView->setToLoggedInView();
        }

        if($this->sessionTracker->sessionCredentialsIsSet()) {
            $this->loginView->setToLoggedInView();
            $this->restoreSession();
        }

        if(isset($_SESSION['message'])){
            $this->loginView->setResponseMessage($_SESSION['message']);
        }

        $this->layoutView->render();
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
            $this->sessionTracker->saveCredentialsToSession($credentials);
        } catch (\Exception $exception) {
            //SET CURRENCT USERNAME IN VIEW, MAKES IT POSSIBLE TO KEEP SAME INPUT IN USERNAME FIELD AT NEXT RENDERING.
            $this->loginView->setUsername();
            $this->loginView->setResponseMessage($exception->getMessage());
        }
    }

    public function logout(){
        session_destroy();
        session_start();
        //TODO: remove string dependency
        $_SESSION['message'] = 'Bye bye';
        //http://stackoverflow.com/questions/15411978/how-to-redirect-user-from-php-file-back-to-index-html-on-dreamhost
        header('Location: index.php');
    }

    private function restoreSession(){
        $credentials = $this->sessionTracker->getSessionCredentials();
        $this->authorize($credentials);
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
}