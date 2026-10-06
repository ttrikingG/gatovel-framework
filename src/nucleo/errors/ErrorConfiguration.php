<?php

namespace nucleo\errors;

use nucleo\config\Config;

class ErrorConfiguration
{
    public static function configure(): void
    {
        $debug = (bool) Config::get(
            'app.debug',
            false
        );

        ini_set(
            'display_errors',
            $debug ? '1' : '0'
        );

        ini_set(
            'display_startup_errors',
            $debug ? '1' : '0'
        );

        if (!$debug) {
            ini_set(
                'log_errors',
                '1'
            );
        }

        error_reporting(
            E_ALL
        );
    }
}
