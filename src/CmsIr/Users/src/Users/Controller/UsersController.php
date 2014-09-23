<?php
namespace CmsIr\Users\Controller;

use CmsIr\Users\Model\Users;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Json\Json;
use Zend\Db\Sql\Predicate;
use CmsIr\Users\Form\UserForm;
use CmsIr\Users\Form\UserFormFilter;


class UsersController extends AbstractActionController
{
    protected $usersTable;
    protected $uploadDir = 'public/files/users/';
    public function usersListAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array( 'name', 'surname', 'email');

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

    public function previewAction()
    {

        return new ViewModel();
    }

    public function createAction()
    {
        $form = new UserForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new UserFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $request->getPost();

                var_dump($data);die;
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        return new ViewModel($viewParams);
    }

    public function editAction()
    {
        return new ViewModel();
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('users-list');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = (int) $request->getPost('id');
                $this->getUsersTable()->deleteUser($id);
                $this->flashMessenger()->addMessage('Użytkownik został usunięty poprawnie.');
                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('users-list');
        }

        return array(
            'id'    => $id,
            'user' => $this->getUsersTable()->getUser($id)
        );
    }

    public function uploadAction ()
    {
        if (!empty($_FILES)) {
            $tempFile   = $_FILES['Filedata']['tmp_name'];
            $targetFile = $_FILES['Filedata']['name'];

            $file = explode('.', $targetFile);
            $fileName = $file[0];
            $fileExt = $file[1];

            $uniqidFilename = $fileName.'-'.uniqid();
            $targetFile = $uniqidFilename.'.'.$fileExt;

            if(move_uploaded_file($tempFile,$this->uploadDir.$targetFile)) {
                echo $targetFile;
            } else {
                echo 0;
            }

        }
        return $this->response;
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