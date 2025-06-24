<?php
namespace App\Controllers\Web;

class CarrinhoController
{
    public function ver(): void
    {
        session_start();
        $itens = $_SESSION['carrinho'] ?? [];

        $subtotal = 0;
        foreach ($itens as $item) {
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
