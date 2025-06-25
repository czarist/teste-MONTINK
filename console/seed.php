<?php

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

foreach ($_ENV as $key => $value) {
    putenv("$key=$value");
}

use App\Services\DatabaseService;

$service = new DatabaseService();
$service->runSqlFile(__DIR__ . '/../src/Database/seeder/insert.sql');
