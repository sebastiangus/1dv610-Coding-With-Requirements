<?php
/**
 * Created by PhpStorm.
 * User: sebastiangustavsson
 * Date: 2016-09-22
 * Time: 11:27
 */

namespace controller;

require_once('./model/DatabaseConnection.php');

class Controller
{
    private $loginView;
    private $credentials;
    private $databaseConnection;

    public function init() {
        $this->databaseConnection = new \model\DatabaseConnection();
    }


    public function login(\view\LoginView $view) {
        //GET LOGIN CREDENTIALS FROM VIEW
        $username = $this->view->getRequestUserName();
        $password = $this->view->getRequestPassword();

        $validator = new \model\CredentialValidator($username, $password);

        if($validator->isValidInput()){
            $this->credentials = $validator->getCredentials();
            $this->authorize($this->credentials);
        } else {
            //SET CURRENCT USERNAME IN VIEW, MAKES IT POSSIBLE TO KEEP SAME INPUT IN USERNAME FIELD AT NEXT RENDERING.
            $view ->setUsername();
            $view->setResponseMessage($validator->getValidationResponseMessage());
        };
    }


    public function  authorize(\model\Credentials $credentials){

    }


}