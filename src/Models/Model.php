<?php

namespace App\Models;

use App\Config\Database;
use PDO;

abstract class Model
{
    protected static function pdo(): PDO
    {
        return Database::getInstance();
    }
}
