<?php

use nucleo\loadSupport\Route;

Route::get(
    '/',
    'app\\controllers\\site\\HomeController',
    'index'
);

Route::get(
    '/home',
    'app\\controllers\\site\\HomeController',
    'index'
);
