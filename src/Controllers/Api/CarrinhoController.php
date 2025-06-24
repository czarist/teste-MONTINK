<?php
namespace App\Controllers\Api;

use App\Models\Produto;

class CarrinhoController
{
    public function adicionar(): void
    {
        session_start();

        $produto_id = $_POST['produto_id'];
        $quantidade = $_POST['quantidade'] ?? 1;
        $variacao   = $_POST['variacao'] ?? null;

        $produto = Produto::buscar($produto_id);
        if (! $produto) {
            http_response_code(404);
            echo json_encode(['error' => 'Produto não encontrado']);
            return;
        }

        $_SESSION['carrinho'][] = [
            'produto_id' => $produto_id,
            'quantidade' => $quantidade,
            'variacao'   => $variacao,
            'preco'      => $produto['preco'],
        ];

        echo json_encode(['success' => true]);
    }
}
