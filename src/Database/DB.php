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

    /**
     * table.
     *
     * @param string table
     *
     * @return void
     */
    public static function table(string $table)
    {
        self::$table = $table;
    }

    /**
     * create.
     *
     * @param array inputs
     *
     * @return void
     */
    public static function create(array $inputs)
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

    /**
     * delete.
     *
     * @return void
     */
    public static function delete()
    {
        self::request('DROP TABLE '.self::$table);
    }

    /**
     * request.
     *
     * @param string sql
     * @param array attributs
     *
     * @return void
     */
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
