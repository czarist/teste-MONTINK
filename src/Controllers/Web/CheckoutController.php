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

        view('checkout.index', compact('itens'));
    }
}
