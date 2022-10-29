<?php

namespace Itomori\Core;

use PDO;
use PDOException;

class Db extends PDO
{
    private static $instance;

    private const DBHOST = 'sql-frweb10.pulseheberg.net';
    private const DBUSER = 'sysadmin';
    private const DBPASS = '07072004E';
    private const DBNAME = 'sp-main';

    /*
    private const DBHOST = 'localhost';
    private const DBUSER = 'root';
    private const DBPASS = 'root';
    private const DBNAME = 'cest-une-pie-encule';
    */

    /**
     * __construct.
     *
     * @return void
     */
    private function __construct()
    {
        $_dsn = 'mysql:dbname='.self::DBNAME.';host='.self::DBHOST;

        try {
            parent::__construct($_dsn, self::DBUSER, self::DBPASS);

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
