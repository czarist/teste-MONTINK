<?php

use App\Controllers\Web\CarrinhoController;
use App\Controllers\Web\ProdutoController;

$router->get('/', function () {
    (new ProdutoController())->index();
});

$router->get('/carrinho', function () {
    (new CarrinhoController())->ver();
});
