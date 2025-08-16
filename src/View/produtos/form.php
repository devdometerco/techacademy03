<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?php echo isset($produto['id_produto']) ? 'Editar' : 'Cadastrar'; ?> Produto - D2tech</title>
    <link rel="stylesheet" href="assets/css/admin.css">
    </head>
<body>
    <div class="container">
    <h1><?php echo isset($produto['id_produto']) ? 'Editar' : 'Cadastrar'; ?> Produto</h1>
    <a href="index.php?action=logout">Sair</a>
    <hr>
    
    <form action="index.php?action=produto_save" method="post">
        <input type="hidden" name="id_produto" value="<?php echo $produto['id_produto'] ?? ''; ?>">
        
        <div>
            <label for="nome">Nome do Produto:</label>
            <input type="text" id="nome" name="nome" value="<?php echo $produto['nome'] ?? ''; ?>" required>
        </div>
        <br>
        <div>
            <label for="categoria">Categoria:</label>
            <select id="categoria" name="categoria_id" required>
                <option value="">Selecione...</option>
                <?php foreach ($categorias as $categoria): ?>
                    <option value="<?php echo $categoria['id_categoria']; ?>" <?php echo (isset($produto['categoria_id']) && $produto['categoria_id'] == $categoria['id_categoria']) ? 'selected' : ''; ?>>
                        <?php echo $categoria['nome']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>
        <div>
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao"><?php echo $produto['descricao'] ?? ''; ?></textarea>
        </div>
        <br>
        <div>
            <label for="preco">Preço (R$):</label>
            <input type="number" id="preco" name="preco" step="0.01" value="<?php echo $produto['preco'] ?? ''; ?>" required>
        </div>
        <br>
        <div>
            <label for="estoque">Quantidade em Estoque:</label>
            <input type="number" id="estoque" name="estoque" value="<?php echo $produto['estoque'] ?? '0'; ?>" required>
        </div>
        <br>
        <button type="submit">Salvar Produto</button>
    </form>
    </div>
</body>
</html>