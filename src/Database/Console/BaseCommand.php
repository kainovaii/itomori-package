<?php

namespace Obsidian\Database\Console;

use Obsidian\Console\Logger;
use Obsidian\Console\Options as Console;

class BaseCommand extends Logger
{
    public $console;

    protected function setup(Console $console)
    {
        $console->setCommand('make');
        $console->setOption('test', 'test', 'test', false, 'make');
    }

    protected function main(Console $console)
    {
    }
}
