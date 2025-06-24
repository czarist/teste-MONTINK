<?php
namespace App\Controllers\Api;

use App\Models\Pedido;

class PedidoController
{
    public function atualizarStatus($id, $status): void
    {
        if ($status === 'cancelado') {
            Pedido::excluir($id);
        } else {
            Pedido::atualizarStatus($id, $status);
        }
    }
}
