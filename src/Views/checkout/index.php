<?php renderHeader('Checkout'); ?>

<h1>Finalizar Pedido</h1>

<form id="formCheckout">
    <div class="mb-3">
        <label>Email:</label>
        <input type="email" name="email" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>CEP:</label>
        <input type="text" name="cep" id="cep" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Endereço:</label>
        <input type="text" name="endereco" id="endereco" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Número:</label>
        <input type="text" name="numero" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Complemento:</label>
        <input type="text" name="complemento" class="form-control">
    </div>

    <div class="mb-3">
        <label>Bairro:</label>
        <input type="text" name="bairro" id="bairro" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>Cidade:</label>
        <input type="text" name="cidade" id="cidade" class="form-control" required>
    </div>

    <div class="mb-3">
        <label>UF:</label>
        <input type="text" name="uf" id="uf" class="form-control" required>
    </div>

    <button type="submit" class="btn btn-success">Confirmar Pedido</button>
</form>

<!-- Modal de Sucesso -->
<div class="modal fade" id="modalSucesso" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">Pedido Finalizado com Sucesso!</h5>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <h6>Dados do Cliente:</h6>
                        <p><strong>Email:</strong> <span id="infoEmail"></span></p>
                        <p><strong>Endereço:</strong> <span id="infoEndereco"></span></p>
                    </div>
                    <div class="col-md-6">
                        <h6>Resumo do Pedido:</h6>
                        <p><strong>Subtotal:</strong> R$ <span id="infoSubtotal"></span></p>
                        <p><strong>Frete:</strong> R$ <span id="infoFrete"></span></p>
                        <div id="cupomContainer" style="display:none;">
                            <p><strong>Cupom:</strong> <span id="infoCupom"></span></p>
                            <p><strong>Desconto:</strong> <span id="infoDesconto"></span></p>
                        </div>
                        <p><strong>Total:</strong> <b>R$ <span id="infoTotal"></span></b></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <a href="/carrinho" class="btn btn-primary">Voltar ao Carrinho</a>
            </div>
        </div>
    </div>
</div>

<script>
    // ViaCEP para preencher endereço
    document.getElementById('cep').addEventListener('blur', function() {
        let cep = this.value.replace(/\D/g, '');
        if (cep.length === 8) {
            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(res => res.json())
                .then(data => {
                    if (!data.erro) {
                        document.getElementById('endereco').value = data.logradouro;
                        document.getElementById('bairro').value = data.bairro;
                        document.getElementById('cidade').value = data.localidade;
                        document.getElementById('uf').value = data.uf;
                    }
                });
        }
    });

    document.getElementById('formCheckout').addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        fetch('/api/checkout/finalizar', {
                method: 'POST',
                body: formData
            }).then(res => res.json())
            .then(data => {
                if (data.error) {
                    alert(data.error);
                    return;
                }

                document.getElementById('infoEmail').innerText = formData.get('email');
                document.getElementById('infoEndereco').innerText =
                    `${formData.get('endereco')}, ${formData.get('numero')} - ${formData.get('bairro')} - ${formData.get('cidade')}/${formData.get('uf')}`;

                document.getElementById('infoSubtotal').innerText = data.subtotal.toFixed(2).replace('.', ',');
                document.getElementById('infoFrete').innerText = data.frete.toFixed(2).replace('.', ',');

                if (data.cupom) {
                    document.getElementById('cupomContainer').style.display = 'block';
                    document.getElementById('infoCupom').innerText = data.cupom;
                    document.getElementById('infoDesconto').innerText = `${data.percentual}% (-R$ ${data.desconto.toFixed(2).replace('.', ',')})`;
                }

                document.getElementById('infoTotal').innerText = data.total.toFixed(2).replace('.', ',');

                new bootstrap.Modal(document.getElementById('modalSucesso')).show();
            });
    });
</script>

<?php renderFooter(); ?>