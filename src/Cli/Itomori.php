<?php

namespace Obsidian\Cli;

class Itomori extends Console
{
    protected function setup(Options $options)
    {
        // Make command
        $options->registerCommand('make', 'The foo command');

        $options->registerOption('controller', 'test', 'c', false, 'make');
        $options->registerOption('model', 'test', 'm', false, 'make');
        $options->registerArgument('file', 'test', true, 'make');

        // Init command
        $options->registerCommand('init', 'The foo command');

        $options->registerOption('auth', 'test', 'a', false, 'init');
    }

    protected function main(Options $options)
    {
        define('ROOT', dirname(__FILE__, 6));

        switch ($options->getCmd()) {
            case 'init':
                if (array_key_exists('auth', $options->getOpt())) {
                    if (!copy(ROOT.'/vendor/kainovaii/itomori/src/Cli/sample/UserModel.sample.php', ROOT.'/src/Models/UserModel.php')) {
                        echo 'Test';
                    } else {
                        $this->success('⚡️ Auth has been init');
                    }
                }
                break;
            case 'make':
                if (array_key_exists('controller', $options->getOpt())) {
                    $fileName = $options->getArgs()[0].'Controller';

                    $myfile = fopen(ROOT.'/src/Http/Controllers/'.$fileName.'.php', 'w');

                    fwrite($myfile, "<?php

namespace App\src\Http\Controllers;

use Itomori\Core\Controller;

class ".$fileName." extends Controller
{
    public function index()
    {
        echo 'Hello world';
    }
}
                    ");

                    $this->success('⚡️ Controller has been created');
                }
                if (array_key_exists('model', $options->getOpt())) {
                    $fileName = $options->getArgs()[0].'Model';

                    $myfile = fopen(ROOT.'/src/Models/'.$fileName.'.php', 'w');

                    fwrite($myfile, "<?php

namespace App\src\Models;

use Itomori\Core\Model;

class ".$fileName." extends Model
{
    protected \$id;

    public function __construct()
    {
        \$this->table = 'table_name';
    }

    public function getId()
    {
        return \$this->id;
    }

    public function setId(\$id)
    {
        \$this->id = \$id;

        return \$this;
    }
}                    
                    ");

                    $this->success('⚡️ Model has been created');
                }

            break;
        }
    }
}
