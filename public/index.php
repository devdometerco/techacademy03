<?php
// Inicia a sessão em todas as páginas
session_start();

// Carrega o autoload do Composer para carregar as classes automaticamente
require_once __DIR__ . '/../vendor/autoload.php';

// Carrega as classes dos Controllers
use App\Controller\DashboardController;
use App\Controller\ClienteController;
use App\Controller\OrcamentoController;



// Carrega as variáveis de ambiente (do arquivo .env)
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

// --- LÓGICA DE ROTEAMENTO ---

// Pega a 'action' da URL. Se não existir, a ação padrão é 'login_form'
$action = $_GET['action'] ?? 'login_form';

// --- CONTROLE DE ACESSO  ---

// Lista com todas as rotas que precisam de login para serem acessadas
$rotas_protegidas = [
    'dashboardController',
    'produto_form', 
    'produto_save',
    'clientes', 
    'cliente_create', 
    'cliente_store', 
    'cliente_edit', 
    'cliente_update', 
    'cliente_delete',
    'orcamento_create',
    'orcamento_store',
    'orcamento_pdf',
     
];

// Se a ação está na lista de protegidas E o usuário NÃO está logado, manda pra tela de login
if (in_array($action, $rotas_protegidas) && !isset($_SESSION['usuario_logado'])) {
    header('Location: index.php?action=login_form');
    exit;
}

// --- SWITCH PRINCIPAL ---

switch ($action) {
    // --- ROTAS DE AUTENTICAÇÃO ---
    case 'login_form':
        require __DIR__ . '/../src/View/auth/login.php';
        break;
    
    case 'login':
        // A lógica de login
        $login = $_POST['login'];
        $senha = $_POST['senha'];
        $pdo = App\Config\Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE login = ?");
        $stmt->execute([$login]);
        $usuario = $stmt->fetch();
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            $_SESSION['usuario_logado'] = $usuario['login'];
            header('Location: index.php?action=dashboard'); // Manda para clientes após logar
        } else {
            $error = "Usuário ou senha inválidos!";
            require __DIR__ . '/../src/View/auth/login.php';
        }
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?action=login_form');
        break;

    // --- ROTAS DE PRODUTOS  ---
    case 'produto_form':
        // Lógica para carregar o form de produtos...
        require __DIR__ . '/../src/View/produtos/form.php';
        break;
        
    case 'produto_save':
        // Lógica para salvar o produto...
        echo "Produto salvo! (Lógica a ser movida para ProdutoController)";
        break;
    
    // --- ROTAS DE CLIENTES  ---
    case 'clientes':
        $clienteController = new ClienteController();
        $clienteController->index();
        break;

    case 'cliente_create':
        $clienteController = new ClienteController();
        $clienteController->create();
        break;

    case 'cliente_store':
        $clienteController = new ClienteController();
        $clienteController->store();
        break;

    case 'cliente_edit':
        if (isset($_GET['id'])) {
            $clienteController = new ClienteController();
            $clienteController->edit($_GET['id']);
        }
        break;

    case 'cliente_update':
        if (isset($_GET['id'])) {
            $clienteController = new ClienteController();
            $clienteController->update($_GET['id']);
        }
        break;

    case 'cliente_delete':
        if (isset($_GET['id'])) {
            $clienteController = new ClienteController();
            $clienteController->delete($_GET['id']);
        }
        break;
        
    // --- ROTAS DE ORÇAMENTOS  ---
    case 'orcamento_create':
        $orcamentoController = new OrcamentoController();
        $orcamentoController->create();
        break;
         
    case 'orcamento_store':
    $orcamentoController = new OrcamentoController();
    $orcamentoController->store();
    break;

    case 'orcamentos': // Rota para a nova lista
    $orcamentoController = new OrcamentoController();
    $orcamentoController->index();
    break;

    case 'orcamento_pdf': // Rota para gerar o PDF
    if (isset($_GET['id'])) {
        $orcamentoController = new OrcamentoController();
        $orcamentoController->gerarPdf($_GET['id']);
    }
    break;
    case 'dashboard':
    $dashboardController = new DashboardController();
    $dashboardController->index();
    break;

    // --- ROTA PADRÃO ---
    default:
        echo "Página não encontrada!";
        break;
}