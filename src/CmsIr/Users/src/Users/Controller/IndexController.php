<?php
namespace CmsIr\Users\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\AuthenticationService;
use Zend\Json\Json;

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

            $offset = $this->getRequest()->getPost('iDisplayStart');
            $limit = $this->getRequest()->getPost('iDisplayLength');

            $sorting = $this->getRequest()->getPost('iSortingCols');
            $columns = array( 'name', 'surname', 'email', 'active');

            for ($i=0 ; $i<intval($sorting); $i++)
            {
                if ($this->getRequest()->getPost('bSortable_'.intval($this->getRequest()->getPost('iSortCol_'.$i))) == "true" )
                {
                    $sortingColumn = $columns[$this->getRequest()->getPost('iSortCol_'.$i)];
                    $sortingDir = $this->getRequest()->getPost('sSortDir_'.$i);
                }
            }

            $listData = $this->getUsersTable()->findBy($limit,$offset,$sortingColumn,$sortingDir);

            $output = array(
                "sEcho" => $this->getRequest()->getPost('sEcho'),
                "iTotalRecords" => $listData['iTotalRecords'],
                "iTotalDisplayRecords" => $listData['iTotalRecords'],
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