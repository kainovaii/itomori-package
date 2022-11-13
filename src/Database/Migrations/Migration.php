<?php

namespace Obsidian\Database\Migrations;

use Obsidian\Database\DB;
use Obsidian\Database\Connection;

class Migration extends Connection
{
    protected static function table(string $table)
    {
        return DB::table($table);
    }

    protected static function create(array $inputs)
    {
        return DB::create($inputs);
    }
}
