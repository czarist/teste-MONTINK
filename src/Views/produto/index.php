<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Cadastro de Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container">
    <h1 class="my-4">Cadastro de Produtos</h1>

    <form action="/api/produto" method="post" class="mb-5">
        <div class="mb-3">
            <label>Nome:</label>
            <input type="text" name="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Preço:</label>
            <input type="number" step="0.01" name="preco" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Variação (opcional):</label>
            <input type="text" name="variacao" class="form-control">
        </div>
        <div class="mb-3">
            <label>Quantidade em estoque:</label>
            <input type="number" name="quantidade" class="form-control">
        </div>
        <button class="btn btn-success">Salvar Produto</button>
    </form>

    <h2>Produtos cadastrados</h2>
    <table class="table">
        <thead><tr><th>Nome</th><th>Preço</th></tr></thead>
        <tbody>
            <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?= htmlspecialchars($produto['nome']) ?></td>
                <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</body>
</html>
