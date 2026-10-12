//Banco de dados atualizado
CREATE TABLE `artesanatos-eloi`.`admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `nome` varchar(100) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_UNIQUE` (`email`),
  UNIQUE KEY `telefone_UNIQUE` (`telefone`)
);

CREATE TABLE `artesanatos-eloi`.`cadastro` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `nome` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `reset_senha` varchar(64) DEFAULT NULL,
  `reset_expira_senha` datetime DEFAULT NULL,
  `codigo_verificacao` varchar(6) DEFAULT NULL,
  `codigo_expira` datetime DEFAULT NULL,
  `email_verificado` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_cadastro_email` (`email`),
  UNIQUE KEY `reset_senha_UNIQUE` (`reset_senha`)
);

CREATE TABLE `artesanatos-eloi`.`carrinho` (
  `pk_id_carrinho` int(11) NOT NULL AUTO_INCREMENT,
  `id_usuario` int(10) unsigned NOT NULL,
  `id_produtos` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL DEFAULT 1,
  PRIMARY KEY (`pk_id_carrinho`),
  UNIQUE KEY `unico_usuario_produto` (`id_usuario`,`id_produtos`),
  KEY `fk_carrinho_usuario_idx` (`id_usuario`),
  KEY `fk_carrinho_produtos_idx` (`id_produtos`),
  CONSTRAINT `fk_carrinho_produtos` FOREIGN KEY (`id_produtos`) REFERENCES `produtos` (`pk_id_produtos`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_carrinho_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `cadastro` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE `artesanatos-eloi`.`categorias` (
  `pk_id_categorias` int(11) NOT NULL AUTO_INCREMENT,
  `nome_categoria` varchar(45) NOT NULL,
  PRIMARY KEY (`pk_id_categorias`)
);

CREATE TABLE `artesanatos-eloi`.`endereco` (
  `pk_id_endereco` int(11) NOT NULL AUTO_INCREMENT,
  `cep` varchar(9) NOT NULL,
  `cidade` varchar(50) NOT NULL,
  `estado` char(2) NOT NULL,
  `numero_casa` int(11) NOT NULL,
  `nome_rua` varchar(100) NOT NULL,
  `nome_bairro` varchar(50) NOT NULL,
  `apartamento` varchar(20) DEFAULT NULL,
  `referencia` varchar(50) DEFAULT NULL,
  `id_usuario` int(10) unsigned NOT NULL,
  PRIMARY KEY (`pk_id_endereco`),
  KEY `fk_endereco_usuario` (`id_usuario`),
  CONSTRAINT `fk_endereco_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `cadastro` (`id`)
);

CREATE TABLE `artesanatos-eloi`.`faixa_etaria` (
  `pk_id_faixa_etaria` int(11) NOT NULL AUTO_INCREMENT,
  `nome_idade` varchar(20) NOT NULL,
  PRIMARY KEY (`pk_id_faixa_etaria`)
);

CREATE TABLE `artesanatos-eloi`.`funcionario` (
  `idfuncionario` int(11) NOT NULL AUTO_INCREMENT,
  `nomefuncionario` varchar(135) NOT NULL,
  `emailfuncionario` varchar(255) NOT NULL,
  `cpffuncionario` varchar(18) NOT NULL,
  `telefonefuncionario` varchar(15) NOT NULL,
  `datanascimento` date NOT NULL,
  `cargofuncionario` varchar(45) NOT NULL,
  `statusfuncionario` tinyint(4) NOT NULL,
  `senha` varchar(255) NOT NULL,
  `nomecontato` varchar(135) DEFAULT NULL,
  `telefonecontato` varchar(15) DEFAULT NULL,
  PRIMARY KEY (`idfuncionario`),
  UNIQUE KEY `idfuncionario_UNIQUE` (`idfuncionario`),
  UNIQUE KEY `email_funcionario_UNIQUE` (`emailfuncionario`),
  UNIQUE KEY `telefonefuncionario_UNIQUE` (`telefonefuncionario`),
  UNIQUE KEY `cpffuncionario_UNIQUE` (`cpffuncionario`)
);

CREATE TABLE `artesanatos-eloi`.`notificacoes` (
  `id_notificacao` int(11) NOT NULL AUTO_INCREMENT,
  `id_funcionario` int(11) NOT NULL,
  `acao` enum('CADASTRO','ALTERACAO','EXCLUSAO') NOT NULL,
  `tabela_afetada` varchar(50) NOT NULL,
  `id_registro` int(11) NOT NULL,
  `mensagem` varchar(255) NOT NULL,
  `data_notificacao` datetime DEFAULT current_timestamp(),
  `lida` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id_notificacao`),
  KEY `id_funcionario` (`id_funcionario`),
  CONSTRAINT `notificacoes_ibfk_1` FOREIGN KEY (`id_funcionario`) REFERENCES `funcionario` (`idfuncionario`)
);

CREATE TABLE `artesanatos-eloi`.`pagamento` (
  `pk_id_pagamento` int(11) NOT NULL AUTO_INCREMENT,
  `nome_pagamento` varchar(20) NOT NULL,
  PRIMARY KEY (`pk_id_pagamento`)
);

//Ao criar a tabela do pagamento, executar este comando abaixo
INSERT INTO pagamento (nome_pagamento)
VALUES ('Cartão');


CREATE TABLE `artesanatos-eloi`.`pedidos` (
  `pk_id_pedidos` int(11) NOT NULL AUTO_INCREMENT,
  `dt_pedido` datetime NOT NULL,
  `total_pedido` decimal(10,2) NOT NULL,
  `status_pedido` varchar(30) NOT NULL,
  `fk_id_endereco` int(11) NOT NULL,
  `fk_id_pagamento` int(11) NOT NULL,
  `id_pessoa` int(11) NOT NULL,
  `stripe_session_id` varchar(255) DEFAULT NULL,
  `status_operacional` varchar(30) NOT NULL DEFAULT 'Aguardando pagamento',
  PRIMARY KEY (`pk_id_pedidos`),
  UNIQUE KEY `uq_pedidos_stripe_session` (`stripe_session_id`),
  KEY `fk_id_endereco_idx` (`fk_id_endereco`),
  KEY `fk_id_pagamento_idx` (`fk_id_pagamento`),
  KEY `id_pessoa_idx` (`id_pessoa`),
  CONSTRAINT `fk_id_endereco` FOREIGN KEY (`fk_id_endereco`) REFERENCES `endereco` (`pk_id_endereco`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_id_pagamento` FOREIGN KEY (`fk_id_pagamento`) REFERENCES `pagamento` (`pk_id_pagamento`) ON DELETE NO ACTION ON UPDATE NO ACTION,
  CONSTRAINT `fk_pedidos_pessoa` FOREIGN KEY (`id_pessoa`) REFERENCES `pessoa` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
);

CREATE TABLE `artesanatos-eloi`.`pessoa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `cpf` varchar(11) NOT NULL,
  `dt_nasc` date NOT NULL,
  `nome` varchar(100) NOT NULL,
  `telefone` varchar(15) NOT NULL,
  `email` varchar(255) NOT NULL,
  `id_usuario` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cpf` (`cpf`),
  UNIQUE KEY `telefone` (`telefone`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_pessoa_usuario` (`id_usuario`),
  CONSTRAINT `fk_pessoa_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `cadastro` (`id`)
);

CREATE TABLE `artesanatos-eloi`.`produto_categoria` (
  `id_produto` int(11) NOT NULL,
  `id_categoria` int(11) NOT NULL,
  PRIMARY KEY (`id_produto`,`id_categoria`),
  KEY `id_categoria` (`id_categoria`),
  CONSTRAINT `produto_categoria_ibfk_1` FOREIGN KEY (`id_produto`) REFERENCES `produtos` (`pk_id_produtos`),
  CONSTRAINT `produto_categoria_ibfk_2` FOREIGN KEY (`id_categoria`) REFERENCES `categorias` (`pk_id_categorias`)
);

CREATE TABLE `artesanatos-eloi`.`produtos` (
  `pk_id_produtos` int(11) NOT NULL AUTO_INCREMENT,
  `nome_produto` varchar(50) NOT NULL,
  `preco_produto` decimal(5,2) NOT NULL,
  `descricao` varchar(500) NOT NULL,
  `foto_produto` longblob NOT NULL,
  `descricao_imagem` varchar(200) DEFAULT NULL,
  `estoque` int(5) NOT NULL,
  `desconto` decimal(4,2) DEFAULT NULL,
  `status_produto` tinyint(1) NOT NULL,
  PRIMARY KEY (`pk_id_produtos`)
);

CREATE TABLE `artesanatos-eloi`.`itens_pedido` (
  `pk_id_itens_pedido` int(11) NOT NULL AUTO_INCREMENT,
  `fk_id_pedidos` int(11) NOT NULL,
  `fk_id_produtos` int(11) NOT NULL,
  `quantidade` int(11) NOT NULL,
  `preco_unitario` decimal(10,2) NOT NULL,
  `subtotal` decimal(10,2) NOT NULL,
  PRIMARY KEY (`pk_id_itens_pedido`),
  KEY `idx_itens_pedido_pedido` (`fk_id_pedidos`),
  KEY `idx_itens_pedido_produto` (`fk_id_produtos`),
  CONSTRAINT `fk_itens_pedido_pedido` FOREIGN KEY (`fk_id_pedidos`) REFERENCES `pedidos` (`pk_id_pedidos`),
  CONSTRAINT `fk_itens_pedido_produto` FOREIGN KEY (`fk_id_produtos`) REFERENCES `produtos` (`pk_id_produtos`),
  CONSTRAINT `chk_itens_pedido_quantidade` CHECK (`quantidade` > 0)
);