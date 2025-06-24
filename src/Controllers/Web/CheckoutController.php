<?php

namespace App\Controllers\Web;

class CheckoutController
{
    public function index(): void
    {
        session_start();

        $itens = $_SESSION['carrinho'] ?? [];

        if (empty($itens)) {
            header("Location: /");
            exit;
        }

        $subtotal = array_reduce($itens, fn($carry, $item) => $carry + ($item['preco'] * $item['quantidade']), 0);
        $frete = $subtotal >= 52 && $subtotal <= 166.59 ? 15.00 : ($subtotal > 200 ? 0 : 20.00);
        $total = $subtotal + $frete;

        view('checkout.index', compact('itens', 'subtotal', 'frete', 'total'));
    }
}
