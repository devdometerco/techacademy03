<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Administração D2tech</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="login-container">
        <h2>Área Restrita - D2tech</h2>
        
        <form action="index.php?action=login" method="post">
            <div class="form-group">
                <label for="login">Usuário:</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div class="form-group">
                <label for="senha">Senha:</label>
                <input type="password" id="senha" name="senha" required>
            </div>
            <button type="submit" class="btn-submit">Entrar</button>
        </form>

        <?php if (isset($error)): ?>
            <p class="error-message"><?php echo $error; ?></p>
        <?php endif; ?>
    </div>
</body>
</html>