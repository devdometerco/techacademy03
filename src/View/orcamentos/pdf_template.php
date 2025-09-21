<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Orçamento <?php echo $orcamento['id_orcamento']; ?></title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header h1 { margin: 0; }
        .header p { margin: 2px 0; }
        .details { border-collapse: collapse; width: 100%; margin-bottom: 20px; }
        .details th, .details td { border: 1px solid #ccc; padding: 8px; }
        .details th { background-color: #f2f2f2; text-align: left; }
        .items-table { border-collapse: collapse; width: 100%; }
        .items-table th, .items-table td { border: 1px solid #ccc; padding: 8px; text-align: center; }
        .items-table th { background-color: #f2f2f2; }
        .total { text-align: right; font-size: 14px; font-weight: bold; margin-top: 20px; }
    </style>
</head>
<body>
    <div class="header">
        <h1><?php echo $empresa['nome']; ?></h1>
        <p><?php echo $empresa['endereco']; ?> - <?php echo $empresa['cidade_estado_cep']; ?></p>
        <p>CNPJ: <?php echo $empresa['cnpj']; ?></p>
    </div>

    <h2>Orçamento Nº <?php echo str_pad($orcamento['id_orcamento'], 4, '0', STR_PAD_LEFT); ?></h2>

    <table class="details">
        <tr>
            <th>Cliente</th>
            <td><?php echo $orcamento['nome_cliente']; ?></td>
            <th>Data de Emissão</th>
            <td><?php echo date('d/m/Y', strtotime($orcamento['data_emissao'])); ?></td>
        </tr>
        <tr>
            <th><?php echo $orcamento['tipo_pessoa'] == 'PF' ? 'CPF' : 'CNPJ'; ?></th>
            <td><?php echo $orcamento['documento_cliente']; ?></td>
            <th>Validade</th>
            <td><?php echo $orcamento['validade_dias']; ?> dias</td>
        </tr>
        <tr>
            <th>Contato</th>
            <td colspan="3"><?php echo $orcamento['email_cliente']; ?> / <?php echo $orcamento['telefone_cliente']; ?></td>
        </tr>
    </table>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Produto</th>
                <th>Qtd.</th>
                <th>Valor Unit.</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            <?php $itemNum = 1; foreach ($itens as $item): ?>
            <tr>
                <td><?php echo $itemNum++; ?></td>
                <td style="text-align: left;"><?php echo $item['nome_produto']; ?></td>
                <td><?php echo $item['quantidade']; ?></td>
                <td>R$ <?php echo number_format($item['valor_unitario'], 2, ',', '.'); ?></td>
                <td>R$ <?php echo number_format($item['valor_unitario'] * $item['quantidade'], 2, ',', '.'); ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="total">
        VALOR TOTAL: R$ <?php echo number_format($orcamento['valor_total'], 2, ',', '.'); ?>
    </div>
</body>
</html>