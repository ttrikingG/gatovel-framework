<?php

namespace Gatovel\Cli\commands;

use Gatovel\Cli\Command;
use Gatovel\Cli\generators\ControllerGenerator;

class MakeControllerCommand extends Command
{
    public function name(): string
    {
        return 'make:controller';
    }

    public function description(): string
    {
        return 'Create a new controller';
    }

    public function handle(array $arguments): int
    {
        $name = $arguments[2] ?? null;

        if ($name === null) {
            echo "Controller name is required." . PHP_EOL;

            return 1;
        }

        try {
            $generator = new ControllerGenerator();

            $path = $generator->generate($name);

            echo "Controller created: {$path}" . PHP_EOL;

            return 0;

        } catch (\Exception $exception) {
            echo $exception->getMessage() . PHP_EOL;

            return 1;
        }
    }
}