<?php

namespace nucleo\loadSupport;

use nucleo\exceptions\view\LayoutNotFoundException;
use nucleo\exceptions\view\ViewNotFoundException;
use InvalidArgumentException;
use Throwable;

class View
{
    public static function render(
        string $view,
        array $data = [],
        string $layout = 'App'
    ): string {
        self::validateViewName(
            $view
        );

        self::validateLayoutName(
            $layout
        );

        $basePath = dirname(
            __DIR__,
            2
        );

        $viewFile = $basePath
            . '/app/views/'
            . str_replace(
                '.',
                '/',
                $view
            )
            . '.php';

        if (!is_file($viewFile)) {
            throw new ViewNotFoundException(
                $view,
                $viewFile
            );
        }

        $content = self::renderFile(
            $viewFile,
            $data
        );

        $layoutFile = $basePath
            . '/app/views/layout/'
            . $layout
            . '.php';

        if (!is_file($layoutFile)) {
            throw new LayoutNotFoundException(
                $layout,
                $layoutFile
            );
        }

        return self::renderFile(
            $layoutFile,
            array_merge(
                $data,
                [
                    'content' => $content,
                ]
            )
        );
    }

    private static function renderFile(
        string $file,
        array $data
    ): string {
        extract(
            $data,
            EXTR_SKIP
        );

        ob_start();

        try {
            require $file;

            $content = ob_get_clean();

            return is_string($content)
                ? $content
                : '';
        } catch (Throwable $exception) {
            ob_end_clean();

            throw $exception;
        }
    }

    private static function validateViewName(
        string $view
    ): void {
        if (
            $view === ''
            || !preg_match(
                '/^[a-zA-Z0-9_-]+(?:\.[a-zA-Z0-9_-]+)*$/',
                $view
            )
        ) {
            throw new InvalidArgumentException(
                "Nome de view inválido: {$view}"
            );
        }
    }

    private static function validateLayoutName(
        string $layout
    ): void {
        if (
            $layout === ''
            || !preg_match(
                '/^[a-zA-Z0-9_-]+$/',
                $layout
            )
        ) {
            throw new InvalidArgumentException(
                "Nome de layout inválido: {$layout}"
            );
        }
    }
}