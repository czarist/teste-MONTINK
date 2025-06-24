<?php

use App\Controllers\Api\CarrinhoController as CarrinhoApiController;
use App\Controllers\Api\CheckoutController as CheckoutApiController;
use App\Controllers\Api\PedidoController as PedidoApiController;
use App\Controllers\Api\ProdutoController as ProdutoApiController;
use App\Controllers\Api\CupomController as CupomApiController;

// CUPONS
$router->post('/api/cupons', function () {
    (new CupomApiController())->salvar();
});

$router->put('/api/cupons/(\d+)', function ($id) {
    (new CupomApiController())->atualizar((int) $id);
});

$router->delete('/api/cupons/(\d+)', function ($id) {
    (new CupomApiController())->excluir((int) $id);
});

$router->post('/api/cupom/validar', function () {
    (new CupomApiController())->validar();
});

// PRODUTOS
$router->post('/api/produto', function () {
    (new ProdutoApiController())->salvar();
});

$router->put('/api/produto/(\d+)', function ($id) {
    (new ProdutoApiController())->atualizar((int) $id);
});

$router->delete('/api/produto/(\d+)', function ($id) {
    (new ProdutoApiController())->deletar((int) $id);
});

// CARRINHO
$router->post('/api/carrinho/adicionar', function () {
    (new CarrinhoApiController())->adicionar();
});

$router->post('/api/carrinho/alterar', function () {
    (new CarrinhoApiController())->alterarQuantidade();
});

$router->post('/api/carrinho/remover', function () {
    (new CarrinhoApiController())->remover();
});

// CHECKOUT
$router->post('/api/checkout/finalizar', function () {
    (new CheckoutApiController())->finalizar();
});

// WEBHOOK PEDIDO
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
