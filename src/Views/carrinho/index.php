<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Seu Carrinho</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container">
    <h1 class="my-4">Seu Carrinho</h1>

    <?php if (empty($itens)): ?>
        <p>O carrinho está vazio.</p>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Variação</th>
                    <th>Quantidade</th>
                    <th>Preço Unitário</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($itens as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['produto_id']) ?></td>
                    <td><?= htmlspecialchars($item['variacao'] ?? '-') ?></td>
                    <td><?= $item['quantidade'] ?></td>
                    <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>

        <h4>Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?></h4>
        <h4>Frete: R$ <?= number_format($frete, 2, ',', '.') ?></h4>
        <h3>Total: R$ <?= number_format($total, 2, ',', '.') ?></h3>
    <?php endif; ?>
</body>
</html>
