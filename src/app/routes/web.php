<?php

use nucleo\loadSupport\Route;

Route::get(
    '/',
    'app\\controllers\\site\\HomeController',
    'index'
);

Route::get(
    '/home/teste/{id}',
    'app\\controllers\\site\\HomeController',
    'teste'
);

Route::post(
    '/home/teste',
    'app\\controllers\\site\\HomeController',
    'teste'
);