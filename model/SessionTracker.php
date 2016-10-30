<?php

namespace model;

require_once('CredentialsValidator.php');

class SessionTracker
{

    public function saveCredentialsToSession(\model\Credentials $c){
        $_SESSION['username'] = $c->getUsername();
        $_SESSION['password'] = $c->getPassword();
        $_SESSION['remoteAddr'] = $this->getRemoteAddr();
        $_SESSION['loggedIn'] = TRUE;
        $this->setUserAgentToSession();
    }

    public function setUserAgentToSession(){
        $_SESSION['userAgent'] = $_SERVER['HTTP_USER_AGENT'];
    }

    public function sessionIsInitatedWithSameUserAgent(){
        if(isset($_SERVER['HTTP_USER_AGENT']) && isset($_SESSION['userAgent'])){
            if($_SERVER['HTTP_USER_AGENT'] === $_SESSION['userAgent']) {
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    public function isLoggedInSession(){
        if(isset($_SESSION['loggedIn'])){
            if($_SESSION['loggedIn']){
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    public function getSessionCredentials() {
        $credentials = '';
        $password = '';
        $username = '';
        $currentAddr = $this->getRemoteAddr();
        if($this->isSameRemoteAddr($currentAddr) && isset($_SESSION['password']) && isset($_SESSION['username'])){
            $password = $_SESSION['password'];
            $username = $_SESSION['username'];
            $credentials = new \model\CredentialValidator($username, $password);
        }
        return $credentials;
    }

    private function isSameRemoteAddr($currentAddr){
        if(isset($_SESSION['remoteAddr'])){
            if($currentAddr == $_SESSION['remoteAddr']){
                return TRUE;
            }
        } else {
            return FALSE;
        }
    }

    private function getRemoteAddr(){
        if(isset($_SERVER['REMOTE_ADDR'])){
            return $_SERVER['REMOTE_ADDR'];
        }
    }

    public function sessionCredentialsIsSet(){
        if($this->getSessionCredentials() !== ''){
            return TRUE;
        } else {
            return FALSE;
        }
    }
}
