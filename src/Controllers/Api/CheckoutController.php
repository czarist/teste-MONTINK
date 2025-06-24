<?php

namespace App\Controllers\Api;

use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Estoque;
use App\Models\Cupom;
use App\Core\Request;
use Exception;
use App\Models\Variacao;

class CheckoutController
{
    private Request $request;

    public function __construct()
    {
        $this->request = new Request();
        session_start();
        header('Content-Type: application/json');
    }

    public function finalizar(): void
    {
        $itens = $_SESSION['carrinho'] ?? [];

        if (empty($itens)) {
            http_response_code(400);
            echo json_encode(['error' => 'Carrinho vazio']);
            return;
        }

        $camposObrigatorios = ['email', 'endereco', 'numero', 'bairro', 'cidade', 'uf', 'cep'];
        foreach ($camposObrigatorios as $campo) {
            if (empty($this->request->input($campo))) {
                http_response_code(400);
                echo json_encode(['error' => "Campo obrigatório: {$campo}"]);
                return;
            }
        }

        $subtotal = array_reduce($itens, function ($carry, $item) {
            return $carry + ($item['preco'] * $item['quantidade']);
        }, 0);

        $frete = 20.00;
        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15.00;
        } elseif ($subtotal > 200) {
            $frete = 0.00;
        }

        $desconto = 0;
        $percentual = 0;
        $cupom_codigo = $_SESSION['cupom_aplicado'] ?? null;

        if ($cupom_codigo) {
            $cupom = Cupom::buscarPorCodigo($cupom_codigo);
            if (!$cupom) {
                http_response_code(400);
                echo json_encode(['error' => 'Cupom inválido ou expirado']);
                return;
            }

            if ($subtotal < $cupom['valor_minimo']) {
                http_response_code(400);
                echo json_encode(['error' => "Este cupom exige um mínimo de R$ " . number_format($cupom['valor_minimo'], 2, ',', '.')]);
                return;
            }

            $percentual = $cupom['desconto_percentual'];
            $desconto = ($subtotal * $percentual) / 100;
        }

        $total = $subtotal + $frete - $desconto;

        $enderecoCompleto = sprintf(
            '%s, %s %s - %s - %s/%s',
            $this->request->input('endereco'),
            $this->request->input('numero'),
            $this->request->input('complemento', ''),
            $this->request->input('bairro'),
            $this->request->input('cidade'),
            $this->request->input('uf')
        );

        try {
            Pedido::beginTransaction();

            $pedido_id = Pedido::inserir(
                $subtotal,
                $frete,
                $total,
                $this->request->input('cep'),
                $enderecoCompleto
            );

            foreach ($itens as $item) {

                PedidoItem::inserir(
                    $pedido_id,
                    $item['produto_id'],
                    $item['variacao_id'] ?? null,
                    (int) $item['quantidade'],
                    (float) $item['preco']
                );
                Estoque::reduzirEstoque((int) $item['produto_id'], (int) $item['quantidade']);
            }


            Pedido::commit();

            unset($_SESSION['carrinho'], $_SESSION['cupom_aplicado']);

            echo json_encode([
                'success'    => true,
                'pedido_id'  => $pedido_id,
                'subtotal'   => $subtotal,
                'frete'      => $frete,
                'desconto'   => $desconto,
                'percentual' => $percentual,
                'total'      => $total,
                'cupom'      => $cupom_codigo
            ]);
        } catch (Exception $e) {
            Pedido::rollback();
            http_response_code(500);
            echo json_encode(['error' => 'Erro ao finalizar pedido: ' . $e->getMessage()]);
        }
    }
}
