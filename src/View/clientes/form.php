<?php
// Definir se estamos em modo de edição ou criação
$isEditing = isset($cliente) && $cliente['id_cliente'];

// Definer a URL da ação do formulário (ou vai para 'store' para criar, ou 'update' para atualizar)
$actionUrl = $isEditing 
    ? "index.php?action=cliente_update&id={$cliente['id_cliente']}" 
    : "index.php?action=cliente_store";


$pageTitle = $isEditing ? 'Editar' : 'Cadastrar';
?>

<?php require __DIR__ . '/../templates/header.php'; ?>
        <h1><?php echo $pageTitle; ?> Cliente</h1>
        <a href="index.php?action=clientes">Voltar para a Lista</a>
        <hr>

        <form action="<?php echo $actionUrl; ?>" method="post">
            
            <?php if ($isEditing): ?>
                <input type="hidden" name="tipo_pessoa" value="<?php echo $cliente['tipo_pessoa']; ?>">
            <?php endif; ?>

            <div class="form-group">
                <label>Tipo de Pessoa:</label>
                <input type="radio" name="tipo_pessoa" value="PF" id="tipoPF" 
                    <?php if ($isEditing && $cliente['tipo_pessoa'] == 'PJ') echo 'disabled'; ?>
                    <?php echo (isset($cliente['tipo_pessoa']) && $cliente['tipo_pessoa'] == 'PF') || !$isEditing ? 'checked' : ''; ?>> 
                <label for="tipoPF">Pessoa Física</label>
                
                <input type="radio" name="tipo_pessoa" value="PJ" id="tipoPJ"
                    <?php if ($isEditing && $cliente['tipo_pessoa'] == 'PF') echo 'disabled'; ?>
                    <?php echo (isset($cliente['tipo_pessoa']) && $cliente['tipo_pessoa'] == 'PJ') ? 'checked' : ''; ?>> 
                <label for="tipoPJ">Pessoa Jurídica</label>
            </div>

            <div id="camposPF">
                <div class="form-group">
                    <label for="nome">Nome Completo:</label>
                    <input type="text" id="nome" name="nome" value="<?php echo $cliente['nome'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="cpf">CPF:</label>
                    <input type="text" id="cpf" name="cpf" value="<?php echo $cliente['cpf'] ?? ''; ?>">
                </div>
            </div>

            <div id="camposPJ">
                <div class="form-group">
                    <label for="cnpj">CNPJ:</label>
                    <input type="text" id="cnpj" name="cnpj" value="<?php echo $cliente['cnpj'] ?? ''; ?>">
                    <?php if (!$isEditing): ?>
                        <button type="button" id="buscarCnpj">Buscar Dados</button> 
                    <?php endif; ?>
                </div>
                <div class="form-group">
                    <label for="razao_social">Razão Social:</label>
                    <input type="text" id="razao_social" name="razao_social" value="<?php echo $cliente['razao_social'] ?? ''; ?>">
                </div>
                <div class="form-group">
                    <label for="nome_fantasia">Nome Fantasia:</label>
                    <input type="text" id="nome_fantasia" name="nome_fantasia" value="<?php echo $cliente['nome_fantasia'] ?? ''; ?>">
                </div>
            </div>

            <hr>

            <div class="form-group">
                <label for="email">E-mail:</label>
                <input type="email" id="email" name="email" value="<?php echo $cliente['email'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label for="telefone">Telefone:</label>
                <input type="text" id="telefone" name="telefone" value="<?php echo $cliente['telefone'] ?? ''; ?>">
            </div>
            <div class="form-group">
                <label for="endereco">Endereço Completo:</label>
                <input type="text" id="endereco" name="endereco" value="<?php echo $cliente['endereco'] ?? ''; ?>">
            </div>
            
            <button type="submit" class="btn btn-success">Salvar Cliente</button>
            <button type="reset" class="btn btn-secondary">Limpar Campos</button>
        </form>
    </div>

    <script>
        // Função para mostrar/esconder os campos corretos
        function toggleFields(tipo) {
            if (tipo === 'PF') {
                document.getElementById('camposPF').style.display = 'block';
                document.getElementById('camposPJ').style.display = 'none';
            } else {
                document.getElementById('camposPF').style.display = 'none';
                document.getElementById('camposPJ').style.display = 'block';
            }
        }

        // Adiciona o evento de troca nos botões de rádio
        document.querySelectorAll('input[name="tipo_pessoa"]').forEach(radio => {
            radio.addEventListener('change', function () {
                toggleFields(this.value);
            });
        });
        
        // VERIFICAÇÃO INICIAL: Ao carregar a página, já mostra os campos corretos
        document.addEventListener('DOMContentLoaded', function() {
            const tipoInicial = document.querySelector('input[name="tipo_pessoa"]:checked').value;
            toggleFields(tipoInicial);
        });

        // Lógica da API do CNPJ (só adiciona o evento se o botão existir)
        const botaoBuscar = document.getElementById('buscarCnpj');
        if (botaoBuscar) {
            botaoBuscar.addEventListener('click', function() {
                // ... (aqui vai o código completo da busca do CNPJ que já tínhamos)
                const campoCnpj = document.getElementById('cnpj');
                const cnpj = campoCnpj.value.replace(/[^0-9]/g, '');
                if (cnpj.length !== 14) {
                    alert('Por favor, digite um CNPJ válido com 14 dígitos.');
                    return;
                }
                const url = `https://brasilapi.com.br/api/cnpj/v1/${cnpj}`;
                botaoBuscar.innerText = 'Buscando...';
                botaoBuscar.disabled = true;
                fetch(url)
                    .then(response => response.ok ? response.json() : Promise.reject('CNPJ não encontrado'))
                    .then(data => {
                        document.getElementById('razao_social').value = data.razao_social || '';
                        document.getElementById('nome_fantasia').value = data.nome_fantasia || '';
                        const enderecoCompleto = `${data.logradouro || ''}, ${data.numero || ''} - ${data.bairro || ''}, ${data.municipio || ''} - ${data.uf || ''}, CEP: ${data.cep || ''}`;
                        document.getElementById('endereco').value = enderecoCompleto;
                        alert('Dados da empresa preenchidos!');
                    })
                    .catch(error => alert(`Erro ao buscar CNPJ: ${error}`))
                    .finally(() => {
                        botaoBuscar.innerText = 'Buscar Dados';
                        botaoBuscar.disabled = false;
                    });
            });
        }
    </script>
<?php require __DIR__ . '/../templates/footer.php'; ?>