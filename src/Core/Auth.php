<?php

namespace Obsidian\Core;

class Auth
{
    private static string $index = 'bTbLQdY0B6rBJqtqvajpQ8Y9HH8BjVvL';

    /**
     * check.
     *
     * @return void
     */
    public static function check()
    {
        if (isset($_SESSION[self::$index])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * logout.
     *
     * @return void
     */
    public static function logout()
    {
        if (self::check()) {
            unset($_SESSION[self::$index]);

            return true;
        }
    }

    /**
     * login.
     *
     * @param array user
     *
     * @return void
     */
    public static function login(array $user)
    {
        $_SESSION[self::$index] = $user;

        return true;
    }

    public static function user(string $index = null)
    {
        if (self::check()) {
            if (isset($index)) {
                return $_SESSION[self::$index][$index];
            } else {
                return $_SESSION[self::$index];
            }
        }
    }
}
