<?php

namespace nucleo\logging;

use nucleo\config\Config;
use Throwable;

class Logger
{
    public static function error(
        Throwable $exception
    ): void {
        if (
            !Config::get(
                'logging.enabled',
                true
            )
        ) {
            return;
        }

        $path = Config::get(
            'logging.path'
        );

        if (
            !is_string($path)
            || $path === ''
        ) {
            return;
        }

        $directory = dirname(
            $path
        );

        if (
            !is_dir($directory)
            && !@mkdir(
                $directory,
                0775,
                true
            )
        ) {
            return;
        }

        $content = sprintf(
            "[%s] ERROR %s: %s%sFile: %s%sLine: %d%sTrace:%s%s%s%s",
            date('Y-m-d H:i:s'),
            get_class($exception),
            $exception->getMessage(),
            PHP_EOL,
            $exception->getFile(),
            PHP_EOL,
            $exception->getLine(),
            PHP_EOL,
            PHP_EOL,
            $exception->getTraceAsString(),
            PHP_EOL,
            str_repeat('-', 80),
            PHP_EOL
        );

        @file_put_contents(
            $path,
            $content,
            FILE_APPEND | LOCK_EX
        );
    }
}
