<?php

namespace Itomori\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Itomori\Autoloader;

abstract class Controller
{
    /**
     * @var mixed loader
     */
    private $loader;

    /**
     * @var mixed twig
     */
    protected $twig;

    /**
     * __construct.
     *
     * @return void
     */
    public function __construct()
    {
        require_once dirname(__FILE__, 2).'\Autoloader.php';

        Autoloader::register();

        $this->loader = new FileSystemLoader(ROOT.'/src/Views/');

        $this->twig = new Environment($this->loader, [
            //'cache' => ROOT.'/src/Cache',
        ]);
    }

    /**
     * render.
     *
     * @param string file
     * @param array data
     *
     * @return void
     */
    public function render(string $file, array $data = [])
    {
        $session = new Session();

        if (isset($_SESSION['USER_TOKEN'])) {
            $registered = $session->get($_SESSION['USER_TOKEN']);

            echo $this->twig->render($file.'.html.twig', $data + ['session' => $registered]);
        } else {
            echo $this->twig->render($file.'.html.twig', $data);
        }
    }
}
