<?php
return [
    'name' => getenv('APP_NAME') ?: 'Thu mua phế liệu',
    'url' => getenv('APP_URL') ?: 'http://localhost',
    'debug' => filter_var(getenv('APP_DEBUG') ?: false, FILTER_VALIDATE_BOOL),
    'key' => getenv('APP_KEY') ?: 'bZWreCN9c8xIzog1fgudqLtmnvhpJrTw',
    'session_name' => getenv('SESSION_NAME') ?: 'MYAPPSESSID',
    'timezone' => 'Asia/Ho_Chi_Minh',
];
