<?php

namespace App\Controllers\Web;

use App\Models\Cupom;

class CupomController
{
    public function index(): void
    {
        $cupons = Cupom::listar() ?? [];
        view('cupom.index', compact('cupons'));
    }
}
