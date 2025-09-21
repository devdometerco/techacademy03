<?php require __DIR__ . '/../templates/header.php'; ?>
           <h1>Lista de Clientes</h1>
        <a href="index.php?action=cliente_create" class="button-add">Novo Cliente</a>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tipo</th>
                    <th>Nome / Razão Social</th>
                    <th>CPF / CNPJ</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                    <tr>
                        <td><?php echo $cliente['id_cliente']; ?></td>
                        <td><?php echo $cliente['tipo_pessoa']; ?></td>
                        <td><?php echo $cliente['tipo_pessoa'] == 'PF' ? $cliente['nome'] : $cliente['razao_social']; ?></td>
                        <td><?php echo $cliente['tipo_pessoa'] == 'PF' ? $cliente['cpf'] : $cliente['cnpj']; ?></td>
                        <td>
                            <a href="index.php?action=cliente_edit&id=<?php echo $cliente['id_cliente']; ?>">Editar</a>
                            <a href="index.php?action=cliente_delete&id=<?php echo $cliente['id_cliente']; ?>" onclick="return confirm('Tem certeza que deseja excluir este cliente?');">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
<?php require __DIR__ . '/../templates/footer.php'; ?>