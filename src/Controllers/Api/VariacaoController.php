<?php

namespace App\Controllers\Api;

use App\Models\Variacao;
use App\Core\Request;

class VariacaoController
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
        header('Content-Type: application/json');
    }

    public function listar(int $produto_id): void
    {
        $variacoes = Variacao::listarPorProduto($produto_id);
        echo json_encode($variacoes);
    }

    public function criar(int $produto_id): void
    {
        $atributo = trim($this->request->input('atributo', ''));
        $valor = trim($this->request->input('valor', ''));

        if ($atributo === '' || $valor === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Preencha todos os campos']);
            return;
        }

        Variacao::inserir($produto_id, $atributo, $valor);

        echo json_encode(['success' => true]);
    }

    public function deletar(int $variacao_id): void
    {
        Variacao::excluir($variacao_id);
        echo json_encode(['success' => true]);
    }
}
