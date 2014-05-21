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
    
    public function getCelularTable()
    {
    	if (!$this->celularTable) {
    		$sm = $this->getServiceLocator();
    		$this->celularTable = $sm->get('Celular\Model\CelularTable');
    	}
    	return $this->celularTable;
    }
}
