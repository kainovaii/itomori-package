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
        if (isset($_SESSION['USER'])) {
            echo $this->twig->render($file.'.html.twig', $data + ['session' => $_SESSION['USER']]);
        } else {
            echo $this->twig->render($file.'.html.twig', $data);
        }
    }
}
