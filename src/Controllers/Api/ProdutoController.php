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
        header('Content-Type: application/json');
    }

    public function salvar(): void
    {
        $nome = trim($this->request->input('nome', ''));
        $preco = (float) $this->request->input('preco', 0);
        $quantidade = (int) $this->request->input('quantidade', 0);

        if ($nome === '' || $preco < 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados inválidos']);
            return;
        }

        try {
            $id = Produto::inserir($nome, $preco);
            Estoque::atualizar($id, $quantidade);
            echo json_encode(['success' => true, 'id' => $id]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function atualizar(int $id): void
    {
        $nome = trim($this->request->input('nome', ''));
        $preco = (float) $this->request->input('preco', 0);
        $quantidade = (int) $this->request->input('quantidade', 0);

        if ($nome === '' || $preco < 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Dados inválidos']);
            return;
        }

        try {
            Produto::atualizar($id, $nome, $preco);
            Estoque::atualizar($id, $quantidade);
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function deletar(int $id): void
    {
        Produto::excluir($id);
        echo json_encode(['success' => true]);
    }
}
