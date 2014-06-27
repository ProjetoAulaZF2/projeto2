Segundo projeto das aulas de Zend Framework 2 com Nataniel Paiva
=======================

Introdução
------------

Esse segundo projeto contempla os seguintes tópicos:

* Criar uma base de dados e uma tabela para realizar um CRUD.
* Conexão com uma base de dados, podendo utilizar a TableGatway.
* Listagem de dados



Criação da base de dados
--------------------------

Execute esse comando em seu Mysql para criar o banco e a tabela que iremos utilizar:
~~~sql
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
~~~
Conexão do ZF2 com a base de dados Mysql
------------------------------------------

Para realizar uma conexão com a base de dados mysql edite o arquivo projeto2/config/autoload/global.php e coloque o seguinte código:
~~~php	
	<?php
	return array(
	    'db' => array(
		'driver'         => 'Pdo',
		'dsn'            => 'mysql:dbname=db_projeto2;host=localhost',
		'driver_options' => array(
		    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\''
		),
	    ),
	    'service_manager' => array(
		'factories' => array(
		    'Zend\Db\Adapter\Adapter'
		            => 'Zend\Db\Adapter\AdapterServiceFactory',
		),
	    ),
	);
~~~
Modifique o arquivo projeto2/config/autoload/local.php também com os parametros do seu banco local:
~~~php
	<?php
	return array(
	    'db' => array(
		'username' => 'root',
		'password' => 'root',
	    ),
	);
~~~
Listagem de registros
------------------------------------------

Vamos inserir alguns registros em nossa tabela para criar a listagem:
~~~sql
	INSERT INTO `db_projeto2`.`tb_celular` (`marca`, `modelo`, `ativo`) VALUES ('Samsung', 'Galaxy 5', '1');
	INSERT INTO `db_projeto2`.`tb_celular` (`id`, `marca`, `modelo`, `ativo`) VALUES ('', 'Motorola', 'Moto G', '1');
	INSERT INTO `db_projeto2`.`tb_celular` (`id`, `marca`, `modelo`, `ativo`) VALUES ('', 'Nokia', 'Lumia', '1');
~~~

Para criarmos nossa primeira listagem teremos que criar alguns arquivos.
O primeiro arquivo que iremos criar é o projeto2/module/Celular/src/Celular/Model/Celular.php com o seguinte código:
~~~php
	<?php
	namespace Celular\Model;

	class Celular
	{

	    public $id;

	    public $marca;

	    public $modelo;

	    public $ativo;


	    public function exchangeArray($data)
	    {
		$this->id = (isset($data['id'])) ? $data['id'] : null;
		$this->marca = (isset($data['marca'])) ? $data['marca'] : null;
		$this->modelo = (isset($data['modelo'])) ? $data['modelo'] : null;
		$this->ativo = (isset($data['ativo'])) ? $data['ativo'] : null;
	    }

	}
~~~
O segundo arquivo necessário é o projeto2/module/Celular/src/Celular/Model/CelularTable.php com o seguinte código:
~~~php
	<?php
	namespace Celular\Model;

	use Zend\Db\Sql\Select;
	use Zend\Db\TableGateway\TableGateway;
	use Zend\Db\Adapter\Adapter;
	use Zend\Db\ResultSet\ResultSet;

	class CelularTable
	{

	    protected $tableGateway;

	    const ATIVO = 1;

	    public function __construct(TableGateway $tableGateway)
	    {
		$this->tableGateway = $tableGateway;
	    }

	    public function fetchAll()
	    {
		$resultSet = $this->tableGateway->select();
		return $resultSet;
	    }

	    public function getCelular($id)
	    {
		$id = (int) $id;
		$rowset = $this->tableGateway->select(array(
		    'id' => $id
		));
		$row = $rowset->current();
		if (! $row) {
		    throw new \Exception("Não existe linha no banco para este id $id");
		}
		return $row;
	    }
	}
~~~
Também é necessário configurar o arquivo projeto2/module/Celular/Module.php inserindo o seguinte método:
~~~php
	//Coloque essas namespaces
	use Celular\Model\Celular;
	use Celular\Model\CelularTable;
	use Zend\Db\ResultSet\ResultSet;
	use Zend\Db\TableGateway\TableGateway;

	// Coloque esse método
	 public function getServiceConfig()
	    {
	    	return array(
	    			'factories' => array(
	    					'Celular\Model\CelularTable' =>  function($sm) {
	    						$tableGateway = $sm->get('CelularTableGateway');
	    						$table = new CelularTable($tableGateway);
	    						return $table;
	    					},
	    					'CelularTableGateway' => function ($sm) {
	    						$dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
	    						$resultSetPrototype = new ResultSet();
	    						$resultSetPrototype->setArrayObjectPrototype(new Celular());
	    						return new TableGateway('tb_celular', $dbAdapter, null, $resultSetPrototype);
	    					},
	    			),
	    	);
	    }
~~~

Sua controller vai ficar assim:
~~~php
	<?php
	namespace Celular\Controller;

	use Zend\Mvc\Controller\AbstractActionController;
	use Zend\View\Model\ViewModel;

	class IndexController extends AbstractActionController
	{
	    protected $celularTable;
	    
	    public function indexAction()
	    {
		return new ViewModel(array(
		    'celulares' => $this->getCelularTable()->fetchAll(),
		));
	    }
	    //Crie esse método
	    public function getCelularTable()
	    {
	    	if (!$this->celularTable) {
	    		$sm = $this->getServiceLocator();
	    		$this->celularTable = $sm->get('Celular\Model\CelularTable');
	    	}
	    	return $this->celularTable;
	    }
	}
~~~

Pronto! Assim conseguiremos conectar com nossa base e criar uma simples listagem no Zend Framework 2.
É muito simples fazer isso, e o melhor é que desenvolvemos isso tudo totalmente com OO.
Se você preferir pode baixar o projeto 2 que está contido todas essas alterações que fizemos.
Agora iremos para o projeto 3 e dar continuidade as nossas aulas.
















