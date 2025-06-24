<?php
namespace App\Controllers\Api;

use App\Models\Estoque;
use App\Models\Produto;

class ProdutoController
{
    public function salvar(): void
    {
        $id         = $_POST['id'] ?? null;
        $nome       = $_POST['nome'];
        $preco      = $_POST['preco'];
        $variacao   = $_POST['variacao'] ?? null;
        $quantidade = $_POST['quantidade'] ?? 0;

        if ($id) {
            Produto::atualizar($id, $nome, $preco);
        } else {
            $id = Produto::inserir($nome, $preco);
        }

        Estoque::atualizar($id, $variacao, $quantidade);

        echo json_encode(['success' => true, 'id' => $id]);
    }
}
