<?php

namespace App\Controllers\Web;

class CarrinhoController
{
    public function index(): void
    {
        session_start();

        $itens = $_SESSION['carrinho'] ?? [];

        $subtotal = 0;

        foreach ($itens as &$item) {
            $item['produto_nome'] = $item['produto_nome'] ?? 'Produto';
            $item['variacao_desc'] = $item['variacao_desc'] ?? '-';

            $subtotal += $item['preco'] * $item['quantidade'];
        }

        if ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15.00;
        } elseif ($subtotal > 200) {
            $frete = 0;
        } else {
            $frete = 20.00;
        }

        $total = $subtotal + $frete;

        view('carrinho.index', compact('itens', 'subtotal', 'frete', 'total'));
    }
}
