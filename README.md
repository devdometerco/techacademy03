# Projeto D2tech - Loja de Informática

Sistema de e-commerce desenvolvido como projeto acadêmico, com o objetivo de aplicar conceitos de PHP Orientado a Objetos, banco de dados MySQL e arquitetura MVC.

## Descrição do Propósito

Este sistema simula o back-end e a área administrativa de uma loja virtual para venda de itens de informática. A primeira etapa do projeto inclui o sistema de login para administradores e o CRUD (criação, leitura, atualização e exclusão) de produtos.

## Instruções de Instalação e Execução

Siga os passos abaixo para executar o projeto em um ambiente de desenvolvimento local.

**Pré-requisitos:**
* PHP 7.4 ou superior
* MySQL ou MariaDB
* Composer

**Passos:**

1.  **Clone o repositório:**
    ```sh
    git clone [https://github.com/devdometerco/techacademy03.git](https://github.com/devdometerco/techacademy03.git)
    ```

2.  **Acesse a pasta do projeto:**
    ```sh
    cd techacademy03
    ```

3.  **Instale as dependências do Composer:**
    ```sh
    composer install
    ```

4.  **Banco de Dados:**
    * Crie um novo banco de dados no seu MySQL (ex: `d2tech_informatica`).
    * Importe o arquivo `database/d2tech_informatica.sql` para o banco de dados que você acabou de criar. Isso criará todas as tabelas e inserirá os dados iniciais.

5.  **Arquivo de Ambiente:**
    * Na raiz do projeto, crie uma cópia do arquivo `.env.example` (se você tiver um) ou crie um novo arquivo chamado `.env`.
    * Configure as variáveis de ambiente no arquivo `.env` com as suas credenciais do banco de dados:
        ```env
        DB_HOST=localhost
        DB_DATABASE=d2tech_informatica
        DB_USER=seu_usuario_mysql
        DB_PASSWORD=sua_senha_mysql
        ```

6.  **Execute o projeto:**
    * Configure um servidor web como Apache (XAMPP) para apontar a raiz do documento para a pasta `/public` do projeto.
    * Ou, para uma solução mais rápida, use o servidor embutido do PHP. Navegue até a raiz do projeto no terminal e rode:
        ```sh
        php -S localhost:8000 -t public
        ```
    * Acesse `http://localhost:8000` no seu navegador.

**Credenciais de Acesso (Admin):**
* **Usuário:** `admin`
* **Senha:** `admin123`

## Nome>

* Otávio Augusto Dometerco
