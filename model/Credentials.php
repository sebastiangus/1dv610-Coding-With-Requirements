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
}