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
require_once('./model/CookieAuthorization.php');
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
    private static $showMessageAttribute = "showMessage";
    private static $messageAttribute = "message";
    private static $logoutMessage = "Bye bye!";
    private static $additionalWelcomeKeepLoggedIn = 'and you will be remembered';
    private static $additionalWelcomeCookieLogin = 'back with cookie';

    public function __construct(\view\LoginView $view, $lv)
    {
        $this->loginView = $view;
        $this->layoutView = $lv;
        $this->sessionTracker = new SessionTracker();
        $this->requestHandler = new RequestHandler($this);

    }

    public function init(){
        session_start();

        $this->requestHandler->checkForRequestAttribute();

        if($this->loginView->userNameOrPasswordIsset()) {
            $this->login();
        }

        if($this->keepLoginAsCookies()){
            $this->setLoginCookies();
            $this->loginView->setWelcomeMessage(self::$additionalWelcomeKeepLoggedIn);
        }

        if($this->isLoggedOutAndCredentialsSavedToCookies()){
            $this->cookieLogin();
        }

        if($this->isLoggedIn()) {
            $this->loginView->setToLoggedInView();
        }

        if($this->sessionTracker->sessionCredentialsIsSet()) {
            $this->restoreLoggedInSession();
        }

        if(isset($_SESSION[self::$messageAttribute])) {
            $this->setResponseMessageFromSessionIfNotSetBeforeAndNotRedirect();
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

    private function cookieLogin(){
        try{
        $this->cookieAuthorize();
        $this->loginView->setWelcomeMessage(self::$additionalWelcomeCookieLogin);
        } catch (\Exception $exception){
            $this->loginView->setResponseMessage($exception->getMessage());
            $this->deleteLoginCookiesIfSet();
        }
    }

    public function logout(){
        $this->destroySessionsAndStartNew();
        $this->prepareLogoutMessage();
        $this->deleteLoginCookiesIfSet();
        //http://stackoverflow.com/questions/15411978/how-to-redirect-user-from-php-file-back-to-index-html-on-dreamhost
        header('Location: index.php');
    }

    public function isLoggedIn(){
        if($this->auth !== null){
            return $this->auth->isAuthorized();
        } else {
            return FALSE;
        }
    }

    private function destroySessionsAndStartNew(){
        session_destroy();
        session_start();
    }

    private function deleteLoginCookiesIfSet(){
        //http://stackoverflow.com/questions/686155/remove-a-cookie
        if($this->loginCookiesIsSet()){
            unset($_COOKIE['username']);
            setcookie('username', '', time() - 3600);
            unset($_COOKIE['password']);
            setcookie('password', '', time() - 3600);
        }
    }

    private function prepareLogoutMessage(){
        $_SESSION[self::$messageAttribute] = self::$logoutMessage;
        $_SESSION[self::$showMessageAttribute] = '1';
    }

    private function setResponseMessageFromSessionIfNotSetBeforeAndNotRedirect(){
        if($_SESSION[self::$showMessageAttribute] === '1' && http_response_code() !== 302) {
            $message = $_SESSION[self::$messageAttribute];
            $this->loginView->setResponseMessage($message);
            $_SESSION[self::$showMessageAttribute] = '0';
        }
    }

    private function restoreLoggedInSession(){
            $credentials = $this->sessionTracker->getSessionCredentials();
        if($this->sessionTracker->sessionIsInitatedWithSameUserAgent()){
            $this->authorize($credentials);
            $this->loginView->setToLoggedInView();
        }
    }

    private function authorize(\model\Credentials $credentials){
        $this->auth = new \model\Authorization($credentials);
    }

    private function cookieAuthorize(){
        $username = $_COOKIE['username'];
        $password = $_COOKIE['password'];
        $validator = new \model\CredentialValidator($username, $password);
        $validator->isValidInput();
        $credentials = $validator->getCredentials();
        $this->auth = new \model\CookieAuthorization($credentials);
    }

    private function setLoginCookies(){
        $credentials = $this->sessionTracker->getSessionCredentials();
        $username = $credentials->getUsername();
        //Get hashed version of password hash.
        $password = $this->auth->getMetaHash();
        setcookie('username', $username);
        setcookie('password', $password);
    }

    private function keepLoginAsCookies(){
        if($this->loginView->keepLoginIsActive() && $this->isLoggedIn()){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function loginCookiesIsSet(){
        if(isset($_COOKIE['username']) && isset($_COOKIE['password'])){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function isLoggedOutAndCredentialsSavedToCookies(){
        if(!$this->isLoggedIn() && $this->loginCookiesIsSet()){
            return TRUE;
        } else {
            return FALSE;
        }
    }

}