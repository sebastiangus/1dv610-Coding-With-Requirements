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
    private static $hashedPassword;
    private static $metaHash;

    protected function __construct(string $username, string $password)
    {
        self::$username = $username;
        self::$password = $password;
        self::$hashedPassword = $this->getHashedPassword();
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

    public function getMetaHash(){
        self::$hashedPassword;
        if(self::$metaHash === null){
            self::$metaHash = password_hash(self::$hashedPassword, PASSWORD_DEFAULT);
            return self::$metaHash;
        } else {
            return self::$metaHash;
        }
    }
}