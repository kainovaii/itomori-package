<?php

namespace Itomori\Core;

use App\src\Routes\Web;

class Main
{
    private $root;

    public function __construct($constant)
    {
        $this->root = $constant;
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
