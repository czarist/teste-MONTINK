<?php

namespace App\Models;

class Estoque extends Model
{
    public static function atualizar(int $produto_id, int $quantidade): void
    {
        $pdo = static::pdo();
        $stmt = $pdo->prepare("SELECT id FROM estoque WHERE produto_id = ?");
        $stmt->execute([$produto_id]);

        if ($stmt->fetch()) {
            $update = $pdo->prepare("UPDATE estoque SET quantidade = ? WHERE produto_id = ?");
            $update->execute([$quantidade, $produto_id]);
        } else {
            $insert = $pdo->prepare("INSERT INTO estoque (produto_id, quantidade) VALUES (?, ?)");
            $insert->execute([$produto_id, $quantidade]);
        }
    }

    public static function buscar(int $produto_id): int
    {
        $stmt = static::pdo()->prepare("SELECT quantidade FROM estoque WHERE produto_id = ?");
        $stmt->execute([$produto_id]);
        $result = $stmt->fetchColumn();
        return $result !== false ? (int) $result : 0;
    }

    public static function reduzirEstoque(int $produto_id, int $quantidade): void
    {
        $stmt = static::pdo()->prepare("UPDATE estoque SET quantidade = quantidade - ? WHERE produto_id = ?");
        $stmt->execute([$quantidade, $produto_id]);
    }
}
