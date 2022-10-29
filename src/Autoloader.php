<?php

namespace Itomori;

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
        define('ROOT2', dirname(__FILE__, 5));

        require_once ROOT2.'/vendor/autoload.php';
        require_once ROOT2.'/vendor/symfony/error-handler/Debug.php';
        require_once ROOT2.'/vendor/twig/twig/src/Environment.php';
        require_once ROOT2.'/vendor/twig/twig/src/Loader/FilesystemLoader.php';

        Debug::enable();

        $class = str_replace(__NAMESPACE__.'\\', '', $class);
        $class = str_replace('\\', '/', $class);

        $fichier = __DIR__.'/'.$class.'.php';
        if (file_exists($fichier)) {
            require_once $fichier;
        }
    }
}
