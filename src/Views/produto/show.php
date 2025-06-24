<?php renderHeader('Detalhes do Produto'); ?>

<h1>Produto: <?= htmlspecialchars($produto['nome']) ?></h1>

<?php if (empty($variacoes)): ?>
    <div class="alert alert-warning">Nenhuma variação cadastrada para este produto.</div>
<?php else: ?>

    <form id="form-adicionar-carrinho" method="post" action="/api/carrinho/adicionar" class="mb-3">
        <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">

        <div class="mb-3">
            <label>Selecione a Variação:</label>
            <select name="variacao_id" class="form-select" required>
                <option value="">Escolha uma variação</option>
                <?php foreach ($variacoes as $v): ?>
                    <option value="<?= $v['id'] ?>">
                        <?= htmlspecialchars($v['atributo']) ?>: <?= htmlspecialchars($v['valor']) ?>
                    </option>
                <?php endforeach ?>
            </select>

        </div>

        <div class="mb-3">
            <label for="quantidade">Quantidade:</label>
            <input type="number" name="quantidade" id="quantidade" class="form-control" value="1" min="1" required>
        </div>

        <button type="submit" class="btn btn-primary">Adicionar ao Carrinho</button>
    </form>

<?php endif; ?>

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

            const data = await response.json();
            if (data.success) {
                window.location.href = '/carrinho';
            } else {
                alert(data.error || 'Erro ao adicionar ao carrinho.');
            }
        } catch (error) {
            console.error(error);
            alert('Erro de rede.');
        }
    });
</script>

<?php renderFooter(); ?>