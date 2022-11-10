<?php

namespace Obsidian\Core;

use Symfony\Component\ErrorHandler\Debug;

class Main
{
    private $root;

    public function __construct($constant)
    {
        $this->root = $constant;
        Debug::enable();
    }

    public function start()
    {
        require_once $this->root.'/src/routes/Web.php';

        if (file_exists($this->root.'/src/routes/Api.php')) {
            require_once 'BaseRoutes.php';
            require_once $this->root.'/src/routes/Api.php';
        }

        require_once 'Router.php';

        // Init session
        session_start();

        // Init app router
        $router = new Router();
        $router->run();
    }
}
