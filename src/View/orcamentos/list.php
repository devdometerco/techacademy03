<?php require __DIR__ . '/../templates/header.php'; ?>
        <h1>Lista de Orçamentos</h1>
        <a href="index.php?action=orcamento_create" class="button-add">Novo Orçamento</a>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Cliente</th>
                    <th>Data</th>
                    <th>Valor Total</th>
                    <th>Status</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orcamentos as $orcamento): ?>
                    <tr>
                        <td><?php echo $orcamento['id_orcamento']; ?></td>
                        <td><?php echo $orcamento['nome_cliente']; ?></td>
                        <td><?php echo date('d/m/Y', strtotime($orcamento['data_emissao'])); ?></td>
                        <td>R$ <?php echo number_format($orcamento['valor_total'], 2, ',', '.'); ?></td>
                        <td><?php echo $orcamento['status']; ?></td>
                        <td>
                            <a href="index.php?action=orcamento_pdf&id=<?php echo $orcamento['id_orcamento']; ?>" target="_blank">Gerar PDF</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php require __DIR__ . '/../templates/footer.php'; ?>