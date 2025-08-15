<?php
require_once dirname(__DIR__) . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$appConfig = require dirname(__DIR__) . '/configs/app.php';
$dbConfig = require dirname(__DIR__) . '/configs/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_name($appConfig['session_name']);
    session_start();
}

use App\Core\Application;

$app = new Application([
    'app' => $appConfig,
    'db' => $dbConfig
]);

require dirname(__DIR__) . '/routes/web.php';

return $app;
