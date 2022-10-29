<?php

namespace Itomori\Cli;

require_once 'bin/Console.php';

class Itomori extends Console
{
    protected function setup(Options $options)
    {
        $options->registerCommand('make', 'The foo command');
        $options->registerCommand('test', 'The foo command');

        $options->registerOption('controller', 'test', 'c', false, 'make');
        $options->registerOption('model', 'test', 'm', false, 'make');
        $options->registerArgument('file', 'test', true, 'make');
    }

    protected function main(Options $options)
    {
        switch ($options->getCmd()) {
            case 'make':
                if (array_key_exists('controller', $options->getOpt())) {
                    $fileName = $options->getArgs()[0].'Controller';

                    $myfile = fopen('src/Http/Controllers/'.$fileName.'.php', 'w');

                    fwrite($myfile, "<?php
                    
                    namespace App\src\Http\Controllers;
                    
                    use App\src\Core\Controller;
                    
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

                    $myfile = fopen('src/Models/'.$fileName.'.php', 'w');

                    fwrite($myfile, "<?php

                    namespace App\src\Models;
                    
                    use App\src\Core\Model;
                    
                    class SampleModel extends Model
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
