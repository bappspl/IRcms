<?php
namespace CmsIr\Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\View\Model\JsonModel;

class IndexController extends AbstractActionController
{
    protected $usersTable;

    public function usersListAction()
    {
        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
            $loggedUser = $auth->getIdentity();
            $this->layout()->loggedUser = $loggedUser;
        }

        $request = $this->getRequest();
        if ($request->isPost()) {

            $output = array(
                "sEcho" => 1,
                "iTotalRecords" => 4,
                "iTotalDisplayRecords" => 4,
                "aaData" => array(
                    array('aaa','aaa','aaa','aaa'),
                    array('aaa','aaa','aaa','aaa'),
                    array('aaa','aaa','aaa','aaa'),
                    array('aaa','aaa','aaa','aaa'),
                )
            );

            $result = new JsonModel(array(
                'some_parameter' => 'some value',
                'success'=>true,
            ));
            return $result;
        }
		return new ViewModel();
	}

    public function createAction()
    {
        $auth = new AuthenticationService();
        if ($auth->hasIdentity()) {
            $loggedUser = $auth->getIdentity();
            $this->layout()->loggedUser = $loggedUser;
        }
        return new ViewModel();
    }
	


    public function getUsersTable()
    {
        if (!$this->usersTable) {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('CmsIr\Users\Model\UsersTable');
        }
        return $this->usersTable;
    }
}