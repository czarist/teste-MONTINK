<?php
namespace App\Models;

class PedidoItem extends Model
{
    public static function inserir(
        int $pedido_id,
        int $produto_id,
        string $variacao,
        int $quantidade,
        float $preco_unitario
    ): void {
        $stmt = static::pdo()->prepare("
            INSERT INTO pedido_itens (pedido_id, produto_id, variacao, quantidade, preco_unitario)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$pedido_id, $produto_id, $variacao, $quantidade, $preco_unitario]);
    }
}
