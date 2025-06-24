<?php

namespace App\Models;

class Produto extends Model
{
    public static function listar(): array
    {
        $stmt = static::pdo()->query("SELECT * FROM produtos WHERE status = 'active'");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function listarPaginado(int $limit, int $offset): array
    {
        $stmt = static::pdo()->prepare("SELECT * FROM produtos WHERE status = 'active' LIMIT ? OFFSET ?");
        $stmt->bindValue(1, $limit, \PDO::PARAM_INT);
        $stmt->bindValue(2, $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function total(): int
    {
        $stmt = static::pdo()->query("SELECT COUNT(*) FROM produtos WHERE status = 'active'");
        return (int) $stmt->fetchColumn();
    }

    public static function excluir(int $id): void
    {
        $stmt = static::pdo()->prepare("UPDATE produtos SET status = 'inactive' WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function buscar(int $id): ?array
    {
        $stmt = static::pdo()->prepare("SELECT * FROM produtos WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result !== false ? $result : null;
    }

    public static function inserir(string $nome, float $preco): int
    {
        $stmt = static::pdo()->prepare("INSERT INTO produtos (nome, preco) VALUES (?, ?)");
        $stmt->execute([$nome, $preco]);
        return (int) static::pdo()->lastInsertId();
    }

    public static function atualizar(int $id, string $nome, float $preco): void
    {
        $stmt = static::pdo()->prepare("UPDATE produtos SET nome = ?, preco = ? WHERE id = ?");
        $stmt->execute([$nome, $preco, $id]);
    }
}
