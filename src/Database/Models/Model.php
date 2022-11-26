<?php

namespace Obsidian\Database\Models;

use Obsidian\Database\Connection;
use Obsidian\Database\DB;

class Model extends Connection
{
    /**
     * @var mixed table
     */
    protected static $table;

    /**
     * findAll.
     *
     * @return void
     */
    public function setTable($table)
    {
        self::$table = $table;
    }

    public static function findAll()
    {
        $query = DB::request('SELECT * FROM '.self::$table);

        return $query->fetchAll();
    }

    /**
     * findBy.
     *
     * @param array data
     *
     * @return void
     */
    public static function findBy(array $data)
    {
        $inputs = [];
        $values = [];

        foreach ($data as $input => $value) {
            $inputs[] = "$input = ?";
            $values[] = $value;
        }

        $input_list = implode(' AND ', $inputs);

        return DB::request('SELECT * FROM '.self::$table.' WHERE '.$input_list, $values)->fetchAll();
    }

    /**
     * find.
     *
     * @param int id
     *
     * @return void
     */
    public static function find(int $id)
    {
        return DB::request('SELECT * FROM '.self::$table." WHERE id = $id")->fetch();
    }

    /**
     * delete.
     *
     * @param int id
     *
     * @return void
     */
    public static function delete(int $id)
    {
        return DB::request('DELETE FROM '.self::$table.' WHERE id = ?', [$id]);
    }

    /**
     * hydrate.
     *
     * @param mixed data
     *
     * @return void
     */
    public function hydrate($data)
    {
        foreach ($data as $key => $value) {
            $setter = 'set'.ucfirst($key);

            if (method_exists($this, $setter)) {
                $this->$setter($value);
            }
        }

        return $this;
    }
}
