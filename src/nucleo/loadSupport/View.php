<?php

namespace nucleo\loadSupport;

class View
{
    public static function render(
        string $view,
        array $data = [],
        string $layout = 'App'
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

        $content = ob_get_clean();

        $layoutFile = dirname(__DIR__, 2)
            . '/app/views/layout/'
            . $layout
            . '.php';

        if (!file_exists($layoutFile)) {
            throw new \Exception(
                "Layout não encontrado: {$layout}"
            );
        }

        ob_start();

        require $layoutFile;

        return ob_get_clean();
    }
}