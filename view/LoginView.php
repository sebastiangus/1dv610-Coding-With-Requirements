<?php

namespace view;

use model\CookieLoginException;
use model\PasswordException;
use model\UsernameException;
use model\WrongUsernameOrPasswordException;

require_once('./model/CustomExceptions/PasswordException.php');
require_once('./model/CustomExceptions/UsernameException.php');
require_once('./model/CustomExceptions/WrongUsernameOrPasswordException.php');
require_once('./model/CustomExceptions/CookieLoginException.php');

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
    private static $showWelcomeMessageAttribute = 'showWelcomeMessage';
    private static $showMessageAttribute = 'showMessage';
    private $responseMessage = '';
    private $welcomeMessage = 'Welcome';
    private static $additionalWelcomeKeepLoggedIn = 'and you will be remembered';
    private static $additionalWelcomeCookieLogin = 'back with cookie';
    private static $loggedInViewActive = FALSE;
    private static $username;


	/**
	 * Create HTTP response
	 *
	 * Should be called after a login attempt has been determined
	 *
	 * @return  void BUT writes to standard output and cookies!
	 */
	public function response() {
        $response = $this->generateLoginFormHTML($this->responseMessage);
        if(self::$loggedInViewActive) {
            $response = $this->generateLoggedInHTML();
        }
		return $response;
	}

	/**
	* Generate HTML code on the output buffer for the logout button
	* @param $message, String output message
	* @return  void, BUT writes to standard output!
	*/
	private function generateLogoutButtonHTML($message) {
		return '
			<form  method="post" >
				<p id="' . self::$messageId . '">' . $message .'</p>
				<input type="submit" name="' . self::$logout . '" value="logout"/>
			</form>
		';
	}
	
	/**
	* @param $message, String output message the message you want to render/display to the user.
	* @return  HTML formatted string
	*/

	private function generateLoginFormHTML($message) {
		return '
			<form action="index.php" method="post"> 
				<fieldset>
					<legend>Login - enter Username and password</legend>
					<p id="' . self::$messageId . '">' . $message . '</p>
					
					<label for="' . self::$name . '">Username :</label>
					<input type="text" id="' . self::$name . '" name="' . self::$name . '" value="' . self::$username . '" />

					<label for="' . self::$password . '">Password :</label>
					<input type="password" id="' . self::$password . '" name="' . self::$password . '" value="" />

					<label for="' . self::$keep . '">Keep me logged in  :</label>
					<input type="checkbox" id="' . self::$keep . '" name="' . self::$keep . '" />
					
					<input type="submit" name="' . self::$login . '" value="login" />
				</fieldset>
			</form>
			';
	}

	private function generateLoggedInHTML(){
	    $message = '';
	    //http://stackoverflow.com/questions/4290230/php-detect-page-refresh
        if(!isset($_SESSION[self::$showWelcomeMessageAttribute])) {
            $message .= $this->welcomeMessage;
            $_SESSION[self::$showWelcomeMessageAttribute] = FALSE;
        } else {
            $message = '';
        }
            return $this->generateLogoutButtonHTML($message);
    }

    public function getRequestUserName() {
        if(isset($_REQUEST[self::$name])) {
            return strip_tags($_REQUEST[self::$name]);
        }
    }

    public function  getRequestPassword() {
        if(isset($_REQUEST[self::$password])) {
            return strip_tags($_REQUEST[self::$password]);
        }
    }

    public function setResponseMessage($message){
        $this->responseMessage = $message;
    }

    public function setResponseMessageFromException(\Exception $exception) {

        $message = '';
        try {
            throw $exception;
        } catch (PasswordException $e) {
            $message = 'Password is missing';
        } catch (UsernameException $e) {
            $message = 'Username is missing';
        } catch (WrongUsernameOrPasswordException $e){
            $message = 'Wrong name or password';
        } catch (CookieLoginException $e) {
            $message = 'Wrong information in cookies';
        } catch (\Exception $e) {
            $message = 'Other exception';
        } finally {
            $this->responseMessage = $message;
        }
    }

    public function setWelcomeMessage(string $message) {
        $this->welcomeMessage .= ' ' . $message;
    }

    public function setWelcomeMessageYouWillBeRememberd(){
        $this->setWelcomeMessage(self::$additionalWelcomeKeepLoggedIn);
    }

    public function setWelcomeMessageLoggedInWithCookie(){
        $this->setWelcomeMessage(self::$additionalWelcomeCookieLogin);
    }

    public function userNameOrPasswordIsset(){
        if(isset($_POST[self::$name]) || isset($_POST[self::$password])){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function setCurrentUsernameToBeViewed(){
        self::$username = $_REQUEST[self::$name];
    }

    public function setToLoggedInView(){
        self::$loggedInViewActive = TRUE;
    }

    public function isloggedIn(){
        return self::$loggedInViewActive;
    }

    public function keepLoginIsActive(){
        if(isset($_REQUEST[self::$keep])) {
            return $_REQUEST[self::$keep];
        }
    }

    public function logoutIsPressed()
    {
        if (isset($_REQUEST[self::$logout])) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function showLoginStateResponseMessageOnce(){

        if(!isset($_SESSION[self::$showMessageAttribute])){
            if($this->logoutIsPressed()){
                $this->setResponseMessage('Bye bye!');
            }
        } else {
            $this->setResponseMessage('');
        }
        $_SESSION[self::$showMessageAttribute] = TRUE;
    }

    public function setLoginCookies($username, $password) {
        setcookie(self::$cookieName, $username, time() + 3600);
        setcookie(self::$cookiePassword, $password, time() + 3600);
    }

    public function deleteLoginCookies(){
        //http://stackoverflow.com/questions/686155/remove-a-cookie
        unset($_COOKIE[self::$cookieName]);
        setcookie(self::$cookieName, '', time() - 3600);
        unset($_COOKIE[self::$cookiePassword]);
        setcookie(self::$cookiePassword, '', time() - 3600);
    }

    public function getCookieName() {
        return $_COOKIE[self::$cookieName];
    }

    public function getCookiePassword() {
        return $_COOKIE[self::$cookiePassword];
    }

    public function loginCookiesIsSet(){
        if(isset($_COOKIE[self::$cookieName]) && isset($_COOKIE[self::$cookiePassword])){
            return TRUE;
        } else {
            return FALSE;
        }
    }




}