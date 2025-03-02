-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Tempo de geração: 02/03/2025 às 03:49
-- Versão do servidor: 9.1.0
-- Versão do PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `igreja`
--
CREATE DATABASE IF NOT EXISTS `igreja` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci;
USE `igreja`;

-- --------------------------------------------------------

--
-- Estrutura para tabela `dias_culto`
--

DROP TABLE IF EXISTS `dias_culto`;
CREATE TABLE IF NOT EXISTS `dias_culto` (
  `id` int NOT NULL AUTO_INCREMENT,
  `igreja_id` int DEFAULT NULL,
  `dia_semana` enum('segunda','terca','quarta','quinta','sexta','sabado','domingo') NOT NULL,
  `horario` enum('manha','tarde','noite') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `igreja_id` (`igreja_id`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `dias_culto`
--

INSERT INTO `dias_culto` (`id`, `igreja_id`, `dia_semana`, `horario`) VALUES
(9, 1, 'quinta', 'noite'),
(2, 1, 'domingo', 'tarde'),
(3, 1, 'domingo', 'noite'),
(4, 2, 'quarta', 'noite'),
(5, 2, 'quinta', 'tarde'),
(6, 2, 'sabado', 'noite'),
(7, 2, 'domingo', 'manha'),
(8, 2, 'domingo', 'noite');

-- --------------------------------------------------------

--
-- Estrutura para tabela `igreja`
--

DROP TABLE IF EXISTS `igreja`;
CREATE TABLE IF NOT EXISTS `igreja` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `endereco` varchar(255) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `igreja`
--

INSERT INTO `igreja` (`id`, `nome`, `endereco`, `telefone`) VALUES
(1, 'Jussara', 'Avenida Jussara', ''),
(2, 'Agenor de Campos', 'Avenida Nossa Senhora de Fatima', '');

-- --------------------------------------------------------

--
-- Estrutura para tabela `portas`
--

DROP TABLE IF EXISTS `portas`;
CREATE TABLE IF NOT EXISTS `portas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `igreja_id` int DEFAULT NULL,
  `quantidade` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `igreja_id` (`igreja_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `portas`
--

INSERT INTO `portas` (`id`, `igreja_id`, `quantidade`) VALUES
(1, 1, 1),
(2, 2, 3);

-- --------------------------------------------------------

--
-- Estrutura para tabela `porteiros`
--

DROP TABLE IF EXISTS `porteiros`;
CREATE TABLE IF NOT EXISTS `porteiros` (
  `id` int NOT NULL AUTO_INCREMENT,
  `igreja_id` int DEFAULT NULL,
  `nome` varchar(255) NOT NULL,
  `telefone` varchar(15) DEFAULT NULL,
  `genero` enum('homem','mulher') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `igreja_id` (`igreja_id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Despejando dados para a tabela `porteiros`
--

INSERT INTO `porteiros` (`id`, `igreja_id`, `nome`, `telefone`, `genero`) VALUES
(2, 2, 'Miguel', '(13) 00000-0002', 'homem'),
(65, 1, 'Solange', '(13) 00000-0005', 'mulher'),
(64, 1, 'Marina', '(13) 00000-0004', 'mulher'),
(63, 1, 'Lilian', '(13) 00000-0003', 'mulher'),
(62, 1, 'Maria', '(13) 00000-0002', 'mulher'),
(61, 1, 'Maíra', '(13) 00000-0001', 'mulher'),
(22, 2, 'Daniel', '(13) 22222-2222', 'homem'),
(23, 2, 'Rafael', '(13) 23232-3232', 'homem'),
(24, 2, 'Gabriel', '(13) 24242-4242', 'homem'),
(25, 2, 'Samuel', '(13) 25252-5252', 'homem'),
(26, 2, 'Eduardo', '(13) 26262-6262', 'homem'),
(27, 2, 'Bruno', '(13) 27272-7272', 'homem'),
(28, 2, 'Leonardo', '(13) 28282-8282', 'homem'),
(29, 2, 'Thiago', '(13) 29292-9292', 'homem'),
(30, 2, 'Vinicius', '(13) 30303-0303', 'homem'),
(31, 2, 'Paulo', '(13) 31313-1313', 'homem'),
(32, 2, 'Roberto', '(13) 32323-2323', 'homem'),
(33, 2, 'Ricardo', '(13) 33333-3333', 'homem'),
(34, 2, 'Marcelo', '(13) 34343-4343', 'homem'),
(35, 2, 'Alexandre', '(13) 35353-5353', 'homem'),
(36, 2, 'Guilherme', '(13) 36363-6363', 'homem'),
(37, 2, 'Diego', '(13) 37373-7373', 'homem'),
(38, 2, 'Rodrigo', '(13) 38383-8383', 'homem'),
(39, 2, 'Fábio', '(13) 39393-9393', 'homem'),
(40, 2, 'César', '(13) 40404-0404', 'homem'),
(41, 2, 'Beatriz', '(13) 41414-1414', 'mulher'),
(42, 2, 'Carolina', '(13) 42424-2424', 'mulher'),
(43, 2, 'Mariana', '(13) 43434-3434', 'mulher'),
(44, 2, 'Patrícia', '(13) 44444-4444', 'mulher'),
(45, 2, 'Vanessa', '(13) 45454-5454', 'mulher'),
(46, 2, 'Tatiane', '(13) 46464-6464', 'mulher'),
(47, 2, 'Larissa', '(13) 47474-7474', 'mulher'),
(48, 2, 'Priscila', '(13) 48484-8484', 'mulher'),
(49, 2, 'Monique', '(13) 49494-9494', 'mulher'),
(50, 2, 'Bianca', '(13) 50505-0505', 'mulher'),
(51, 2, 'Renata', '(13) 51515-1515', 'mulher'),
(52, 2, 'Daniela', '(13) 52525-2525', 'mulher'),
(53, 2, 'Aline', '(13) 53535-3535', 'mulher'),
(54, 2, 'Sandra', '(13) 54545-4545', 'mulher'),
(55, 2, 'Cristina', '(13) 55555-5555', 'mulher'),
(56, 2, 'Elaine', '(13) 56565-6565', 'mulher'),
(57, 2, 'Márcia', '(13) 57575-7575', 'mulher'),
(58, 2, 'Lúcia', '(13) 58585-8585', 'mulher'),
(59, 2, 'Helena', '(13) 59595-9595', 'mulher'),
(60, 2, 'Sônia', '(13) 60606-0606', 'mulher');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
