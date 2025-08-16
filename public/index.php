<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

$action = $_GET['action'] ?? 'login_form';

$rotas_protegidas = ['produto_form', 'produto_save', 'dashboard'];
if (in_array($action, $rotas_protegidas) && !isset($_SESSION['usuario_logado'])) {
    header('Location: index.php?action=login_form');
    exit;
}

switch ($action) {
    case 'login_form':
        require __DIR__ . '/../src/View/auth/login.php';
        break;
    
    case 'login':
        $login = $_POST['login'];
        $senha = $_POST['senha'];
        $pdo = App\Config\Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE login = ?");
        $stmt->execute([$login]);
        $usuario = $stmt->fetch();
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_logado'] = $usuario['login'];
            header('Location: index.php?action=produto_form');
        } else {
            $error = "Usuário ou senha inválidos!";
            require __DIR__ . '/../src/View/auth/login.php';
        }
        break;

    case 'produto_form':
        $pdo = App\Config\Database::getInstance();
        $produto = [];
        $stmt_categorias = $pdo->query("SELECT * FROM categorias ORDER BY nome");
        $categorias = $stmt_categorias->fetchAll();
        if (isset($_GET['id'])) {
            $stmt_produto = $pdo->prepare("SELECT * FROM produtos WHERE id_produto = ?");
            $stmt_produto->execute([$_GET['id']]);
            $produto = $stmt_produto->fetch();
        }
        require __DIR__ . '/../src/View/produtos/form.php';
        break;
        
    case 'produto_save':
        $pdo = App\Config\Database::getInstance();
        $id = $_POST['id_produto'];
        $nome = $_POST['nome'];
        $descricao = $_POST['descricao'];
        $preco = $_POST['preco'];
        $estoque = $_POST['estoque'];
        $categoria_id = $_POST['categoria_id'];

        if (empty($id)) {
            $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco, estoque, categoria_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$nome, $descricao, $preco, $estoque, $categoria_id]);
        } else {
            $stmt = $pdo->prepare("UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ?, categoria_id = ? WHERE id_produto = ?");
            $stmt->execute([$nome, $descricao, $preco, $estoque, $categoria_id, $id]);
        }
        
        echo "Produto salvo com sucesso! <br>";
        echo "<a href='index.php?action=produto_form'>Cadastrar Novo Produto</a> ou <a href='#'>Ver Lista de Produtos</a>";
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?action=login_form');
        break;

    default:
        echo "Página não encontrada!";
        break;
}