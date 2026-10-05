<?php

namespace nucleo\middleware;

use nucleo\http\Request;
use nucleo\http\Response;

abstract class Middleware
{
    abstract public function handle(
        Request $request,
        callable $next
    ): Response;
}