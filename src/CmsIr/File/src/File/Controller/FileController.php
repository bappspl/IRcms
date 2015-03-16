<?php
namespace CmsIr\File\Controller;

use CmsIr\File\Form\FileForm;
use CmsIr\File\Form\FileFormFilter;
use CmsIr\File\Model\File;
use CmsIr\System\Util\Inflector;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class FileController extends AbstractActionController
{
    protected $uploadDir = 'public/temp_files/file/';
    protected $destinationUploadDir = 'public/files/file/';

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
                $file->setSlug(Inflector::slugify($file->getName()));

                $fileArray = array();

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));
                if(!empty($scannedDirectory))
                {
                    foreach($scannedDirectory as $filename)
                    {
                        array_push($fileArray, $filename);
                        rename($this->uploadDir.'/'.$filename, $this->destinationUploadDir.'/'.$filename);
                    }
                }

                $file->setFilename(serialize($fileArray));
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

        $file = $this->getFileTable()->getOneBy(array('id' => $id));

        if(!$file) {
            return $this->redirect()->toRoute('file', array('category' => $category));
        }

        $fileFiles = $this->getFileTable()->getBy(array('id' => $id));
        $fileFiles = reset($fileFiles);
        $fileFiles = unserialize($fileFiles->getFilename());

        $form = new FileForm();
        $form->bind($file);

        $request = $this->getRequest();

        if ($request->isPost()) {

            $form->setInputFilter(new FileFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $data = $form->getData();
                $file->setName($data->getName());

                $fileArray = array();

                $scannedDirectory = array_diff(scandir($this->uploadDir), array('..', '.'));
                if(!empty($scannedDirectory))
                {
                    foreach($scannedDirectory as $filename)
                    {
                        array_push($fileArray, $filename);
                        rename($this->uploadDir.'/'.$filename, $this->destinationUploadDir.'/'.$filename);
                    }
                }

                $allFiles = array_merge($fileFiles, $fileArray);

                $file->setFilename(serialize($allFiles));
                $this->getFileTable()->save($file);

                $this->flashMessenger()->addMessage('Usługa została edytowana poprawnie.');

                return $this->redirect()->toRoute('file', array('category' => $category));
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
        $viewParams['category'] = $category;
        $viewParams['fileFiles'] = $fileFiles;
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

    public function deletePhotoAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = $request->getPost('id');
            $name = $request->getPost('name');
            $filePath = $request->getPost('filePath');

            if(!empty($id))
            {
                $file = $this->getFileTable()->getOneBy(array('id' => $id));
                $filenames = unserialize($file->getFilename());

                foreach($filenames as $key => $filename)
                {
                    if($filename == $name){
                        unset($filenames[$key]);
                    }
                }

                $newFilenames = serialize($filenames);
                $file->setFilename($newFilenames);
                $this->getFileTable()->save($file);

                unlink('./public'.$filePath);
            } else
            {
                unlink('./public'.$filePath);
            }
        }

        $jsonObject = Json::encode($params['status'] = 'success', true);
        echo $jsonObject;
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