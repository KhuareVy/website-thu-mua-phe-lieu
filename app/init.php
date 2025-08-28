<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$appConfig = require dirname(__DIR__) . '/configs/app.php';
$dbConfig = require dirname(__DIR__) . '/configs/database.php';

if (session_status() === PHP_SESSION_NONE) {
    session_name($appConfig['session_name']);
    session_start();
}

use App\Core\Application;
use App\Core\Middleware\CsrfMiddleware;
use App\Core\Middleware\CorsMiddleware;
use \App\Core\Middleware\RateLimitMiddleware;

$app = new Application(array_merge($appConfig, ['database' => $dbConfig]));

// Add global middlewares
$app->addMiddleware(new CorsMiddleware());
$app->addMiddleware(new CsrfMiddleware());
$app->addMiddleware(new RateLimitMiddleware());

require dirname(__DIR__) . '/routes/web.php';

return $app;
