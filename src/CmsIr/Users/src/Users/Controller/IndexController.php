<?php
namespace CmsIr\Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Json\Json;
use Zend\Db\Sql\Predicate;


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

            $data = $this->getRequest()->getPost();
            $columns = array( 'name', 'surname', 'email', 'active');

            $listData = $this->getUsersTable()->findBy($columns,$data);
            $output = array(
                "sEcho" => $this->getRequest()->getPost('sEcho'),
                "iTotalRecords" => $listData['iTotalRecords'],
                "iTotalDisplayRecords" => $listData['iTotalDisplayRecords'],
                "aaData" => $listData['aaData']
            );

            $jsonObject = Json::encode($output, true);
            echo $jsonObject;
            return $this->response;
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


    /**
     * @return \CmsIr\Users\Model\UsersTable
     */
    public function getUsersTable()
    {
        if (!$this->usersTable) {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('CmsIr\Users\Model\UsersTable');
        }
        return $this->usersTable;
    }
}