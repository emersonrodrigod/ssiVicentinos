-- phpMyAdmin SQL Dump
-- version 4.0.4
-- http://www.phpmyadmin.net
--
-- Máquina: localhost
-- Data de Criação: 12-Fev-2014 às 20:01
-- Versão do servidor: 5.6.12-log
-- versão do PHP: 5.4.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de Dados: `ssi`
--
CREATE DATABASE IF NOT EXISTS `ssi` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ssi`;

-- --------------------------------------------------------

--
-- Estrutura da tabela `departamento`
--

CREATE TABLE IF NOT EXISTS `departamento` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(155) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT '1',
  `id_empresa` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_departamento_empresa_idx` (`id_empresa`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `departamento`
--

INSERT INTO `departamento` (`id`, `nome`, `ativo`, `id_empresa`) VALUES
(1, 'TI', 1, 1),
(2, 'Administração', 1, 1),
(3, 'Recursos Humanos', 1, 1),
(4, 'PCP', 1, 1),
(5, 'Comercial', 1, 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `empresa`
--

CREATE TABLE IF NOT EXISTS `empresa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `empresa`
--

INSERT INTO `empresa` (`id`, `nome`, `ativo`) VALUES
(1, 'Vicentinos do Brasil Ltda', 1),
(2, 'Vicentinos Industrial Química Ltda', 1),
(3, 'Loydi embalagens Plásticas Ltda', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `historico_ssi`
--

CREATE TABLE IF NOT EXISTS `historico_ssi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataHora` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `descricao` varchar(255) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_ssi` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_historico_usuario_idx` (`id_usuario`),
  KEY `fk_historico_ssi_idx` (`id_ssi`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3 ;

--
-- Extraindo dados da tabela `historico_ssi`
--

INSERT INTO `historico_ssi` (`id`, `dataHora`, `descricao`, `id_usuario`, `id_ssi`) VALUES
(1, '2014-02-12 17:53:11', 'Solicitação cadastrada no sistema.', 3, 4),
(2, '2014-02-12 18:26:18', 'Solicitação cadastrada no sistema.', 3, 5);

-- --------------------------------------------------------

--
-- Estrutura da tabela `log_acessos`
--

CREATE TABLE IF NOT EXISTS `log_acessos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataEntrada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `dataSaida` timestamp NULL DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_log_acessos_usuario_idx` (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Estrutura da tabela `processo`
--

CREATE TABLE IF NOT EXISTS `processo` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(255) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `processo`
--

INSERT INTO `processo` (`id`, `nome`, `ativo`) VALUES
(1, 'Operação do computador', 1),
(2, 'Internet', 1),
(3, 'Planilhas Excel', 1),
(4, 'Documentos Word', 1),
(5, 'Manutenção de Pastas e Permissões Servidor', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `ssi`
--

CREATE TABLE IF NOT EXISTS `ssi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `dataAbertura` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `resumo` varchar(200) NOT NULL,
  `descricao` text NOT NULL,
  `previsaoEncerramento` timestamp NULL DEFAULT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'PENDENTE',
  `dataEncerramento` timestamp NULL DEFAULT NULL,
  `id_usuario` int(11) NOT NULL,
  `id_responsavel` int(11) DEFAULT NULL,
  `id_tipo` int(11) DEFAULT NULL,
  `id_processo` int(11) DEFAULT NULL,
  `id_empresa` int(11) NOT NULL,
  `id_departamento` int(11) NOT NULL,
  `id_usuario_abertura` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ssi_usuario_abertura_idx` (`id_usuario`),
  KEY `fk_ssi_usuario_responsável_idx` (`id_responsavel`),
  KEY `fk_ssi_tipo_ssi_idx` (`id_tipo`),
  KEY `fk_processo_ssi` (`id_processo`),
  KEY `fk_empresa_ssi` (`id_empresa`),
  KEY `fk_departamento_ssi` (`id_departamento`),
  KEY `fk_usuario_abertura` (`id_usuario_abertura`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Extraindo dados da tabela `ssi`
--

INSERT INTO `ssi` (`id`, `dataAbertura`, `resumo`, `descricao`, `previsaoEncerramento`, `status`, `dataEncerramento`, `id_usuario`, `id_responsavel`, `id_tipo`, `id_processo`, `id_empresa`, `id_departamento`, `id_usuario_abertura`) VALUES
(1, '2014-02-11 13:37:18', 'Teste de inclusao de chamados', 'esse chamado foi incluido no sistema apenas para testar os filtros da pesquisa que esta sendo desenvolvida', NULL, 'PENDENTE', NULL, 1, 1, 1, 1, 1, 1, 1),
(4, '2014-02-12 17:53:11', 'Teste na interface', 'Esse teste ja foi incluso pela interface do usuário.\r\n\r\nVerificando se o cadastro já pode ser realizado!', NULL, 'PENDENTE', NULL, 3, NULL, NULL, NULL, 1, 1, 1),
(5, '2014-02-12 18:26:18', 'Teste de inclusão de Solicitação por parte do usuário', 'Esta solicitação foi incluída no sistema pela tela de inclusão vista pelo perfil ''USUARIO''.\r\n\r\nNeste ele precisa pegar os dados de usuário, empresa e departamento automaticamente.\r\n\r\n', NULL, 'PENDENTE', NULL, 3, NULL, NULL, NULL, 1, 1, 3);

-- --------------------------------------------------------

--
-- Estrutura da tabela `tipo_ssi`
--

CREATE TABLE IF NOT EXISTS `tipo_ssi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Extraindo dados da tabela `tipo_ssi`
--

INSERT INTO `tipo_ssi` (`id`, `nome`, `ativo`) VALUES
(1, 'Pequena manutenção', 1),
(2, 'Projeto', 1),
(3, 'Dúvida', 1),
(4, 'Treinamento', 1);

-- --------------------------------------------------------

--
-- Estrutura da tabela `usuario`
--

CREATE TABLE IF NOT EXISTS `usuario` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nome` varchar(100) NOT NULL,
  `email` varchar(200) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `ativo` int(11) NOT NULL DEFAULT '1',
  `dataCadastro` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `role` varchar(45) NOT NULL,
  `id_empresa` int(11) DEFAULT NULL,
  `id_departamento` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  KEY `fk_usuario_empresa_idx` (`id_empresa`),
  KEY `fk_usuario_departamento_idx` (`id_departamento`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Extraindo dados da tabela `usuario`
--

INSERT INTO `usuario` (`id`, `nome`, `email`, `senha`, `ativo`, `dataCadastro`, `role`, `id_empresa`, `id_departamento`) VALUES
(1, 'Emerson', 'emersonrodrigod@gmail.com', '779a923d69b2e072747b11975ba86949de167037', 1, '2013-10-23 13:02:55', 'admin', 1, 1),
(3, 'Usuário Teste', 'ti@vicentinos.com.br', '779a923d69b2e072747b11975ba86949de167037', 1, '2013-11-26 12:47:56', 'usuario', 1, 1);

--
-- Constraints for dumped tables
--

--
-- Limitadores para a tabela `departamento`
--
ALTER TABLE `departamento`
  ADD CONSTRAINT `fk_departamento_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `historico_ssi`
--
ALTER TABLE `historico_ssi`
  ADD CONSTRAINT `fk_historico_ssi` FOREIGN KEY (`id_ssi`) REFERENCES `ssi` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_historico_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `log_acessos`
--
ALTER TABLE `log_acessos`
  ADD CONSTRAINT `fk_log_acessos_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Limitadores para a tabela `ssi`
--
ALTER TABLE `ssi`
  ADD CONSTRAINT `fk_usuario_abertura` FOREIGN KEY (`id_usuario_abertura`) REFERENCES `usuario` (`id`),
  ADD CONSTRAINT `fk_departamento_ssi` FOREIGN KEY (`id_departamento`) REFERENCES `departamento` (`id`),
  ADD CONSTRAINT `fk_empresa_ssi` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`),
  ADD CONSTRAINT `fk_processo_ssi` FOREIGN KEY (`id_processo`) REFERENCES `processo` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ssi_tipo_ssi` FOREIGN KEY (`id_tipo`) REFERENCES `tipo_ssi` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ssi_usuario_abertura` FOREIGN KEY (`id_usuario`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_ssi_usuario_responsável` FOREIGN KEY (`id_responsavel`) REFERENCES `usuario` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Limitadores para a tabela `usuario`
--
ALTER TABLE `usuario`
  ADD CONSTRAINT `fk_usuario_departamento` FOREIGN KEY (`id_departamento`) REFERENCES `departamento` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_usuario_empresa` FOREIGN KEY (`id_empresa`) REFERENCES `empresa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
