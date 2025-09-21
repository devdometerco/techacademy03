<?php
namespace App\Controller;

// App\Model\Cliente;`
use App\Config\Database;

class ClienteController
{
    /**
     * Exibe a lista de todos os clientes.
     */
    public function index()
    {
        // Lógica para buscar todos os clientes no banco de dados
        $pdo = Database::getInstance();

        // Para juntar os dados de PF e PJ
        $sql = "SELECT c.id_cliente, c.tipo_pessoa, 
                       pf.nome, pf.cpf, 
                       pj.razao_social, pj.cnpj
                FROM clientes c
                LEFT JOIN clientes_pf pf ON c.id_cliente = pf.id_cliente AND c.tipo_pessoa = 'PF'
                LEFT JOIN clientes_pj pj ON c.id_cliente = pj.id_cliente AND c.tipo_pessoa = 'PJ'
                ORDER BY c.id_cliente DESC";

        $stmt = $pdo->query($sql);
        $clientes = $stmt->fetchAll();

        // Carrega a view da lista de clientes
        require __DIR__ . '/../View/clientes/list.php';
    }

    /**
     * Exibe o formulário para criar um novo cliente.
     */
    public function create()
    {
        // Apenas carrega a view do formulário
        require __DIR__ . '/../View/clientes/form.php';
    }

   /**
 * Salva um novo cliente no banco de dados.
 */
public function store()
{
    $pdo = Database::getInstance();
    $pdo->beginTransaction(); // Inicia a transação!

    try {
        // 1. Inserir na tabela principal "Mãe" (clientes)
        $stmt = $pdo->prepare("INSERT INTO clientes (tipo_pessoa) VALUES (?)");
        $stmt->execute([$_POST['tipo_pessoa']]);

        // Pega o ID do cliente que acabou de ser criado
        $idCliente = $pdo->lastInsertId();

        // 2. Inserir na tabela dependente "filha" específica (PF ou PJ)
        if ($_POST['tipo_pessoa'] === 'PF') {
            $stmtPf = $pdo->prepare(
                "INSERT INTO clientes_pf (id_cliente, nome, cpf, telefone, email, endereco) 
                 VALUES (?, ?, ?, ?, ?, ?)"
            );
            $stmtPf->execute([
                $idCliente,
                $_POST['nome'],
                $_POST['cpf'],
                $_POST['telefone'],
                $_POST['email'],
                $_POST['endereco']
            ]);
        } elseif ($_POST['tipo_pessoa'] === 'PJ') {
            $stmtPj = $pdo->prepare(
                "INSERT INTO clientes_pj (id_cliente, cnpj, razao_social, nome_fantasia, telefone, email, endereco) 
                 VALUES (?, ?, ?, ?, ?, ?, ?)"
            );
            $stmtPj->execute([
                $idCliente,
                $_POST['cnpj'],
                $_POST['razao_social'],
                $_POST['nome_fantasia'],
                $_POST['telefone'],
                $_POST['email'],
                $_POST['endereco']
            ]);
        }

        $pdo->commit(); // Se tudo deu certo, commit para tornar permanente.

    } catch (\Exception $e) {
        $pdo->rollBack(); // Se deu qualquer erro, roolback desfaz tudo!
        die("Erro ao salvar cliente: " . $e->getMessage());
    }

    // Redireciona para a lista de clientes
    header('Location: index.php?action=clientes');
    exit;
}
/**
 * Exibe o formulário para editar um cliente existente.
 */
public function edit($id)
{
    $pdo = Database::getInstance();

    // Query para buscar todos os dados do cliente (da tabela mãe e da filha)
    $sql = "SELECT c.*, pf.*, pj.* FROM clientes c
            LEFT JOIN clientes_pf pf ON c.id_cliente = pf.id_cliente
            LEFT JOIN clientes_pj pj ON c.id_cliente = pj.id_cliente
            WHERE c.id_cliente = ?";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $cliente = $stmt->fetch();

    // Carrega a view do formulário, passando os dados do cliente
    require __DIR__ . '/../View/clientes/form.php';
}

/**
 * Atualiza os dados de um cliente no banco.
 */
public function update($id)
{
    $pdo = Database::getInstance();
    $pdo->beginTransaction();

    try {
        // Update para alterar dados
        
        if ($_POST['tipo_pessoa'] === 'PF') {
            $stmtPf = $pdo->prepare(
                "UPDATE clientes_pf SET nome = ?, cpf = ?, telefone = ?, email = ?, endereco = ?
                 WHERE id_cliente = ?"
            );
            $stmtPf->execute([
                $_POST['nome'], $_POST['cpf'], $_POST['telefone'], 
                $_POST['email'], $_POST['endereco'], $id
            ]);
        } elseif ($_POST['tipo_pessoa'] === 'PJ') {
            $stmtPj = $pdo->prepare(
                "UPDATE clientes_pj SET cnpj = ?, razao_social = ?, nome_fantasia = ?, telefone = ?, email = ?, endereco = ?
                 WHERE id_cliente = ?"
            );
            $stmtPj->execute([
                $_POST['cnpj'], $_POST['razao_social'], $_POST['nome_fantasia'], 
                $_POST['telefone'], $_POST['email'], $_POST['endereco'], $id
            ]);
        }

        $pdo->commit();

    } catch (\Exception $e) {
        $pdo->rollBack();
        die("Erro ao atualizar cliente: " . $e->getMessage());
    }

    header('Location: index.php?action=clientes');
    exit;
}
/**
 * Exclui um cliente do banco de dados.
 */
public function delete($id)
{
    $pdo = Database::getInstance();

    try {
        // COM ON DELETE CASCADE, vai apagar da tabela "mãe", que ela se encarregará de deletar as "PF ou PJ".
        $stmt = $pdo->prepare("DELETE FROM clientes WHERE id_cliente = ?");
        $stmt->execute([$id]);

    } catch (\Exception $e) {
        // Se der erro (por exemplo, um orçamento está atrelado a este cliente e não pode ser apagado),
        // a gente captura a exceção.
        die("Erro ao excluir cliente: " . $e->getMessage());
    }

    // Redireciona de volta para a lista de clientes
    header('Location: index.php?action=clientes');
    exit;
}
}