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
}

