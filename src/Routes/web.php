<?php

use App\Controllers\Web\CarrinhoController;
use App\Controllers\Web\CheckoutController;
use App\Controllers\Web\ProdutoController;
use App\Controllers\Web\CupomController;

$router->get('/cupons', function () {
    (new CupomController())->index();
});

$router->get('/', function () {
    (new ProdutoController())->catalogo();
});

$router->get('/cadastro/produtos', function () {
    (new ProdutoController())->index();
});

$router->get('/carrinho', function () {
    (new CarrinhoController())->index();
});

$router->get('/checkout', function () {
    (new CheckoutController())->index();
});

$router->get('/produto/(\d+)', function($id) {
    (new ProdutoController())->show((int) $id);
});

