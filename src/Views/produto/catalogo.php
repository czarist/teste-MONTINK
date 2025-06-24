<?php renderHeader('Catálogo de Produtos'); ?>

<h1 class="my-4">Catálogo de Produtos</h1>

<div class="row">
    <?php foreach ($produtos as $produto): ?>
        <div class="col-md-4 mb-4">
            <div class="card h-100">
                <div class="card-body text-center">
                    <h5 class="card-title"><?= htmlspecialchars($produto['nome']) ?></h5>
                    <p class="card-text">R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>
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