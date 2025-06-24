<?php

namespace App\Models;

class Variacao extends Model
{
    public static function listarPorProduto(int $produto_id): array
    {
        $stmt = static::pdo()->prepare("SELECT id, atributo, valor FROM variacoes WHERE produto_id = ?");
        $stmt->execute([$produto_id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function inserir(int $produto_id, string $atributo, string $valor): void
    {
        $stmt = static::pdo()->prepare(
            "INSERT INTO variacoes (produto_id, atributo, valor) VALUES (?, ?, ?)"
        );
        $stmt->execute([$produto_id, $atributo, $valor]);
    }

    public static function excluir(int $variacao_id): void
    {
        $stmt = static::pdo()->prepare("DELETE FROM variacoes WHERE id = ?");
        $stmt->execute([$variacao_id]);
    }

    public static function buscarPorId(int $id): ?array
    {
        $stmt = static::pdo()->prepare("SELECT * FROM variacoes WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result !== false ? $result : null;
    }
}
