<?php
/**
 * Created by PhpStorm.
 * User: sebastiangustavsson
 * Date: 2016-09-22
 * Time: 11:27
 */

namespace controller;


class Controller
{
    private $loginView;

    public function validateCredentials(\view\LoginView $view) {
        $this->loginView = $view;
        $username = $this->loginView->getRequestUserName();
        $password = $this->loginView->getRequestPassword();
        $validator = new \model\CredentialValidator($username, $password);
        $validator->validateInput();
        $view->setResponseMessage($validator->getValidationResponseMessage());
    }

}