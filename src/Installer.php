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

        $auth = $this->ask(
            'Do you want to install Gatovel Auth?'
        );

        echo PHP_EOL;
        echo "Installation configuration:" . PHP_EOL;
        echo "CLI: " . ($cli ? 'yes' : 'no') . PHP_EOL;
        echo "Database: " . ($database ? 'yes' : 'no') . PHP_EOL;
        echo "Auth: " . ($auth ? 'yes' : 'no') . PHP_EOL;
        echo PHP_EOL;

        if ($cli === true) {
            $this->installPackage('gatovel/cli');
        }

        if ($database === true) {
            $this->installPackage('gatovel/database');
        }

        if ($auth === true) {
            $this->installPackage('gatovel/auth');
        }

        echo PHP_EOL;
        echo "Gatovel Framework installation completed." . PHP_EOL;
        echo PHP_EOL;
    }

    private function ask(string $question): bool
    {
        while (true) {
            echo "? {$question} [yes/no]: ";

            $answer = trim(fgets(STDIN));

            $answer = strtolower($answer);

            if ($answer === 'yes' || $answer === 'y') {
                return true;
            }

            if ($answer === 'no' || $answer === 'n') {
                return false;
            }

            echo "Please answer yes or no." . PHP_EOL;
        }
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

(new Installer())->run();