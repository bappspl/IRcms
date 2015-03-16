<?php
namespace CmsIr\File\Controller;

use CmsIr\File\Form\FileForm;
use CmsIr\File\Form\FileFormFilter;
use CmsIr\File\Model\File;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class FileController extends AbstractActionController
{
    protected $uploadDir = 'public/files/file/';

    public function listAction()
    {
        $category = $this->params()->fromRoute('category');
        $currentWebsiteId = $_COOKIE['website_id'];

        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('name');

            $listData = $this->getFileTable()->getFileDatatables($columns, $data, $category, $currentWebsiteId);

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

        $viewParams = array();
        $viewParams['category'] = $category;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function createAction()
    {
        $category = $this->params()->fromRoute('category');
        $currentWebsiteId = $_COOKIE['website_id'];
        $form = new FileForm();

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new FileFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $file = new File();

                $file->exchangeArray($form->getData());
                $file->setWebsiteId($currentWebsiteId);
                $file->setCategory($category);
                $this->getFileTable()->save($file);

                $this->flashMessenger()->addMessage('Usługa została dodana poprawnie.');

                return $this->redirect()->toRoute('file', array('category' => $category));
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['category'] = $category;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function editAction()
    {
        $id = $this->params()->fromRoute('file_id');
        $category = $this->params()->fromRoute('category');
        $currentWebsiteId = $_COOKIE['website_id'];

        $file = $this->getFileTable()->getOneBy(array('id' => $id));

        if(!$file) {
            return $this->redirect()->toRoute('file', array('category' => $category));
        }

        $form = new FileForm();
        $form->bind($file);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new FileFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $file->setWebsiteId($currentWebsiteId);
                $this->getFileTable()->save($file);

                $this->flashMessenger()->addMessage('Usługa została edytowana poprawnie.');

                return $this->redirect()->toRoute('file', array('category' => $category));
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['category'] = $category;
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function deleteAction()
    {
        $request = $this->getRequest();
        $id = (int) $this->params()->fromRoute('file_id', 0);
        if (!$id) {
            return $this->redirect()->toRoute('file');
        }

        if ($request->isPost()) {
            $del = $request->getPost('del', 'Anuluj');

            if ($del == 'Tak') {
                $id = (int) $request->getPost('id');

                $this->getFileTable()->deleteFile($id);
                $this->flashMessenger()->addMessage('Usługa została usunięta poprawnie.');

                $modal = $request->getPost('modal', false);
                if($modal == true) {
                    $jsonObject = Json::encode($params['status'] = $id, true);
                    echo $jsonObject;
                    return $this->response;
                }
            }

            return $this->redirect()->toRoute('file');
        }

        return array(
            'id'    => $id,
            'page'  => $this->getFileTable()->getOneBy(array('id' => $id))
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
     * @return \CmsIr\File\Model\FileTable
     */
    public function getFileTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\FileTable');
    }
}