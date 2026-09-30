<?php

namespace app;

class Installer
{
    private const PACKAGES = [
        'cli' => 'gatovel/cli:^2.0',
        'database' => 'gatovel/database:^2.0',
        'auth' => 'gatovel/auth:^1.0',
    ];

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

        if ($cli) {
            $this->installPackage(
                self::PACKAGES['cli']
            );
        }

        if ($database) {
            $this->installPackage(
                self::PACKAGES['database']
            );
        }

        if ($auth) {
            $this->installPackage(
                self::PACKAGES['auth']
            );
        }

        echo PHP_EOL;
        echo "Gatovel Framework installation completed." . PHP_EOL;
        echo PHP_EOL;
    }

    private function ask(string $question): bool
    {
        while (true) {
            echo "? {$question} [yes/no]: ";

            $answer = fgets(STDIN);

            if ($answer === false) {
                return false;
            }

            $answer = strtolower(
                trim($answer)
            );

            if (
                $answer === 'yes'
                || $answer === 'y'
            ) {
                return true;
            }

            if (
                $answer === 'no'
                || $answer === 'n'
            ) {
                return false;
            }

            echo "Please answer yes or no." . PHP_EOL;
        }
    }

    private function installPackage(
        string $package
    ): void {
        echo PHP_EOL;
        echo "Installing {$package}..." . PHP_EOL;

        $command = sprintf(
            'composer require %s --no-interaction',
            escapeshellarg($package)
        );

        passthru(
            $command,
            $exitCode
        );

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
