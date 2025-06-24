<?php
namespace App\Models;

class Pedido extends Model
{
    public static function inserir(
        float $subtotal,
        float $frete,
        float $total,
        string $cep,
        string $endereco,
        string $status = 'aguardando_pagamento'
    ): int {
        $stmt = static::pdo()->prepare("
            INSERT INTO pedidos (subtotal, frete, total, cep, endereco, status)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([$subtotal, $frete, $total, $cep, $endereco, $status]);
        return (int) static::pdo()->lastInsertId();
    }

    public static function atualizarStatus(int $id, string $status): void
    {
        $stmt = static::pdo()->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
    }

    public static function excluir(int $id): void
    {
        $stmt = static::pdo()->prepare("DELETE FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
    }
}
