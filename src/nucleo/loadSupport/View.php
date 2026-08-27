<?php

namespace nucleo\loadSupport;

class View
{
    public static function render(
        string $view,
        array $data = []
    ): string {
        $viewFile = dirname(__DIR__, 2)
            . '/app/views/'
            . str_replace('.', '/', $view)
            . '.php';

        if (!file_exists($viewFile)) {
            throw new \Exception(
                "View não encontrada: {$view}"
            );
        }

        extract($data);

        ob_start();

        require $viewFile;

        return ob_get_clean();
    }
}