-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 17/08/2025 às 00:13
-- Versão do servidor: 8.0.41
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `d2tech_informatica`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `categorias`
--

CREATE TABLE `categorias` (
  `id_categoria` int NOT NULL,
  `nome` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `categorias`
--

INSERT INTO `categorias` (`id_categoria`, `nome`) VALUES
(4, 'Armazenamento SSD'),
(6, 'Fonte de Alimentação'),
(3, 'Memória RAM'),
(2, 'Placa de Vídeo'),
(5, 'Placa-Mãe'),
(1, 'Processador');

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes`
--

CREATE TABLE `clientes` (
  `id_cliente` int NOT NULL,
  `cpf` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  `nome` varchar(150) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `clientes_enderecos`
--

CREATE TABLE `clientes_enderecos` (
  `id_endereco` int NOT NULL,
  `cliente_id` int NOT NULL,
  `telefone` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `endereco_completo` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pedidos`
--

CREATE TABLE `pedidos` (
  `id_pedido` int NOT NULL,
  `cliente_id` int NOT NULL,
  `data_pedido` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('pendente','pago','enviado','cancelado') COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'pendente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `produtos`
--

CREATE TABLE `produtos` (
  `id_produto` int NOT NULL,
  `nome` varchar(150) COLLATE utf8mb4_general_ci NOT NULL,
  `descricao` text COLLATE utf8mb4_general_ci,
  `preco` decimal(10,2) NOT NULL,
  `estoque` int NOT NULL DEFAULT '0',
  `categoria_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `produtos`
--

INSERT INTO `produtos` (`id_produto`, `nome`, `descricao`, `preco`, `estoque`, `categoria_id`) VALUES
(1, 'Processador Intel Core I5 13400', 'Processador intel core i5 de ultima geração.', 2300.00, 5, 1);

-- --------------------------------------------------------

--
-- Estrutura para tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int NOT NULL,
  `login` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `senha` varchar(255) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `login`, `senha`) VALUES
(1, 'admin', '$2y$10$rZkL5C1CNZ/sHnjJeYo7n.GMY/0Ry3UharSoSidY3SVh3veSXl/l6');

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id_categoria`),
  ADD UNIQUE KEY `nome_categoria_unico` (`nome`);

--
-- Índices de tabela `clientes`
--
ALTER TABLE `clientes`
  ADD PRIMARY KEY (`id_cliente`),
  ADD UNIQUE KEY `cpf_unico` (`cpf`);

--
-- Índices de tabela `clientes_enderecos`
--
ALTER TABLE `clientes_enderecos`
  ADD PRIMARY KEY (`id_endereco`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices de tabela `pedidos`
--
ALTER TABLE `pedidos`
  ADD PRIMARY KEY (`id_pedido`),
  ADD KEY `cliente_id` (`cliente_id`);

--
-- Índices de tabela `produtos`
--
ALTER TABLE `produtos`
  ADD PRIMARY KEY (`id_produto`),
  ADD KEY `categoria_id` (`categoria_id`);

--
-- Índices de tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `login_unico` (`login`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id_categoria` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de tabela `clientes`
--
ALTER TABLE `clientes`
  MODIFY `id_cliente` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `clientes_enderecos`
--
ALTER TABLE `clientes_enderecos`
  MODIFY `id_endereco` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `pedidos`
--
ALTER TABLE `pedidos`
  MODIFY `id_pedido` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de tabela `produtos`
--
ALTER TABLE `produtos`
  MODIFY `id_produto` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `clientes_enderecos`
--
ALTER TABLE `clientes_enderecos`
  ADD CONSTRAINT `clientes_enderecos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id_cliente`) ON DELETE CASCADE;

--
-- Restrições para tabelas `pedidos`
--
ALTER TABLE `pedidos`
  ADD CONSTRAINT `pedidos_ibfk_1` FOREIGN KEY (`cliente_id`) REFERENCES `clientes` (`id_cliente`);

--
-- Restrições para tabelas `produtos`
--
ALTER TABLE `produtos`
  ADD CONSTRAINT `produtos_ibfk_1` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id_categoria`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
