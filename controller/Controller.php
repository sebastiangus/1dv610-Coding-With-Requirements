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

require_once('./model/Authorization.php');
require_once('./model/CookieAuthorization.php');
require_once('./model/SessionTracker.php');

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
    private static $additionalWelcomeKeepLoggedIn = 'and you will be remembered';
    private static $additionalWelcomeCookieLogin = 'back with cookie';

    public function __construct(\view\LoginView $view, $lv)
    {
        $this->loginView = $view;
        $this->layoutView = $lv;
        $this->sessionTracker = new SessionTracker();
    }

    public function init(){
        session_start();

        if($this->loginView->userNameOrPasswordIsset()) {
            $this->login();
        }

        if($this->isLoggingOut()){
            $this->logout();
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

        $this->layoutView->render();
    }

    private function login() {
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
            $this->loginView->setCurrentUsernameToBeViewed();
            $this->loginView->setResponseMessageFromException($exception);
        }
    }

    private function cookieLogin(){
        try{
        $this->cookieAuthorize();
        $this->loginView->setWelcomeMessage(self::$additionalWelcomeCookieLogin);
        } catch (\Exception $exception){
            $this->loginView->setResponseMessageFromException($exception);
            $this->deleteLoginCookiesIfSet();
        }
    }

    private function isLoggedIn(){
        if($this->auth !== null){
            return $this->auth->isAuthorized();
        } else {
            return FALSE;
        }
    }

    private function isLoggingOut(){
        if($this->loginView->logoutIsPressed() && !$this->isLoggedIn()){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function logout(){
        $this->deleteLoginCookiesIfSet();
        $this->destroySessionsAndStartNewIfLoggedIn();
        $this->loginView->showLoginStateResponseMessageOnce();
    }

    private function destroySessionsAndStartNewIfLoggedIn(){
        if($this->sessionTracker->isLoggedInSession()){
            session_destroy();
            session_start();
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
        try{
        $username = $this->loginView->getCookieName();
        $password = $this->loginView->getCookiePassword();
        $validator = new \model\CredentialValidator($username, $password);
        $validator->isValidInput();
        $credentials = $validator->getCredentials();
        $this->auth = new \model\CookieAuthorization($credentials);
        } catch (\Exception $exception) {
            $this->loginView->setResponseMessageFromException($exception);
        }
    }

    private function setLoginCookies(){
        $credentials = $this->sessionTracker->getSessionCredentials();
        $username = $credentials->getUsername();
        //Get hashed version of password hash.
        $password = $this->auth->getMetaHash();
        $this->loginView->setLoginCookies($username, $password);
    }

    private function deleteLoginCookiesIfSet(){
        //http://stackoverflow.com/questions/686155/remove-a-cookie
        if($this->loginView->loginCookiesIsSet()){
            $this->loginView->deleteLoginCookies();
        }
    }

    private function keepLoginAsCookies(){
        if($this->loginView->keepLoginIsActive() && $this->isLoggedIn()){
            return TRUE;
        } else {
            return FALSE;
        }
    }


    private function isLoggedOutAndCredentialsSavedToCookies(){
        if(!$this->isLoggedIn() && $this->loginView->loginCookiesIsSet()){
            return TRUE;
        } else {
            return FALSE;
        }
    }

}