<?php

namespace App\Models;

use PDO;

class Cupom extends Model
{
    public static function buscarPorCodigo(string $codigo): ?array
    {
        $stmt = static::pdo()->prepare("SELECT * FROM cupons WHERE codigo = ? AND validade >= CURDATE()");
        $stmt->execute([$codigo]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result !== false ? $result : null;
    }

    public static function listar(): array
    {
        $stmt = static::pdo()->query("SELECT * FROM cupons WHERE status = 'active'");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function excluir(int $id): void
    {
        $stmt = static::pdo()->prepare("UPDATE cupons SET status = 'inactive' WHERE id = ?");
        $stmt->execute([$id]);
    }

    public static function atualizar(int $id, string $codigo, float $valor_minimo, float $desconto_percentual, string $validade): void
    {
        $stmt = static::pdo()->prepare("UPDATE cupons SET codigo = ?, valor_minimo = ?, desconto_percentual = ?, validade = ? WHERE id = ?");
        $stmt->execute([$codigo, $valor_minimo, $desconto_percentual, $validade, $id]);
    }

    public static function inserir(string $codigo, float $valor_minimo, float $desconto_percentual, string $validade): void
    {
        $stmt = static::pdo()->prepare("INSERT INTO cupons (codigo, valor_minimo, desconto_percentual, validade) VALUES (?, ?, ?, ?)  ");
        $stmt->execute([$codigo, $valor_minimo, $desconto_percentual, $validade]);
    }
}
