<?php

namespace model;

require_once('Credentials.php');
require_once('./model/CustomExceptions/UsernameException.php');
require_once('./model/CustomExceptions/PasswordException.php');


class CredentialValidator extends Credentials {
    private $credentialsValidated = FALSE;
    private $credentials;

    function __construct(string $user, string $pass) {
        $this->credentials = new Credentials($user, $pass);
    }

    public function throwExceptionIfInvalidUserCredentials() {
        if(!$this->isUsernameValidFormat()) {
            throw new UsernameException();
        }
        if(!$this->isPasswordValidFormat()) {
            throw new PasswordException();
        }

        $this->credentialsValidated = TRUE;
        return TRUE;
    }

    private function isUsernameValidFormat() : bool {
        if($this->isInputStringAndLongerThanZero($this->credentials->getUsername())) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function  isPasswordValidFormat() : bool {
        if($this->isInputStringAndLongerThanZero($this->credentials->getPassword())) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    private function isInputStringAndLongerThanZero($input) : bool {

        if (is_string($input) && strlen($input) > 0) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getCredentials() {
        if($this->credentialsValidated){
        return $this->credentials;
        } else {
            throw new \Exception('Credentials not validated');
        }
    }

}