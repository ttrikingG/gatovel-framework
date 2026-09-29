<?php

return [

    'google' => [

        'client_id' => $_ENV['GOOGLE_OAUTH_CLIENT_ID'] ?? '',

        'client_secret' => $_ENV['GOOGLE_OAUTH_CLIENT_SECRET'] ?? '',

        'redirect_uri' => $_ENV['GOOGLE_OAUTH_REDIRECT_URI'] ?? '',

    ],

];
