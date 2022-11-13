<?php

namespace Obsidian\Database\Console;

use Obsidian\Console\Options as Console;

class MigrationCommand extends BaseCommand
{
    private $command = 'orm';

    protected function setup(Console $console)
    {
        $console->setCommand($this->command);
        $console->setOption('make:migration', 'make:migration', 'make:migration', false, $this->command);
        $console->setOption('start:migrate', 'start:migrate', 'start:migrate', false, $this->command);
        $console->setArg('file', 'file', true, $this->command);
    }

    protected function main(Console $console)
    {
        define('ROOT', dirname(__FILE__, 7));

        if ($console->getCmd($this->command)) {
            // Make migrate file
            if ($console->isOption('make:migration')) {
                $fileName = $console->getArgs()[0];
                $className = $this->snakeToPascal($fileName);

                $myfile = fopen(ROOT.'/src/Migrations/'.$fileName.'.php', 'w');

                $write = fwrite($myfile, "<?php
                
namespace App\src\Migrations;

use Obsidian\Database\DB;
use Obsidian\Database\Migrations\Migration;

class ".$className.' extends Migration
{

    public static function run()
    {

    }
}
                
                ');
                if ($write) {
                    dump('Migration: '.$fileName.'.php has been created');
                }
            }

            // Migrate commande
            if ($console->isOption('start:migrate')) {
                $fileName = $console->getArgs()[0];
                $className = $this->snakeToPascal($fileName);

                require_once ROOT.'/src/Migrations/'.$fileName.'.php';

                $finalName = '\\App\\src\\Migrations\\'.$className.'';

                $finalName::run();
            }
        }
    }

    public function snakeToPascal($string)
    {
        $str = $string;
        $str = preg_replace_callback('/(?:^|_)([a-z])/', function ($matches) {
            //       Start or underscore    ^      ^ lowercase character
            return strtoupper($matches[1]);
        }, $str);

        return $str;
    }
}
