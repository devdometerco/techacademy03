<?php
namespace App\Controller;

use App\Config\Database;
use Dompdf\Dompdf;
use Dompdf\Options; 

class OrcamentoController
{
    /**
     * Exibe a lista de todos os orçamentos.
     */
    public function index()
    {
        $pdo = Database::getInstance();
        $sql = "SELECT o.id_orcamento, o.data_emissao, o.valor_total, o.status,
                       COALESCE(pf.nome, pj.razao_social) as nome_cliente
                FROM orcamentos o
                JOIN clientes c ON o.cliente_id = c.id_cliente
                LEFT JOIN clientes_pf pf ON c.id_cliente = pf.id_cliente
                LEFT JOIN clientes_pj pj ON c.id_cliente = pj.id_cliente
                ORDER BY o.id_orcamento DESC";
        
        $stmt = $pdo->query($sql);
        $orcamentos = $stmt->fetchAll();

        require __DIR__ . '/../View/orcamentos/list.php';
    }

    /**
     * Exibe o formulário para criar um novo orçamento.
     */
    public function create()
    {
        $empresa = require __DIR__ . '/../Config/empresa.php';
        $pdo = Database::getInstance();
        $sqlClientes = "SELECT c.id_cliente, c.tipo_pessoa, 
                               pf.nome, pj.razao_social
                        FROM clientes c
                        LEFT JOIN clientes_pf pf ON c.id_cliente = pf.id_cliente
                        LEFT JOIN clientes_pj pj ON c.id_cliente = pj.id_cliente
                        WHERE c.ativo = 1
                        ORDER BY pf.nome, pj.razao_social";
        $stmtClientes = $pdo->query($sqlClientes);
        $clientes = $stmtClientes->fetchAll();
        $sqlProdutos = "SELECT id_produto, nome, preco FROM produtos ORDER BY nome";
        $stmtProdutos = $pdo->query($sqlProdutos);
        $produtos = $stmtProdutos->fetchAll();
        require __DIR__ . '/../View/orcamentos/form.php';
    }

    /**
     * Salva um novo orçamento no banco de dados.
     */
    public function store()
    {
        if (empty($_POST['cliente_id']) || empty($_POST['produtos'])) {
            die("Erro: Cliente ou produtos não selecionados.");
        }
        $pdo = Database::getInstance();
        $pdo->beginTransaction();
        try {
            $valorTotal = 0;
            foreach ($_POST['produtos'] as $produto) {
                $valorTotal += $produto['preco'] * $produto['qtd'];
            }
            $stmt = $pdo->prepare(
                "INSERT INTO orcamentos (cliente_id, valor_total) VALUES (?, ?)"
            );
            $stmt->execute([$_POST['cliente_id'], $valorTotal]);
            $idOrcamento = $pdo->lastInsertId();
            $stmtItens = $pdo->prepare(
                "INSERT INTO orcamento_itens (orcamento_id, produto_id, quantidade, valor_unitario)
                 VALUES (?, ?, ?, ?)"
            );
            foreach ($_POST['produtos'] as $produto) {
                $stmtItens->execute([$idOrcamento, $produto['id'], $produto['qtd'], $produto['preco']]);
            }
            $pdo->commit();
            header('Location: index.php?action=orcamentos');
            exit;
        } catch (\Exception $e) {
            $pdo->rollBack();
            die("Erro ao salvar orçamento: " . $e->getMessage());
        }
    }
    
    /**
     * Gerar o PDF de um orçamento específico.
     */
     public function gerarPdf($id)
    {
        // Buscar os dados
        $pdo = Database::getInstance();
        $empresa = require __DIR__ . '/../Config/empresa.php';
        // ... (código para buscar $orcamento e $itens sem alterações)
        $sqlOrcamento = "SELECT o.*, c.tipo_pessoa, COALESCE(pf.nome, pj.razao_social) as nome_cliente, COALESCE(pf.cpf, pj.cnpj) as documento_cliente, COALESCE(pf.email, pj.email) as email_cliente, COALESCE(pf.telefone, pj.telefone) as telefone_cliente, COALESCE(pf.endereco, pj.endereco) as endereco_cliente FROM orcamentos o JOIN clientes c ON o.cliente_id = c.id_cliente LEFT JOIN clientes_pf pf ON c.id_cliente = pf.id_cliente LEFT JOIN clientes_pj pj ON c.id_cliente = pj.id_cliente WHERE o.id_orcamento = ?";
        $stmtOrcamento = $pdo->prepare($sqlOrcamento);
        $stmtOrcamento->execute([$id]);
        $orcamento = $stmtOrcamento->fetch();
        $sqlItens = "SELECT i.*, p.nome as nome_produto FROM orcamento_itens i JOIN produtos p ON i.produto_id = p.id_produto WHERE i.orcamento_id = ?";
        $stmtItens = $pdo->prepare($sqlItens);
        $stmtItens->execute([$id]);
        $itens = $stmtItens->fetchAll();

        
        ob_start();
        require __DIR__ . '/../View/orcamentos/pdf_template.php';
        $html = ob_get_clean();

        // --- GERANDO O PDF COM DOMPDF," Podemos configurar Orientação, tamano ---
        try {
            // Configurações do Dompdf
            $options = new Options();
            $options->set('isRemoteEnabled', true); // Permite carregar imagens externas, se precisar

            // Cria a instância do Dompdf
            $dompdf = new Dompdf($options);

            // Carrega o nosso HTML
            $dompdf->loadHtml($html);

            // Configura o tamanho e a orientação do papel
            $dompdf->setPaper('A4', 'portrait');

            // Renderiza o HTML para PDF
            $dompdf->render();

            // Envia o PDF para o navegador
            $dompdf->stream("orcamento_{$id}.pdf", ["Attachment" => false]); // false para abrir no navegador

        } catch (\Exception $e) {
            die("Erro ao gerar PDF: " . $e->getMessage());
        }
    }
}
