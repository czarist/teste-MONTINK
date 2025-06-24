<?php

namespace App\Controllers\Api;

use App\Models\Cupom;
use App\Core\Request;

class CupomController
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
        session_start();
        header('Content-Type: application/json');
    }

    public function validar(): void
    {
        $codigo = trim($this->request->input('codigo', ''));

        if ($codigo === '') {
            echo json_encode(['error' => 'Informe o código do cupom.']);
            return;
        }

        $cupom = Cupom::buscarPorCodigo($codigo);
        if (!$cupom) {
            echo json_encode(['error' => 'Cupom inválido ou expirado.']);
            return;
        }

        $subtotal = 0;
        foreach ($_SESSION['carrinho'] ?? [] as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }

        if ($subtotal < $cupom['valor_minimo']) {
            echo json_encode(['error' => "Este cupom exige valor mínimo de R$ " . number_format($cupom['valor_minimo'], 2, ',', '.')]);
            return;
        }

        $desconto = ($subtotal * $cupom['desconto_percentual']) / 100;
        echo json_encode([
            'success' => true,
            'desconto' => $desconto,
            'percentual' => $cupom['desconto_percentual'],
            'valor_aplicado' => number_format($desconto, 2, ',', '.')
        ]);
    }

    public function salvar(): void
    {
        $codigo = trim($this->request->input('codigo', ''));
        $valor_minimo = (float) $this->request->input('valor_minimo', 0);
        $desconto_percentual = (float) $this->request->input('desconto_percentual', 0);
        $validade = trim($this->request->input('validade', ''));

        if (empty($codigo) || empty($validade)) {
            http_response_code(400);
            echo json_encode(['error' => 'Preencha todos os campos obrigatórios']);
            return;
        }

        if ($desconto_percentual < 0 || $desconto_percentual > 100) {
            http_response_code(400);
            echo json_encode(['error' => 'Percentual de desconto inválido (0 a 100)']);
            return;
        }

        try {
            Cupom::inserir($codigo, $valor_minimo, $desconto_percentual, $validade);
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }

    public function atualizar(int $id): void
    {
        $codigo = trim($this->request->input('codigo', ''));
        $valor_minimo = (float) $this->request->input('valor_minimo', 0);
        $desconto_percentual = (float) $this->request->input('desconto_percentual', 0);
        $validade = trim($this->request->input('validade', ''));

        if (empty($codigo) || empty($validade)) {
            http_response_code(400);
            echo json_encode(['error' => 'Preencha todos os campos obrigatórios']);
            return;
        }

        if ($desconto_percentual < 0 || $desconto_percentual > 100) {
            http_response_code(400);
            echo json_encode(['error' => 'Percentual de desconto inválido (0 a 100)']);
            return;
        }

        try {
            Cupom::atualizar($id, $codigo, $valor_minimo, $desconto_percentual, $validade);
            echo json_encode(['success' => true]);
        } catch (\Exception $e) {
            http_response_code(400);
            echo json_encode(['error' => $e->getMessage()]);
        }
    }


    public function excluir(int $id): void
    {
        Cupom::excluir($id);
        echo json_encode(['success' => true]);
    }
}
