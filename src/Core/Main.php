<?php

namespace Itomori\Core;

use App\src\Routes\Web;
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
        require_once 'Router.php';

        // Init session
        session_start();

        // Init app router
        new Web(new Router());
    }
}
