<?php

namespace app;

class Installer
{
    public function run(): void
    {
        echo PHP_EOL;
        echo "Gatovel Framework Installer" . PHP_EOL;
        echo PHP_EOL;

        $cli = $this->ask(
            'Do you want to install the Gatovel CLI?'
        );

        $database = $this->ask(
            'Do you want to install Database support?'
        );

        $miau = $this->ask(
            'Do you want to install Miau?'
        );

        echo PHP_EOL;
        echo "Installation configuration:" . PHP_EOL;
        echo "CLI: " . ($cli ? 'yes' : 'no') . PHP_EOL;
        echo "Database: " . ($database ? 'yes' : 'no') . PHP_EOL;
        echo "Miau: " . ($miau ? 'yes' : 'no') . PHP_EOL;
        echo PHP_EOL;

        if ($cli) {
            $this->installPackage('gatovel/cli');
        }
    }

    private function ask(string $question): bool
    {
        echo "? {$question} [yes/no]: ";

        $answer = trim(fgets(STDIN));

        return in_array(
            strtolower($answer),
            ['yes', 'y']
        );
    }

    private function installPackage(string $package): void
    {
        echo PHP_EOL;
        echo "Installing {$package}..." . PHP_EOL;

        $command = "composer require {$package}";

        passthru($command, $exitCode);

        if ($exitCode !== 0) {
            echo PHP_EOL;
            echo "Failed to install {$package}." . PHP_EOL;

            return;
        }

        echo PHP_EOL;
        echo "{$package} installed successfully." . PHP_EOL;
    }
}