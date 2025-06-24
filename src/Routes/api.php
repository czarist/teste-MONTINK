<?php

use App\Controllers\Api\CarrinhoController as CarrinhoApiController;
use App\Controllers\Api\PedidoController as PedidoApiController;
use App\Controllers\Api\ProdutoController as ProdutoApiController;

$router->post('/api/produto', function () {
    (new ProdutoApiController())->salvar();
});

$router->post('/api/carrinho/adicionar', function () {
    (new CarrinhoApiController())->adicionar();
});

$router->post('/api/webhook/pedido', function () {
    $data = json_decode(file_get_contents('php://input'), true);

    if (! isset($data['id']) || ! isset($data['status'])) {
        http_response_code(400);
        echo json_encode(['error' => 'Dados inválidos']);
        return;
    }

    (new PedidoApiController())->atualizarStatus($data['id'], $data['status']);
    echo json_encode(['success' => true]);
});
