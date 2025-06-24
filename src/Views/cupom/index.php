<?php renderHeader('Gerenciar Cupons'); ?>

<h1>Gerenciar Cupons</h1>

<form id="formCadastroCupom" class="mb-4">
    <div class="row g-3">
        <div class="col">
            <input type="text" name="codigo" class="form-control" placeholder="Código" required>
        </div>
        <div class="col">
            <input type="number" step="0.01" name="valor_minimo" class="form-control" placeholder="Valor Mínimo" required>
        </div>
        <div class="col">
            <input type="number" step="0.01" name="desconto_percentual" class="form-control" placeholder="Desconto (%)" required>
        </div>
        <div class="col">
            <input type="date" name="validade" class="form-control" required>
        </div>
        <div class="col">
            <button class="btn btn-success">Salvar</button>
        </div>
    </div>
</form>

<table class="table">
    <thead>
        <tr>
            <th>Código</th>
            <th>Mínimo</th>
            <th>Desconto</th>
            <th>Validade</th>
            <th>Ações</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($cupons as $cupom): ?>
            <tr>
                <td><?= htmlspecialchars($cupom['codigo']) ?></td>
                <td>R$ <?= number_format($cupom['valor_minimo'], 2, ',', '.') ?></td>
                <td><?= number_format($cupom['desconto_percentual'], 2, ',', '.') ?>%</td>
                <td><?= htmlspecialchars($cupom['validade']) ?></td>
                <td>
                    <button class="btn btn-primary btn-sm" onclick="abrirEditar(<?= $cupom['id'] ?>, '<?= htmlspecialchars($cupom['codigo']) ?>', <?= $cupom['valor_minimo'] ?>, <?= $cupom['desconto_percentual'] ?>, '<?= $cupom['validade'] ?>')">Editar</button>
                    <button class="btn btn-danger btn-sm" onclick="abrirExcluir(<?= $cupom['id'] ?>)">Excluir</button>
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
                <h5 class="modal-title">Editar Cupom</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" name="id" id="editarId">
                <div class="mb-3">
                    <label>Código:</label>
                    <input type="text" name="codigo" id="editarCodigo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Valor Mínimo:</label>
                    <input type="number" step="0.01" name="valor_minimo" id="editarMinimo" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Desconto (%):</label>
                    <input type="number" step="0.01" name="desconto_percentual" id="editarDesconto" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label>Validade:</label>
                    <input type="date" name="validade" id="editarValidade" class="form-control" required>
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
                <p>Deseja realmente excluir este cupom?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-danger" id="btnConfirmarExcluir">Excluir</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Cadastro
    document.getElementById('formCadastroCupom').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('/api/cupons', {
            method: 'POST',
            body: formData
        }).then(() => location.reload());
    });

    // Editar
    function abrirEditar(id, codigo, minimo, desconto, validade) {
        document.getElementById('editarId').value = id;
        document.getElementById('editarCodigo').value = codigo;
        document.getElementById('editarMinimo').value = minimo;
        document.getElementById('editarDesconto').value = desconto;
        document.getElementById('editarValidade').value = validade;
        new bootstrap.Modal(document.getElementById('modalEditar')).show();
    }

    document.getElementById('formEditar').addEventListener('submit', function(e) {
        e.preventDefault();
        const id = document.getElementById('editarId').value;
        const formData = new FormData(this);
        fetch(`/api/cupons/${id}`, {
            method: 'PUT',
            body: formData
        }).then(() => location.reload());
    });

    // Excluir
    let idExcluir = null;

    function abrirExcluir(id) {
        idExcluir = id;
        new bootstrap.Modal(document.getElementById('modalExcluir')).show();
    }

    document.getElementById('btnConfirmarExcluir').addEventListener('click', function() {
        fetch(`/api/cupons/${idExcluir}`, {
            method: 'DELETE'
        }).then(() => location.reload());
    });
</script>

<?php renderFooter(); ?>