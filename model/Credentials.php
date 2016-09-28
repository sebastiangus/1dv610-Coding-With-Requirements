<?php
/**
 * Created by PhpStorm.
 * User: sebastiangustavsson
 * Date: 2016-09-22
 * Time: 11:34
 */
namespace model;

class Credentials {
    private static $username;
    private static $password;

    protected function __construct(string $username, string $password) {
        self::$username = $username;
        self::$password = $password;
    }

    public function getUsername() : string {
        return self::$username;
    }

    public function getPassword() : string {
        return self::$password;
    }

    //http://php.net/manual/en/faq.passwords.php
    public function getHashedPassword(){
        return password_hash(self::$password, PASSWORD_DEFAULT);
    }
}