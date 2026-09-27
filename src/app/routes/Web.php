<?php

use nucleo\loadSupport\Route;

Route::get(
    '/home',
    'app\\controllers\\site\\HomeController',
    'index',
    [
        'app\\middlewares\\AuthMiddleware',
    ]
);

Route::get(
    '/login',
    'app\\controllers\\auth\\AuthController',
    'showLogin',
    [
        'app\\middlewares\\GuestMiddleware',
    ]
);

Route::post(
    '/login',
    'app\\controllers\\auth\\AuthController',
    'login',
    [
        'app\\middlewares\\GuestMiddleware',
        'nucleo\\middleware\\CsrfMiddleware',
    ]
);

Route::get(
    '/mfa',
    'app\\controllers\\auth\\MfaController',
    'show',
    [
        'app\\middlewares\\MfaChallengeMiddleware',
    ]
);

Route::post(
    '/mfa',
    'app\\controllers\\auth\\MfaController',
    'verify',
    [
        'app\\middlewares\\MfaChallengeMiddleware',
        'nucleo\\middleware\\CsrfMiddleware',
    ]
);

Route::post(
    '/logout',
    'app\\controllers\\auth\\AuthController',
    'logout',
    [
        'app\\middlewares\\AuthMiddleware',
        'nucleo\\middleware\\CsrfMiddleware',
    ]
);

Route::get(
    '/oauth/{provider}',
    'app\\controllers\\auth\\OAuthController',
    'start'
);

Route::get(
    '/oauth/{provider}/callback',
    'app\\controllers\\auth\\OAuthController',
    'callback'
);

Route::get(
    '/users/{id}',
    'app\\controllers\\site\\UserController',
    'show',
    [
        'app\\middlewares\\AuthMiddleware',
        'app\\middlewares\\AuthorizationMiddleware',
    ],
    [
        'ability' => 'view',
        'resource' => 'users',
        'model' => 'app\\models\\User',
        'parameter' => 'id',
    ]
);

Route::post(
    '/users',
    'app\\controllers\\site\\UserController',
    'create',
    [
        'app\\middlewares\\AuthMiddleware',
        'nucleo\\middleware\\CsrfMiddleware',
        'app\\middlewares\\AuthorizationMiddleware',
    ],
    [
        'ability' => 'create',
        'resource' => 'users',
    ]
);

Route::put(
    '/users/{id}',
    'app\\controllers\\site\\UserController',
    'update',
    [
        'app\\middlewares\\AuthMiddleware',
        'nucleo\\middleware\\CsrfMiddleware',
        'app\\middlewares\\AuthorizationMiddleware',
    ],
    [
        'ability' => 'update',
        'resource' => 'users',
        'model' => 'app\\models\\User',
        'parameter' => 'id',
    ]
);

Route::delete(
    '/users/{id}',
    'app\\controllers\\site\\UserController',
    'delete',
    [
        'app\\middlewares\\AuthMiddleware',
        'nucleo\\middleware\\CsrfMiddleware',
        'app\\middlewares\\AuthorizationMiddleware',
    ],
    [
        'ability' => 'delete',
        'resource' => 'users',
        'model' => 'app\\models\\User',
        'parameter' => 'id',
    ]
);

Route::get(
    '/mfa/setup',
    'app\\controllers\\auth\\MfaSetupController',
    'show',
    [
        'app\\middlewares\\AuthMiddleware',
    ]
);

Route::post(
    '/mfa/setup',
    'app\\controllers\\auth\\MfaSetupController',
    'enable',
    [
        'app\\middlewares\\AuthMiddleware',
        'nucleo\\middleware\\CsrfMiddleware',
    ]
);

Route::post(
    '/mfa/setup/regenerate',
    'app\\controllers\\auth\\MfaSetupController',
    'regenerate',
    [
        'app\\middlewares\\AuthMiddleware',
        'nucleo\\middleware\\CsrfMiddleware',
    ]
);

Route::get(
    '/forgot-password',
    'app\\controllers\\auth\\PasswordRecoveryController',
    'showForgotPassword',
    [
        'app\\middlewares\\GuestMiddleware',
    ]
);

Route::post(
    '/forgot-password',
    'app\\controllers\\auth\\PasswordRecoveryController',
    'forgotPassword',
    [
        'app\\middlewares\\GuestMiddleware',
        'nucleo\\middleware\\CsrfMiddleware',
    ]
);

Route::get(
    '/reset-password/{token}',
    'app\\controllers\\auth\\PasswordRecoveryController',
    'showResetPassword',
    [
        'app\\middlewares\\GuestMiddleware',
    ]
);

Route::post(
    '/reset-password/{token}',
    'app\\controllers\\auth\\PasswordRecoveryController',
    'resetPassword',
    [
        'app\\middlewares\\GuestMiddleware',
        'nucleo\\middleware\\CsrfMiddleware',
    ]
);

Route::get(
    '/verify-email/{token}',
    'app\\controllers\\auth\\EmailVerificationController',
    'verify'
);

Route::get(
    '/resend-verification',
    'app\\controllers\\auth\\EmailVerificationController',
    'showResend',
    [
        'app\\middlewares\\GuestMiddleware',
    ]
);

Route::post(
    '/resend-verification',
    'app\\controllers\\auth\\EmailVerificationController',
    'resend',
    [
        'app\\middlewares\\GuestMiddleware',
        'nucleo\\middleware\\CsrfMiddleware',
    ]
);