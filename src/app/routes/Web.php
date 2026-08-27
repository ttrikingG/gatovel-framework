<?php

use nucleo\loadSupport\Route;

Route::get(
    '/home', 
    'app\\controllers\\site\\HomeController',
    'index',
    [
        // MIDDLEWARES
        // 'app\\middlewares\\AuthMiddleware',
        // 'app\\middlewares\\GuestMiddleware',
        // 'app\\middlewares\\CsrfMiddleware',
    ]
);

/* ===============================================================================================================================
   EXEMPLO
   ===============================================================================================================================

   Route::get(
       '/perfil/{id}',                          1 URI "Onde o usuário vai acessar?"
       'app\\controllers\\UserController',      2️ CONTROLLER "Quem vai cuidar da requisição?"
       'show',                                  3️ MÉTODO "Quem vai cuidar da requisição?"
       [
           'app\\middlewares\\AuthMiddleware'   4️ MIDDLEWARES "Quem precisa passar pela segurança antes de chegar ao Controller?"
       ]
   );

================================================================================================================================ */


