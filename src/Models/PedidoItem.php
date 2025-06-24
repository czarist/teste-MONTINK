<?php

namespace App\Models;

class PedidoItem extends Model
{
    public static function inserir(
        int $pedido_id,
        int $produto_id,
        ?int $variacao_id,
        int $quantidade,
        float $preco
    ): void {
        $stmt = static::pdo()->prepare("
        INSERT INTO pedido_itens (pedido_id, produto_id, variacao_id, quantidade, preco) 
        VALUES (?, ?, ?, ?, ?)
    ");

        $stmt->execute([
            $pedido_id,
            $produto_id,
            $variacao_id,
            $quantidade,
            $preco
        ]);
    }
}
