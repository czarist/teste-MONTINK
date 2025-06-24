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

    public static function inserir(string $codigo, float $valor_minimo, float $desconto_percentual, string $validade): void
    {
        $pdo = static::pdo();

        $stmt = $pdo->prepare("SELECT id, status FROM cupons WHERE codigo = ?");
        $stmt->execute([$codigo]);
        $cupomExistente = $stmt->fetch();

        if ($cupomExistente) {
            if ($cupomExistente['status'] == 1) {
                throw new \Exception("Já existe um cupom ativo com esse código.");
            } else {
                $stmtUpdate = $pdo->prepare("
                UPDATE cupons 
                SET valor_minimo = ?, desconto_percentual = ?, validade = ?, status = 1 
                WHERE id = ?
            ");
                $stmtUpdate->execute([
                    $valor_minimo,
                    $desconto_percentual,
                    $validade,
                    $cupomExistente['id']
                ]);
                return;
            }
        }

        $stmtInsert = $pdo->prepare("
        INSERT INTO cupons (codigo, valor_minimo, desconto_percentual, validade, status) 
        VALUES (?, ?, ?, ?, 1)
    ");
        $stmtInsert->execute([$codigo, $valor_minimo, $desconto_percentual, $validade]);
    }

    public static function atualizar(int $id, string $codigo, float $valor_minimo, float $desconto_percentual, string $validade): void
    {
        $stmt = static::pdo()->prepare("SELECT id FROM cupons WHERE codigo = ? AND id != ?");
        $stmt->execute([$codigo, $id]);
        if ($stmt->fetch()) {
            throw new \Exception("Já existe um cupom com esse código.");
        }

        $stmt = static::pdo()->prepare(
            "UPDATE cupons SET codigo = ?, valor_minimo = ?, desconto_percentual = ?, validade = ? WHERE id = ?"
        );
        $stmt->execute([$codigo, $valor_minimo, $desconto_percentual, $validade, $id]);
    }
}
