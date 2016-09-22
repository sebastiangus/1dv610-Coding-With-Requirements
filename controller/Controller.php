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

    public function validateCredentialsAndSaveUsername(\view\LoginView $view) {
        $this->loginView = $view;
        $username = $this->loginView->getRequestUserName();
        $password = $this->loginView->getRequestPassword();

        //SAVES USERNAME IN VIEW, SO IT WILL BE DISPLAYED NEXT TIME RENDERED.
        $view ->setUsername();
        $validator = new \model\CredentialValidator($username, $password);
        $validator->isValidateInput();
        $view->setResponseMessage($validator->getValidationResponseMessage());
    }


}