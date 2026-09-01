<?php

namespace Gatovel\Cli;

use Gatovel\Cli\commands\HelpCommand;
use Gatovel\Cli\commands\VersionCommand;
use Gatovel\Cli\commands\MakeControllerCommand;

class Application
{
    private CommandRegistry $registry;

    public function __construct()
    {
        $this->registry = new CommandRegistry();

        $this->registerCommands();
    }

    private function registerCommands(): void
    {
        $this->registry->register(
            'help',
            new HelpCommand($this->registry)
        );

        $this->registry->register(
            'version',
            new VersionCommand()
        );

        $this->registry->register(
            'make:controller',
            new MakeControllerCommand()
        );
    }

    public function run(array $arguments): int
    {
        $commandName = $arguments[1] ?? 'help';

        if (!$this->registry->has($commandName)) {

            echo "Command not found: {$commandName}" . PHP_EOL;
            echo "Run 'php gatovel help' for available commands." . PHP_EOL;

            return 1;
        }

        return $this->registry
            ->get($commandName)
            ->handle($arguments);
    }
}