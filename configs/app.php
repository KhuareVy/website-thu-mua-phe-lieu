<?php
return [
    'environment'  => getenv('APP_ENV') ?: 'development', // development, production
    'name'         => getenv('APP_NAME') ?: 'Thu mua phế liệu',
    'url'          => getenv('APP_URL') ?: 'http://localhost',
    'debug'        => filter_var(getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOL),
    'key'          => getenv('APP_KEY') ?: 'bZWreCN9c8xIzog1fgudqLtmnvhpJrTw',
    'session_name' => getenv('SESSION_NAME') ?: 'MYAPPSESSID',
    'timezone'     => getenv('APP_TIMEZONE') ?: 'Asia/Ho_Chi_Minh',
    'views_path'   => __DIR__ . '/../app/Views/',
];
