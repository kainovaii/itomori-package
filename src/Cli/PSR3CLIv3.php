<?php

namespace Itomori\Cli;

use Psr\Log\LoggerInterface;
use Psr\Log\LoggerTrait;

abstract class PSR3CLIv3 extends Core implements LoggerInterface
{
    use LoggerTrait;

    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->logMessage($level, $message, $context);
    }
}
