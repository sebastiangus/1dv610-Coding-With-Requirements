<?php

namespace view;

class LoginView {
	private static $login = 'LoginView::Login';
	private static $logout = 'LoginView::Logout';
	private static $name = 'LoginView::UserName';
	private static $password = 'LoginView::Password';
	private static $cookieName = 'LoginView::CookieName';
	private static $cookiePassword = 'LoginView::CookiePassword';
	private static $keep = 'LoginView::KeepMeLoggedIn';
	private static $messageId = 'LoginView::Message';
    private static $responseMessage = '';
    private $welcomeMessage = 'Welcome';
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
        $response = $this->generateLoginFormHTML(self::$responseMessage);
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
        if(!isset($_SESSION['showWelcomeMessage'])) {
            $message .= $this->welcomeMessage;
            $_SESSION['showWelcomeMessage'] = FALSE;
        } else {
            $message = '';
        }
            return $this->generateLogoutButtonHTML($message);
    }

    public function getRequestUserName() {
        if(isset($_REQUEST[self::$name])) {
            return $_REQUEST[self::$name];
        }
    }

    public function  getRequestPassword() {
        if(isset($_REQUEST[self::$password])) {
            return $_REQUEST[self::$password];
        }
    }

    public function setResponseMessage(string $message) {
        self::$responseMessage = $message;
    }

    public function setWelcomeMessage(string $message) {
        $this->welcomeMessage .= ' ' . $message;
    }

    public function userNameOrPasswordIsset(){
        if(isset($_POST[self::$name]) || isset($_POST[self::$password])){
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function setUsername(){
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
}