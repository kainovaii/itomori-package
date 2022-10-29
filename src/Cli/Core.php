<?php

namespace Itomori\Cli;

require_once 'Colors.php';
require_once 'Exception.php';
require_once 'Options.php';

abstract class Core
{
    /** @var string the executed script itself */
    protected $bin;
    /** @var Options the option parser */
    protected $options;
    /** @var Colors */
    public $colors;

    /** @var array PSR-3 compatible loglevels and their prefix, color, output channel */
    protected $loglevel = [
        'debug' => ['', Colors::C_RESET, STDOUT],
        'info' => ['ℹ ', Colors::C_CYAN, STDOUT],
        'notice' => ['☛ ', Colors::C_CYAN, STDOUT],
        'success' => ['✓ ', Colors::C_GREEN, STDOUT],
        'warning' => ['⚠ ', Colors::C_BROWN, STDERR],
        'error' => ['✗ ', Colors::C_RED, STDERR],
        'critical' => ['☠ ', Colors::C_LIGHTRED, STDERR],
        'alert' => ['✖ ', Colors::C_LIGHTRED, STDERR],
        'emergency' => ['✘ ', Colors::C_LIGHTRED, STDERR],
    ];

    protected $logdefault = 'info';

    /**
     * constructor.
     *
     * Initialize the arguments, set up helper classes and set up the CLI environment
     *
     * @param bool $autocatch should exceptions be catched and handled automatically?
     */
    public function __construct($autocatch = true)
    {
        if ($autocatch) {
            set_exception_handler([$this, 'fatal']);
        }

        $this->colors = new Colors();
        $this->options = new Options($this->colors);
    }

    /**
     * Register options and arguments on the given $options object.
     *
     * @return void
     *
     * @throws Exception
     */
    abstract protected function setup(Options $options);

    /**
     * Your main program.
     *
     * Arguments and options have been parsed when this is run
     *
     * @return void
     *
     * @throws Exception
     */
    abstract protected function main(Options $options);

    /**
     * Execute the CLI program.
     *
     * Executes the setup() routine, adds default options, initiate the options parsing and argument checking
     * and finally executes main() - Each part is split into their own protected function below, so behaviour
     * can easily be overwritten
     *
     * @throws Exception
     */
    public function run()
    {
        if ('cli' != php_sapi_name()) {
            throw new Exception('This has to be run from the command line');
        }

        $this->setup($this->options);
        $this->registerDefaultOptions();
        $this->parseOptions();
        $this->handleDefaultOptions();
        $this->setupLogging();
        $this->checkArguments();
        $this->execute();
    }

    // region run handlers - for easier overriding

    /**
     * Add the default help, color and log options.
     */
    protected function registerDefaultOptions()
    {
        $this->options->registerOption(
            'help',
            'Display this help screen and exit immediately.',
            'h'
        );
        $this->options->registerOption(
            'no-colors',
            'Do not use any colors in output. Useful when piping output to other tools or files.'
        );
        $this->options->registerOption(
            'loglevel',
            'Minimum level of messages to display. Default is '.$this->colors->wrap($this->logdefault, Colors::C_CYAN).'. '.
            'Valid levels are: debug, info, notice, success, warning, error, critical, alert, emergency.',
            null,
            'level'
        );
    }

    /**
     * Handle the default options.
     */
    protected function handleDefaultOptions()
    {
        if ($this->options->getOpt('no-colors')) {
            $this->colors->disable();
        }
        if ($this->options->getOpt('help')) {
            echo $this->options->help();
            exit(0);
        }
    }

    /**
     * Handle the logging options.
     */
    protected function setupLogging()
    {
        $level = $this->options->getOpt('loglevel', $this->logdefault);
        if (!isset($this->loglevel[$level])) {
            $this->fatal('Unknown log level');
        }
        foreach (array_keys($this->loglevel) as $l) {
            if ($l == $level) {
                break;
            }
            unset($this->loglevel[$l]);
        }
    }

    /**
     * Wrapper around the option parsing.
     */
    protected function parseOptions()
    {
        $this->options->parseOptions();
    }

    /**
     * Wrapper around the argument checking.
     */
    protected function checkArguments()
    {
        $this->options->checkArguments();
    }

    /**
     * Wrapper around main.
     */
    protected function execute()
    {
        $this->main($this->options);
    }

    // endregion

    // region logging

    /**
     * Exits the program on a fatal error.
     *
     * @param \Exception|string $error either an exception or an error message
     */
    public function fatal($error, array $context = [])
    {
        $code = 0;
        if (is_object($error) && is_a($error, 'Exception')) {
            /* @var Exception $error */
            $this->logMessage('debug', get_class($error).' caught in '.$error->getFile().':'.$error->getLine());
            $this->logMessage('debug', $error->getTraceAsString());
            $code = $error->getCode();
            $error = $error->getMessage();
        }
        if (!$code) {
            $code = Exception::E_ANY;
        }

        $this->logMessage('critical', $error, $context);
        exit($code);
    }

    /**
     * Normal, positive outcome (This is not a PSR-3 level).
     *
     * @param string $string
     */
    public function success($string, array $context = [])
    {
        $this->logMessage('success', $string, $context);
    }

    /**
     * @param string $level
     * @param string $message
     */
    protected function logMessage($level, $message, array $context = [])
    {
        // is this log level wanted?
        if (!isset($this->loglevel[$level])) {
            return;
        }

        /** @var string $prefix */
        /** @var string $color */
        /** @var resource $channel */
        list($prefix, $color, $channel) = $this->loglevel[$level];
        if (!$this->colors->isEnabled()) {
            $prefix = '';
        }

        $message = $this->interpolate($message, $context);
        $this->colors->ptln($prefix.$message, $color, $channel);
    }

    /**
     * Interpolates context values into the message placeholders.
     *
     * @param $message
     *
     * @return string
     */
    protected function interpolate($message, array $context = [])
    {
        // build a replacement array with braces around the context keys
        $replace = [];
        foreach ($context as $key => $val) {
            // check that the value can be casted to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{'.$key.'}'] = $val;
            }
        }

        // interpolate replacement values into the message and return
        return strtr((string) $message, $replace);
    }

    // endregion
}
