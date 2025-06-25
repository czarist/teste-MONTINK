<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

foreach ($_ENV as $key => $value) {
    putenv("$key=$value");
}

require_once __DIR__ . '/../src/helpers/view.php';

use Bramus\Router\Router;

$router = new Router();

require_once __DIR__ . '/../src/Routes/web.php';
require_once __DIR__ . '/../src/Routes/api.php';

$router->run();
