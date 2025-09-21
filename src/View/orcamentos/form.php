<?php require __DIR__ . '/../templates/header.php'; ?>

<div class="empresa-header">
    <h2><?php echo $empresa['nome']; ?></h2>
    <p>
        <strong>CNPJ:</strong> <?php echo $empresa['cnpj']; ?><br>
        <strong>Endereço:</strong> <?php echo $empresa['endereco']; ?><br>
        <?php echo $empresa['cidade_estado_cep']; ?>
    </p>
</div>

<h1>Novo Orçamento</h1>
<hr>

<form action="index.php?action=orcamento_store" method="post" id="orcamentoForm">
    
    <div class="form-group">
        <label for="cliente_id">Selecione o Cliente:</label>
        <select name="cliente_id" id="cliente_id" required>
            <option value="">Selecione...</option>
            <?php foreach ($clientes as $cliente): ?>
                <option value="<?php echo $cliente['id_cliente']; ?>">
                    <?php echo $cliente['tipo_pessoa'] == 'PF' ? $cliente['nome'] . ' (PF)' : $cliente['razao_social'] . ' (PJ)'; ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <hr>

    <h3>Adicionar Produtos ao Orçamento</h3>
    <div class="form-group-inline">
        <div class="form-group">
            <label for="produto">Produto:</label>
            <select id="produto">
                <option value="">Selecione um produto...</option>
                <?php foreach ($produtos as $produto): ?>
                    <option value="<?php echo $produto['id_produto']; ?>" data-preco="<?php echo $produto['preco']; ?>">
                        <?php echo $produto['nome']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="quantidade">Quantidade:</label>
            <input type="number" id="quantidade" value="1" min="1" style="width: 80px;">
        </div>
        <button type="button" id="btnAdicionarProduto" class="btn btn-secondary">Adicionar</button>
    </div>

    <hr>

    <h3>Itens do Orçamento</h3>
    <div class="orcamento-itens-list">
        <table id="tabelaItens">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Qtd.</th>
                    <th>Valor Unit. (R$)</th>
                    <th>Subtotal (R$)</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right; font-weight: bold;">TOTAL:</td>
                    <td id="valorTotal" style="font-weight: bold;">R$ 0,00</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
    
    <br>
    <button type="submit" class="btn btn-success">Salvar Orçamento</button>
</form>

<script>
    // ... (todo o nosso código JS para adicionar/remover/atualizar itens) ...
</script>

<?php require __DIR__ . '/../templates/footer.php'; ?>