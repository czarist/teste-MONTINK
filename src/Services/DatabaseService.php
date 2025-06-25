<?php

namespace App\Services;

use App\Config\Database as Database;
use PDO;
use PDOException;

class DatabaseService
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = Database::getInstance();
    }

    public function runSqlFile(string $filePath): void
    {
        if (!file_exists($filePath)) {
            echo "Arquivo SQL não encontrado: $filePath" . PHP_EOL;
            exit(1);
        }

        $sql = file_get_contents($filePath);

        try {
            $this->pdo->exec($sql);
            echo "Migração executada com sucesso!" . PHP_EOL;
        } catch (PDOException $e) {
            echo "Erro ao executar SQL: " . $e->getMessage() . PHP_EOL;
            exit(1);
        }
    }
}
