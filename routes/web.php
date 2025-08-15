<?php
use App\Controllers\HomeController;

$app->router->get('/', [HomeController::class, 'index']);
