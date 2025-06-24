<?php

namespace App\Controllers\Api;

use App\Models\Produto;
use App\Core\Request;

class CarrinhoController
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function adicionar(): void
    {
        session_start();
        header('Content-Type: application/json');

        $produto_id = (int) $this->request->input('produto_id', 0);
        $variacao   = trim($this->request->input('variacao', ''));
        $quantidade = (int) $this->request->input('quantidade', 1);

        if ($produto_id <= 0 || $quantidade <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados inválidos']);
            return;
        }

        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        $encontrado = false;

        foreach ($_SESSION['carrinho'] as &$item) {
            if ($item['produto_id'] == $produto_id && $item['variacao'] == $variacao) {
                $item['quantidade'] += $quantidade;
                $encontrado = true;
                break;
            }
        }

        if (!$encontrado) {
            $produto = Produto::buscar($produto_id);
            if (!$produto) {
                http_response_code(404);
                echo json_encode(['error' => 'Produto não encontrado']);
                return;
            }

            $_SESSION['carrinho'][] = [
                'produto_id' => $produto_id,
                'variacao'   => $variacao,
                'quantidade' => $quantidade,
                'preco'      => $produto['preco']
            ];
        }

        echo json_encode(['success' => true]);
    }

    public function alterarQuantidade(): void
    {
        session_start();
        header('Content-Type: application/json');

        $produto_id = (int) $this->request->input('produto_id', 0);
        $variacao   = trim($this->request->input('variacao', ''));
        $quantidade = (int) $this->request->input('quantidade', 1);

        if ($quantidade <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Quantidade inválida']);
            return;
        }

        foreach ($_SESSION['carrinho'] as &$item) {
            if ($item['produto_id'] == $produto_id && $item['variacao'] == $variacao) {
                $item['quantidade'] = $quantidade;
                break;
            }
        }

        echo json_encode(['success' => true]);
    }

    public function remover(): void
    {
        session_start();
        header('Content-Type: application/json');

        $produto_id = (int) $this->request->input('produto_id', 0);
        $variacao   = trim($this->request->input('variacao', ''));

        $_SESSION['carrinho'] = array_filter($_SESSION['carrinho'], function ($item) use ($produto_id, $variacao) {
            return !($item['produto_id'] == $produto_id && $item['variacao'] == $variacao);
        });

        echo json_encode(['success' => true]);
    }
}
