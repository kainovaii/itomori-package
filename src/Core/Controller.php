<?php

namespace Obsidian\Core;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Obsidian\Autoloader;
use Symfony\Component\ErrorHandler\Debug;

abstract class Controller
{
    /**
     * @var mixed loader
     */
    protected static $loader;

    /**
     * @var mixed twig
     */
    protected static $twig;

    /**
     * __construct.
     *
     * @return void
     */
    public function __construct()
    {
        require_once dirname(__FILE__, 2).'\Autoloader.php';

        Autoloader::register();

        Debug::enable();

        self::$loader = new FileSystemLoader(ROOT.'/src/Views/');

        self::$twig = new Environment(self::$loader, [
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
    public function view(string $file, array $data = [])
    {
        echo self::$twig->render($file.'.html.twig', $data);
    }
}
