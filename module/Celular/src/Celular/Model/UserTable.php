<?php
namespace User\Model;

use Zend\Db\Sql\Select;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Adapter\Adapter;
use Zend\Db\ResultSet\ResultSet;

class UserTable
{
	protected $tableGateway;
	const ATIVO = 1;

	public function __construct(TableGateway $tableGateway)
	{
		$this->tableGateway = $tableGateway;
	}

	public function fetchAll()
	{
		$select = new Select();
		$select->from('tb_usuario')
		->columns(array('id','nome', 'email', 'login', 'senha'))
		->join(array('p'=>'tb_perfil'), 'tb_usuario.perfil_id = p.id',
				array( 'perfil_id' => 'id', 'nome_perfil' => 'nome' ))
		->where( array( 'tb_usuario.ativo' => UserTable::ATIVO ) );

		$resultSet = $this->tableGateway->selectWith($select);
		return $resultSet;
	}

	public function getUser($id)
	{
		$id = (int) $id;

		$select = new Select();
		$select->from('tb_usuario')
		->columns(array('*'))
		->join(array('p'=>'tb_perfil'), 'tb_usuario.perfil_id = p.id',
				array('nome_perfil'=>'nome'))
		->where(array('tb_usuario.id' => $id));

		$rowset = $this->tableGateway->selectWith($select);
		$row = $rowset->current();
		return $row;
	}

	public function getUserIndentity( $login )
	{
		$select = new Select();
		$select->from('tb_usuario')
		       ->columns(array( 'id', 'nome', 'perfil_id' ))
		       ->where( array('ativo' => 1) )
		       ->where( array( 'login' => $login ) );

		$rowset = $this->tableGateway->selectWith($select);
		$row    = $rowset->current();
		return $row;
	}

	public function saveUser(User $user)
	{
		$data = array(
			'nome' => $user->name,
			'perfil_id' => $user->roleUser->id,
		    'login'     => $user->login,
		    'senha'  => md5($user->password),
		    'codigo_celula' => $user->cod_cell,
		    'ativo'  => UserTable::ATIVO
		);
		$id = $user->id;
		if (!$this->getUser($id))
		{
			$data['id'] = $id;
			$this->tableGateway->insert($data);
		}
		else
		{
			$this->tableGateway->update($data, array('id' => $id));
		}
	}

	public function saveMyData( User $user )
	{
		$data = array(
		    'nome' => $user->name,
		    'login'     => $user->login,
		    'senha'  => md5($user->password),
		    'codigo_celula' => $user->cod_cell,
		    'ativo'  => UserTable::ATIVO
		);
		$id = $user->id;

		$this->tableGateway->update($data, array('id' => $id));
	}

	public function deleteUser($id)
	{
		$this->tableGateway->delete(array('id' => $id));
	}

}