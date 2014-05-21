Segundo projeto das aulas de Zend Framework 2 com Nataniel Paiva
=======================

Introdução
------------

Esse segundo projeto contempla os seguintes tópicos:

* Criar uma base de dados e uma tabela para realizar um CRUD.
* Conexão com uma base de dados, podendo utilizar a TableGatway.
* Criar formulários utilizando o Zend\Form\Form 
* Filtros e validadores
* Listagem de dados


Criação da base de dados
--------------------------

Execute esse comando em seu Mysql para criar o banco e a tabela que iremos utilizar:

	CREATE SCHEMA IF NOT EXISTS `db_projeto2` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ;

	CREATE TABLE IF NOT EXISTS `db_projeto2`.`tb_celular` (
	  `id` INT(11) NOT NULL AUTO_INCREMENT,
	  `marca` VARCHAR(100) NOT NULL,
	  `modelo` VARCHAR(100) NOT NULL,
	  `ativo` TINYINT(4) NULL DEFAULT NULL,
	  PRIMARY KEY (`id`))
	ENGINE = InnoDB
	DEFAULT CHARACTER SET = utf8
	COLLATE = utf8_general_ci;

Conexão do ZF2 com a base de dados Mysql:
------------------------------------------

Para realizar uma conexão com a base de dados mysql edite o arquivo projeto2/config/autoload/local.php e coloque o seguinte código:

	<?php

	return array(

	    'db' => array(
		            'driver' => 'Pdo',
		            'dsn' => 'mysql:dbname=db_projeto2;host=localhost',
		            'username' => 'root',
		            'password' => 'root',
		            'driver_options' => array(
		                            PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
		            ),
	    ),
	    'display_startup_errors' => true,
	    'display_errors' => true
	);









