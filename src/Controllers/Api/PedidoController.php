<?php

namespace App\Controllers\Api;

use App\Models\Pedido;
use App\Core\Request;

class PedidoController
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
        header('Content-Type: application/json');
    }

    public function atualizarStatus(): void
    {
        $id = (int) $this->request->input('id');
        $status = trim($this->request->input('status'));

        if (!$id || $status === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Dados inválidos']);
            return;
        }

        if ($status === 'cancelado') {
            Pedido::excluir($id);
        } else {
            Pedido::atualizarStatus($id, $status);
        }

        echo json_encode(['success' => true]);
    }
}
