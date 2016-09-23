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

    public function login(\view\LoginView $view) {
        //GET LOGIN CREDENTIALS FROM VIEW
        $this->loginView = $view;
        $username = $this->loginView->getRequestUserName();
        $password = $this->loginView->getRequestPassword();

        $validator = new \model\CredentialValidator($username, $password);

        if($validator->isValidInput()){

        } else {
            //SET CURRENCT USERNAME IN VIEW, MAKES IT POSSIBLE TO KEEP SAME INPUT IN USERNAME FIELD AT NEXT RENDERING.
            $view ->setUsername();

            $view->setResponseMessage($validator->getValidationResponseMessage());
        };
    }

    public function  authorize(){

    }


}