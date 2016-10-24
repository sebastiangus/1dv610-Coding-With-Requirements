<?php
/**
 * Created by PhpStorm.
 * User: sebastiangustavsson
 * Date: 2016-10-24
 * Time: 07:56
 */

namespace controller;


class RequestHandler
{
    public function __construct(\controller\Controller $mainController)
    {
        $this->mainController = $mainController;
    }

    public function checkForRequestAttribute(){
    //TODO: Remove string dependency!
    if(isset($_REQUEST['LoginView::Logout'])){
        $this->mainController->logout();
    }
}
}