<?php renderHeader('Seu Carrinho'); ?>

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
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($itens as $item): ?>
                <tr>
                    <td><?= htmlspecialchars($item['produto_nome']) ?></td>
                    <td><?= htmlspecialchars($item['variacao_desc'] ?? '-') ?></td>
                    <td>
                        <input type="number" min="1" value="<?= $item['quantidade'] ?>"
                            class="form-control quantidade-input"
                            data-produto-id="<?= $item['produto_id'] ?>"
                            data-variacao-id="<?= $item['variacao_id'] ?>">
                    </td>
                    <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                    <td>R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></td>
                    <td>
                        <button class="btn btn-danger btn-sm remover-item"
                            data-produto-id="<?= $item['produto_id'] ?>"
                            data-variacao-id="<?= $item['variacao_id'] ?>">Remover</button>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>

    <button class="btn btn-primary mb-3" onclick="salvarQuantidades()">Salvar Alterações</button>

    <hr>

    <form class="mb-3">
        <div class="input-group">
            <input type="text" id="cupom" class="form-control" placeholder="Digite o cupom">
            <button type="button" class="btn btn-secondary" onclick="validarCupom()">Aplicar</button>
        </div>
    </form>

    <div id="descontoContainer" style="display: none;">
        <h4 id="descontoLabel"></h4>
    </div>

    <h4>Subtotal: R$ <?= number_format($subtotal, 2, ',', '.') ?></h4>
    <h4>Frete: R$ <?= number_format($frete, 2, ',', '.') ?></h4>
    <h3 id="totalLabel">Total: R$ <?= number_format($total, 2, ',', '.') ?></h3>

    <a href="/checkout" class="btn btn-success">Criar Pedido</a>

<?php endif; ?>

<script>
    function salvarQuantidades() {
        document.querySelectorAll('.quantidade-input').forEach(input => {
            const produto_id = input.dataset.produtoId;
            const variacao_id = input.dataset.variacaoId;
            const quantidade = input.value;

            fetch('/api/carrinho/alterar', {
                method: 'POST',
                body: new URLSearchParams({
                    produto_id: produto_id,
                    variacao_id: variacao_id,
                    quantidade: quantidade
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    location.reload();
                }
            });
        });
    }

    document.querySelectorAll('.remover-item').forEach(button => {
        button.addEventListener('click', function() {
            const produto_id = this.dataset.produtoId;
            const variacao_id = this.dataset.variacaoId;

            fetch('/api/carrinho/remover', {
                method: 'POST',
                body: new URLSearchParams({
                    produto_id: produto_id,
                    variacao_id: variacao_id
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                } else {
                    location.reload();
                }
            });
        });
    });

    function validarCupom() {
        const codigo = document.getElementById('cupom').value.trim();
        if (codigo === '') return;

        fetch('/api/cupom/validar', {
            method: 'POST',
            body: new URLSearchParams({
                codigo: codigo
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }

            document.getElementById('descontoContainer').style.display = 'block';
            document.getElementById('descontoLabel').innerText =
                `Desconto aplicado: ${data.percentual}% (-R$ ${data.valor_aplicado})`;

            const totalAtual = <?= $total ?>;
            const novoTotal = totalAtual - data.desconto;
            document.getElementById('totalLabel').innerText =
                `Total: R$ ${novoTotal.toFixed(2).replace('.', ',')}`;
        });
    }
</script>

<?php renderFooter(); ?>
