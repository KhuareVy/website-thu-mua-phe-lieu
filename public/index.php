<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$app = require __DIR__ . '/../app/init.php';


$app->run();