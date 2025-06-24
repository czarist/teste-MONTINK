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
        session_start();
        header('Content-Type: application/json');
    }

    public function adicionar(): void
    {
        $produto_id = (int) $this->request->input('produto_id', 0);
        $variacao_id = (int) $this->request->input('variacao_id', 0);
        $quantidade = (int) $this->request->input('quantidade', 1);

        if ($produto_id <= 0 || $quantidade <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados inválidos']);
            return;
        }

        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }

        foreach ($_SESSION['carrinho'] as &$item) {
            if ($item['produto_id'] == $produto_id && $item['variacao_id'] == $variacao_id) {
                $item['quantidade'] += $quantidade;
                echo json_encode(['success' => true]);
                return;
            }
        }

        $produto = Produto::buscar($produto_id);
        if (!$produto) {
            http_response_code(404);
            echo json_encode(['error' => 'Produto não encontrado']);
            return;
        }

        $variacaoDesc = '-';
        if ($variacao_id > 0) {
            $variacao = Produto::buscarVariacaoPorId($variacao_id);
            if (!$variacao) {
                http_response_code(404);
                echo json_encode(['error' => 'Variação não encontrada']);
                return;
            }
            $variacaoDesc = "{$variacao['atributo']}: {$variacao['valor']}";
        }

        $_SESSION['carrinho'][] = [
            'produto_id' => $produto_id,
            'produto_nome' => $produto['nome'],
            'variacao_id' => $variacao_id,
            'variacao_desc' => $variacaoDesc,
            'quantidade' => $quantidade,
            'preco' => $produto['preco']
        ];

        echo json_encode(['success' => true]);
    }

    public function alterarQuantidade(): void
    {
        $produto_id = (int) $this->request->input('produto_id', 0);
        $variacao_id = (int) $this->request->input('variacao_id', 0);
        $quantidade = (int) $this->request->input('quantidade', 1);

        if ($quantidade <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Quantidade inválida']);
            return;
        }

        foreach ($_SESSION['carrinho'] as &$item) {
            if ($item['produto_id'] == $produto_id && $item['variacao_id'] == $variacao_id) {
                $item['quantidade'] = $quantidade;
                echo json_encode(['success' => true]);
                return;
            }
        }

        http_response_code(404);
        echo json_encode(['error' => 'Item não encontrado no carrinho']);
    }

    public function remover(): void
    {
        $produto_id = (int) $this->request->input('produto_id', 0);
        $variacao_id = (int) $this->request->input('variacao_id', 0);

        $antes = count($_SESSION['carrinho'] ?? []);

        $_SESSION['carrinho'] = array_filter($_SESSION['carrinho'], function ($item) use ($produto_id, $variacao_id) {
            return !($item['produto_id'] == $produto_id && $item['variacao_id'] == $variacao_id);
        });

        $depois = count($_SESSION['carrinho']);

        if ($antes === $depois) {
            http_response_code(404);
            echo json_encode(['error' => 'Item não encontrado para remoção']);
        } else {
            echo json_encode(['success' => true]);
        }
    }
}
