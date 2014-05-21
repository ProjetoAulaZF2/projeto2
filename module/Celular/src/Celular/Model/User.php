<?php
namespace User\Model;

use Zend\InputFilter\Factory as InputFactory;
use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

class User
{
	public $id;
	public $name;
	public $roleUser;
	public $login;
	public $mail;
	public $password;
	public $active;
	public $cod_cell;

	protected $inputFilter;

	public function exchangeArray($data)
	{
		$this->id	            = (isset($data['id'])) ? $data['id'] : null;
		$this->name 	        = (isset($data['nome'])) ? $data['nome'] : null;
		$this->roleUser 	    = new RoleUser();
		$this->roleUser->id     = (isset($data['perfil_id'])) ? $data['perfil_id'] : null;
		$this->roleUser->name   = (isset($data['nome_perfil'])) ? $data['nome_perfil'] : null;

		$this->login            = (isset($data['login'])) ? $data['login'] : null;
		$this->mail             = (isset($data['email'])) ? $data['email'] : null;
		$this->password         = (isset($data['senha'])) ? $data['senha'] : null;
		$this->active           = (isset($data['ativo'])) ? $data['ativo'] : null;
		$this->cod_cell         = (isset($data['codigo_celula'])) ? $data['codigo_celula'] : null;

	}

	public function getInputFilter()
	{
		if (!$this->inputFilter) {
			$inputFilter = new InputFilter();
			$factory     = new InputFactory();

			$inputFilter->add($factory->createInput(array(
					'name'     => 'nome',
					'required' => true,
					'filters'  => array(
							array('name' => 'StripTags'),
							array('name' => 'StringTrim'),
					),
					'validators' => array(
							array(
									'name'    => 'StringLength',
									'options' => array(
											'encoding' => 'UTF-8',
											'min'      => 2,
											'max'      => 30,
									),
							),
					),
			)));

			$inputFilter->add($factory->createInput(array(
			                'name'     => 'login',
			                'required' => true,
			                'filters'  => array(
			                                array('name' => 'StripTags'),
			                                array('name' => 'StringTrim'),
			                ),
			                'validators' => array(
			                                array(
			                                                'name'    => 'StringLength',
			                                                'options' => array(
			                                                                'encoding' => 'UTF-8',
			                                                                'min'      => 2,
			                                                                'max'      => 30,
			                                                ),
			                                ),
			                ),
			)));


			$inputFilter->add($factory->createInput(array(
					'name'     => 'perfil_id',
					'required' => true,
					'filters'  => array(
							array('name' => 'Int'),
					),
					'validators' => array(
							array(
									'name'    => 'Digits'
							),
					),
			)));

			$this->inputFilter = $inputFilter;
		}

		return $this->inputFilter;
	}

	public function getInputFilterMyData()
	{
	    if (!$this->inputFilter) {
	        $inputFilter = new InputFilter();
	        $factory     = new InputFactory();

	        $inputFilter->add($factory->createInput(array(
	                        'name'     => 'nome',
	                        'required' => true,
	                        'filters'  => array(
	                                        array('name' => 'StripTags'),
	                                        array('name' => 'StringTrim'),
	                        ),
	                        'validators' => array(
	                                        array(
	                                                        'name'    => 'StringLength',
	                                                        'options' => array(
	                                                                        'encoding' => 'UTF-8',
	                                                                        'min'      => 2,
	                                                                        'max'      => 30,
	                                                        ),
	                                        ),
	                        ),
	        )));

	        $inputFilter->add($factory->createInput(array(
	                        'name'     => 'login',
	                        'required' => true,
	                        'filters'  => array(
	                                        array('name' => 'StripTags'),
	                                        array('name' => 'StringTrim'),
	                        ),
	                        'validators' => array(
	                                        array(
	                                                        'name'    => 'StringLength',
	                                                        'options' => array(
	                                                                        'encoding' => 'UTF-8',
	                                                                        'min'      => 2,
	                                                                        'max'      => 30,
	                                                        ),
	                                        ),
	                        ),
	        )));

	        $this->inputFilter = $inputFilter;
	    }

	    return $this->inputFilter;
	}

	public function getArrayCopy()
	{
		return array(
				'id'           => $this->id,
				'nome'         => $this->name,
				'perfil_id'    => $this->roleUser->id,
		        'login'        => $this->login,
		        'email'        => $this->mail,
		        'senha'        => $this->password,
		        'ativo'        => $this->active,
		        'codigo_celula'=> $this->cod_cell
				);
	}
}
