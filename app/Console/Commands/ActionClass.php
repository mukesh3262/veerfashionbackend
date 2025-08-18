<?php

declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class ActionClass extends GeneratorCommand
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'make:action';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new Action class';

    /**
     * The type of class being generated.
     *
     * @var string
     */
    protected $type = 'action';

    /**
     * Get the stub file for the generator.
     *
     * @return string
     */
    protected function getStub()
    {
        return resource_path('stubs/actionStub.stub');
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace."\Action";
    }
}
