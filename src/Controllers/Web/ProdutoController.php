<?php
namespace App\Controllers\Web;

use App\Models\Produto;

class ProdutoController
{
    public function index(): void
    {
        $produtos = Produto::listar();
        view('produto.index', compact('produtos'));
    }
}
