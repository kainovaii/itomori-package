<?php

namespace Obsidian\Auth;

use Obsidian\Console\Logger;
use Obsidian\Console\Options;

class AuthCommand extends Logger
{
    protected function setup(Options $command)
    {
        $command->setCommand('test', 'The foo command');
        $command->setOption('model', 'test', 'm', false, 'test');
    }

    protected function main(Options $command)
    {
        if ($command->isOption('model')) {
            $this->success('success');
        } else {
            $this->error('error');
        }
    }
}
