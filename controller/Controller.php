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

    public function login(\view\LoginView $view) {
        //GET LOGIN CREDENTIALS FROM VIEW
        $this->loginView = $view;
        $username = $this->loginView->getRequestUserName();
        $password = $this->loginView->getRequestPassword();

        $validator = new \model\CredentialValidator($username, $password);

        try {
            $validator->isValidInput();
            $credentials = $validator->getCredentials();
            $this->authorize($credentials);
        } catch (\Exception $exception) {
            //SET CURRENCT USERNAME IN VIEW, MAKES IT POSSIBLE TO KEEP SAME INPUT IN USERNAME FIELD AT NEXT RENDERING.
            $view->setUsername();
            $view->setResponseMessage($exception->getMessage());
        }
    }

    private function authorize(\model\Credentials $credentials){
        $this->authorization = new \model\Authorization($credentials);
    }
}