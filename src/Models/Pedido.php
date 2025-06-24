<?php
namespace App\Models;

class Pedido extends Model
{
    public static function inserir($subtotal, $frete, $total, $cep, $endereco, $status = 'aguardando_pagamento')
    {
        $stmt = static::pdo()->prepare("INSERT INTO pedidos (subtotal, frete, total, cep, endereco, status) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$subtotal, $frete, $total, $cep, $endereco, $status]);
        return static::pdo()->lastInsertId();
    }

    public static function atualizarStatus($id, $status)
    {
        $stmt = static::pdo()->prepare("UPDATE pedidos SET status = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
    }

    public static function excluir($id)
    {
        $stmt = static::pdo()->prepare("DELETE FROM pedidos WHERE id = ?");
        $stmt->execute([$id]);
    }
}
