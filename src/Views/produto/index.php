<?php renderHeader('Cadastro de Produto'); ?>

<h1 class="my-4">Cadastro de Produto</h1>

<form id="formCadastroProduto" class="mb-5">
    <div class="mb-3">
        <label>Nome:</label>
        <input type="text" name="nome" class="form-control" required>
    </div>
    <div class="mb-3">
        <label>Preço base (opcional):</label>
        <input type="number" step="0.01" name="preco" class="form-control">
    </div>
    <div class="mb-3">
        <label>Estoque:</label>
        <input type="number" name="quantidade" class="form-control" value="0" min="0">
    </div>
    <button class="btn btn-success">Salvar Produto</button>
</form>

<h2>Produtos cadastrados</h2>

<table class="table">
    <thead>
        <tr>
            <th>Nome</th>
            <th>Preço Base</th>
            <th>Estoque</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($produtos as $produto):
            $estoque = \App\Models\Estoque::buscar($produto['id']); ?>
            <tr>
                <td><?= htmlspecialchars($produto['nome']) ?></td>
                <td>R$ <?= number_format($produto['preco'], 2, ',', '.') ?></td>
                <td><?= $estoque ?></td>
                <td>
                    <button class="btn btn-sm btn-warning" onclick="abrirVariacoes(<?= $produto['id'] ?>)">Gerenciar Variações</button>
                    <button class="btn btn-sm btn-primary" onclick="abrirEditar(<?= $produto['id'] ?>, '<?= htmlspecialchars($produto['nome']) ?>', <?= $produto['preco'] ?>, <?= $estoque ?>)">Editar</button>
                    <button class="btn btn-sm btn-danger" onclick="abrirExcluir(<?= $produto['id'] ?>)">Excluir</button>
                </td>
            </tr>
        <?php endforeach ?>
    </tbody>
</table>

<!-- Modal Variações -->
<div class="modal fade" id="modalVariacoes" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Gerenciar Variações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formVariacao">
                    <input type="hidden" name="produto_id" id="variacaoProdutoId">
                    <div class="mb-3">
                        <label>Atributo:</label>
                        <input type="text" name="atributo" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Valor:</label>
                        <input type="text" name="valor" class="form-control" required>
                    </div>
                    <button class="btn btn-success">Adicionar Variação</button>
                </form>

                <hr>

                <h5>Variações já cadastradas:</h5>
                <ul id="listaVariacoes" class="list-group"></ul>
            </div>
        </div>
    </div>
</div>


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
                    <label>Preço Base:</label>
                    <input type="number" step="0.01" name="preco" id="editarPreco" class="form-control">
                </div>
                <div class="mb-3">
                    <label>Estoque:</label>
                    <input type="number" name="quantidade" id="editarQuantidade" class="form-control" min="0">
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
    function abrirVariacoes(produtoId) {
        document.getElementById('variacaoProdutoId').value = produtoId;
        listarVariacoes(produtoId);
        new bootstrap.Modal(document.getElementById('modalVariacoes')).show();
    }

    function listarVariacoes(produtoId) {
        fetch(`/api/produto/${produtoId}/variacoes`, {
                method: 'GET',
            })
            .then(res => res.json())
            .then(data => {
                const lista = document.getElementById('listaVariacoes');
                lista.innerHTML = '';
                data.forEach(variacao => {
                    const li = document.createElement('li');
                    li.className = 'list-group-item d-flex justify-content-between align-items-center';
                    li.textContent = `${variacao.atributo}: ${variacao.valor}`;

                    const btnExcluir = document.createElement('button');
                    btnExcluir.className = 'btn btn-sm btn-danger';
                    btnExcluir.textContent = 'Excluir';
                    btnExcluir.onclick = () => excluirVariacao(variacao.id, produtoId);

                    li.appendChild(btnExcluir);
                    lista.appendChild(li);
                });
            })
            .catch(err => alert("Erro ao buscar variações"));
    }

    document.getElementById('formVariacao').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const produtoId = document.getElementById('variacaoProdutoId').value;

        fetch(`/api/produto/${produtoId}/variacoes`, {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }
                listarVariacoes(produtoId);
                this.reset();
            })
            .catch(err => alert("Erro ao salvar variação"));
    });


    function excluirVariacao(id, produtoId) {
        if (!confirm("Tem certeza que deseja excluir esta variação?")) return;

        fetch(`/api/variacoes/${id}`, {
                method: 'DELETE'
            })
            .then(res => res.json())
            .then(() => listarVariacoes(produtoId))
            .catch(err => alert("Erro ao excluir variação"));
    }
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
            });
    });

    let idExcluir = null;

    function abrirEditar(id, nome, preco, quantidade) {
        document.getElementById('editarId').value = id;
        document.getElementById('editarNome').value = nome;
        document.getElementById('editarPreco').value = preco;
        document.getElementById('editarQuantidade').value = quantidade;
        new bootstrap.Modal(document.getElementById('modalEditar')).show();
    }

    document.getElementById('formEditar').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('editarId').value;
        const formData = new FormData(this);
        fetch(`/api/produto/${id}`, {
            method: 'PUT',
            body: formData
        }).then(res => res.json()).then(data => {
            if (data.error) {
                alert(data.error);
                return;
            }
            location.reload();
        });
    });

    function abrirExcluir(id) {
        idExcluir = id;
        new bootstrap.Modal(document.getElementById('modalExcluir')).show();
    }

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
            });
    });
</script>

<?php renderFooter(); ?>