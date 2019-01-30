<?php

return [

    /*
    |--------------------------------------------------------------------------
    | ADMIN skin
    |--------------------------------------------------------------------------
    */

    // blue | blue-light | black | black-light | green | green-light | purple | purple-light | red | red-light | yellow | yellow-light
    'admin_skin' => 'blue',

    /*
    |--------------------------------------------------------------------------
    | If admin must include google analytics tracking
    |--------------------------------------------------------------------------
    */

    'admin_google_analytics' => false,

    /*
    |--------------------------------------------------------------------------
    | If admin must load google fonts (disabled on local)
    |--------------------------------------------------------------------------
    */

    'admin_fonts' => (env('APP_ENV', 'production') != 'local'),
];
