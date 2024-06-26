-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 29-Maio-2024 às 19:04
-- Versão do servidor: 10.4.32-MariaDB
-- versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `fastimoveis`
--

-- --------------------------------------------------------

--
-- Estrutura da tabela `imoveis`
--

CREATE TABLE `imoveis` (
  `id` int(20) NOT NULL,
  `cidade` varchar(100) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `categoria` varchar(50) DEFAULT NULL,
  `preco` decimal(10,2) NOT NULL,
  `nome_vendedor` varchar(255) NOT NULL,
  `telefone_vendedor` varchar(20) NOT NULL,
  `email_vendedor` varchar(255) NOT NULL,
  `status` varchar(30) DEFAULT NULL,
  `foto` varchar(255) NOT NULL,
  `descricao` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `imoveis`
--

INSERT INTO `imoveis` (`id`, `cidade`, `endereco`, `categoria`, `preco`, `nome_vendedor`, `telefone_vendedor`, `email_vendedor`, `status`, `foto`, `descricao`) VALUES
(1, 'Praia Grande', 'avenida presidente wilson, 178, ', 'Casa', 150000.00, 'Lucas Santos', '3490-8375', 'santos@gmail.com', 'à_venda', 'img/casa3.jpg', 'Casa'),
(2, 'São Vicente', 'avenida presidente kennedy, 390, ', 'apartamento', 200000.00, 'Maria Alves', '3380-9084', 'maria123@gmail.com', 'à venda', 'img/casa1.jpg', NULL),
(3, 'Santos', 'rua domingos costa, 400, ', 'Casa', 100000.50, 'Iago Teixeira', '3267-9327', 'teixeira@gmail.com', 'à venda', 'img/casa2.jpg', NULL),
(4, 'Santos', 'rua padre gastão, 1340, ', 'Casa', 300000.80, 'Joaquim Maia', '98250-3798', 'joaquim980@gmail.com', 'à venda', 'img/decoracao-casa-moderna-casa-j-a-fachada-externa-revisitearquiteturaeconstru-295624-proportional-height_cover_medium.jpg', NULL),
(5, 'Santos', 'avenida paulista, 5708, ', 'apartamento', 980000.00, 'Alfred Silva', '98720-8054', 'alfredsilva@gmail.com', 'à venda', 'img/ed2.jpg', NULL),
(6, 'Santos', 'rua saldanha da gama, 370, ', 'apartamento', 300000.00, 'João Simões', '3289-2157', 'johnsimoes@gmail.com', 'à venda', 'img/mansao.jpg', NULL),
(7, 'Santos', 'rua doutor jaime oliveira, 250, ', 'Casa', 350000.00, 'Sophia Alves', '96703-0548', 'alves2802@gmail.com', 'à venda', 'img/mansao_2.png', NULL),
(8, 'Santos', 'avenida guido mangioca, 99, ', 'Casa', 150000.00, 'Marvado', '13997655041', 'lucas.mendes16@fatec.sp.gov.br', 'à_venda', 'img/peruibe.jpg', NULL),
(9, 'Santos', 'aaaaaa', 'Casa', 1234.00, '1234', '1234', '1234', 'à_venda', 'img/casa3.jpg', NULL),
(10, 'Santos', 'Rua Rogers, 231', 'Casa', 300000.00, 'Tony Stark', '(13)32459087', 'tonystark@gmail.com', 'à_venda', 'img/baxter.jpg', NULL);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(20) NOT NULL,
  `nome` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `isAdmin` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Extraindo dados da tabela `usuarios`
--

INSERT INTO `usuarios` (`id`, `nome`, `email`, `senha`, `isAdmin`) VALUES
(4, 'João', 'joao@gmail.com', 'dccd96c256bc7dd39bae41a405f25e43', 1);

--
-- Índices para tabelas despejadas
--

--
-- Índices para tabela `imoveis`
--
ALTER TABLE `imoveis`
  ADD PRIMARY KEY (`id`);

--
-- Índices para tabela `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `imoveis`
--
ALTER TABLE `imoveis`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de tabela `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
