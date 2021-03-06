<?php

//INCLUDE THE FILES NEEDED...
require_once('./view/LoginView.php');
require_once('./view/DateTimeView.php');
require_once('./view/LayoutView.php');
require_once('./model/CredentialsValidator.php');
require_once('./controller/Controller.php');

//MAKE SURE ERRORS ARE SHOWN... MIGHT WANT TO TURN THIS OFF ON A PUBLIC SERVER

error_reporting(E_ALL);
ini_set('display_errors', 'On');

//CREATE OBJECTS OF THE VIEWS
$v = new \view\LoginView();
$dtv = new DateTimeView();
$lv = new LayoutView($v, $dtv);

$controller = new \controller\Controller($v, $lv);
$controller->init();