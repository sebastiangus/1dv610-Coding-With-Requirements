<?php
/**
 * Created by PhpStorm.
 * User: sebastiangustavsson
 * Date: 2016-09-21
 * Time: 11:40
 */
namespace model;

require_once('Credentials.php');

class CredentialValidator extends \model\Credentials {
    private static $usernameMissingResponse = 'Username is missing';
    private static $passwordMissingResponse = 'Password is missing';
    private $username = '';
    private $password = '';
    private $response;
    private $credentialsValidated = FALSE;


    function __construct(string $user, string $pass) {
        $this->username = $user;
        $this->password = $pass;
    }


    public function isValidInput() {
        if(!$this->isUsernameValidFormat()) {
            $this->response = self::$usernameMissingResponse;
            return FALSE;
        }
        if(!$this->isPasswordValidFormat()) {
            $this->response = self::$passwordMissingResponse;
            return FALSE;
        }

        $this->response = '';
        $this->credentialsValidated = TRUE;
        return TRUE;
    }


    private function isUsernameValidFormat() : bool {
        if($this->isInputStringAndLongerThanZero($this->username)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }


    private function  isPasswordValidFormat() : bool {
        if($this->isInputStringAndLongerThanZero($this->password)) {
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
        return new \model\Credentials($this->username, $this->password);
        } else {
            throw new Error('Credentials not validated');
        }
    }

}