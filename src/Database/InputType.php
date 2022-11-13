<?php

namespace Obsidian\Database;

class InputType
{
    /**
     * increments.
     *
     * @param string name
     * @param int size
     *
     * @return void
     */
    public static function increments(string $name, int $size = 11)
    {
        return $name.' INT('.$size.') NOT NULL AUTO_INCREMENT, ADD PRIMARY KEY(`'.$name.'`)';
    }

    /**
     * int.
     *
     * @param string name
     * @param int size
     *
     * @return void
     */
    public static function int(string $name, int $size = 11)
    {
        return $name.' INT('.$size.') NOT NULL';
    }

    /**
     * string.
     *
     * @param string name
     * @param int size
     *
     * @return void
     */
    public static function string(string $name, int $size = 256)
    {
        return $name.' VARCHAR('.$size.') NOT NULL';
    }

    /**
     * text.
     *
     * @param string name
     *
     * @return void
     */
    public static function text(string $name)
    {
        return $name.' TEXT NOT NULL';
    }
}
