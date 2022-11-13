<?php

namespace Obsidian\Database;

class DB extends Connection
{
    /**
     * @var mixed table
     */
    protected static $table;

    /**
     * @var mixed db
     */
    private static $db;

    public static function table(string $table)
    {
        self::$table = $table;
    }

    public static function create($inputs)
    {
        // Create table whith temp input
        self::request('CREATE TABLE '.self::$table.' (temps INT);');
        dump('Migration: '.self::$table.' has been created');

        // Create all inputs
        foreach ($inputs as $input) {
            self::request('ALTER TABLE '.self::$table.' ADD '.$input.';');
        }

        // Delete temp input
        self::request('ALTER TABLE '.self::$table.' DROP temps');
        dump('Migration: success');
    }

    public static function delete()
    {
        self::request('DROP TABLE '.self::$table);
    }

    public static function request(string $sql, array $attributs = null)
    {
        self::$db = Connection::getInstance();

        if ($attributs !== null) {
            $query = self::$db->prepare($sql);
            $query->execute($attributs);

            return $query;
        } else {
            return self::$db->query($sql);
        }
    }
}
