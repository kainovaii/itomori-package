<?php

namespace Obsidian\Database\Console;

use Obsidian\Console\Logger;
use Obsidian\Console\Options;

class DbCommand extends Logger
{
    protected function setup(Options $command)
    {
        $command->setCommand('make');
        $command->setOption('migration', 'test', 'migration', false, 'make');
    }

    protected function main(Options $command)
    {
        if ($command->isOption('migration')) {
            $this->success('success');
        } else {
            $this->error('error');
        }
    }
}
