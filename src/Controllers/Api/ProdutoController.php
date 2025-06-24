<?php

namespace App\Controllers\Api;

use App\Models\Estoque;
use App\Models\Produto;
use App\Core\Request;

class ProdutoController
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
    }

    public function salvar(): void
    {
        header('Content-Type: application/json');

        $id         = $this->request->input('id');
        $nome       = trim($this->request->input('nome', ''));
        $preco      = (float) $this->request->input('preco', 0);
        $variacao   = $this->request->input('variacao', null);
        $quantidade = (int) $this->request->input('quantidade', 0);

        if ($nome === '' || $preco <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados inválidos']);
            return;
        }

        if ($id) {
            Produto::atualizar($id, $nome, $preco);
        } else {
            $id = Produto::inserir($nome, $preco);
        }

        Estoque::atualizar($id, $variacao, $quantidade);

        echo json_encode(['success' => true, 'id' => $id]);
    }

    public function atualizar(int $id): void
    {
        header('Content-Type: application/json');

        $nome = trim($this->request->input('nome', ''));
        $preco = (float) $this->request->input('preco', 0);

        if ($nome === '' || $preco <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados inválidos']);
            return;
        }

        Produto::atualizar($id, $nome, $preco);

        echo json_encode(['success' => true]);
    }

    public function deletar(int $id): void
    {
        header('Content-Type: application/json');

        Produto::excluir($id);

        echo json_encode(['success' => true]);
    }
}
