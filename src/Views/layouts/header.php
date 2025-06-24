<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title><?= defined('PAGE_TITLE') ? PAGE_TITLE : 'ERP' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="pt-5 pb-5">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top w-100">
            <div class="container">
                <a class="navbar-brand" href="/">ERP</a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav ms-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="/">Catálogo</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/carrinho">Carrinho</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/cadastro/produtos">Cadastro de Produtos</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/cupons">Cupons</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <div class="container mb-5 mt-5">