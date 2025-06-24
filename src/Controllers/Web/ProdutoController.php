<?php

namespace App\Controllers\Web;

use App\Models\Produto;
use App\Core\Request;

class ProdutoController
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function index(): void
    {
        $produtos = Produto::listar();
        view('produto.index', compact('produtos'));
    }

    public function catalogo(): void
    {
        $page = (int) $this->request->input('page', 1);
        if ($page < 1) $page = 1;

        $limit = 6;
        $offset = ($page - 1) * $limit;

        $produtos = Produto::listarPaginado($limit, $offset);
        $total = Produto::total();
        $totalPages = ceil($total / $limit);

        view('produto.catalogo', compact('produtos', 'page', 'totalPages'));
    }

    public function show(int $id): void
    {
        $produto = Produto::buscar($id);

        if (!$produto) {
            http_response_code(404);
            echo "Produto não encontrado.";
            return;
        }

        $variacoes = Produto::buscarVariacoes($id);

        view('produto.show', compact('produto', 'variacoes'));
    }
}
