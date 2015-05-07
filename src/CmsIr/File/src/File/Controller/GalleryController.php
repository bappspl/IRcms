<?php
namespace CmsIr\File\Controller;

use CmsIr\File\Form\FileForm;
use CmsIr\File\Form\FileFormFilter;
use CmsIr\File\Form\GalleryForm;
use CmsIr\File\Form\GalleryFormFilter;
use CmsIr\File\Model\File;
use CmsIr\File\Model\Gallery;
use CmsIr\System\Util\Inflector;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\Authentication\Adapter\DbTable as AuthAdapter;
use Zend\Json\Json;

class GalleryController extends AbstractActionController
{
    protected $uploadDir = 'public/temp_files/gallery/';
    protected $destinationUploadDir = 'public/files/gallery/';

    public function listAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {

            $data = $this->getRequest()->getPost();
            $columns = array('name');

            $listData = $this->getGalleryTable()->getDatatables($columns, $data);

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
        $viewModel = new ViewModel();
        $viewModel->setVariables($viewParams);
        return $viewModel;
    }

    public function createAction()
    {
        $form = new GalleryForm();

        $request = $this->getRequest();

        if ($request->isPost())
        {
            $form->setInputFilter(new GalleryFormFilter($this->getServiceLocator()));
            $form->setData($request->getPost());

            if ($form->isValid())
            {
                $gallery = new Gallery();
                $gallery->exchangeArray($form->getData());
                $gallery->setSlug(Inflector::slugify($gallery->getName()));

                $this->getGalleryTable()->save($gallery);

                $this->flashMessenger()->addMessage('Galeria została dodana poprawnie.');
                return $this->redirect()->toRoute('gallery');
            }
        }

        $viewParams = array();
        $viewParams['form'] = $form;
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

        if(!is_array($fileFiles))
        {
            $fileFiles = array();
        }

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

                if($category == 'document')
                {
                    $this->flashMessenger()->addMessage('Dokument został edytowany poprawnie.');
                } else
                {
                    $this->flashMessenger()->addMessage('Galeria została edytowana poprawnie.');
                }

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
                $this->flashMessenger()->addMessage('Element został usunięty poprawnie.');

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

    public function uploadFilesAction ()
    {
        if (!empty($_FILES))
        {
            $tempFile   = $_FILES['Filedata']['tmp_name'];
            $targetFile = $_FILES['Filedata']['name'];
            $file = explode('.', $targetFile);

            $fileName = $file[0];
            $fileExt = $file[1];

            $uniqidFilename = $fileName.'-'.uniqid();
            $targetFile = $uniqidFilename.'.'.$fileExt;

            if(move_uploaded_file($tempFile,$this->uploadDir.$targetFile))
            {
                echo $targetFile;
            } else
            {
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
     * @return \CmsIr\File\Model\GalleryTable
     */
    public function getGalleryTable()
    {
        return $this->getServiceLocator()->get('CmsIr\File\Model\GalleryTable');
    }
}