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

    public static function beginTransaction(): void
    {
        static::pdo()->beginTransaction();
    }

    public static function commit(): void
    {
        static::pdo()->commit();
    }

    public static function rollback(): void
    {
        static::pdo()->rollBack();
    }
}
