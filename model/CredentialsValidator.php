<?php
/**
 * Created by PhpStorm.
 * User: sebastiangustavsson
 * Date: 2016-09-21
 * Time: 11:40
 */
namespace model;

require_once('Credentials.php');

class CredentialValidator extends Credentials {
    private static $usernameMissingResponse = 'Username is missing';
    private static $passwordMissingResponse = 'Password is missing';
    private $response;
    private $credentialsValidated = FALSE;
    private $credentials;


    function __construct(string $user, string $pass) {
        $this->credentials = new Credentials($user, $pass);
    }


    public function isValidInput() {
        if(!$this->isUsernameValidFormat()) {
            throw new \Exception(self::$usernameMissingResponse);
        }
        if(!$this->isPasswordValidFormat()) {
            throw new \Exception(self::$passwordMissingResponse);
        }

        $this->response = 'Validated format on credentials';
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


    public function getValidationResponseMessage(){
        return $this->response;
    }


    public function getCredentials() {
        if($this->credentialsValidated){
        return $this->credentials;
        } else {
            throw new \Exception('Credentials not validated');
        }
    }

}