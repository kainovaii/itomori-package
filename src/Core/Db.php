<?php

namespace Obsidian\Core;

use PDO;
use PDOException;

class Db extends PDO
{
    private static $instance;

    /**
     * __construct.
     *
     * @return void
     */
    private function __construct()
    {
        $_dsn = 'mysql:dbname='.$_ENV['DB_NAME'].';host='.$_ENV['DB_HOST'];

        try {
            parent::__construct($_dsn, $_ENV['DB_USER'], $_ENV['DB_PASS']);

            $this->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
            $this->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            $this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    /**
     * getInstance.
     *
     * @return undefined
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}
