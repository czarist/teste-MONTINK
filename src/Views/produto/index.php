<?php renderHeader('Cadastro de Produto'); ?>

<h1 class="my-4">Cadastro de Produtos</h1>

<form id="formCadastroProduto" class="mb-5">
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
    <thead>
        <tr>
            <th>Nome</th>
            <th>Preço</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produtos as $produto): ?>
            <tr>
                <td><?= htmlspecialchars($produto['nome']) ?></td>
                <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                <td>
                    <button class="btn btn-sm btn-primary" onclick="abrirEditar(<?= $produto['id'] ?>, '<?= htmlspecialchars($produto['nome']) ?>', <?= $produto['preco'] ?>)">Editar</button>
                    <button class="btn btn-sm btn-danger" onclick="abrirExcluir(<?= $produto['id'] ?>)">Excluir</button>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<!-- Modal Editar -->
<div class="modal fade" id="modalEditar" tabindex="-1">
    <div class="modal-dialog">
        <form class="modal-content" id="formEditar">
            <div class="modal-header">
                <h5 class="modal-title">Editar Produto</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="editarId">
                <div class="mb-3">
                    <label>Nome:</label>
                    <input type="text" name="nome" id="editarNome" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Preço:</label>
                    <input type="number" step="0.01" name="preco" id="editarPreco" class="form-control" required>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-success">Salvar</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Excluir -->
<div class="modal fade" id="modalExcluir" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmar Exclusão</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Deseja realmente excluir este produto?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" id="btnConfirmarExcluir">Excluir</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('formCadastroProduto').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('/api/produto', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                location.reload();
            })
            .catch(err => {
                alert("Erro inesperado: " + err);
            });
    });

    let idExcluir = null;

    function abrirEditar(id, nome, preco) {
        document.getElementById('editarId').value = id;
        document.getElementById('editarNome').value = nome;
        document.getElementById('editarPreco').value = preco;
        new bootstrap.Modal(document.getElementById('modalEditar')).show();
    }

    function abrirExcluir(id) {
        idExcluir = id;
        new bootstrap.Modal(document.getElementById('modalExcluir')).show();
    }

    document.getElementById('formEditar').addEventListener('submit', function(e) {
        e.preventDefault();

        const id = document.getElementById('editarId').value;
        const nome = document.getElementById('editarNome').value;
        const preco = document.getElementById('editarPreco').value;

        const params = new URLSearchParams();
        params.append('nome', nome);
        params.append('preco', preco);

        fetch(`/api/produto/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: params.toString()
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                location.reload();
            })
            .catch(err => {
                alert("Erro inesperado: " + err);
            });
    });

    document.getElementById('btnConfirmarExcluir').addEventListener('click', function() {
        fetch(`/api/produto/${idExcluir}`, {
                method: 'DELETE'
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                location.reload();
            })
            .catch(err => {
                alert("Erro inesperado: " + err);
            });
    });
</script>

<?php renderFooter(); ?>