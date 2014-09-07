<?php
namespace CmsIr\Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;

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

            $displayStart = $this->getRequest()->getPost('iDisplayStart');
            $displayLength = $this->getRequest()->getPost('iDisplayLength');
            if(isset($displayStart) && $displayLength != '-1') {
                $limit = $displayLength;
                $offset = $displayStart;
            }
            $output = array(
                "sEcho" => $this->getRequest()->getPost('sEcho'),
                "iTotalRecords" => 15,
                "iTotalDisplayRecords" => 10,
                "aaData" => array(
                    array($limit,$offset,'aaa','aaa'),
                    array('aaa','aaa','aaa','aaa'),
                    array('aaa','aaa','aaa','aaa'),
                    array('aaa','aaa','aaa','aaa'),
                    array('aaa','aaa','aaa','aaa'),
                    array('aaa','aaa','aaa','aaa'),
                    array('aaa','aaa','aaa','aaa'),
                    array('aaa','aaa','aaa','aaa'),
                    array('aaa','aaa','aaa','aaa'),
                    array('aaa','aaa','aaa','aaa'),
                )
            );

            echo json_encode( $output );
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
	


    public function getUsersTable()
    {
        if (!$this->usersTable) {
            $sm = $this->getServiceLocator();
            $this->usersTable = $sm->get('CmsIr\Users\Model\UsersTable');
        }
        return $this->usersTable;
    }
}