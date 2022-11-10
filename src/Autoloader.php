<?php

namespace Obsidian;

use Dotenv\Dotenv;
use Symfony\Component\ErrorHandler\Debug;

class Autoloader
{
    public static function register()
    {
        spl_autoload_register([
            __CLASS__,
            'autoload',
        ]);
    }

    public static function autoload($class)
    {
        require_once ROOT.'/vendor/autoload.php';
        require_once ROOT.'/vendor/symfony/error-handler/Debug.php';
        require_once ROOT.'/vendor/twig/twig/src/Environment.php';
        require_once ROOT.'/vendor/twig/twig/src/Loader/FilesystemLoader.php';

        $dotenv = Dotenv::createImmutable(dirname(__FILE__, 5));
        $dotenv->safeLoad();

        Debug::enable();

        $class = str_replace(__NAMESPACE__.'\\', '', $class);
        $class = str_replace('\\', '/', $class);

        $fichier = __DIR__.'/'.$class.'.php';
        if (file_exists($fichier)) {
            require_once $fichier;
        }
    }
}
