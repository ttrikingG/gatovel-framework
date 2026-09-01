<?php

namespace Gatovel\Cli\commands;

use Gatovel\Cli\Command;

class VersionCommand extends Command
{
    public function name(): string
    {
        return 'version';
    }

    public function description(): string
    {
        return 'Show Gatovel version';
    }

    public function handle(array $arguments): int
    {
        echo "Gatovel Framework v1.0.0" . PHP_EOL;

        return 0;
    }
}