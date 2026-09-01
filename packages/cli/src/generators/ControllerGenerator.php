<?php

namespace Gatovel\Cli\generators;

class ControllerGenerator
{
    public function generate(string $name): string
    {
        $name = trim($name, '/');

        $parts = explode('/', $name);

        $className = array_pop($parts);

        $directory = 'src/app/controllers';

        if (!empty($parts)) {
            $directory .= '/' . implode('/', $parts);
        }

        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        $namespace = 'app\\controllers';

        if (!empty($parts)) {
            $namespace .= '\\' . implode('\\', $parts);
        }

        $file = $directory . '/' . $className . '.php';

        if (file_exists($file)) {
            throw new \Exception(
                "Controller already exists: {$className}"
            );
        }

        $content = "<?php\n\n";
        $content .= "namespace {$namespace};\n\n";
        $content .= "class {$className}\n";
        $content .= "{\n";
        $content .= "    public function index(): void\n";
        $content .= "    {\n";
        $content .= "        //\n";
        $content .= "    }\n\n";
        $content .= "    public function create(): void\n";
        $content .= "    {\n";
        $content .= "        //\n";
        $content .= "    }\n\n";
        $content .= "    public function store(): void\n";
        $content .= "    {\n";
        $content .= "        //\n";
        $content .= "    }\n\n";
        $content .= "    public function show(): void\n";
        $content .= "    {\n";
        $content .= "        //\n";
        $content .= "    }\n\n";
        $content .= "    public function edit(): void\n";
        $content .= "    {\n";
        $content .= "        //\n";
        $content .= "    }\n\n";
        $content .= "    public function update(): void\n";
        $content .= "    {\n";
        $content .= "        //\n";
        $content .= "    }\n\n";
        $content .= "    public function destroy(): void\n";
        $content .= "    {\n";
        $content .= "        //\n";
        $content .= "    }\n";
        $content .= "}\n";

        file_put_contents($file, $content);

        return $file;
    }
}