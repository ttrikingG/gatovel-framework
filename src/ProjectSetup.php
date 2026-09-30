<?php

namespace app;

class ProjectSetup
{
    public function run(): void
    {
        $projectPath = dirname(__DIR__);

        $environmentFile = $projectPath . '/.env';
        $exampleFile = $projectPath . '/.env.example';

        if (is_file($environmentFile)) {
            echo "Environment file already exists." . PHP_EOL;

            return;
        }

        if (!is_file($exampleFile)) {
            echo "Warning: .env.example was not found." . PHP_EOL;

            return;
        }

        if (!copy($exampleFile, $environmentFile)) {
            echo "Warning: could not create .env file." . PHP_EOL;

            return;
        }

        echo "Environment file created from .env.example." . PHP_EOL;
    }
}

(new ProjectSetup())->run();
