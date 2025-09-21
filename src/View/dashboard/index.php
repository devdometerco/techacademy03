<?php require __DIR__ . '/../templates/header.php'; ?>

<h1 style="text-align: center; margin-bottom: 40px;">Painel de Controle</h1>

<div class="dashboard-menu">
    <a href="index.php?action=clientes" class="menu-item">
        <h2>Clientes</h2>
        <p>Cadastrar, consultar e gerenciar clientes</p>
    </a>
    <a href="index.php?action=produto_form" class="menu-item">
        <h2>Produtos</h2>
        <p>Cadastrar e gerenciar produtos</p>
    </a>
    <a href="index.php?action=orcamentos" class="menu-item">
        <h2>Orçamentos</h2>
        <p>Criar novos orçamentos ou consultar existentes</p>
    </a>
</div>

<?php require __DIR__ . '/../templates/footer.php'; ?>