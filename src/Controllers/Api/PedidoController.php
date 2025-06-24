<?php
namespace App\Controllers\Api;

use App\Models\Pedido;

class PedidoController
{
    public function atualizarStatus(int $id, string $status): void
    {
        if ($status === 'cancelado') {
            Pedido::excluir($id);
        } else {
            Pedido::atualizarStatus($id, $status);
        }
    }
}
