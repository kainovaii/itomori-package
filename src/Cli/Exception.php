<?php

namespace Itomori\Cli;

class Exception extends \RuntimeException
{
    public const E_ANY = -1;
    public const E_UNKNOWN_OPT = 1;
    public const E_OPT_ARG_REQUIRED = 2;
    public const E_OPT_ARG_DENIED = 3;
    public const E_OPT_ABIGUOUS = 4;
    public const E_ARG_READ = 5;

    /**
     * __construct.
     *
     * @param mixed message
     * @param mixed code
     * @param \Exception previous
     *
     * @return void
     */
    public function __construct($message = '', $code = 0, \Exception $previous = null)
    {
        if (!$code) {
            $code = self::E_ANY;
        }
        parent::__construct($message, $code, $previous);
    }
}
