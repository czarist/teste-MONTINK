<?php renderHeader('Detalhes do Produto'); ?>

<h1>Produto: <?= htmlspecialchars($produto['nome']) ?></h1>

<p><strong>Preço:</strong> R$ <?= number_format($produto['preco'], 2, ',', '.') ?></p>

<form id="form-adicionar-carrinho" method="post" action="/api/carrinho/adicionar" class="mb-3">
    <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
    <input type="hidden" name="variacao" value="">

    <div class="mb-3">
        <label for="quantidade">Quantidade:</label>
        <input type="number" name="quantidade" id="quantidade" class="form-control" value="1" min="1" required>
    </div>

    <button type="submit" class="btn btn-primary">Adicionar ao Carrinho</button>
</form>

<a href="/" class="btn btn-secondary">Voltar ao Catálogo</a>

<script>
    document.getElementById('form-adicionar-carrinho').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData
            });

            if (response.ok) {
                // Se deu bom, redireciona
                window.location.href = '/carrinho';
            } else {
                alert('Erro ao adicionar ao carrinho.');
            }
        } catch (error) {
            console.error(error);
            alert('Erro de rede.');
        }
    });
</script>

<?php renderFooter(); ?>