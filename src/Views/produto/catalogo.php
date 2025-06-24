<?php renderHeader('Catálogo de Produtos'); ?>

<h1 class="my-4">Catálogo de Produtos</h1>

<div class="row">
    <?php foreach ($produtos as $produto): ?>
        <?php
        $menorPreco = $produto['preco'];
        $maiorPreco = $produto['preco'];

        if (!empty($produto['variacoes'])) {
            $precos = array_column($produto['variacoes'], 'preco');
            $menorPreco = min($precos);
            $maiorPreco = max($precos);
        }
        ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title"><?= htmlspecialchars($produto['nome']) ?></h5>
                    <?php if ($menorPreco === $maiorPreco): ?>
                        <p class="card-text">R$ <?= number_format($menorPreco, 2, ',', '.') ?></p>
                    <?php else: ?>
                        <p class="card-text">De R$ <?= number_format($menorPreco, 2, ',', '.') ?> até R$ <?= number_format($maiorPreco, 2, ',', '.') ?></p>
                    <?php endif ?>
                    <a href="/produto/<?= $produto['id'] ?>" class="btn btn-outline-primary">Ver Produto</a>
                </div>
            </div>
        </div>
    <?php endforeach ?>
</div>

<nav>
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor ?>
    </ul>
</nav>

<?php renderFooter(); ?>