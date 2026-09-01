<?php

namespace nucleo\middleware;

use nucleo\loadSupport\Request;
use nucleo\loadSupport\Response;

abstract class Middleware
{
    abstract public function handle(
        Request $request,
        callable $next
    ): Response;
}